<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('created_at')->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return $this->renderForm();
    }

    public function store(ProductRequest $request)
    {
        if ($request->input('action') === 'sync_specs') {
            return redirect()
                ->route('admin.products.create')
                ->with('status', 'Характеристики підвантажено.')
                ->withInput($request->except('action'));
        }

        try {
            DB::transaction(function () use ($request): void {
                $product = Product::create(
                    $this->formatInput($request)
                );

                $this->handleImages($product, $request);
            });
        } catch (\Throwable $exception) {
            $this->logImageUploadException('store', $exception, $request);
            report($exception);

            return back()
                ->withErrors(['images' => 'Не вдалося зберегти зображення у базі даних. Спробуйте ще раз.'])
                ->withInput();
        }

        return redirect()->route('admin.products.index')->with('status', 'Товар створено.');
    }

    public function edit(Product $product)
    {
        return $this->renderForm($product);
    }

    protected function renderForm(?Product $product = null, ?int $overrideCategoryId = null)
    {
        $categories = Category::orderBy('order')->get();
        $oldCategoryId = session('_old_input.category_id');
        $categoryId = $overrideCategoryId ?? $oldCategoryId ?? $product?->category_id ?? null;

        if (blank($categoryId)) {
            $categoryId = null;
        }

        $specFields = $this->specFieldsFor($categoryId);

        return view('admin.products.form', compact('product', 'categories', 'specFields'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if ($request->input('action') === 'sync_specs') {
            return redirect()
                ->to(route('admin.products.edit', $product) . '#specs')
                ->with('status', 'Характеристики підвантажено.')
                ->withInput($request->except('action'));
        }

        try {
            DB::transaction(function () use ($request, $product): void {
                $product->update(
                    $this->formatInput($request, $product)
                );

                $this->handleImages($product, $request);
            });
        } catch (\Throwable $exception) {
            $this->logImageUploadException('update', $exception, $request, $product->id);
            report($exception);

            return back()
                ->withErrors(['images' => 'Не вдалося зберегти зображення у базі даних. Зміни не збережено.'])
                ->withInput();
        }

        return redirect()->route('admin.products.index')->with('status', 'Товар оновлено.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Товар видалено.');
    }

    protected function formatInput(ProductRequest $request, ?Product $product = null): array
    {
        $isSyncAction = $request->input('action') === 'sync_specs';
        $name = $request->filled('name')
            ? $request->name
            : $product?->name ?? 'Товар без назви';

        $brand = $request->filled('brand')
            ? $request->brand
            : $product?->brand ?? 'Бренд';

        $model = $request->filled('model')
            ? $request->model
            : $product?->model ?? 'Модель';

        $slug = $request->input('slug')
            ?: $product?->slug
            ?: $this->generateSlug($name, $product);

        $price = $request->filled('price')
            ? $request->price
            : $product?->price ?? 0;

        $discount = $request->filled('discount')
            ? $request->discount
            : $product?->discount ?? 0;

        $stock = $request->filled('stock')
            ? $request->stock
            : $product?->stock ?? 0;

        $description = $request->filled('description')
            ? $request->description
            : $product?->description ?? '';

        $isActive = $request->has('is_active')
            ? $request->boolean('is_active')
            : ($isSyncAction ? ($product?->is_active ?? true) : false);

        $isNew = $request->has('is_new')
            ? $request->boolean('is_new')
            : ($isSyncAction ? ($product?->is_new ?? false) : false);

        $isHit = $request->has('is_hit')
            ? $request->boolean('is_hit')
            : ($isSyncAction ? ($product?->is_hit ?? false) : false);

        return [
            'category_id' => $request->category_id,
            'name' => $name,
            'slug' => $slug,
            'brand' => $brand,
            'model' => $model,
            'price' => $price,
            'discount' => $discount,
            'stock' => $stock,
            'description' => $description,
            'is_active' => $isActive,
            'is_new' => $isNew,
            'is_hit' => $isHit,
            'specifications' => $this->filteredSpecValues(
                $request->category_id,
                $request->input('spec_values', [])
            ),
        ];
    }

    protected function filteredSpecValues(?int $categoryId, array $values): array
    {
        $allowedKeys = $this->allowedSpecKeys($categoryId);

        return collect($values)
            ->only($allowedKeys)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->toArray();
    }

    protected function allowedSpecKeys(?int $categoryId): array
    {
        if (! $categoryId) {
            return [];
        }

        return Category::find($categoryId)?->specFields()->pluck('key')->toArray() ?? [];
    }

    protected function generateSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'product';
        }

        $slug = $base;
        $suffix = 0;

        while (Product::where('slug', $slug)
            ->when($product?->id, fn ($query) => $query->where('id', '!=', $product->id))
            ->exists()) {
            $suffix++;
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }

    private function specFieldsFor(?int $categoryId)
    {
        if (! $categoryId) {
            return collect();
        }

        return Category::find($categoryId)?->specFields()->orderBy('order')->get() ?? collect();
    }

    protected function handleImages(Product $product, ProductRequest $request): void
    {
        $this->deleteImages($product, $request);
        $this->updateImageSort($product, $request);
        $this->storeUploadedImages($product, $request);
        $this->syncMainImage($product, $request);
    }

    protected function deleteImages(Product $product, ProductRequest $request): void
    {
        $deleteIds = collect($request->input('delete_images', []))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->toArray();

        if (empty($deleteIds)) {
            return;
        }

        ProductImage::where('product_id', $product->id)
            ->whereIn('id', $deleteIds)
            ->delete();
    }

    protected function updateImageSort(Product $product, ProductRequest $request): void
    {
        foreach ($request->input('image_sort', []) as $id => $sort) {
            if (! is_numeric($id) || ! is_numeric($sort)) {
                continue;
            }

            ProductImage::where('id', $id)
                ->where('product_id', $product->id)
                ->update(['sort_order' => (int) $sort]);
        }
    }

    protected function storeUploadedImages(Product $product, ProductRequest $request): void
    {
        if (! $request->hasFile('image') && ! $request->hasFile('images')) {
            return;
        }

        if ($request->hasFile('image')) {
            $singleFile = $request->file('image');

            if ($singleFile instanceof UploadedFile) {
                $this->replacePrimaryImage($product, $singleFile);
            }
        }

        foreach ($request->file('images', []) as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $this->storeGalleryImage($product, $file);
        }
    }

    protected function replacePrimaryImage(Product $product, UploadedFile $file): void
    {
        $payload = $this->buildImagePayload($file);
        $currentPrimary = $product->images()->where('is_primary', true)->first();

        if ($currentPrimary) {
            $currentPrimary->update($payload);

            return;
        }

        $nextSort = (($product->images()->max('sort_order')) ?? 0) + 1;

        $product->images()->create(array_merge($payload, [
            'is_primary' => true,
            'sort_order' => $nextSort,
        ]));
    }

    protected function storeGalleryImage(Product $product, UploadedFile $file): void
    {
        $payload = $this->buildImagePayload($file);
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = (($product->images()->max('sort_order')) ?? 0) + 1;

        $product->images()->create(array_merge($payload, [
            'is_primary' => ! $hasPrimary,
            'sort_order' => $nextSort,
        ]));
    }

    protected function buildImagePayload(UploadedFile $file): array
    {
        $realPath = $file->getRealPath();

        if (! $realPath || ! is_readable($realPath)) {
            throw new \RuntimeException('Uploaded image file is not readable.');
        }

        $binary = file_get_contents($realPath);

        if ($binary === false || $binary === '') {
            throw new \RuntimeException('Uploaded image is empty or unreadable.');
        }

        return [
            'filename' => $this->imageFileName($file),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'image_data' => $binary,
        ];
    }

    protected function syncMainImage(Product $product, ProductRequest $request): void
    {
        $selectedMain = $request->input('main_image');

        if ($selectedMain) {
            $selectedId = (int) $selectedMain;

            if (ProductImage::where('product_id', $product->id)->where('id', $selectedId)->exists()) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                ProductImage::where('product_id', $product->id)->where('id', $selectedId)->update(['is_primary' => true]);

                return;
            }
        }

        if (! $product->images()->where('is_primary', true)->exists()) {
            $fallback = $product->images()->orderBy('sort_order')->first();

            if ($fallback) {
                $fallback->update(['is_primary' => true]);
            }
        }
    }

    protected function imageFileName(UploadedFile $file): string
    {
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $safeBaseName = Str::slug($baseName) ?: 'product-image';

        return $safeBaseName . '-' . Str::uuid() . '.' . $extension;
    }

    protected function logImageUploadException(
        string $operation,
        \Throwable $exception,
        ProductRequest $request,
        ?int $productId = null
    ): void {
        Log::error("Product {$operation} failed during image processing.", [
            'product_id' => $productId,
            'message' => $exception->getMessage(),
            'class' => get_class($exception),
            'has_image' => $request->hasFile('image'),
            'has_images' => $request->hasFile('images'),
            'files_keys' => array_keys($request->allFiles()),
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductImageWorkflowTest extends TestCase
{
    public function test_it_uploads_single_image_sets_primary_and_serves_binary_endpoint(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'action' => 'save',
            'category_id' => $category->id,
            'name' => 'Test Product One',
            'slug' => 'test-product-one',
            'brand' => 'TechnoDim',
            'model' => 'T-100',
            'price' => 10000,
            'discount' => 0,
            'stock' => 5,
            'description' => 'Test product',
            'images' => [
                UploadedFile::fake()->image('single.jpg', 600, 600)->size(300),
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'test-product-one')->firstOrFail();
        $images = $product->images()->orderBy('sort_order')->get();

        $this->assertCount(1, $images);
        $this->assertTrue($images[0]->is_primary);
        $this->assertSame(0, $images[0]->sort_order);
        $fullImage = ProductImage::query()->findOrFail($images[0]->id);

        $this->assertNotEmpty($fullImage->image_data);
        $this->assertStringStartsWith('image/', $images[0]->mime_type);

        $imageResponse = $this->get('/product-image/' . $images[0]->id);

        $imageResponse->assertOk();
        $imageResponse->assertHeader('Content-Type', $images[0]->mime_type);
    }

    public function test_it_uploads_multiple_images_in_order_and_marks_only_first_as_primary(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'action' => 'save',
            'category_id' => $category->id,
            'name' => 'Test Product Multi',
            'slug' => 'test-product-multi',
            'brand' => 'TechnoDim',
            'model' => 'T-200',
            'price' => 20000,
            'discount' => 0,
            'stock' => 5,
            'description' => 'Test product',
            'images' => [
                UploadedFile::fake()->image('first.jpg', 600, 600)->size(300),
                UploadedFile::fake()->image('second.png', 600, 600)->size(300),
                UploadedFile::fake()->image('third.webp', 600, 600)->size(300),
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'test-product-multi')->firstOrFail();
        $images = $product->images()->orderBy('sort_order')->get();

        $this->assertCount(3, $images);
        $this->assertSame([0, 1, 2], $images->pluck('sort_order')->all());
        $this->assertTrue($images[0]->is_primary);
        $this->assertFalse($images[1]->is_primary);
        $this->assertFalse($images[2]->is_primary);
    }

    public function test_it_rejects_non_image_and_files_larger_than_2mb(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $response = $this->actingAs($admin)->from(route('admin.products.create'))->post(route('admin.products.store'), [
            'action' => 'save',
            'category_id' => $category->id,
            'name' => 'Invalid Product',
            'slug' => 'invalid-product',
            'brand' => 'TechnoDim',
            'model' => 'T-300',
            'price' => 5000,
            'discount' => 0,
            'stock' => 1,
            'description' => 'Invalid',
            'images' => [
                UploadedFile::fake()->create('file.txt', 100, 'text/plain'),
                UploadedFile::fake()->image('too-large.jpg', 600, 600)->size(2500),
            ],
        ]);

        $response->assertRedirect(route('admin.products.create'));
        $response->assertSessionHasErrors(['images.0', 'images.1']);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
    }

    private function createCategory(): Category
    {
        return Category::query()->create([
            'name' => 'Test Category',
            'slug' => 'test-category-' . uniqid(),
            'description' => null,
            'order' => 1,
            'is_active' => true,
        ]);
    }
}

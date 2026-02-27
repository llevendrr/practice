<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('order')->paginate(12);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::orderBy('order')->get();

        return view('admin.categories.form', compact('parents'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Категорію збережено.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('order')->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if ($request->parent_id === $category->id) {
            return back()->with('error', 'Категорія не може бути власним батьком.');
        }

        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Категорію оновлено.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('status', 'Категорію видалено.');
    }
}

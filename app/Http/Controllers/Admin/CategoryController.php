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

        return redirect()->route('admin.categories.index')->with('status', __('messages.admin.category.saved'));
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('order')->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if ($request->parent_id === $category->id) {
            return back()->with('error', __('messages.admin.category.self_parent'));
        }

        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('status', __('messages.admin.category.updated'));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('status', __('messages.admin.category.deleted'));
    }
}

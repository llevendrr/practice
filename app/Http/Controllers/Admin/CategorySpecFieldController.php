<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategorySpecFieldRequest;
use App\Models\Category;
use App\Models\CategorySpecField;

class CategorySpecFieldController extends Controller
{
    public function index(Category $category)
    {
        $fields = $category->specFields()->orderBy('order')->get();

        return view('admin.categories.specs', compact('category', 'fields'));
    }

    public function store(CategorySpecFieldRequest $request, Category $category)
    {
        $category->specFields()->create($request->validated());

        return back()->with('status', __('messages.admin.spec.saved'));
    }

    public function update(CategorySpecFieldRequest $request, Category $category, CategorySpecField $field)
    {
        $field->update($request->validated());

        return back()->with('status', __('messages.admin.spec.updated'));
    }

    public function destroy(Category $category, CategorySpecField $field)
    {
        $field->delete();

        return back()->with('status', __('messages.admin.spec.deleted'));
    }

    public function api(Category $category)
    {
        $fields = $category->specFields()->orderBy('order')->get();

        return response()->json($fields);
    }
}

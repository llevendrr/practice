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

        return back()->with('status', 'Характеристику збережено.');
    }

    public function update(CategorySpecFieldRequest $request, Category $category, CategorySpecField $field)
    {
        $field->update($request->validated());

        return back()->with('status', 'Характеристику оновлено.');
    }

    public function destroy(Category $category, CategorySpecField $field)
    {
        $field->delete();

        return back()->with('status', 'Характеристику видалено.');
    }

    public function api(Category $category)
    {
        $fields = $category->specFields()->orderBy('order')->get();

        return response()->json($fields);
    }
}

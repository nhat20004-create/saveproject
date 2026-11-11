<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('admin.categories', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Category::uniqueSlug($data['name']);
        Category::create($data);

        return back()->with('success', 'Đã thêm danh mục.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);
        $data['slug'] = Category::uniqueSlug($data['name'], $category->id);
        $category->update($data);

        return back()->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->withTrashed()->exists()) {
            return back()->withErrors([
                'category' => 'Không thể xóa danh mục đang có bài viết.',
            ]);
        }

        $category->delete();

        return back()->with('success', 'Đã xóa danh mục.');
    }

    private function validated(Request $request, ?Category $category = null)
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục tối đa 50 ký tự.',
        ]);
    }
}

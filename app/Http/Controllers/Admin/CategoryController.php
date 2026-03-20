<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->ordered()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->ordered()->get();
        return view('admin.categories.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->ordered()
            ->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateCategory($request, $category->id);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count()) {
            return back()->with('error', 'No puedes eliminar una categoría con subcategorías.');
        }

        if ($category->products()->count()) {
            return back()->with('error', 'No puedes eliminar una categoría con productos asociados.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada.');
    }

    protected function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        $slugUnique = 'unique:categories,slug' . ($ignoreId ? ",{$ignoreId}" : '');

        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string|max:500',
            'image'            => 'nullable|image|max:2048',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
        ]);

        $data['slug']       = Str::slug($data['name']);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}

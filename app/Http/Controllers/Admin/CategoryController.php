<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\PublicProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly PublicProductImage $images) {}

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $this->data($request);
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $this->data($request, $category);
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->images->delete($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada. Sus productos quedaron sin categoría.');
    }

    private function data(CategoryRequest $request, ?Category $category = null): array
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['name']);
        $data['active'] = $request->boolean('active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $this->images->delete($category?->image);
            $data['image'] = $this->images->store($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            $this->images->delete($category?->image);
            $data['image'] = null;
        }

        return $data;
    }
}

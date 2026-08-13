<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\PublicProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly PublicProductImage $images) {}

    public function index(): View
    {
        $products = Product::with(['category', 'images', 'sizes'])
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when(request()->filled('category'), fn ($query) => request('category') === 'none' ? $query->whereNull('category_id') : $query->where('category_id', request('category')))
            ->orderBy('sort_order')->latest('id')->paginate(15)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(),
            'totalProductCount' => Product::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::create($this->data($request));
            $this->syncSizes($product, $request->input('sizes', []));
            $this->storeImages($product, $request);

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'sizes']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($this->data($request));
            $this->removeImages($product, $request->input('remove_images', []));
            $this->syncSizes($product, $request->input('sizes', []));
            $this->storeImages($product, $request);
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteProduct($product);

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'select_all' => ['nullable', 'boolean'],
            'product_ids' => [Rule::requiredIf(! $request->boolean('select_all')), 'nullable', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'distinct', 'exists:products,id'],
        ]);

        $products = Product::with('images')
            ->when(! $request->boolean('select_all'), fn ($query) => $query->whereKey($data['product_ids'] ?? []))
            ->get();

        foreach ($products as $product) {
            $this->deleteProduct($product);
        }

        return redirect()->route('admin.products.index')->with('success', $products->count().' producto(s) eliminado(s) correctamente.');
    }

    private function data(ProductRequest $request): array
    {
        $data = $request->safe()->except(['colors_text', 'images', 'remove_images', 'sizes']);
        $data['category_id'] = ($data['category_id'] ?? null) ?: null;
        $data['slug'] = Str::slug(($data['slug'] ?? null) ?: $data['name']);
        $data['colors'] = collect(explode(',', (string) $request->input('colors_text')))->map(fn ($color) => trim($color))->filter()->values()->all();
        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['availability'] = ($data['availability'] ?? null) ?: 'Consultar disponibilidad';

        return $data;
    }

    private function syncSizes(Product $product, array $sizes): void
    {
        $product->sizes()->delete();
        $clean = collect($sizes)->filter(fn ($size) => filled($size['name'] ?? null))->map(fn ($size) => [
            'name' => trim($size['name']),
        ])->values()->all();
        $product->sizes()->createMany($clean);
    }

    private function storeImages(Product $product, ProductRequest $request): void
    {
        $nextOrder = ((int) $product->images()->max('sort_order')) + 1;
        foreach ($request->file('images', []) as $file) {
            $product->images()->create([
                'image' => $this->images->store($file),
                'sort_order' => $nextOrder++,
            ]);
        }
    }

    private function removeImages(Product $product, array $ids): void
    {
        $images = $product->images()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            $this->images->delete($image->image);
            $image->delete();
        }
    }

    private function deleteProduct(Product $product): void
    {
        foreach ($product->images as $image) {
            $this->images->delete($image->image);
        }

        $product->delete();
    }
}

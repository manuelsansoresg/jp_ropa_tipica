<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\Testimonial;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'categories' => Category::where('active', true)->orderBy('sort_order')->get(),
            'products' => Product::with(['category', 'images', 'sizes'])->where('active', true)->where('featured', true)->take(8)->get(),
            'sections' => Section::where('active', true)->get()->keyBy('key'),
            'testimonials' => Testimonial::where('active', true)->get(),
        ]);
    }

    public function collections(): View
    {
        return view('pages.collections', [
            'categories' => Category::withCount(['products' => fn ($query) => $query->where('active', true)])->where('active', true)->orderBy('sort_order')->get(),
            'products' => Product::with(['category', 'images', 'sizes'])->where('active', true)->get(),
        ]);
    }

    public function collection(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('active', true)->firstOrFail();
        $products = $category->products()->with(['category', 'images', 'sizes'])->where('active', true)->get();

        return view('pages.collection', compact('category', 'products'));
    }

    public function product(string $slug): View
    {
        $product = Product::with(['category', 'images', 'sizes'])->where('slug', $slug)->where('active', true)->firstOrFail();
        $related = Product::with(['category', 'images', 'sizes'])->where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('active', true)->take(3)->get();

        return view('pages.product', compact('product', 'related'));
    }

    public function sizes(): View
    {
        return view('pages.sizes');
    }

    public function about(): View
    {
        return view('pages.about', ['section' => Section::where('key', 'about')->first()]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}

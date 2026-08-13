<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('products', 'slug')->ignore($product)],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'material' => ['nullable', 'string', 'max:255'],
            'colors_text' => ['nullable', 'string', 'max:500'],
            'availability' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'exists:product_images,id'],
            'sizes' => ['nullable', 'array', 'max:30'],
            'sizes.*.name' => ['nullable', 'string', 'max:40'],
        ];
    }
}

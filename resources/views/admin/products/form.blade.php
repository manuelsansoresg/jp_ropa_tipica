@extends('admin.layouts.app')
@section('title', $product->exists ? 'Editar producto' : 'Nuevo producto')
@section('section', 'Productos')
@section('header-action')<a href="{{ route('admin.products.index') }}" class="text-link">Volver →</a>@endsection
@section('content')
@php
    $initialSizes = old('sizes', $product->exists ? $product->sizes->map(fn($size) => ['name' => $size->name])->values()->all() : [
        ['name' => 'CH'], ['name' => 'M'], ['name' => 'G'], ['name' => 'XG'],
    ]);
@endphp
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-7xl" x-data='{ sizes: @json($initialSizes) }'>
    @csrf @if($product->exists) @method('PUT') @endif
    <div class="mb-9"><p class="eyebrow">{{ $product->exists ? 'Editar prenda' : 'Alta de producto' }}</p><h1 class="display-md mt-4">{{ $product->exists ? $product->name : 'Nuevo producto' }}</h1></div>
    <div class="grid gap-6 xl:grid-cols-[1.35fr_.65fr]">
        <div class="space-y-6">
            <section class="admin-panel">
                <h2 class="mb-6 border-b border-line pb-4 text-3xl">Información principal</h2>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2"><label for="name" class="admin-label">Nombre del producto *</label><input id="name" name="name" value="{{ old('name', $product->name) }}" required class="admin-input" placeholder="Ej. Guayabera Blanca Clásica"></div>
                    <div><label for="category_id" class="admin-label">Categoría opcional</label><select id="category_id" name="category_id" class="admin-select"><option value="">Sin categoría</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                    <div><label for="slug" class="admin-label">Slug</label><input id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="admin-input" placeholder="Se genera desde el nombre"></div>
                    <div class="sm:col-span-2"><label for="short_description" class="admin-label">Descripción corta</label><input id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="admin-input" placeholder="Texto breve para presentar la prenda"></div>
                    <div class="sm:col-span-2"><label for="description" class="admin-label">Descripción completa</label><textarea id="description" name="description" rows="7" class="admin-textarea" placeholder="Detalles y características de la prenda">{{ old('description', $product->description) }}</textarea></div>
                </div>
            </section>

            <section class="admin-panel">
                <div class="mb-6 flex items-end justify-between border-b border-line pb-4"><div><p class="eyebrow">Variantes</p><h2 class="mt-2 text-3xl">Tallas disponibles</h2><p class="mt-2 text-xs text-muted">Deja únicamente las tallas que el cliente puede solicitar.</p></div><button type="button" @click="sizes.push({name:''})" class="text-link">Agregar talla +</button></div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <template x-for="(size, index) in sizes" :key="index"><div class="grid grid-cols-[1fr_auto] gap-3"><input :name="`sizes[${index}][name]`" x-model="size.name" class="admin-input !mt-0" placeholder="Ej. CH, M, G o 36"><button type="button" @click="sizes.splice(index,1)" class="px-3 text-xl text-muted hover:text-red-700" aria-label="Eliminar talla">×</button></div></template>
                </div>
            </section>

            <section class="admin-panel" x-data="multipleImageUpload()">
                <h2 class="mb-6 border-b border-line pb-4 text-3xl">Fotografías</h2>
                @if($product->exists && $product->images->isNotEmpty())<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">@foreach($product->images as $image)<label class="group relative block"><img src="{{ $image->image }}" alt="Imagen actual" class="aspect-[3/4] w-full object-cover"><span class="mt-2 flex items-center gap-2 text-xs text-muted"><input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="accent-ink"> Eliminar</span></label>@endforeach</div>@endif
                <input id="images" x-ref="files" name="images[]" type="file" multiple accept="image/jpeg,image/png,image/webp" class="sr-only" @change="choose($event)">
                <button type="button" @click="$refs.files.click()" class="admin-upload">
                    <span class="font-display text-5xl leading-none">+</span>
                    <span class="mt-3 text-[10px] font-semibold uppercase tracking-[.16em]">Seleccionar fotografías</span>
                    <span class="mt-2 text-xs text-muted">Hasta 8 imágenes · JPG, PNG o WebP</span>
                </button>
                <div x-cloak x-show="previews.length" class="mt-5">
                    <div class="mb-3 flex items-center justify-between"><p class="admin-label">Vista previa de nuevas imágenes</p><button type="button" @click="clear()" class="text-[9px] font-semibold uppercase tracking-[.12em] text-red-700">Quitar selección</button></div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4"><template x-for="image in previews" :key="image.url"><figure><img :src="image.url" :alt="image.name" class="aspect-[3/4] w-full object-cover"><figcaption class="mt-2 truncate text-[10px] text-muted" x-text="image.name"></figcaption></figure></template></div>
                </div>
                <p class="mt-3 text-xs text-muted">La primera imagen será la portada. Recomendado: 1200 × 1600 px.</p>
                @error('images.*')<p class="admin-error">{{ $message }}</p>@enderror
            </section>
        </div>

        <aside class="space-y-6">
            <section class="admin-panel space-y-6">
                <div><label for="price" class="admin-label">Precio desde</label><div class="relative"><span class="absolute left-4 top-[1.1rem] text-sm text-muted">$</span><input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="admin-input pl-8" placeholder="1450"></div></div>
                <div><label for="material" class="admin-label">Material</label><input id="material" name="material" value="{{ old('material', $product->material) }}" class="admin-input" placeholder="Lino y algodón"></div>
                <div><label for="colors_text" class="admin-label">Colores</label><input id="colors_text" name="colors_text" value="{{ old('colors_text', collect($product->colors)->join(', ')) }}" class="admin-input" placeholder="Blanco, Marfil, Negro"><p class="mt-2 text-xs text-muted">Separa cada color con una coma.</p></div>
                <div><label for="availability" class="admin-label">Disponibilidad general</label><input id="availability" name="availability" value="{{ old('availability', $product->availability) }}" class="admin-input" placeholder="Consultar disponibilidad"></div>
            </section>
            <section class="admin-panel space-y-5">
                <div><label for="sort_order" class="admin-label">Orden de aparición</label><input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $product->sort_order ?? 0) }}" class="admin-input"></div>
                <label class="admin-check"><input type="hidden" name="active" value="0"><input type="checkbox" name="active" value="1" @checked(old('active', $product->exists ? $product->active : true))> Producto visible</label>
                <label class="admin-check"><input type="hidden" name="featured" value="0"><input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured))> Mostrar en destacados de Home</label>
            </section>
            @if($product->exists && $product->active)<a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn-light w-full">Ver ficha pública ↗</a>@endif
            <button class="btn-dark w-full">{{ $product->exists ? 'Guardar cambios' : 'Crear producto' }} →</button>
        </aside>
    </div>
</form>
@endsection

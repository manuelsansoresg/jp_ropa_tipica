@extends('admin.layouts.app')
@section('title', $category->exists ? 'Editar categoría' : 'Nueva categoría')
@section('section', 'Categorías')
@section('header-action')<a href="{{ route('admin.categories.index') }}" class="text-link">Volver →</a>@endsection
@section('content')
<form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-5xl">
    @csrf @if($category->exists) @method('PUT') @endif
    <div class="mb-9"><p class="eyebrow">{{ $category->exists ? 'Editar colección' : 'Nueva colección' }}</p><h1 class="display-md mt-4">{{ $category->exists ? $category->name : 'Crear categoría' }}</h1></div>
    <div class="grid gap-6 lg:grid-cols-[1.4fr_.6fr]">
        <section class="admin-panel space-y-6">
            <div><label for="name" class="admin-label">Nombre *</label><input id="name" name="name" value="{{ old('name', $category->name) }}" required class="admin-input" placeholder="Ej. Guayaberas"></div>
            <div><label for="slug" class="admin-label">Slug</label><input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="admin-input" placeholder="Se genera desde el nombre"><p class="mt-2 text-xs text-muted">Se utiliza en la dirección de la colección.</p></div>
            <div><label for="description" class="admin-label">Descripción</label><textarea id="description" name="description" rows="6" class="admin-textarea" placeholder="Descripción breve de la colección">{{ old('description', $category->description) }}</textarea></div>
        </section>
        <aside class="space-y-6">
            <section class="admin-panel" x-data="singleImageUpload({{ Js::from($category->image) }})">
                <label for="image" class="admin-label">Imagen de portada</label>
                <input id="image" x-ref="file" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="choose($event)">
                <input type="hidden" name="remove_image" :value="remove ? 1 : 0">

                <div x-cloak x-show="preview" class="relative mt-4 overflow-hidden bg-warm">
                    <img :src="preview" alt="Vista previa de portada" class="aspect-[4/5] w-full object-cover">
                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-3 bg-ink/85 px-4 py-3 text-white backdrop-blur-sm">
                        <span class="truncate text-[9px] uppercase tracking-[.12em]" x-text="filename || 'Portada actual'"></span>
                        <button type="button" @click="clear()" class="shrink-0 text-[9px] font-semibold uppercase tracking-[.12em] text-red-300 hover:text-white">Eliminar</button>
                    </div>
                </div>

                <button x-cloak x-show="!preview" type="button" @click="$refs.file.click()" class="admin-upload mt-4">
                    <span class="font-display text-5xl leading-none">+</span>
                    <span class="mt-3 text-[10px] font-semibold uppercase tracking-[.16em]">Seleccionar imagen de portada</span>
                    <span class="mt-2 text-xs text-muted">JPG, PNG o WebP · Máximo 5 MB</span>
                </button>
                <button x-cloak x-show="preview" type="button" @click="$refs.file.click()" class="btn-light mt-3 w-full">Reemplazar imagen</button>
                <p x-cloak x-show="remove && !preview" class="mt-3 text-xs text-red-700">La portada se eliminará al guardar los cambios.</p>
                <p class="mt-3 text-xs text-muted">Formato recomendado: 1200 × 1500 px.</p>
                @error('image')<p class="admin-error">{{ $message }}</p>@enderror
            </section>
            <section class="admin-panel space-y-5"><div><label for="sort_order" class="admin-label">Orden en el menú</label><input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="admin-input"></div><label class="admin-check"><input type="hidden" name="active" value="0"><input type="checkbox" name="active" value="1" @checked(old('active', $category->exists ? $category->active : true))> Mostrar en sitio y menú</label></section>
            <button class="btn-dark w-full">{{ $category->exists ? 'Guardar cambios' : 'Crear categoría' }} →</button>
        </aside>
    </div>
</form>
@endsection

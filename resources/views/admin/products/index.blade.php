@extends('admin.layouts.app')
@section('title', 'Productos')
@section('section', 'Productos')
@section('header-action')<a href="{{ route('admin.products.create') }}" class="btn-dark !min-h-10 !px-4">Nuevo producto</a>@endsection
@section('content')
<div class="mb-9 max-w-2xl">
    <p class="eyebrow">Catálogo</p>
    <h1 class="display-md mt-4">Productos</h1>
    <p class="copy mt-5">Gestiona la información, fotografías, tallas y visibilidad de cada prenda.</p>
</div>

<form method="GET" class="mb-6 grid gap-3 bg-white p-4 sm:grid-cols-[1fr_260px_auto]">
    <input name="search" value="{{ request('search') }}" class="admin-input !mt-0" placeholder="Buscar producto…">
    <select name="category" class="admin-select !mt-0">
        <option value="">Todas las categorías</option>
        <option value="none" @selected(request('category') === 'none')>Sin categoría</option>
        @foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>@endforeach
    </select>
    <button class="btn-dark">Filtrar</button>
</form>

@if($products->total())
<form action="{{ route('admin.products.bulk-destroy') }}" method="POST"
      x-ref="bulkForm"
      x-data="bulkProductSelection({{ Js::from($products->pluck('id')) }}, {{ $totalProductCount }})"
      @submit.prevent="requestDelete()">
    @csrf
    @method('DELETE')

    <div class="mb-4 flex flex-col gap-4 border border-line bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
        <label class="admin-check">
            <input type="checkbox" name="select_all" value="1" x-model="selectAllCatalog" @change="if (selectAllCatalog) selected = []">
            <span>Seleccionar todo el catálogo <span class="text-muted">({{ $totalProductCount }} productos)</span></span>
        </label>
        <div x-cloak x-show="selectAllCatalog || selected.length" x-transition.opacity class="flex items-center justify-between gap-5 sm:justify-end">
            <span class="text-xs text-muted" x-text="selectAllCatalog ? `${total} seleccionados` : `${selected.length} seleccionado(s)`"></span>
            <button type="submit" class="min-h-10 border border-red-700 px-4 text-[10px] font-semibold uppercase tracking-[.14em] text-red-700 transition hover:bg-red-700 hover:text-white">Eliminar selección</button>
        </div>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="!w-12">
                        <input type="checkbox" aria-label="Seleccionar productos de esta página" class="h-4 w-4 accent-ink"
                               :disabled="selectAllCatalog"
                               :checked="pageIds.length > 0 && selected.length === pageIds.length"
                               x-effect="$el.indeterminate = selected.length > 0 && selected.length < pageIds.length"
                               @change="togglePage($event.target.checked)">
                    </th>
                    <th>Producto</th><th>Categoría</th><th>Precio</th><th>Tallas</th><th>Estado</th><th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><input type="checkbox" name="product_ids[]" value="{{ $product->id }}" x-model="selected" :disabled="selectAllCatalog" aria-label="Seleccionar {{ $product->name }}" class="h-4 w-4 accent-ink"></td>
                    <td><div class="flex items-center gap-4"><img src="{{ $product->primary_image }}" alt="" class="h-20 w-16 bg-warm object-cover"><div><p class="font-medium">{{ $product->name }}</p><p class="mt-1 text-xs text-muted">/{{ $product->slug }}</p></div></div></td>
                    <td>{{ $product->category?->name ?? 'Sin categoría' }}</td>
                    <td>{{ $product->price ? '$'.number_format((float) $product->price, 0) : 'Consultar' }}</td>
                    <td class="text-xs text-muted">{{ $product->sizes->pluck('name')->join(' · ') ?: '—' }}</td>
                    <td><div class="flex flex-wrap gap-2"><span class="inline-flex border px-2 py-1 text-[9px] uppercase tracking-[.12em] {{ $product->active ? 'border-ink' : 'border-line text-muted' }}">{{ $product->active ? 'Visible' : 'Oculto' }}</span>@if($product->featured)<span class="inline-flex bg-ink px-2 py-1 text-[9px] uppercase tracking-[.12em] text-white">Destacado</span>@endif</div></td>
                    <td><div class="flex justify-end gap-4">@if($product->active)<a href="{{ route('products.show', $product->slug) }}" target="_blank" class="text-[10px] font-semibold uppercase tracking-[.12em] text-muted">Ver ↗</a>@endif<a href="{{ route('admin.products.edit', $product) }}" class="text-[10px] font-semibold uppercase tracking-[.12em]">Editar</a><button type="submit" form="delete-product-{{ $product->id }}" onclick="return confirm('¿Eliminar definitivamente este producto? Esta acción no se puede deshacer.')" class="text-[10px] font-semibold uppercase tracking-[.12em] text-red-700">Eliminar</button></div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-cloak x-show="showConfirmation" x-transition.opacity @keydown.escape.window="showConfirmation = false" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/55 p-5" role="dialog" aria-modal="true" aria-labelledby="bulk-delete-title">
        <div @click.outside="showConfirmation = false" class="w-full max-w-md bg-white p-7 shadow-2xl sm:p-9">
            <p class="eyebrow text-red-700">Confirmar eliminación</p>
            <h2 id="bulk-delete-title" class="mt-4 text-4xl leading-none">¿Eliminar productos?</h2>
            <p class="mt-5 text-sm leading-6 text-muted" x-text="confirmationMessage()"></p>
            <p class="mt-2 text-sm font-medium">Esta acción no se puede deshacer.</p>
            <div class="mt-8 grid gap-3 sm:grid-cols-2">
                <button type="button" @click="showConfirmation = false" class="btn-light">Cancelar</button>
                <button type="button" @click="submitConfirmed($refs.bulkForm)" class="inline-flex min-h-12 items-center justify-center bg-red-700 px-5 text-[10px] font-semibold uppercase tracking-[.14em] text-white transition hover:bg-red-800">Sí, eliminar</button>
            </div>
        </div>
    </div>
</form>

@foreach($products as $product)
<form id="delete-product-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
@endforeach

<div class="mt-6">{{ $products->links() }}</div>
@else
<div class="border border-line bg-white py-16 text-center text-sm text-muted">No se encontraron productos.</div>
@endif
@endsection

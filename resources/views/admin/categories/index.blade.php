@extends('admin.layouts.app')
@section('title', 'Categorías')
@section('section', 'Categorías')
@section('header-action')<a href="{{ route('admin.categories.create') }}" class="btn-dark !min-h-10 !px-4">Nueva categoría</a>@endsection
@section('content')
<div class="mb-9 max-w-2xl"><p class="eyebrow">Navegación y colecciones</p><h1 class="display-md mt-4">Categorías</h1><p class="copy mt-5">Las categorías activas aparecen automáticamente en el menú, el footer y las colecciones del sitio.</p></div>
<div class="overflow-x-auto border border-line bg-white">
    <table class="admin-table">
        <thead><tr><th>Orden</th><th>Categoría</th><th>Productos</th><th>Estado</th><th class="text-right">Acciones</th></tr></thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td class="font-display text-2xl text-silver">{{ str_pad($category->sort_order, 2, '0', STR_PAD_LEFT) }}</td>
                    <td><div class="flex items-center gap-4">@if($category->image)<img src="{{ $category->image }}" alt="" class="h-16 w-13 object-cover">@else<div class="h-16 w-13 bg-warm"></div>@endif<div><p class="font-medium">{{ $category->name }}</p><p class="mt-1 text-xs text-muted">/{{ $category->slug }}</p></div></div></td>
                    <td>{{ $category->products_count }}</td>
                    <td><span class="inline-flex border px-3 py-1 text-[9px] font-semibold uppercase tracking-[.12em] {{ $category->active ? 'border-ink text-ink' : 'border-line text-muted' }}">{{ $category->active ? 'Visible' : 'Oculta' }}</span></td>
                    <td><div class="flex justify-end gap-4">@if($category->active)<a href="{{ route('collections.show', $category->slug) }}" target="_blank" class="text-[10px] font-semibold uppercase tracking-[.12em] text-muted">Ver ↗</a>@endif<a href="{{ route('admin.categories.edit', $category) }}" class="text-[10px] font-semibold uppercase tracking-[.12em]">Editar</a><form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Eliminar esta categoría? Los productos se conservarán sin categoría.')">@csrf @method('DELETE')<button class="text-[10px] font-semibold uppercase tracking-[.12em] text-red-700">Eliminar</button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-16 text-center text-muted">No hay categorías registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

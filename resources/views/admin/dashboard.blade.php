@extends('admin.layouts.app')
@section('title', 'Resumen')
@section('section', 'Resumen general')
@section('header-action')<a href="{{ route('admin.products.create') }}" class="btn-dark !min-h-10 !px-4">Nuevo producto</a>@endsection
@section('content')
<div class="mb-10"><p class="eyebrow">Catálogo</p><h1 class="display-md mt-4">Todo en orden,<br><span class="italic">todo editable.</span></h1></div>
<div class="grid gap-px bg-line border border-line sm:grid-cols-2 xl:grid-cols-4">
    @foreach([['Categorías',$categoryCount],['Productos',$productCount],['Productos visibles',$activeProductCount],['Sin categoría',$uncategorizedCount]] as $stat)
        <div class="bg-white p-6"><p class="admin-label">{{ $stat[0] }}</p><p class="mt-5 font-display text-5xl">{{ $stat[1] }}</p></div>
    @endforeach
</div>
<section class="mt-10 bg-white p-6 sm:p-8">
    <div class="flex items-end justify-between border-b border-line pb-5"><div><p class="eyebrow">Actividad</p><h2 class="mt-2 text-3xl">Productos recientes</h2></div><a href="{{ route('admin.products.index') }}" class="text-link">Ver todos →</a></div>
    <div class="divide-y divide-line">@forelse($recentProducts as $product)<a href="{{ route('admin.products.edit', $product) }}" class="grid grid-cols-[1fr_auto] items-center gap-4 py-5 hover:text-muted sm:grid-cols-[1fr_180px_auto]"><span class="font-medium">{{ $product->name }}</span><span class="hidden text-sm text-muted sm:block">{{ $product->category?->name ?? 'Sin categoría' }}</span><span class="text-xs uppercase tracking-[.12em]">Editar →</span></a>@empty<p class="py-8 text-sm text-muted">Todavía no hay productos.</p>@endforelse</div>
</section>
@endsection

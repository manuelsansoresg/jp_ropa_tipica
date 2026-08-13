@extends('layouts.app')
@section('title', 'Colecciones | JV Ropa Típica')
@section('description', 'Explora guayaberas, vestidos y pantalones tradicionales elaborados en Yucatán.')
@section('content')
<section class="bg-warm pb-16 pt-36 md:pb-24 md:pt-44">
    <div class="shell grid gap-8 lg:grid-cols-2"><div><p class="eyebrow">Catálogo JV</p><h1 class="display-xl mt-6">Colecciones</h1></div><p class="copy max-w-lg self-end lg:pb-3">Una selección de prendas hechas para conservar la esencia de Yucatán y acompañar el presente con naturalidad.</p></div>
</section>
<section class="py-5"><div class="shell flex gap-7 overflow-x-auto border-b border-line pb-5 text-[10px] font-semibold uppercase tracking-[.15em]"><a href="#todas" class="border-b border-ink pb-1">Todas</a>@foreach($categories as $category)<a href="{{ route('collections.show', $category->slug) }}" class="whitespace-nowrap text-muted hover:text-ink">{{ $category->name }} <span class="text-silver">{{ $category->products_count }}</span></a>@endforeach<a href="{{ route('sizes') }}" class="ml-auto whitespace-nowrap text-muted">Guía de tallas →</a></div></section>
<section id="todas" class="section-pad !pt-16"><div class="shell">@if($products->isNotEmpty())<div class="grid grid-cols-1 gap-x-3 gap-y-12 sm:grid-cols-2 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>@else<x-empty-catalog-state />@endif</div></section>
<x-catalog-cta />
@endsection

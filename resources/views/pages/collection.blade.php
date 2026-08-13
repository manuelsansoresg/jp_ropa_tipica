@extends('layouts.app')
@section('title', $category->name.' | JV Ropa Típica')
@section('description', $category->description)
@section('content')
<section class="relative min-h-[72vh] bg-ink pt-20 text-white">
    <img src="{{ $category->image }}" alt="Colección {{ $category->name }}" class="absolute inset-0 h-full w-full object-cover opacity-55" width="1200" height="900">
    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-black/10"></div>
    <div class="shell relative flex min-h-[72vh] items-end pb-14 md:pb-20"><div class="max-w-2xl reveal"><p class="eyebrow !text-white/60">Colección</p><h1 class="display-xl mt-5">{{ $category->name }}</h1><p class="mt-6 max-w-lg text-sm leading-7 text-white/70">{{ $category->description }}</p></div></div>
</section>
<section class="section-pad"><div class="shell"><div class="mb-10 flex items-center justify-between border-b border-line pb-5 text-[10px] uppercase tracking-[.15em]"><span>{{ $products->count() }} prendas</span><a href="{{ route('sizes') }}" class="text-muted">Guía de tallas →</a></div>@if($products->isNotEmpty())<div class="grid grid-cols-1 gap-x-3 gap-y-12 sm:grid-cols-2 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>@else<x-empty-catalog-state />@endif</div></section>
<x-catalog-cta />
@endsection

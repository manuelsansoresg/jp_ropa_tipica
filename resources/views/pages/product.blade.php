@extends('layouts.app')
@section('title', $product->name.' | JV Ropa Típica')
@section('description', $product->short_description)
@push('head')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Product','name'=>$product->name,'image'=>$product->images->map(fn($image)=>asset($image->image))->all(),'description'=>$product->description,'brand'=>['@type'=>'Brand','name'=>'JV Ropa Típica'],'offers'=>['@type'=>'Offer','priceCurrency'=>'MXN','price'=>$product->price,'availability'=>'https://schema.org/InStock']], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@section('content')
@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $productMessage = urlencode('Hola, vi en su página la prenda "'.$product->name.'" y me gustaría conocer disponibilidad, precio y tallas.');
    $catalogMessage = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
@endphp
<section class="pb-20 pt-24 md:pb-28 md:pt-32"><div class="shell">
    <nav class="mb-7 text-[9px] uppercase tracking-[.14em] text-muted"><a href="{{ route('collections.index') }}">Colecciones</a> / @if($product->category)<a href="{{ route('collections.show', $product->category->slug) }}">{{ $product->category->name }}</a> / @endif<span class="text-ink">{{ $product->name }}</span></nav>
    <div class="grid gap-10 lg:grid-cols-[1.3fr_.7fr] lg:gap-16" x-data="{ active: '{{ $product->primary_image }}' }">
        <div class="grid gap-3 sm:grid-cols-[80px_1fr]">
            <div class="order-2 flex gap-2 sm:order-1 sm:flex-col">@foreach($product->images as $image)<button @click="active='{{ $image->image }}'" class="aspect-[3/4] w-16 overflow-hidden border border-transparent focus:border-ink sm:w-full"><img src="{{ $image->image }}" alt="Vista de {{ $product->name }}" class="h-full w-full object-cover"></button>@endforeach</div>
            <div class="order-1 aspect-[3/4] overflow-hidden bg-warm sm:order-2"><img :src="active" alt="{{ $product->name }}" class="h-full w-full object-cover" width="900" height="1200"></div>
        </div>
        <div class="lg:sticky lg:top-32 lg:self-start">
            <p class="eyebrow">{{ $product->category?->name ?? 'Colección JV' }}</p><h1 class="display-md mt-5">{{ $product->name }}</h1>
            @if($product->price)<p class="mt-5 text-sm text-muted">Desde ${{ number_format((float)$product->price, 0) }} MXN</p>@endif
            <p class="copy mt-7">{{ $product->description }}</p>
            <dl class="mt-9 border-y border-line text-sm"><div class="grid grid-cols-3 border-b border-line py-4"><dt class="text-muted">Tallas</dt><dd class="col-span-2">{{ $product->sizes->pluck('name')->join(' · ') }}</dd></div><div class="grid grid-cols-3 border-b border-line py-4"><dt class="text-muted">Material</dt><dd class="col-span-2">{{ $product->material }}</dd></div><div class="grid grid-cols-3 py-4"><dt class="text-muted">Colores</dt><dd class="col-span-2">{{ collect($product->colors)->join(' · ') }}</dd></div></dl>
            <p class="mt-4 text-xs text-muted">{{ $product->availability }}</p>
            <div class="mt-8 grid gap-3"><a href="https://wa.me/{{ $whatsapp }}?text={{ $productMessage }}" target="_blank" rel="noopener" class="btn-dark">Consultar disponibilidad por WhatsApp →</a><a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-light">Solicitar catálogo completo</a></div>
            <a href="{{ route('sizes') }}" class="mt-6 inline-block text-[10px] font-semibold uppercase tracking-[.14em] underline underline-offset-4">Consulta la guía de tallas</a>
        </div>
    </div>
</div></section>
@if($related->isNotEmpty())<section class="border-t border-line py-20"><div class="shell"><h2 class="display-md">También podría gustarte</h2><div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 md:gap-6">@foreach($related as $item)<x-product-card :product="$item" />@endforeach</div></div></section>@endif
@endsection

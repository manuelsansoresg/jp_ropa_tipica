@props(['product'])
<article class="group min-w-0 reveal">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="product-image"><img src="{{ $product->primary_image }}" alt="{{ $product->name }}" loading="lazy" width="700" height="930"></div>
        <div class="pt-4">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-[9px] uppercase tracking-[.18em] text-muted">{{ $product->category->name }}</p><h3 class="mt-1 font-sans text-sm font-medium leading-5">{{ $product->name }}</h3></div>
                @if($product->price)<p class="shrink-0 text-xs text-muted">Desde ${{ number_format((float) $product->price, 0) }}</p>@endif
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-line pt-3"><span class="text-[10px] uppercase tracking-[.12em] text-muted">{{ $product->sizes->pluck('name')->join(' · ') }}</span><span class="text-[10px] font-semibold uppercase tracking-[.12em]">Ver detalles →</span></div>
        </div>
    </a>
</article>

@extends('layouts.app')

@section('content')
@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $catalogMessage = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
    $manifesto = $sections->get('manifesto');
@endphp

<section class="min-h-[760px] pt-20 lg:min-h-screen lg:pt-0">
    <div class="grid min-h-[760px] lg:min-h-screen lg:grid-cols-[58%_42%]">
        <div class="image-reveal order-2 h-[60vh] min-h-[500px] overflow-hidden lg:order-1 lg:h-screen">
            <img src="/images/editorial/hero-jv.png" alt="Guayabera blanca elaborada en Yucatán" fetchpriority="high" width="1024" height="1280" class="h-full w-full object-cover object-top">
        </div>
        <div class="order-1 flex items-center bg-warm px-5 py-20 sm:px-10 lg:order-2 lg:px-12 xl:px-20">
            <div class="max-w-xl reveal">
                <p class="eyebrow rule-title">Ropa típica yucateca</p>
                <h1 class="display-xl mt-10">Tradición,<br><span class="italic">hecha para vestir.</span></h1>
                <p class="copy mt-8 max-w-md">Prendas yucatecas elaboradas con identidad, detalle y estilo. Guayaberas, vestidos y ropa típica con envíos a todo México.</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row"><a href="{{ route('collections.index') }}" class="btn-dark">Ver colección <span>→</span></a><a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-light">Solicitar catálogo</a></div>
                <div class="mt-12 flex gap-8 border-t border-silver/60 pt-5 text-[9px] font-semibold uppercase tracking-[.2em] text-muted"><span>Hecho en Yucatán</span><span>Envíos a todo México</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad overflow-hidden">
    <div class="shell grid items-center gap-14 lg:grid-cols-12 lg:gap-10">
        <div class="reveal lg:col-span-6 lg:col-start-2">
            <p class="eyebrow">{{ $manifesto?->subtitle ?? 'Desde Yucatán' }}</p>
            <h2 class="display-lg mt-6">{{ $manifesto?->title ?? 'Vestimos una tradición que sigue evolucionando.' }}</h2>
            <p class="copy mt-8 max-w-xl">{{ $manifesto?->content }}</p>
            <a href="{{ route('about') }}" class="text-link mt-9">Conoce nuestra historia →</a>
        </div>
        <div class="relative lg:col-span-4 lg:col-start-9 lg:mt-24">
            <div class="image-reveal aspect-[3/4] overflow-hidden"><img src="{{ $manifesto?->image ?? '/images/editorial/artesania.jpg' }}" alt="Detalle del trabajo artesanal" loading="lazy" class="h-full w-full object-cover" width="700" height="930"></div>
            <span class="absolute -bottom-7 -left-7 hidden h-28 w-px bg-silver lg:block"></span>
        </div>
    </div>
</section>

<section class="border-y border-line py-20 md:py-28">
    <div class="shell">
        <div class="mb-10 flex items-end justify-between reveal"><div><p class="eyebrow">Colecciones</p><h2 class="display-md mt-4">Explora nuestras colecciones</h2></div><a href="{{ route('collections.index') }}" class="text-link hidden sm:inline-flex">Ver todas →</a></div>
        @if($categories->isNotEmpty())
            <div class="grid gap-3 md:grid-cols-3">@foreach($categories as $category)<x-category-tile :category="$category" />@endforeach</div>
        @else
            <x-empty-catalog-state />
        @endif
    </div>
</section>

<section class="section-pad">
    <div class="shell">
        <div class="mb-12 flex items-end justify-between reveal"><div><p class="eyebrow">Selección JV</p><h2 class="display-md mt-4">Prendas seleccionadas</h2></div><a href="{{ route('collections.index') }}" class="text-link hidden sm:inline-flex">Explorar catálogo →</a></div>
        @if($products->isNotEmpty())
            <div class="grid grid-cols-2 gap-x-3 gap-y-12 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>
        @else
            <x-empty-catalog-state />
        @endif
    </div>
</section>

<section class="bg-ink py-8 text-white md:py-12">
    <div class="shell grid min-h-[75vh] items-center gap-12 lg:grid-cols-2">
        <div class="image-reveal h-[55vh] min-h-[420px] overflow-hidden lg:h-[68vh]"><img src="/images/editorial/textura-lino.jpg" alt="Detalle de bordado sobre lino" loading="lazy" class="h-full w-full scale-110 object-cover" width="700" height="700"></div>
        <div class="reveal py-12 lg:px-14">
            <p class="eyebrow !text-silver">100% hecho en Yucatán</p>
            <h2 class="display-lg mt-6">Detalles que hablan de nuestra tierra.</h2>
            <p class="mt-8 max-w-lg text-[15px] leading-7 text-white/60">Cada diseño lleva consigo una forma distinta de vestir Yucatán: frescura, elegancia y una tradición que sigue presente generación tras generación.</p>
            <div class="mt-12 flex gap-3"><span class="h-px w-20 bg-silver"></span><span class="h-px w-7 bg-silver/40"></span></div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="shell">
        <p class="eyebrow reveal">De Yucatán a tu puerta</p><h2 class="display-md mt-4 reveal">Comprar con nosotros es sencillo</h2>
        <div class="mt-14 grid border-t border-ink sm:grid-cols-2 lg:grid-cols-4">
            @foreach([['01','Explora','Descubre nuestros modelos y colecciones.'],['02','Consulta','Escríbenos por WhatsApp para conocer disponibilidad, tallas y colores.'],['03','Confirma','Te ayudamos a encontrar la prenda ideal.'],['04','Recibe','Realizamos envíos a toda la República Mexicana.']] as $step)
                <div class="reveal border-b border-line py-8 sm:px-6 sm:first:pl-0 lg:border-b-0 lg:border-r lg:last:border-r-0"><span class="font-display text-5xl text-silver">{{ $step[0] }}</span><h3 class="mt-7 text-3xl">{{ $step[1] }}</h3><p class="mt-3 text-sm leading-6 text-muted">{{ $step[2] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-warm section-pad">
    <div class="shell grid items-end gap-10 lg:grid-cols-2">
        <div class="reveal"><p class="eyebrow">¿Buscas más modelos?</p><h2 class="display-lg mt-5">Tenemos mucho más<br><span class="italic">por mostrarte.</span></h2></div>
        <div class="reveal lg:pb-2"><p class="copy max-w-lg">Solicita nuestro catálogo y conoce modelos disponibles, tallas, colores y opciones de envío.</p><a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-dark mt-8 w-full sm:w-auto">Solicitar catálogo por WhatsApp →</a></div>
    </div>
</section>

<section class="border-b border-line py-16 md:py-20">
    <div class="shell grid gap-10 lg:grid-cols-[1.2fr_1.8fr]">
        <div class="reveal"><p class="eyebrow">Envíos</p><h2 class="display-md mt-4">Desde Yucatán hasta donde estés.</h2></div>
        <div class="reveal lg:pt-8"><p class="copy max-w-2xl">Realizamos envíos a toda la República Mexicana y te acompañamos durante el proceso para ayudarte con tallas y disponibilidad.</p><div class="mt-9 grid grid-cols-3 gap-3 border-t border-line pt-6 text-[10px] font-semibold uppercase leading-5 tracking-[.12em]"><span>Entrega<br>en Mérida</span><span>Envíos<br>nacionales</span><span>Atención por<br>WhatsApp</span></div></div>
    </div>
</section>

<section class="section-pad">
    <div class="shell"><p class="eyebrow reveal">Experiencias</p><h2 class="display-md mt-4 reveal">Lo que dicen nuestros clientes</h2>
        <div class="mt-14 grid gap-10 lg:grid-cols-3">@foreach($testimonials as $testimonial)<blockquote class="reveal border-t border-ink pt-7"><p class="font-display text-[1.7rem] leading-[1.25]">“{{ $testimonial->text }}”</p><footer class="mt-7 text-[10px] font-semibold uppercase tracking-[.16em] text-muted">— {{ $testimonial->name }}</footer></blockquote>@endforeach</div>
        <p class="mt-8 text-[9px] uppercase tracking-[.12em] text-silver">Testimonios de demostración · pendientes de reemplazar desde administración</p>
    </div>
</section>

<section class="pb-24 md:pb-36">
    <div class="shell"><div class="mb-10 flex items-end justify-between reveal"><div><p class="eyebrow">Comunidad JV</p><h2 class="display-md mt-4">Síguenos y descubre nuevos diseños</h2></div><div class="hidden gap-6 text-[10px] font-semibold uppercase tracking-[.15em] sm:flex"><a href="#">Instagram ↗</a><a href="#">Facebook ↗</a></div></div>
        <div class="grid grid-cols-2 gap-2 md:grid-cols-6">@foreach(['/images/editorial/guayabera-clasica.jpg','/images/editorial/vestido-regional.jpg','/images/editorial/bordado-detalle.jpg','/images/editorial/guayabera-negra.jpg','/images/editorial/artesania.jpg','/images/editorial/vestido-marfil.jpg'] as $image)<a href="#" class="image-reveal aspect-square overflow-hidden"><img src="{{ $image }}" alt="Diseño de JV Ropa Típica" loading="lazy" class="h-full w-full object-cover transition duration-700 hover:scale-105" width="450" height="450"></a>@endforeach</div>
    </div>
</section>
@endsection

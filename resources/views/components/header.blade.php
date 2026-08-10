@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $catalogMessage = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
    $nav = [
        ['Inicio', 'home'], ['Colecciones', 'collections.index'], ['Guayaberas', 'collections.show', 'guayaberas'],
        ['Vestidos', 'collections.show', 'vestidos'], ['Pantalones', 'collections.show', 'pantalones'], ['Nosotros', 'about'],
    ];
@endphp
<div x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 12">
<header class="fixed inset-x-0 top-0 z-50 border-b bg-white/90 backdrop-blur-md transition duration-300" :class="scrolled || open ? 'border-line' : 'border-transparent'">
    <div class="shell flex h-20 items-center justify-between lg:h-24">
        <a href="{{ route('home') }}" class="relative z-50 flex items-center gap-3" aria-label="JV Ropa Típica, inicio">
            <span class="font-display text-[2rem] font-semibold leading-none tracking-[-.08em]">JV</span>
            <span class="hidden border-l border-silver pl-3 text-[9px] font-semibold uppercase leading-[1.35] tracking-[.2em] sm:block">Ropa<br>Típica</span>
        </a>
        <nav class="hidden items-center gap-6 xl:gap-8 lg:flex" aria-label="Navegación principal">
            @foreach($nav as $item)
                @php $params = isset($item[2]) ? ['slug' => $item[2]] : []; @endphp
                <a href="{{ route($item[1], $params) }}" class="text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->routeIs($item[1]) ? 'border-b border-ink pb-1' : '' }}">{{ $item[0] }}</a>
            @endforeach
        </nav>
        <div class="flex items-center gap-3">
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="hidden text-[10px] font-semibold uppercase tracking-[.15em] md:inline-flex">WhatsApp ↗</a>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-dark hidden !min-h-10 !px-5 xl:inline-flex">Solicitar catálogo</a>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="text-[10px] font-semibold uppercase tracking-[.12em] lg:hidden">WhatsApp</a>
            <button @click="open = !open; document.body.classList.toggle('menu-open', open)" class="relative z-50 flex h-11 w-11 items-center justify-center lg:hidden" :aria-expanded="open" aria-label="Abrir menú">
                <span class="relative h-3.5 w-6"><i class="absolute left-0 top-0 h-px w-6 bg-ink transition" :class="open && 'translate-y-[6px] rotate-45'"></i><i class="absolute bottom-0 left-0 h-px w-6 bg-ink transition" :class="open && '-translate-y-[7px] -rotate-45'"></i></span>
            </button>
        </div>
    </div>
</header>
    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-warm pt-28 lg:hidden">
        <nav class="shell flex h-full flex-col" aria-label="Navegación móvil">
            @foreach($nav as $item)
                @php $params = isset($item[2]) ? ['slug' => $item[2]] : []; @endphp
                <a href="{{ route($item[1], $params) }}" class="border-b border-line py-3 font-display text-[2rem] leading-tight">{{ $item[0] }}</a>
            @endforeach
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-dark mt-8">Solicitar catálogo</a>
            <p class="mt-auto pb-10 text-xs text-muted">Tekit, Yucatán · Envíos a todo México</p>
        </nav>
    </div>
</div>

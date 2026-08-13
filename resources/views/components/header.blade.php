@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $catalogMessage = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
    $useCollectionsMenu = $menuCategories->count() > 5;
@endphp
<div x-data="{ open: false, scrolled: false, collectionsOpen: false, mobileCollectionsOpen: false }" @scroll.window="scrolled = window.scrollY > 12">
    <header class="fixed inset-x-0 top-0 z-50 border-b bg-white/90 backdrop-blur-md transition duration-300" :class="scrolled || open || collectionsOpen ? 'border-line' : 'border-transparent'">
        <div class="shell flex h-20 items-center justify-between lg:h-24">
            <a href="{{ route('home') }}" class="relative z-50 flex items-center gap-3" aria-label="JV Ropa Típica, inicio">
                <span class="font-display text-[2rem] font-semibold leading-none tracking-[-.08em]">JV</span>
                <span class="hidden border-l border-silver pl-3 text-[9px] font-semibold uppercase leading-[1.35] tracking-[.2em] sm:block">Ropa<br>Típica</span>
            </a>

            <nav class="hidden items-center gap-5 lg:flex xl:gap-7" aria-label="Navegación principal" data-menu-mode="{{ $useCollectionsMenu ? 'grouped' : 'inline' }}">
                <a href="{{ route('home') }}" class="text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->routeIs('home') ? 'border-b border-ink pb-1' : '' }}">Inicio</a>

                @if($useCollectionsMenu)
                    <div class="relative" @click.outside="collectionsOpen = false" @keydown.escape.window="collectionsOpen = false">
                        <button type="button" @click="collectionsOpen = !collectionsOpen" :aria-expanded="collectionsOpen" aria-controls="collections-mega-menu" class="flex items-center gap-2 text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->routeIs('collections.*') ? 'border-b border-ink pb-1' : '' }}">
                            Colecciones
                            <span class="text-[8px] transition duration-200" :class="collectionsOpen && 'rotate-180'">▾</span>
                        </button>
                        <div id="collections-mega-menu" x-cloak x-show="collectionsOpen" x-transition.origin.top class="absolute left-1/2 top-[calc(100%+2rem)] w-[min(680px,calc(100vw-3rem))] -translate-x-1/2 border border-line bg-white p-7 shadow-[0_24px_60px_rgba(0,0,0,.12)]">
                            <div class="mb-6 flex items-end justify-between border-b border-line pb-4">
                                <div><p class="eyebrow">Catálogo JV</p><p class="mt-2 font-display text-3xl">Explorar colecciones</p></div>
                                <span class="text-[10px] uppercase tracking-[.14em] text-muted">{{ $menuCategories->count() }} categorías</span>
                            </div>
                            <div class="grid max-h-[52vh] grid-cols-2 gap-x-8 overflow-y-auto pr-2">
                                @foreach($menuCategories as $category)
                                    <a href="{{ route('collections.show', $category->slug) }}" class="group flex items-center justify-between border-b border-line py-4 text-sm transition hover:text-muted">
                                        <span>{{ $category->name }}</span><span class="translate-x-0 text-xs transition group-hover:translate-x-1">→</span>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('collections.index') }}" class="btn-dark mt-6 w-full">Ver catálogo completo →</a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('collections.index') }}" class="text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->routeIs('collections.index') ? 'border-b border-ink pb-1' : '' }}">Catálogo</a>
                    @foreach($menuCategories as $category)
                        <a href="{{ route('collections.show', $category->slug) }}" class="whitespace-nowrap text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->is('colecciones/'.$category->slug) ? 'border-b border-ink pb-1' : '' }}">{{ $category->name }}</a>
                    @endforeach
                @endif

                <a href="{{ route('about') }}" class="text-[11px] font-medium tracking-[.04em] transition hover:text-muted {{ request()->routeIs('about') ? 'border-b border-ink pb-1' : '' }}">Nosotros</a>
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

    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-40 overflow-y-auto bg-warm pt-24 lg:hidden">
        <nav class="shell flex min-h-full flex-col pb-8" aria-label="Navegación móvil">
            <a href="{{ route('home') }}" class="border-b border-line py-3 font-display text-[2rem] leading-tight">Inicio</a>
            <button type="button" @click="mobileCollectionsOpen = !mobileCollectionsOpen" :aria-expanded="mobileCollectionsOpen" class="flex w-full items-center justify-between border-b border-line py-3 text-left font-display text-[2rem] leading-tight">
                Colecciones <span class="font-sans text-base transition" :class="mobileCollectionsOpen && 'rotate-45'">+</span>
            </button>
            <div x-cloak x-show="mobileCollectionsOpen" x-transition.opacity class="border-b border-line bg-white/45 px-4">
                <a href="{{ route('collections.index') }}" class="flex items-center justify-between border-b border-line py-4 text-[10px] font-semibold uppercase tracking-[.15em]">Catálogo completo <span>→</span></a>
                @foreach($menuCategories as $category)
                    <a href="{{ route('collections.show', $category->slug) }}" class="flex items-center justify-between border-b border-line py-4 text-sm last:border-0"><span>{{ $category->name }}</span><span>→</span></a>
                @endforeach
            </div>
            <a href="{{ route('about') }}" class="border-b border-line py-3 font-display text-[2rem] leading-tight">Nosotros</a>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $catalogMessage }}" target="_blank" rel="noopener" class="btn-dark mt-8">Solicitar catálogo</a>
            <p class="mt-auto pt-12 text-xs text-muted">Tekit, Yucatán · Envíos a todo México</p>
        </nav>
    </div>
</div>

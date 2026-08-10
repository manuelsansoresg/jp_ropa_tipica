@php $whatsapp = $siteSettings->get('whatsapp', '5219999085831'); @endphp
<footer class="bg-ink text-white">
    <div class="shell py-16 md:py-24">
        <div class="grid gap-14 border-b border-white/15 pb-16 lg:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <a href="{{ route('home') }}" class="font-display text-5xl font-semibold tracking-[-.08em]">JV</a>
                <p class="mt-5 max-w-xs font-display text-2xl leading-tight text-white/85">Tradición y estilo<br>desde Yucatán.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 text-xs leading-8 text-white/65">
                <div><p class="mb-3 text-[10px] font-semibold uppercase tracking-[.2em] text-white">Colecciones</p><a class="block hover:text-white" href="{{ route('collections.show', 'guayaberas') }}">Guayaberas</a><a class="block hover:text-white" href="{{ route('collections.show', 'vestidos') }}">Vestidos</a><a class="block hover:text-white" href="{{ route('collections.show', 'pantalones') }}">Pantalones</a><a class="block hover:text-white" href="{{ route('sizes') }}">Guía de tallas</a></div>
                <div><p class="mb-3 text-[10px] font-semibold uppercase tracking-[.2em] text-white">Atención</p><a class="block hover:text-white" href="https://wa.me/{{ $whatsapp }}">WhatsApp</a><a class="block hover:text-white" href="{{ route('contact') }}">Envíos</a><a class="block hover:text-white" href="{{ route('contact') }}#preguntas">Preguntas frecuentes</a></div>
            </div>
            <div class="text-sm leading-7 text-white/65">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-[.2em] text-white">JV Ropa Típica</p>
                <a href="tel:+529999085831" class="block hover:text-white">{{ $siteSettings->get('phone_display', '999 908 5831') }}</a>
                <p>{{ $siteSettings->get('location', 'Tekit, Yucatán, México') }}</p><p>Envíos a toda la República Mexicana.</p>
            </div>
        </div>
        <div class="flex flex-col gap-3 pt-7 text-[10px] uppercase tracking-[.12em] text-white/40 sm:flex-row sm:justify-between"><p>© {{ date('Y') }} JV Ropa Típica</p><p>Todos los derechos reservados</p></div>
    </div>
</footer>

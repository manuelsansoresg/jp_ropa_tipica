@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $message = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
@endphp
<section class="bg-warm py-20 md:py-28"><div class="shell grid items-end gap-8 lg:grid-cols-2"><div><p class="eyebrow">¿Buscas más modelos?</p><h2 class="display-lg mt-5">Tenemos mucho más<br><span class="italic">por mostrarte.</span></h2></div><div><p class="copy max-w-lg">Solicita nuestro catálogo para conocer diseños, tallas, colores y opciones de envío disponibles.</p><a class="btn-dark mt-7 w-full sm:w-auto" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ $message }}">Solicitar catálogo por WhatsApp →</a></div></div></section>

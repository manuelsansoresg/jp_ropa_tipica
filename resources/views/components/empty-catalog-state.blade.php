@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $message = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera recibir su catálogo de modelos disponibles.');
@endphp

<div class="grid min-h-72 place-items-center border-y border-line bg-warm px-6 py-14 text-center">
    <div class="max-w-lg">
        <p class="eyebrow">Catálogo en actualización</p>
        <h3 class="mt-4 text-3xl md:text-4xl">Estamos preparando nuevas prendas.</h3>
        <p class="copy mt-4">Mientras actualizamos esta selección, puedes solicitar por WhatsApp el catálogo disponible.</p>
        <a href="https://wa.me/{{ $whatsapp }}?text={{ $message }}" target="_blank" rel="noopener" class="btn-dark mt-7">Solicitar catálogo →</a>
    </div>
</div>

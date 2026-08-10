@php
    $whatsapp = $siteSettings->get('whatsapp', '5219999085831');
    $message = urlencode('Hola, encontré JV Ropa Típica en su página web y quisiera conocer sus modelos disponibles.');
@endphp
<a href="https://wa.me/{{ $whatsapp }}?text={{ $message }}" target="_blank" rel="noopener" aria-label="Consultar por WhatsApp" class="fixed bottom-4 left-4 right-4 z-40 flex h-13 items-center justify-center bg-ink text-[10px] font-semibold uppercase tracking-[.15em] text-white shadow-xl sm:left-auto sm:right-6 sm:w-auto sm:px-6">
    <span class="sm:hidden">Consultar por WhatsApp</span><span class="hidden sm:inline">WhatsApp&nbsp;&nbsp;·&nbsp;&nbsp;Consultar</span>
</a>

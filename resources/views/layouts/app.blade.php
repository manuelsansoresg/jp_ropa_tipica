<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080808">
    <title>@yield('title', 'JV Ropa Típica | Guayaberas y Ropa Típica de Yucatán')</title>
    <meta name="description" content="@yield('description', 'Guayaberas, vestidos y ropa típica yucateca. Diseños tradicionales hechos en Yucatán con envíos a toda la República Mexicana.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_MX">
    <meta property="og:site_name" content="JV Ropa Típica">
    <meta property="og:title" content="@yield('title', 'JV Ropa Típica | Guayaberas y Ropa Típica de Yucatán')">
    <meta property="og:description" content="@yield('description', 'Tradición y estilo desde Yucatán, con envíos a todo México.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/editorial/campaign-grid.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'JV Ropa Típica')">
    <meta name="twitter:description" content="@yield('description', 'Tradición y estilo desde Yucatán.')">
    <meta name="twitter:image" content="{{ asset('images/editorial/campaign-grid.jpg') }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'ClothingStore', 'name' => 'JV Ropa Típica',
        'url' => url('/'), 'telephone' => '+52 999 908 5831', 'description' => 'Guayaberas y ropa típica yucateca.',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Tekit', 'addressRegion' => 'Yucatán', 'addressCountry' => 'MX'],
        'areaServed' => 'México',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <a href="#contenido" class="fixed left-4 top-3 z-[100] -translate-y-20 bg-white px-4 py-3 text-xs font-semibold transition focus:translate-y-0">Ir al contenido</a>
    <x-header />
    <main id="contenido">@yield('content')</main>
    <x-footer />
    <x-whatsapp-float />
</body>
</html>

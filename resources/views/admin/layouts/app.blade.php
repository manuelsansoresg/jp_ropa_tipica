<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Administración') · JV Ropa Típica</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm" x-data="{ sidebar: false }">
    <div class="min-h-screen lg:grid lg:grid-cols-[270px_1fr]">
        <aside class="fixed inset-y-0 left-0 z-50 flex w-[270px] -translate-x-full flex-col bg-ink text-white transition duration-300 lg:translate-x-0" :class="sidebar && 'translate-x-0'">
            <div class="flex h-24 items-center justify-between border-b border-white/15 px-7">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3"><span class="font-display text-4xl font-semibold tracking-[-.08em]">JV</span><span class="border-l border-white/30 pl-3 text-[9px] uppercase leading-4 tracking-[.2em] text-white/60">Panel<br>administrativo</span></a>
                <button @click="sidebar=false" class="text-2xl lg:hidden" aria-label="Cerrar menú">×</button>
            </div>
            <nav class="flex-1 space-y-1 p-4 text-sm" aria-label="Administración">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"><span>Resumen</span><span>01</span></a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}"><span>Categorías</span><span>02</span></a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}"><span>Productos</span><span>03</span></a>
                @if(auth()->user()->isOwner())<a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}"><span>Usuarios</span><span>04</span></a>@endif
            </nav>
            <div class="border-t border-white/15 p-5 text-xs text-white/60">
                <a href="{{ route('home') }}" target="_blank" class="mb-4 block text-white hover:text-white/70">Ver sitio público ↗</a>
                <p class="truncate">{{ auth()->user()->email }}</p>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">@csrf<button class="text-[10px] font-semibold uppercase tracking-[.15em] hover:text-white">Cerrar sesión</button></form>
            </div>
        </aside>

        <div class="lg:col-start-2">
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur-md sm:px-8 lg:h-24 lg:px-12">
                <div class="flex items-center gap-4"><button @click="sidebar=true" class="flex h-10 w-10 items-center justify-center border border-line lg:hidden" aria-label="Abrir menú"><span class="text-lg">☰</span></button><div><p class="eyebrow">JV Ropa Típica</p><p class="mt-1 text-sm font-medium">@yield('section', 'Administración')</p></div></div>
                @yield('header-action')
            </header>
            <main class="p-5 pb-16 sm:p-8 lg:p-12">
                @if(session('success'))<div class="mb-7 border-l-2 border-ink bg-white px-5 py-4 text-sm" role="status">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="mb-7 border-l-2 border-red-700 bg-white px-5 py-4 text-sm text-red-800"><p class="font-semibold">Revisa los campos indicados.</p><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                @yield('content')
            </main>
        </div>
    </div>
    <div x-cloak x-show="sidebar" x-transition.opacity @click="sidebar=false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>
</body>
</html>

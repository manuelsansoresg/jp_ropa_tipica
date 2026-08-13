<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Acceso administrativo · JV Ropa Típica</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm">
    <main class="grid min-h-screen lg:grid-cols-2">
        <div class="hidden overflow-hidden bg-ink lg:block"><img src="/images/editorial/guayabera-negra.jpg" alt="" class="h-full w-full scale-125 object-cover opacity-70"></div>
        <div class="flex items-center px-5 py-16 sm:px-12 lg:px-20">
            <div class="mx-auto w-full max-w-md">
                <a href="{{ route('home') }}" class="font-display text-5xl font-semibold tracking-[-.08em]">JV</a>
                <p class="eyebrow mt-12">Acceso privado</p>
                <h1 class="display-md mt-5">Administración<br><span class="italic">del catálogo.</span></h1>
                <form action="{{ route('admin.login.store') }}" method="POST" class="mt-10 space-y-6">
                    @csrf
                    <div><label for="email" class="admin-label">Correo electrónico</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus class="admin-input">@error('email')<p class="admin-error">{{ $message }}</p>@enderror</div>
                    <div><label for="password" class="admin-label">Contraseña</label><input id="password" name="password" type="password" autocomplete="current-password" required class="admin-input">@error('password')<p class="admin-error">{{ $message }}</p>@enderror</div>
                    <label class="flex items-center gap-3 text-xs text-muted"><input type="checkbox" name="remember" value="1" class="h-4 w-4 accent-ink"> Mantener sesión iniciada</label>
                    <button class="btn-dark w-full">Entrar al panel →</button>
                </form>
                <a href="{{ route('home') }}" class="mt-8 block text-center text-[10px] font-semibold uppercase tracking-[.15em] text-muted">Volver al sitio</a>
            </div>
        </div>
    </main>
</body>
</html>

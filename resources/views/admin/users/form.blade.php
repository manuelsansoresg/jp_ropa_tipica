@extends('admin.layouts.app')
@section('title', $managedUser->exists ? 'Editar usuario' : 'Nuevo usuario')
@section('section', 'Usuarios')
@section('header-action')<a href="{{ route('admin.users.index') }}" class="text-link">Volver →</a>@endsection
@section('content')
<form action="{{ $managedUser->exists ? route('admin.users.update', $managedUser) : route('admin.users.store') }}" method="POST" class="mx-auto max-w-4xl">
    @csrf
    @if($managedUser->exists) @method('PUT') @endif

    <div class="mb-9">
        <p class="eyebrow">{{ $managedUser->exists ? 'Editar acceso' : 'Alta de acceso' }}</p>
        <h1 class="display-md mt-4">{{ $managedUser->exists ? $managedUser->name : 'Nuevo usuario' }}</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.25fr_.75fr]">
        <section class="admin-panel space-y-6">
            <div><label for="name" class="admin-label">Nombre *</label><input id="name" name="name" value="{{ old('name', $managedUser->name) }}" required autocomplete="name" class="admin-input" placeholder="Nombre del usuario">@error('name')<p class="admin-error">{{ $message }}</p>@enderror</div>
            <div>
                <label for="email" class="admin-label">Correo electrónico *</label>
                <input id="email" name="email" type="email" value="{{ old('email', $managedUser->email) }}" required autocomplete="email" class="admin-input disabled:bg-warm disabled:text-muted" @disabled($managedUser->exists && $managedUser->isOwner())>
                @if($managedUser->exists && $managedUser->isOwner())<input type="hidden" name="email" value="{{ $managedUser->email }}"><p class="mt-2 text-xs text-muted">El correo de la cuenta principal está protegido y no puede cambiarse.</p>@endif
                @error('email')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
        </section>

        <aside class="space-y-6">
            <section class="admin-panel space-y-6">
                <div><label for="password" class="admin-label">{{ $managedUser->exists ? 'Nueva contraseña' : 'Contraseña *' }}</label><input id="password" name="password" type="password" @required(!$managedUser->exists) autocomplete="new-password" class="admin-input" placeholder="Mínimo 10 caracteres">@if($managedUser->exists)<p class="mt-2 text-xs text-muted">Déjala vacía para conservar la contraseña actual.</p>@endif @error('password')<p class="admin-error">{{ $message }}</p>@enderror</div>
                <div><label for="password_confirmation" class="admin-label">Confirmar contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" @required(!$managedUser->exists) autocomplete="new-password" class="admin-input" placeholder="Repite la contraseña"></div>
            </section>
            <button class="btn-dark w-full">{{ $managedUser->exists ? 'Guardar cambios' : 'Crear usuario' }} →</button>
        </aside>
    </div>
</form>
@endsection

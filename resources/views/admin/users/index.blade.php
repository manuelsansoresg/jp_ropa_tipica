@extends('admin.layouts.app')
@section('title', 'Usuarios')
@section('section', 'Usuarios')
@section('header-action')<a href="{{ route('admin.users.create') }}" class="btn-dark !min-h-10 !px-4">Nuevo usuario</a>@endsection
@section('content')
<div class="mb-9 max-w-2xl">
    <p class="eyebrow">Accesos administrativos</p>
    <h1 class="display-md mt-4">Usuarios</h1>
    <p class="copy mt-5">Crea las cuentas que podrán gestionar categorías y productos. Solo la cuenta principal puede consultar y administrar esta lista.</p>
</div>

<div class="overflow-x-auto border border-line bg-white">
    <table class="admin-table">
        <thead><tr><th>Usuario</th><th>Correo electrónico</th><th>Tipo de acceso</th><th>Creado</th><th class="text-right">Acciones</th></tr></thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td><div class="flex items-center gap-4"><span class="grid h-11 w-11 place-items-center bg-warm font-display text-xl uppercase">{{ mb_substr($user->name, 0, 1) }}</span><span class="font-medium">{{ $user->name }}</span></div></td>
                <td class="text-muted">{{ $user->email }}</td>
                <td><span class="inline-flex border px-3 py-1 text-[9px] font-semibold uppercase tracking-[.12em] {{ $user->isOwner() ? 'border-ink bg-ink text-white' : 'border-line text-muted' }}">{{ $user->isOwner() ? 'Cuenta principal' : 'Editor del sitio' }}</span></td>
                <td class="text-xs text-muted">{{ $user->created_at?->format('d/m/Y') }}</td>
                <td><div class="flex justify-end gap-4"><a href="{{ route('admin.users.edit', $user) }}" class="text-[10px] font-semibold uppercase tracking-[.12em]">Editar</a>@unless($user->isOwner())<form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente a {{ addslashes($user->name) }}? Ya no podrá acceder al panel.')">@csrf @method('DELETE')<button class="text-[10px] font-semibold uppercase tracking-[.12em] text-red-700">Eliminar</button></form>@endunless</div></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

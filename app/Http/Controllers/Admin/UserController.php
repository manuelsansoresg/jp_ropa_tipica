<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::orderBy('name')->orderBy('email')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['managedUser' => new User]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', ['managedUser' => $user]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if ($user->isOwner()) {
            $data['email'] = $user->email;
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->isOwner(), 422, 'La cuenta principal no puede eliminarse.');
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

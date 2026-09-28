<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $query = User::with('rol');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['estado'] = $request->has('estado') ?? true;

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['estado'] = $request->has('estado') ?? $user->estado;

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
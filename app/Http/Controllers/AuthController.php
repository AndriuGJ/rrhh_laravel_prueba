<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'El correo electrónico no está registrado.',
            ])->withInput();
        }

        if (!$user->estado) {
            return back()->withErrors([
                'email' => 'El usuario está inactivo.',
            ])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'password' => 'La contraseña es incorrecta.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
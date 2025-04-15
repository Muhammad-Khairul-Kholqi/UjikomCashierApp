<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session()->flash('success', 'Selamat datang, ' . ucfirst($user->role) . '!');

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard.index-dashboard');
            } elseif ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard.index-dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil Keluar Akun!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function editAccountAdmin()
    {
        return view('admin.account');
    }

    public function updateAccountAdmin(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_pengguna' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('nama_pengguna')) {
            $user->name = $validated['nama_pengguna'];
        }

        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/admin/account')->with('success', 'Profil berhasil diperbarui');
    }

    public function editAccountCashier()
    {
        return view('cashier.account');
    }

    public function updateAccountCashier(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_pengguna' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('nama_pengguna')) {
            $user->name = $validated['nama_pengguna'];
        }

        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/cashier/account')->with('success', 'Profil berhasil diperbarui');
    }
}

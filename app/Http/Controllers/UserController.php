<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);

        $users = User::when($search, function($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate($entries);

        return view('admin.user.index-user', compact('users', 'entries'));
    }


    // public function index(Request $request)
    // {
    //     $search = $request->input('search');
    //     $entries = $request->input('entries', 10);

    //     $users = User::where('role', 'cashier')
    //         ->when($search, function($query, $search) {
    //             return $query->where('name', 'like', "%$search%");
    //         })
    //         ->paginate($entries);

    //     return view('admin.user.index-user', compact('users', 'entries'));
    // }

    public function create()
    {
        return view('admin.user.create-user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:admin,cashier',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['nama_pengguna'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => 'required|in:admin,cashier',
            'password' => 'nullable|string|min:8',
        ]);

        $userData = [
            'name' => $validated['nama_pengguna'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}

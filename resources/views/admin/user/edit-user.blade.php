@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl mb-5">Edit Pengguna</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Pengguna</label>
                    <input type="text" name="nama_pengguna" value="{{ old('nama_pengguna', $user->name) }}" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                    @error('nama_pengguna')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div>
                    <label class="block text-gray-700 font-medium mb-1">Role</label>
                    <select name="role" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                        <option value="cashier" {{ (old('role', $user->role) == 'cashier') ? 'selected' : '' }}>Cashier</option>
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                    <span class="text-gray-500 text-sm">Biarkan kosong jika tidak ingin mengubah password</span>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Update Pengguna
            </button>
        </form>
    </div>
@endsection

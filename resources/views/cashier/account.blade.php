@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Akun Admin</h1>

    <form action="/cashier/account/update" method="POST">
        @csrf
        @method('PUT')

        <div class="mt-5 grid grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama</label>
                <input type="text" name="nama_pengguna" value="{{ auth()->user()->name }}"
                    class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}"
                    class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                <span class="text-gray-500 text-sm">Biarkan kosong jika tidak ingin mengubah password</span>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300 mt-5">
            Simpan Perubahan
        </button>
    </form>
@endsection

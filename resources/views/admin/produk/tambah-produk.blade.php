@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl mb-5">Tambah Produk</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Harga</label>
                    <input type="number" name="harga" required min="1"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Stok</label>
                    <input type="number" name="stok" required min="1"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Gambar Produk</label>
                    <div class="relative border border-gray-300 rounded-lg px-4 h-10 flex items-center cursor-pointer hover:border-blue-500">
                        <input type="file" name="foto_produk" required class="absolute inset-0 opacity-0 cursor-pointer" id="fileInput">
                        <span id="fileLabel" class="text-gray-500 truncate w-full block">Pilih file...</span>
                    </div>
                </div>
            </div>

            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Buat Produk
            </button>
        </form>
    </div>
@endsection

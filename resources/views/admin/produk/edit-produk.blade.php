@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl mb-5">Edit Produk</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" value="" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Harga</label>
                    <input type="number" name="harga" value="" required min="1"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Stok</label>
                    <input type="number" name="stok" value="" required min="1"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Gambar Produk</label>
                    <div class="flex items-center space-x-3">
                        <p>image sebelumnya here</p>
                        <input type="file" name="foto_produk" class="border border-gray-300 focus:border-blue-500 rounded-r-lg w-full outline-none">
                    </div>
                </div>
            </div>

            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection

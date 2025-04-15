@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl mb-5">Edit Produk</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Harga</label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}" required
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                    <p id="payment_limit_error" class="text-red-500 text-sm hidden">Nominal terlalu besar</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required min="1"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Gambar Produk</label>
                    <div class="flex items-center space-x-3">
                        @if ($produk->foto_produk)
                            <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="Gambar Produk" class="w-16 h-16 object-cover rounded">
                        @endif
                        <input type="file" name="foto_produk" class="border border-gray-300 focus:border-blue-500 rounded-r-lg w-full outline-none">
                    </div>
                </div>
            </div>

            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <script>
        function validateForm() {
            var harga = document.getElementById('harga').value;
            var maxHarga = 10000000;  // Batas harga maksimal (misalnya 10 juta)
            var errorMessage = document.getElementById('payment_limit_error');

            if (harga > maxHarga) {
                errorMessage.classList.remove('hidden');
                return false;  // Mencegah form disubmit
            } else {
                errorMessage.classList.add('hidden');
                return true;  // Form dapat disubmit
            }
        }
    </script>

@endsection

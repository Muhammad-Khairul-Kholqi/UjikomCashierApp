@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Detail Member & Poin</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="border p-5 shadow-md rounded-xl mt-5">
        <form action="{{ route('cashier.member-points.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Informasi Member</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama Member:</label>
                        <div class="p-2 border rounded bg-gray-50">{{ $member->name }}</div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Nomor Telepon:</label>
                        <div class="p-2 border rounded bg-gray-50">{{ $member->phone_number }}</div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Alamat:</label>
                        <div class="p-2 border rounded bg-gray-50">{{ $member->address ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Total Poin:</label>
                        <div class="p-2 border rounded bg-gray-50 font-semibold">{{ $member->points }}</div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Penggunaan Poin</h2>

                @if(!$hasPreviewsSales)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">Poin tidak dapat digunakan pada pembelanjaan pertama.</span>
                    </div>
                @elseif($member->points <= 0)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">Member belum memiliki poin.</span>
                    </div>
                @else
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="use_points" id="use_points" class="mr-2 h-5 w-5" value="1" onchange="togglePointsUsage()">
                        <label for="use_points" class="font-medium">Gunakan poin</label>
                    </div>

                    <div id="points_input_container" class="mb-4 hidden">
                        <label for="points_used" class="block mb-1 font-medium">Jumlah Poin:</label>
                        <input type="number" name="points_used" id="points_used" class="w-full p-2 border rounded"
                               min="0" max="{{ $member->points }}" value="0">
                        <p class="text-sm text-gray-600 mt-1">Max: {{ $member->points }} poin (1 poin = Rp. 1)</p>
                    </div>
                @endif

                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">
                        <strong>Informasi Poin:</strong> Setiap pembelanjaan Rp. 10.000 akan mendapatkan 1 poin.
                        Poin dapat ditukarkan pada transaksi berikutnya.
                    </span>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-3">Detail Produk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2 text-left">Nama Produk</th>
                                <th class="border p-2 text-center">Jumlah</th>
                                <th class="border p-2 text-right">Harga</th>
                                <th class="border p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < count($tempSalesData['product_ids']); $i++)
                                <tr>
                                    <td class="border p-2">{{ $tempSalesData['product_names'][$i] }}</td>
                                    <td class="border p-2 text-center">{{ $tempSalesData['quantities'][$i] }}</td>
                                    <td class="border p-2 text-right">Rp. {{ number_format($tempSalesData['prices'][$i], 0, ',', '.') }}</td>
                                    <td class="border p-2 text-right">Rp. {{ number_format($tempSalesData['subtotals'][$i], 0, ',', '.') }}</td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="border p-2 text-right font-semibold">Total:</td>
                                <td class="border p-2 text-right font-bold">Rp. {{ number_format($tempSalesData['total_amount'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex justify-between space-x-3">
                <a href="{{ route('cashier.pembelian.checkout') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-lg font-semibold">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-semibold">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        function togglePointsUsage() {
            const usePoints = document.getElementById('use_points').checked;
            const pointsInputContainer = document.getElementById('points_input_container');

            if (usePoints) {
                pointsInputContainer.classList.remove('hidden');
                document.getElementById('points_used').setAttribute('required', 'required');
            } else {
                pointsInputContainer.classList.add('hidden');
                document.getElementById('points_used').removeAttribute('required');
                document.getElementById('points_used').value = 0;
            }
        }
    </script>
@endsection

@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl">Pembelian</h1>

    <div class="p-5 border mt-5">
        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 md:gap-5">
            <div id="exportExcel" class="bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Export Excel</span>
            </div>

            <select id="entries" name="entries" class="px-5 py-2 border rounded-md text-gray-700 cursor-pointer w-full md:w-[300px]">
                <option>10 Data</option>
                <option>25 Data</option>
                <option>50 Data</option>
                <option>100 Data</option>
            </select>

            <form id="searchForm" class="w-full">
                <input type="text" id="searchInput" name="search"
                    class="w-full border py-2 px-5 rounded-md" placeholder="Cari nama pelanggan">
            </form>
        </div>

        <div class="relative overflow-x-auto mt-5">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="text-md text-gray-700 uppercase border-b">
                    <tr>
                        <th scope="col" class="px-6 py-5">
                            No
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Nama pelanggan
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Tanggal penjualan
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Total harga
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Dibuat oleh
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 font-medium">
                            1
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            non memberrrrr
                        </td>
                        <td class="px-6 py-4">
                            12 agustus
                        </td>
                        <td class="px-6 py-4">
                            Rp 30.000
                        </td>
                        <td class="px-6 py-4">
                            cashier
                        </td>
                        <td class="px-6 py-4 flex items-center justify-start gap-2">
                            <div class="view-purchase bg-yellow-500 hover:bg-yellow-600 p-2 rounded-md cursor-pointer">
                                <x-heroicon-o-eye class="w-4 h-4 text-white" />
                            </div>
                            <div class="bg-red-500 hover:bg-red-600 p-2 rounded-md cursor-pointer">
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-white" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

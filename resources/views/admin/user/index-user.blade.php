@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl">Pengguna</h1>

    <div class="p-5 border mt-5">
        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 md:gap-5">
            <a href="/admin/create/user"
                class="bg-blue-500 hover:bg-blue-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Tambah Pengguna</span>
            </a>

            <select id="entries" name="entries"
                class="px-5 py-2 border rounded-md text-gray-700 cursor-pointer w-full md:w-[300px]">
                <option>10 Data</option>
                <option>25 Data</option>
                <option>50 Data</option>
                <option>100 Data</option>
                <option>200 Data</option>
            </select>

            <form id="searchForm" class="w-full">
                <input type="text" id="searchInput" name="search" class="w-full border py-2 px-5 rounded-md"
                    placeholder="Cari nama pengguna">
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
                            Email
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b">
                        <th scope="row" class="px-6 py-4 font-medium">
                            1
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium">
                            1
                        </th>
                        <td class="px-6 py-4">
                            1
                        </td>
                        <td class="px-6 py-4">
                            1
                        </td>
                        <td class="px-6 py-4 flex items-center justify-start gap-2">
                            <a href=""
                                class="bg-yellow-500 hover:bg-yellow-600 p-2 rounded-md cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-4 h-4 text-white" />
                            </a>
                            <form action="" method="POST"
                                onsubmit="return">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 p-2 rounded-md cursor-pointer">
                                    <x-heroicon-o-trash class="w-4 h-4 text-white" />
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

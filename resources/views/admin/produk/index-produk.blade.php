@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl">Produk</h1>

    {{-- <div class="p-5 border mt-5">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif --}}

        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 md:gap-5 mt-5">
            <a href="/admin/create/produk"
                class="bg-blue-500 hover:bg-blue-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Tambah produk</span>
            </a>

            <div id="exportExcel"
                class="bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Export Excel</span>
            </div>

            <select id="entries" name="entries"
                class="px-5 py-2 border rounded-md text-gray-700 cursor-pointer w-full md:w-[300px]">
                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10 Data</option>
                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25 Data</option>
                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50 Data</option>
                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100 Data</option>
            </select>

            <form id="searchForm" class="w-full">
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                    class="w-full border py-2 px-5 rounded-md" placeholder="Cari nama produk">
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
                            Foto produk
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Nama produk
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Stok
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $index => $item)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4">{{ ($produk->currentPage() - 1) * $produk->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <img src="{{ asset('storage/' . $item->foto_produk) }}" alt="Foto Produk"
                                    class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $item->nama_produk }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $item->stok }}</td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <a href="{{ route('admin.produk.edit', $item->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 p-2 rounded-md">
                                    <x-heroicon-o-pencil-square class="w-4 h-4 text-white" />
                                </a>
                                <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirmDelete(event);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 p-2 rounded-md">
                                        <x-heroicon-o-trash class="w-4 h-4 text-white" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada produk yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $produk->appends(['search' => request('search'), 'entries' => request('entries')])->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    {{-- script export excel --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("exportExcel").addEventListener("click", function() {
                const url = new URL(window.location.href);
                url.searchParams.set("export", "true");

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    let workbook = XLSX.utils.book_new();

                    let excelData = data.map(item => ({
                        'Nama Produk': item.nama_produk,
                        'Harga': `Rp ${Number(item.harga).toLocaleString('id-ID')}`,
                        'Stok': item.stok
                    }));

                    let worksheet = XLSX.utils.json_to_sheet(excelData);

                    XLSX.utils.book_append_sheet(workbook, worksheet, "Produk");

                    XLSX.writeFile(workbook, "Produk.xlsx", {
                        bookType: "xlsx",
                        type: "binary"
                    });

                    Swal.close();
                })
            });
        });
    </script>

    {{-- alert dari sweet alert --}}
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            let form = event.target;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data produk akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    {{-- script search data dan entries data --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const entriesSelect = document.getElementById("entries");
            const searchInput = document.getElementById("searchInput");

            entriesSelect.addEventListener("change", function() {
                updateURL();
            });

            searchInput.addEventListener("input", function() {
                updateURL();
            });

            function updateURL() {
                const entries = entriesSelect.value;
                const search = searchInput.value;
                const url = new URL(window.location.href);
                url.searchParams.set("entries", entries);
                if (search) {
                    url.searchParams.set("search", search);
                } else {
                    url.searchParams.delete("search");
                }
                window.location.href = url.toString();
            }
        });
    </script>
@endsection

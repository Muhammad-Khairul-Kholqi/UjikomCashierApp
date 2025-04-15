@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Member</h1>

    <div class="p-5 border mt-5">
        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 md:gap-5">
            <a href="{{ route('cashier.member.create-member') }}" class="bg-blue-500 hover:bg-blue-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Tambah Member</span>
            </a>

            <select id="entries" name="entries" class="px-5 py-2 border rounded-md text-gray-700 cursor-pointer w-full md:w-[300px]">
                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10 Data</option>
                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25 Data</option>
                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50 Data</option>
                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100 Data</option>
            </select>

            <form id="searchForm" class="w-full">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                        class="w-full border py-2 px-5 rounded-md" placeholder="Cari nama member">
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
                            Nama Member
                        </th>
                        <th scope="col" class="px-6 py-5">
                            No Telpon
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Total Poin
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Dibuat Oleh
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Tanggal Bergabung
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 font-semibold">{{ $member->name }}</td>
                            <td class="py-3 px-4">{{ $member->phone_number }}</td>
                            <td class="py-3 px-4">{{ $member->points }}</td>
                            <td class="py-3 px-4">
                                @if ($member->creator)
                                    {{ $member->creator->name }}
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $member->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }}</td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('cashier.member.edit', $member) }}" class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-3 rounded-md">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('cashier.member.destroy', $member) }}" class="inline" onsubmit="return confirmDelete(event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-white bg-red-500 hover:bg-red-600 py-2 px-3 rounded-md ">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-4 text-center">Tidak ada data member</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            {{ $members->appends(['search' => request('search'), 'entries' => request('entries')])->links() }}
        </div>
    </div>
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            let form = event.target;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data member akan dihapus permanen!",
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const entriesSelect = document.getElementById("entries");
            const searchInput = document.getElementById("searchInput");

            entriesSelect.addEventListener("change", function () {
                updateURL();
            });

            searchInput.addEventListener("input", function () {
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

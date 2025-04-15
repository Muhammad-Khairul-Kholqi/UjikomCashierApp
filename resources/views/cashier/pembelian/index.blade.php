@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Pembelian</h1>

    <div class="p-5 border mt-5">
        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 md:gap-5">
            <a href="{{ route('cashier.pembelian.create') }}" class="bg-blue-500 hover:bg-blue-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Tambah penjualan</span>
            </a>

            <div id="exportExcel" class="bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-md cursor-pointer w-full md:w-[300px] flex justify-center">
                <span>Export Excel</span>
            </div>

            <select id="entries" name="entries" class="px-5 py-2 border rounded-md text-gray-700 cursor-pointer w-full md:w-[300px]">
                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10 Data</option>
                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25 Data</option>
                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50 Data</option>
                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100 Data</option>
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
                            Tanggal pembelian
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
                    @forelse ($pembelian as $index => $items )
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium">
                                {{ ($pembelian->currentPage() - 1) * $pembelian->perPage() + $loop->iteration }}
                            </th>
                            <td class="px-6 py-4 font-bold text-gray-700">
                                {{ $items->member->name ?? 'Non-member' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $items->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                Rp {{ number_format($items->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $cashierName }}
                            </td>

                            <td class="px-6 py-4 flex items-center justify-start gap-2">
                                <td class="px-6 py-4 flex items-center justify-start gap-2">
                                    <div data-id="{{ $items->id }}" class="view-purchase bg-yellow-500 hover:bg-yellow-600 p-2 rounded-md cursor-pointer"><x-heroicon-o-eye class="w-4 h-4 text-white" /></div>
                                    <div class="bg-red-500 hover:bg-red-600 p-2 rounded-md cursor-pointer"><x-heroicon-o-arrow-down-on-square class="w-4 h-4 text-white" /></div>
                                </td>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada produk yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
                <div class="bg-white p-5 rounded-lg max-w-3xl w-full">
                    <h2 class="text-xl font-bold mb-4">Detail Pembelian</h2>
                    <div id="modalContent" class="space-y-3">
                    </div>
                    <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-5">
            {{ $pembelian->appends(['search' => request('search'), 'entries' => request('entries')])->links() }}
        </div>

        <div id="purchaseDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden h-full w-full z-50 flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-lg max-w-4xl w-full mx-4 md:mx-auto">
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Detail Pembelian
                    </h3>
                    <button type="button" id="closeModal" class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Informasi Pelanggan</h4>
                            <div class="space-y-1">
                                <p><span class="font-medium">Status:</span> <span id="customerStatus"></span></p>
                                <p id="customerPhoneSection" class="hidden"><span class="font-medium">No HP:</span> <span id="customerPhone"></span></p>
                                <p id="customerPointsSection" class="hidden"><span class="font-medium">Poin Member:</span> <span id="customerPoints"></span></p>
                                <p id="customerJoinDateSection" class="hidden"><span class="font-medium">Tanggal Bergabung:</span> <span id="customerJoinDate"></span></p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Informasi Transaksi</h4>
                            <div class="space-y-1">
                                <p><span class="font-medium">No. Transaksi:</span> <span id="transactionId"></span></p>
                                <p><span class="font-medium">Tanggal Transaksi:</span> <span id="transactionDate"></span></p>
                                <p><span class="font-medium">Petugas:</span> <span id="transactionEmployee"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto rounded-md border">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Produk</th>
                                    <th scope="col" class="px-6 py-3 text-center">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-right">Harga</th>
                                    <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total Bayar:</span>
                            <span id="totalAmount" class="font-bold text-xl"></span>
                        </div>
                        <div id="paymentDetailsSection" class="mt-2 space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Pembayaran:</span>
                                <span id="paymentAmount"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Kembalian:</span>
                                <span id="changeAmount"></span>
                            </div>
                        </div>
                        <div id="pointsDetailsSection" class="mt-2 hidden">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Poin yang digunakan:</span>
                                <span id="pointsUsed"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Poin yang didapat:</span>
                                <span id="pointsEarned"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end p-6 border-t border-gray-200 rounded-b">
                    <button id="closeModalButton" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function exportTableToExcel() {
                try {
                    const exportBtn = document.getElementById("exportExcel");
                    const originalText = exportBtn.innerHTML;
                    exportBtn.innerHTML = "<span>Loading...</span>";

                    fetch(`${window.location.pathname}?export=true`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const wb = XLSX.utils.book_new();

                        const ws = XLSX.utils.json_to_sheet(data.map((item, index) => {
                            return {
                                'No': index + 1,
                                'Nama Pelanggan': item.member ? item.member.name : 'Non-member',
                                'Tanggal Pembelian': new Date(item.created_at).toLocaleString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }),
                                'Total Harga': `Rp ${item.total_amount.toLocaleString('id-ID')}`,
                                'Dibuat Oleh': item.cashier_name
                            };
                        }));

                        const wscols = [
                            {wch: 5},
                            {wch: 25},
                            {wch: 25},
                            {wch: 20},
                            {wch: 20},
                        ];
                        ws['!cols'] = wscols;

                        XLSX.utils.book_append_sheet(wb, ws, "Pembelian");

                        XLSX.writeFile(wb, "Data_Pembelian.xlsx");

                        exportBtn.innerHTML = originalText;
                    })
                    .catch(error => {
                        console.error("Export error:", error);
                        alert("Terjadi kesalahan saat mengekspor data: " + error.message);
                        exportBtn.innerHTML = originalText;
                    });

                    return true;
                } catch (error) {
                    console.error("Export error:", error);
                    alert("Terjadi kesalahan saat mengekspor data: " + error.message);
                    return false;
                }
            }

            const exportButton = document.getElementById("exportExcel");
            if (exportButton) {
                exportButton.addEventListener("click", exportTableToExcel);
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const entriesSelect = document.getElementById("entries");
            const searchInput = document.getElementById("searchInput");
            const downloadButtons = document.querySelectorAll(".bg-red-500.hover\\:bg-red-600");

            downloadButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const purchaseId = this.parentElement.querySelector(".view-purchase").getAttribute("data-id");
                    exportPdf(purchaseId);
                });
            });

            function exportPdf(id) {
                window.location.href = `/cashier/pembelian/${id}/pdf`;
            }


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

        document.addEventListener("DOMContentLoaded", function () {
            const entriesSelect = document.getElementById("entries");
            const searchInput = document.getElementById("searchInput");
            const modal = document.getElementById("purchaseDetailModal");
            const closeModalBtn = document.getElementById("closeModal");
            const closeModalButtonBtn = document.getElementById("closeModalButton");

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

            const viewButtons = document.querySelectorAll(".view-purchase");

            viewButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const purchaseId = this.getAttribute("data-id");
                    fetchPurchaseDetails(purchaseId);
                });
            });

            closeModalBtn.addEventListener("click", closeModal);
            closeModalButtonBtn.addEventListener("click", closeModal);

            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            function closeModal() {
                modal.classList.add("hidden");
                document.body.style.overflow = "auto";
            }

            function openModal() {
                modal.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            }

            function fetchPurchaseDetails(id) {
                openModal();
                document.getElementById("productTableBody").innerHTML = '<tr><td colspan="4" class="text-center py-4">Loading...</td></tr>';

                fetch(`/cashier/pembelian/${id}/detail`)
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`Network response error (${response.status}): ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateModalWithData(data);
                    })
                    .catch(error => {
                        console.error("Error fetching purchase details:", error);
                        document.getElementById("productTableBody").innerHTML =
                            `<tr><td colspan="4" class="text-center py-4 text-red-500">
                                Error loading data: ${error.message}
                            </td></tr>`;
                    });
            }

            function populateModalWithData(data) {
                console.log("Purchase data:", data);

                document.getElementById("customerStatus").textContent = data.status === 'member' ? 'Member' : 'Non-Member';

                const phoneSection = document.getElementById("customerPhoneSection");
                const pointsSection = document.getElementById("customerPointsSection");
                const joinDateSection = document.getElementById("customerJoinDateSection");
                const pointsDetailsSection = document.getElementById("pointsDetailsSection");

                if (data.status === 'member' && data.member) {
                    phoneSection.classList.remove("hidden");
                    pointsSection.classList.remove("hidden");
                    joinDateSection.classList.remove("hidden");

                    document.getElementById("customerPhone").textContent = data.member.phone_number;
                    document.getElementById("customerPoints").textContent = data.member.points;
                    document.getElementById("customerJoinDate").textContent = formatDate(data.member.created_at);

                    if (data.points_used > 0 || data.points_earned > 0) {
                        pointsDetailsSection.classList.remove("hidden");
                        document.getElementById("pointsUsed").textContent = data.points_used;
                        document.getElementById("pointsEarned").textContent = data.points_earned;
                    } else {
                        pointsDetailsSection.classList.add("hidden");
                    }
                } else {
                    phoneSection.classList.add("hidden");
                    pointsSection.classList.add("hidden");
                    joinDateSection.classList.add("hidden");
                    pointsDetailsSection.classList.add("hidden");
                }

                document.getElementById("transactionId").textContent = data.id;
                document.getElementById("transactionDate").textContent = formatDate(data.created_at);
                document.getElementById("transactionEmployee").textContent = "Petugas";

                const tableBody = document.getElementById("productTableBody");
                tableBody.innerHTML = '';

                console.log("Sales details:", data.sales_details);

                if (data.sales_details && Array.isArray(data.sales_details)) {
                    data.sales_details.forEach(item => {
                        console.log("Sales detail item:", item);

                        const row = document.createElement('tr');
                        row.className = 'bg-white border-b';

                        const productName = item.product ? (item.product.nama_produk || 'Produk tidak ditemukan') : 'Produk tidak ditemukan';

                        row.innerHTML = `
                            <td class="px-6 py-4 font-medium text-gray-900">${productName}</td>
                            <td class="px-6 py-4 text-center">${item.quantity}</td>
                            <td class="px-6 py-4 text-right">Rp ${formatNumber(item.price)}</td>
                            <td class="px-6 py-4 text-right">Rp ${formatNumber(item.subtotal)}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada data produk</td></tr>';
                }

                document.getElementById("totalAmount").textContent = `Rp ${formatNumber(data.total_amount)}`;
                document.getElementById("paymentAmount").textContent = `Rp ${formatNumber(data.payment)}`;
                document.getElementById("changeAmount").textContent = `Rp ${formatNumber(data.change)}`;
            }

            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }

            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endsection

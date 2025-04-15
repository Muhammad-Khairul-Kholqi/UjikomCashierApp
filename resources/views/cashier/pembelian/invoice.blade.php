@extends('layouts.cashier-layouts')

@section('content-cashier')
    <div class="flex justify-between items-center mb-5">
        <h1 class="font-bold text-2xl">Invoice Penjualan</h1>
        <button onclick="printPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Unduh PDF
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="border p-5 shadow-md rounded-xl mt-5" id="invoice-content">
        <div class="mb-5 flex justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Invoice #{{ $sales->id }}</h2>
                <p class="text-gray-600">{{ $sales->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">Status: {{ ucfirst($sales->status) }}</p>
                @if($sales->member_id)
                    <p class="text-gray-600">Member: {{ $sales->member->name }}</p>
                    <p class="text-gray-600">Phone: {{ $sales->member->phone_number }}</p>
                @endif
            </div>
        </div>

        <div class="mb-5">
            <h3 class="text-xl font-semibold mb-3">Detail Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2 text-left">Nama Produk</th>
                            <th class="border p-2 text-right">Harga</th>
                            <th class="border p-2 text-center">Jumlah</th>
                            <th class="border p-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales->salesDetails as $detail)
                            <tr>
                                <td class="border p-2">{{ $detail->product->nama_produk }}</td>
                                <td class="border p-2 text-right">Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="border p-2 text-center">{{ $detail->quantity }}</td>
                                <td class="border p-2 text-right">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="border p-2 text-right font-semibold">Total:</td>
                            <td class="border p-2 text-right font-bold">Rp. {{ number_format($sales->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border p-2 text-right font-semibold">Pembayaran:</td>
                            <td class="border p-2 text-right">Rp. {{ number_format($sales->payment, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border p-2 text-right font-semibold">Kembalian:</td>
                            <td class="border p-2 text-right">Rp. {{ number_format($sales->change, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-center mt-8 text-gray-500">
            <p>Terima kasih telah berbelanja di toko kami!</p>
        </div>
    </div>

    <div class="flex items-center gap-5 mt-5">
        <a href="{{ route('cashier.pembelian.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-semibold">Pembelian Baru</a>
        <a href="{{ route('cashier.pembelian.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-lg font-semibold">Daftar Pembelian</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('clearCart'))
                localStorage.removeItem('selectedProducts');
            @endif

            if (typeof html2canvas === 'undefined' || typeof jspdf === 'undefined') {
                loadLibraries();
            }
        });

        function loadLibraries() {
            var html2canvasScript = document.createElement('script');
            html2canvasScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
            html2canvasScript.async = true;
            document.head.appendChild(html2canvasScript);

            var jsPDFScript = document.createElement('script');
            jsPDFScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
            jsPDFScript.async = true;
            document.head.appendChild(jsPDFScript);
        }

        function printPDF() {
            const btn = document.querySelector('button[onclick="printPDF()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="inline-block mr-2">Loading...</span>';

            const element = document.getElementById('invoice-content');

            const clonedElement = element.cloneNode(true);
            const tempDiv = document.createElement('div');
            tempDiv.appendChild(clonedElement);
            document.body.appendChild(tempDiv);
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';

            const styleFixElement = document.createElement('style');
            styleFixElement.textContent = `
                * {
                    color: black !important;
                    background-color: white !important;
                    border-color: black !important;
                }
                .bg-gray-50, .bg-gray-100 {
                    background-color: #f9fafb !important;
                }
                .text-gray-500, .text-gray-600 {
                    color: #6b7280 !important;
                }
            `;
            tempDiv.appendChild(styleFixElement);

            if (typeof html2pdf !== 'undefined') {
                const opt = {
                    margin: 10,
                    filename: 'invoice-{{ $sales->id }}.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        logging: true,
                        allowTaint: true,
                        backgroundColor: '#ffffff'
                    },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().from(clonedElement).set(opt).save()
                    .then(() => {
                        document.body.removeChild(tempDiv);
                        btn.innerHTML = originalText;
                        console.log('PDF generated successfully');
                    })
                    .catch(error => {
                        console.error('Error in PDF generation:', error);
                        document.body.removeChild(tempDiv);
                        btn.innerHTML = originalText;
                        alert('Terjadi kesalahan saat membuat PDF: ' + error.message);
                    });
            } else {
                alert('Library html2pdf tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                btn.innerHTML = originalText;
                document.body.removeChild(tempDiv);
            }
        }
    </script>
@endsection

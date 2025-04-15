<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $sales->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            vertical-align: top;
            padding: 5px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table.items td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="bold text-xl">BelanjaDisini.</h1>
        <p>Jl Sumatera 112-114, Surabaya, Jawa Timur, Indonesia</p>
        <div class="header">
            <h1>INVOICE</h1>
            <p>No. Transaksi: #{{ $sales->id }}</p>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Informasi Pelanggan:</strong><br>
                        {{ $sales->member ? 'Member: ' . $sales->member->name : 'Non-Member' }}<br>
                        @if($sales->member)
                            No HP: {{ $sales->member->phone_number }}<br>
                            Poin Member: {{ $sales->member->points }}<br>
                        @endif
                    </td>
                    <td width="50%">
                        <strong>Informasi Transaksi:</strong><br>
                        Tanggal: {{ \Carbon\Carbon::parse($sales->created_at)->timezone('Asia/Jakarta')->format('d F Y, H:i') }}<br>
                        Petugas: {{ $sales->employee ? $sales->employee->name : '-' }}<br>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-details">
            <table class="items">
                <thead>
                    <tr>
                        <th width="50%">Nama Produk</th>
                        <th width="15%" class="text-center">Quantity</th>
                        <th width="15%" class="text-right">Harga</th>
                        <th width="20%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales->salesDetails as $item)
                        <tr>
                            <td>{{ $item->product ? $item->product->nama_produk : 'Produk #' . $item->product_id }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>Total Bayar:</span>
                <span class="bold">Rp {{ number_format($sales->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Pembayaran:</span>
                <span>Rp {{ number_format($sales->payment, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($sales->change, 0, ',', '.') }}</span>
            </div>

            @if($sales->member && ($pointsUsed > 0 || $pointsEarned > 0))
                <div class="total-row">
                    <span>Poin yang digunakan:</span>
                    <span>{{ $pointsUsed }}</span>
                </div>
                <div class="total-row">
                    <span>Poin yang didapat:</span>
                    <span>{{ $pointsEarned }}</span>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Terima kasih atas pembelian Anda.</p>
            <p>Dokumen ini dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>

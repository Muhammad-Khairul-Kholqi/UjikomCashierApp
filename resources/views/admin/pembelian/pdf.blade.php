<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #34</title>
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
        <div class="header">
            <h1>INVOICE</h1>
            <p>No. Transaksi: #12</p>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Informasi Pelanggan:</strong><br>
                        No HP: 0877722<br>
                        Poin Member: 0745672<br>
                    </td>
                    <td width="50%">
                        <strong>Informasi Transaksi:</strong><br>
                        Tanggal: 12 agustus 9090<br>
                        Petugas: saya<br>
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
                    <tr>
                        <td>Produk #12</td>
                        <td class="text-center">12</td>
                        <td class="text-right">Rp 23.000</td>
                        <td class="text-right">Rp 34.000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>Total Bayar:</span>
                <span class="bold">Rp 32.000</span>
            </div>
            <div class="total-row">
                <span>Pembayaran:</span>
                <span>Rp 36.000</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp 34.000</span>
            </div>

            <div class="total-row">
                <span>Poin yang digunakan:</span>
                <span>35</span>
            </div>
            <div class="total-row">
                <span>Poin yang didapat:</span>
                <span>35</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembelian Anda.</p>
            <p>Dokumen ini dicetak pada: 12 juli 2020222</p>
        </div>
    </div>
</body>
</html>

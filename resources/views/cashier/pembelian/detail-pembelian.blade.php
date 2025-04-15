@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Detail Pesanan</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="border p-5 shadow-md rounded-xl mt-5">
        <form action="{{ route('cashier.pembelian.store-temp') }}" method="POST" id="checkoutForm" onsubmit="return validateForm()">
            @csrf
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-3">Detail Produk</h2>
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
                        <tbody id="productDetails">
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="border p-2 text-right font-semibold">Total:</td>
                                <td class="border p-2 text-right font-bold" id="grandTotal">Rp. 0</td>
                                <input type="hidden" name="total_amount" id="total_amount_input">
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-3">Detail Pembayaran</h2>
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block mb-2 font-medium">Status Pelanggan:</label>
                        <select name="status" id="status" class="w-full p-2 border rounded" onchange="toggleMemberField()">
                            <option value="non-member">Non-Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <div id="memberField" class="hidden">
                        <label for="member_phone" class="block mb-2 font-medium">No. Telepon Member:</label>
                        <input type="text" name="member_phone" id="member_phone" class="w-full p-2 border rounded" placeholder="Masukkan nomor telepon member">
                        <p id="member_phone_error" class="text-red-500 text-sm hidden">Nomor telepon member harus diisi</p>
                    </div>

                    <div id="member_not_found_message" class="text-red-500 text-sm mt-2 hidden">
                        Nomor tidak terdaftar sebagai member.
                        <a href="{{ route('cashier.member.create-member') }}" class="text-blue-600 hover:underline font-semibold">Buat member baru?</a>
                    </div>

                    <div>
                        <label for="payment" class="block mb-2 font-medium">Total Bayar:</label>
                        <input type="number" name="payment" id="payment" class="w-full p-2 border rounded" placeholder="Rp. 0" onkeyup="calculateChange()" required>
                        <p id="payment_error" class="text-red-500 text-sm hidden">Pembayaran harus diisi dan tidak boleh kurang dari total belanja</p>
                        <p id="payment_limit_error" class="text-red-500 text-sm hidden">Nominal terlalu besar</p>
                    </div>

                    <div>
                        <label for="change" class="block mb-2 font-medium">Kembalian:</label>
                        <input type="text" name="change_display" id="change" class="w-full p-2 border rounded bg-gray-100" readonly>
                        <input type="hidden" name="change" id="change_value">
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('cashier.pembelian.create') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-lg font-semibold">Kembali</a>
                <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-semibold">Pesan</button>
            </div>
        </form>
    </div>

    <script>
        let selectedProducts = [];

        window.onload = function() {
            const storedProducts = localStorage.getItem('selectedProducts');
            if (storedProducts) {
                selectedProducts = JSON.parse(storedProducts);
                selectedProducts = selectedProducts.filter(product => product.quantity > 0);

                if (selectedProducts.length === 0) {
                    alert('Tidak ada produk yang dipilih');
                    window.location.href = "{{ route('cashier.pembelian.create') }}";
                    return;
                }

                displayProducts();
                calculateTotal();
            } else {
                alert('Tidak ada produk yang dipilih');
                window.location.href = "{{ route('cashier.pembelian.create') }}";
            }

            @if(session('member_not_found'))
            document.getElementById('status').value = 'member';
            toggleMemberField();
            document.getElementById('member_phone').value = "{{ session('member_not_found') }}";
            document.getElementById('member_not_found_message').classList.remove('hidden');
            @endif
        };

        function displayProducts() {
            const tbody = document.getElementById('productDetails');
            tbody.innerHTML = '';

            selectedProducts.forEach(product => {
                if (product.quantity > 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border p-2">${product.name}
                            <input type="hidden" name="product_ids[]" value="${product.id}">
                            <input type="hidden" name="product_names[]" value="${product.name}">
                        </td>
                        <td class="border p-2 text-right">Rp. ${formatNumber(product.price)}
                            <input type="hidden" name="prices[]" value="${product.price}">
                        </td>
                        <td class="border p-2 text-center">${product.quantity}
                            <input type="hidden" name="quantities[]" value="${product.quantity}">
                        </td>
                        <td class="border p-2 text-right">Rp. ${formatNumber(product.subtotal)}
                            <input type="hidden" name="subtotals[]" value="${product.subtotal}">
                        </td>
                    `;
                    tbody.appendChild(row);
                }
            });
        }

        function calculateTotal() {
            let total = 0;
            selectedProducts.forEach(product => {
                if (product.quantity > 0) {
                    total += product.subtotal;
                }
            });

            document.getElementById('grandTotal').innerText = 'Rp. ' + formatNumber(total);
            document.getElementById('total_amount_input').value = total;

            document.getElementById('payment').value = '';
            document.getElementById('change').value = '';
        }

        function toggleMemberField() {
            const status = document.getElementById('status').value;
            const memberField = document.getElementById('memberField');

            if (status === 'member') {
                memberField.classList.remove('hidden');
                document.getElementById('member_phone').setAttribute('required', 'required');
            } else {
                memberField.classList.add('hidden');
                document.getElementById('member_phone').removeAttribute('required');
                document.getElementById('member_phone').value = '';
                document.getElementById('member_phone_error').classList.add('hidden');
                document.getElementById('member_not_found_message').classList.add('hidden');
            }
        }

        function calculateChange() {
            const totalAmount = parseFloat(document.getElementById('total_amount_input').value);
            const payment = parseFloat(document.getElementById('payment').value) || 0;

            const change = payment - totalAmount;

            if (change >= 0) {
                document.getElementById('change').value = 'Rp. ' + formatNumber(change);
                document.getElementById('change_value').value = change;
                document.getElementById('payment_error').classList.add('hidden');
            } else {
                document.getElementById('change').value = 'Pembayaran kurang';
                document.getElementById('change_value').value = '';
                document.getElementById('payment_error').classList.remove('hidden');
            }
        }

        function validateForm() {
            let isValid = true;
            const status = document.getElementById('status').value;
            const memberPhone = document.getElementById('member_phone').value;
            const totalAmount = parseFloat(document.getElementById('total_amount_input').value);
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            const paymentLimit = 9999999999999;

            if (status === 'member' && !memberPhone.trim()) {
                document.getElementById('member_phone_error').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('member_phone_error').classList.add('hidden');
            }

            if (payment < totalAmount) {
                document.getElementById('payment_error').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('payment_error').classList.add('hidden');
            }

            if (payment > paymentLimit) {
                document.getElementById('payment_limit_error').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('payment_limit_error').classList.add('hidden');
            }

            return isValid;
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function calculateChange() {
            const totalAmount = parseFloat(document.getElementById('total_amount_input').value);
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            const paymentLimit = 9999999999999;

            if (payment > paymentLimit) {
                document.getElementById('payment_limit_error').classList.remove('hidden');
            } else {
                document.getElementById('payment_limit_error').classList.add('hidden');
            }

            const change = payment - totalAmount;

            if (change >= 0) {
                document.getElementById('change').value = 'Rp. ' + formatNumber(change);
                document.getElementById('change_value').value = change;
                document.getElementById('payment_error').classList.add('hidden');
            } else {
                document.getElementById('change').value = 'Pembayaran kurang';
                document.getElementById('change_value').value = '';
                document.getElementById('payment_error').classList.remove('hidden');
            }
        }
    </script>
@endsection

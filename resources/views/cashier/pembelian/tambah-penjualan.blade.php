@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Tambah Penjualan</h1>

    <div class="mb-[100px]">
        <div class="border p-5 shadow-md rounded-xl mt-5">
            <div class="grid gap-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                @forelse ($produk as $index => $items)
                    <div class="border p-5 rounded-lg flex flex-col items-center space-y-2" data-id="{{ $items->id }}">
                        <img class="rounded-md w-[200px] h-[200px]" src="{{ asset('storage/' . $items->foto_produk) }}" alt="">
                        <h1 class="text-xl font-medium">{{ $items->nama_produk }}</h1>
                        <h2 class="text-gray-600 text-lg">Stok {{ $items->stok }}</h2>
                        <h3 class="text-lg">Rp {{ number_format($items->harga, 0, ',', '.') }}</h3>
                        <div class="flex items-center gap-3">
                            <button class="bg-white text-3xl px-3" onclick="decreaseQuantity(this)">-</button>
                            <p class="font-semibold text-lg quantity">0</p>
                            <button class="bg-white text-3xl px-3" onclick="increaseQuantity(this, {{ $items->stok }})">+</button>
                        </div>
                        <h3 class="text-lg">Sub Total <span class="font-semibold subtotal">Rp. 0</span></h3>
                    </div>
                    @empty
                        <p class="text-center py-4">Tidak ada produk yang ditemukan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 w-full bg-white p-4 border-t-2 rounded-t-2xl flex justify-center">
        <div class="w-full max-w-[1460px] flex justify-between items-center">
            <div>
                <h3 class="text-md font-semibold" id="totalItems">Total Item: 0</h3>
                <h3 class="text-md font-semibold" id="totalPrice">Total Harga: Rp. 0</h3>
            </div>
            <button onclick="processCheckout()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-semibold">Selanjutnya</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storedProducts = localStorage.getItem('selectedProducts');
            if (storedProducts) {
                const products = JSON.parse(storedProducts);

                products.forEach(product => {
                    const productElement = document.querySelector(`[data-id="${product.id}"]`);
                    if (productElement) {
                        const quantityElement = productElement.querySelector('.quantity');
                        const subtotalElement = productElement.querySelector('.subtotal');

                        quantityElement.innerText = product.quantity;
                        subtotalElement.innerText = `Rp. ${formatNumber(product.subtotal)}`;
                    }
                });

                updateTotals();
            }
        });

        function increaseQuantity(button, maxStock) {
            let productContainer = button.closest(".border.p-5.rounded-lg");
            let quantityElement = button.previousElementSibling;
            let quantity = parseInt(quantityElement.innerText);

            if (quantity < maxStock) {
                quantityElement.innerText = quantity + 1;
                updateSubtotal(button, quantity + 1);
                updateProductsData();
                updateTotals();
            } else {
                alert("Stok tidak mencukupi!");
            }
        }

        function decreaseQuantity(button) {
            let quantityElement = button.nextElementSibling;
            let quantity = parseInt(quantityElement.innerText);
            if (quantity > 0) {
                quantityElement.innerText = quantity - 1;
                updateSubtotal(button, quantity - 1);
                updateProductsData();
                updateTotals();
            }
        }

        function updateSubtotal(button, quantity) {
            let productContainer = button.closest(".border.p-5.rounded-lg");
            let priceElement = productContainer.querySelector("h3").innerText;
            let price = parseInt(priceElement.replace("Rp ", "").replace(/\./g, ""));
            let subtotalElement = productContainer.querySelector(".subtotal");
            let subtotal = price * quantity;
            subtotalElement.innerText = `Rp. ${formatNumber(subtotal)}`;
        }

        function updateProductsData() {
            let products = [];
            let productContainers = document.querySelectorAll(".border.p-5.rounded-lg[data-id]");

            productContainers.forEach(container => {
                let id = container.getAttribute('data-id');
                let name = container.querySelector("h1").innerText;
                let priceText = container.querySelector("h3").innerText;
                let price = parseInt(priceText.replace("Rp ", "").replace(/\./g, ""));
                let quantity = parseInt(container.querySelector(".quantity").innerText);
                let subtotalText = container.querySelector(".subtotal").innerText;
                let subtotal = parseInt(subtotalText.replace("Rp. ", "").replace(/\./g, ""));

                products.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: quantity,
                    subtotal: subtotal
                });
            });

            localStorage.setItem('selectedProducts', JSON.stringify(products));
        }

        function updateTotals() {
            let totalItems = 0;
            let totalPrice = 0;

            let productContainers = document.querySelectorAll(".border.p-5.rounded-lg[data-id]");
            productContainers.forEach(container => {
                let quantity = parseInt(container.querySelector(".quantity").innerText);
                let subtotalText = container.querySelector(".subtotal").innerText;
                let subtotal = parseInt(subtotalText.replace("Rp. ", "").replace(/\./g, ""));

                totalItems += quantity;
                totalPrice += subtotal;
            });

            document.getElementById('totalItems').innerText = `Total Item: ${totalItems}`;
            document.getElementById('totalPrice').innerText = `Total Harga: Rp. ${formatNumber(totalPrice)}`;
        }

        function processCheckout() {
            let totalItems = 0;
            let quantityElements = document.querySelectorAll('.quantity');
            quantityElements.forEach(el => {
                totalItems += parseInt(el.innerText);
            });

            if (totalItems === 0) {
                alert('Tidak ada produk yang dipilih!');
                return;
            }

            updateProductsData();
            window.location.href = "{{ route('cashier.pembelian.checkout') }}";
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    </script>
@endsection

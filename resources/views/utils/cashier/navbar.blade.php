<div class="flex justify-center mt-[80px] bg-white border-b border-[#DBDFE9]">
    <div class="w-full max-w-[1500px]">
        <nav class=" flex items-center gap-5 justify-start text-lg p-5">
            <a href="/cashier/dashboard" class="{{Request::is('cashier/dashboard') ? 'font-semibold' : ''}}">Dashboard</a>
            <a href="/cashier/member" class="{{Request::is('cashier/member') ? 'font-semibold' : ''}}">Member</a>
            <a href="/cashier/produk" class="{{Request::is('cashier/produk') ? 'font-semibold' : ''}}">Produk</a>
            <a href="/cashier/pembelian" class="{{Request::is('cashier/pembelian') ? 'font-semibold' : ''}}">Pembelian</a>
        </nav>
    </div>
</div>

<div class="flex justify-center mt-[80px] bg-white border-b border-[#DBDFE9]">
    <div class="w-full max-w-[1500px]">
        <nav class=" flex items-center gap-5 justify-start text-lg p-5">
            <a href="/admin/dashboard" class="{{Request::is('admin/dashboard') ? 'font-semibold' : ''}}">Dashboard</a>
            <a href="/admin/produk" class="{{Request::is('admin/produk') ? 'font-semibold' : ''}}">Produk</a>
            <a href="/admin/pembelian" class="{{Request::is('admin/pembelian') ? 'font-semibold' : ''}}">Pembelian</a>
            <a href="/admin/pengguna" class="{{Request::is('admin/pengguna') ? 'font-semibold' : ''}}">Pengguna</a>
        </nav>
    </div>
</div>

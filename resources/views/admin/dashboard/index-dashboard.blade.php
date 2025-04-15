@extends('layouts.admin-layouts')

@section('content-admin')
    <h1 class="font-bold text-2xl">Dashboard</h1>
    <div class="mt-5">
        <h2 class="text-xl">Selamat datang, Admin!</h2>
        @include('admin.dashboard.card')
        <div class="flex flex-wrap md:flex-col lg:flex-row w-full justify-between items-center md:gap-5 lg:gap-0">
            <div class="w-full lg:w-[63.5%] mt-5">
                @include('admin.dashboard.chart-pembelian-harian')
            </div>
            <div class="w-full lg:w-[35%] mt-5">
                @include('admin.dashboard.chart-persentase-produk')
            </div>
        </div>
    </div>
@endsection

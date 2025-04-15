@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Dashboard</h1>
    <div class="mt-5">
        <h2 class="text-xl">Selamat datang, Petugas!</h2>
        @include('cashier.dashboard.card')
        @include('cashier.dashboard.chart-pembelian-harian')
    </div>
@endsection

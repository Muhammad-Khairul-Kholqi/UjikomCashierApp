<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index-dashboard');
});

// produk
Route::get('/admin/produk', function () {
    return view('admin.produk.index-produk');
});

Route::get('/admin/create/produk', function () {
    return view('admin.produk.tambah-produk');
});

Route::get('/admin/edit/produk', function () {
    return view('admin.produk.edit-produk');
});

// pembelian
Route::get('/admin/pembelian', function () {
    return view('admin.pembelian.index-pembelian');
});

// user
Route::get('/admin/pengguna', function () {
    return view('admin.user.index-user');
});

Route::get('/admin/create/user', function () {
    return view('admin.user.create-user');
});

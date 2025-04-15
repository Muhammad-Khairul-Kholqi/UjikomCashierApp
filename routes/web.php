<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\AccountController;

// Auth
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexDashboardAdmin'])->name('dashboard.index-dashboard');
    Route::get('/account', [AccountController::class, 'editAccountAdmin'])->name('admin.account');
    Route::put('/account/update', [AccountController::class, 'updateAccountAdmin'])->name('admin.account.update');

    // admin - produk
    Route::get('/produk', [ProductController::class, 'indexProdukAdmin'])->name('produk');
    Route::get('/create/produk', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/store/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{produk}', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProductController::class, 'destroy'])->name('produk.destroy');

    // admin - pembelian
    Route::get('/pembelian', [PembelianController::class, 'getPembelianAdmin'])->name('admin.pembelian.index');
    Route::get('/pembelian/{id}/detail', [PembelianController::class, 'getDetailPembelianAdmin'])->name('admin.pembelian.detail');
    Route::get('/pembelian/{id}/pdf', [PembelianController::class, 'exportPembelianPDF'])->name('admin.pembelian.pdf');
    Route::get('/pembelian/export-all', [PembelianController::class, 'exportAllPembelian'])->name('admin.pembelian.export-all');

    // admin - pengguna
    Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/pengguna/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/pengguna/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Cashier
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexDashboardCashier'])->name('dashboard.index-dashboard');
    Route::get('/produk', [ProductController::class, 'indexProdukCashier'])->name('produk');
    Route::get('/account', [AccountController::class, 'editAccountCashier'])->name('cashier.account');
    Route::put('/account/update', [AccountController::class, 'updateAccountCashier'])->name('cashier.account.update');

    // cashier - member
    Route::get('/member', [MemberController::class, 'index'])->name('member.index');
    Route::get('/member/create', [MemberController::class, 'create'])->name('member.create-member');
    Route::post('/member', [MemberController::class, 'store'])->name('member.store');
    Route::get('/member/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::put('/member/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('/member/{member}', [MemberController::class, 'destroy'])->name('member.destroy');

    // cashier - pembelian
    Route::get('/pembelian', [PembelianController::class, 'getPembelianCashier'])->name('pembelian.index');
    Route::get('/pembelian/create', [SalesController::class, 'dataProduct'])->name('pembelian.create');
    Route::get('/pembelian/checkout', [SalesController::class, 'checkout'])->name('pembelian.checkout');
    Route::post('/pembelian/store-temp', [SalesController::class, 'storeTempSales'])->name('pembelian.store-temp');
    Route::get('/pembelian/finalize', [SalesController::class, 'store'])->name('pembelian.finalize');
    Route::get('/pembelian/{sales}/invoice', [SalesController::class, 'invoice'])->name('pembelian.invoice');
    Route::get('/pembelian/{sales}', [SalesController::class, 'show'])->name('pembelian.show');
    Route::get('/member-points/create', [PointController::class, 'create'])->name('member-points.create');
    Route::post('/member-points/store', [PointController::class, 'store'])->name('member-points.store');
    Route::get('/pembelian/{id}/detail', [PembelianController::class, 'getDetailPembelianCashier'])->name('pembelian.detail');
    Route::get('/pembelian/{id}/detail', [SalesController::class, 'getDetail'])->name('pembelian.detail');
    Route::get('pembelian/get-all-data', [PembelianController::class, 'getAllData']);
    Route::get('/pembelian/export-all', [PembelianController::class, 'exportAllPembelian'])->name('cashier.pembelian.export-all');
    Route::get('/pembelian/{id}/pdf', [PembelianController::class, 'exportPembelianPDF'])->name('cashier.pembelian.pdf');
});

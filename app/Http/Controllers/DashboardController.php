<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function indexDashboardAdmin()
    {
        $jumlahProduk = Product::count();
        $jumlahCashier = User::where('role', 'cashier')->count();
        $jumlahPembelianHariIni = Sales::count();
        $jumlahPembelianBulanIni = Sales::whereMonth('created_at', now()->month)->count();
        $dataChartPembelian = Sales::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        $dataChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataChart[] = $dataChartPembelian[$i] ?? 0;
        }

        $penjualanProdukHariIni = DB::table('sales')
            ->join('sales_details', 'sales.id', '=', 'sales_details.sales_id')
            ->join('products', 'sales_details.product_id', '=', 'products.id')
            ->select(
                'products.nama_produk as nama_produk',
                DB::raw('COUNT(sales_details.product_id) as jumlah_terjual')
            )
            ->whereDate('sales.created_at', Carbon::today())
            ->groupBy('products.nama_produk')
            ->get();

        $totalPenjualanHariIni = $penjualanProdukHariIni->sum('jumlah_terjual');

        $produkLabels = [];
        $produkData = [];
        $produkPersentase = [];

        foreach ($penjualanProdukHariIni as $item) {
            $produkLabels[] = $item->nama_produk;
            $produkData[] = $item->jumlah_terjual;

            if ($totalPenjualanHariIni > 0) {
                $persentase = round(($item->jumlah_terjual / $totalPenjualanHariIni) * 100, 2);
            } else {
                $persentase = 0;
            }
            $produkPersentase[] = $persentase;
        }

        return view('admin.dashboard.index-dashboard', compact(
            'jumlahProduk',
            'jumlahCashier',
            'jumlahPembelianHariIni',
            'jumlahPembelianBulanIni',
            'dataChart',
            'produkLabels',
            'produkData',
            'produkPersentase'
        ));
    }

    public function indexDashboardCashier()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $cashierName = $user->name;

        $today = Carbon::today();
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $firstDayOfYear = Carbon::now()->startOfYear();

        $jumlahProduk = Product::count();

        $pembelianHariIni = Sales::where('employee_id', $userId)
            ->whereDate('created_at', $today)
            ->count();

        $pembelianBulanIni = Sales::where('employee_id', $userId)
            ->whereDate('created_at', '>=', $firstDayOfMonth)
            ->count();

        $pembelianTahunIni = Sales::where('employee_id', $userId)
            ->whereDate('created_at', '>=', $firstDayOfYear)
            ->count();

        $penjualanPerBulan = Sales::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('employee_id', $userId)
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        $dataChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataChart[] = $penjualanPerBulan[$i] ?? 0;
        }

        // Mendapatkan data persentase penjualan produk hari ini untuk cashier
        $penjualanProdukHariIni = DB::table('sales')
            ->join('sales_details', 'sales.id', '=', 'sales_details.sales_id')
            ->join('products', 'sales_details.product_id', '=', 'products.id')
            ->select(
                'products.nama_produk as nama_produk',
                DB::raw('COUNT(sales_details.product_id) as jumlah_terjual')
            )
            ->where('sales.employee_id', $userId)
            ->whereDate('sales.created_at', Carbon::today())
            ->groupBy('products.nama_produk')
            ->get();

        // Hitung total penjualan hari ini
        $totalPenjualanHariIni = $penjualanProdukHariIni->sum('jumlah_terjual');

        // Format data untuk chart
        $produkLabels = [];
        $produkData = [];
        $produkPersentase = [];

        foreach ($penjualanProdukHariIni as $item) {
            $produkLabels[] = $item->nama_produk;
            $produkData[] = $item->jumlah_terjual;

            // Hitung persentase
            if ($totalPenjualanHariIni > 0) {
                $persentase = round(($item->jumlah_terjual / $totalPenjualanHariIni) * 100, 2);
            } else {
                $persentase = 0;
            }
            $produkPersentase[] = $persentase;
        }

        return view('cashier.dashboard.index-dashboard', compact(
            'jumlahProduk',
            'pembelianHariIni',
            'pembelianBulanIni',
            'pembelianTahunIni',
            'cashierName',
            'dataChart',
            'produkLabels',
            'produkData',
            'produkPersentase'
        ));
    }
}

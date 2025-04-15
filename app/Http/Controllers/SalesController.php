<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Product;
use App\Models\Member;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::with('salesDetails.product')->orderBy('created_at', 'desc')->get();
        return view('cashier.pembelian.index', compact('sales'));
    }

    public function dataProduct()
    {
        $produk = Product::all();
        return view('cashier.pembelian.tambah-penjualan', compact('produk'));
    }

    public function checkout()
    {
        return view('cashier.pembelian.detail-pembelian');
    }

    public function storeTempSales(Request $request)
    {
        $validatedData = $request->validate([
            'product_ids' => 'required|array',
            'quantities' => 'required|array',
            'prices' => 'required|array',
            'subtotals' => 'required|array',
            'total_amount' => 'required|numeric',
            'payment' => 'required|numeric|min:0',
            'status' => 'required|in:member,non-member',
        ]);

        if ($request->payment < $request->total_amount) {
            return redirect()->back()->with('error', 'Pembayaran kurang dari total belanja.');
        }

        $tempSalesData = [
            'product_ids' => $request->product_ids,
            'product_names' => $request->product_names,
            'quantities' => $request->quantities,
            'prices' => $request->prices,
            'subtotals' => $request->subtotals,
            'total_amount' => $request->total_amount,
            'payment' => $request->payment,
            'status' => $request->status,
            'change' => $request->payment - $request->total_amount
        ];

        Session::put('temp_sales', $tempSalesData);

        if ($request->status == 'member') {
            if (!$request->filled('member_phone')) {
                return redirect()->back()->with('error', 'Nomor telepon member harus diisi.');
            }

            $member = Member::where('phone_number', $request->member_phone)->first();
            if ($member) {
                Session::put('member_id', $member->id);
                return redirect()->route('cashier.member-points.create');
            } else {
                return redirect()->back()->with('member_not_found', true);
            }
        } else {
            return $this->store();
        }
    }

    public function store()
    {
        $tempSalesData = Session::get('temp_sales');
        $memberId = Session::get('member_id');
        $usePoints = Session::get('use_points', false);
        $pointsUsed = Session::get('points_used', 0);

        if (!$tempSalesData) {
            return redirect()->route('cashier.pembelian.create')->with('error', 'Data transaksi tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = $tempSalesData['total_amount'];

            if ($usePoints && $pointsUsed > 0) {
                $totalAmount -= $pointsUsed;
            }

            $sales = Sales::create([
                'employee_id' => Auth::id(),
                'member_id' => $memberId,
                'total_amount' => $totalAmount,
                'status' => $tempSalesData['status'],
                'payment' => $tempSalesData['payment'],
                'change' => $tempSalesData['payment'] - $totalAmount,
            ]);

            for ($i = 0; $i < count($tempSalesData['product_ids']); $i++) {
                SalesDetail::create([
                    'sales_id' => $sales->id,
                    'product_id' => $tempSalesData['product_ids'][$i],
                    'quantity' => $tempSalesData['quantities'][$i],
                    'price' => $tempSalesData['prices'][$i],
                    'subtotal' => $tempSalesData['subtotals'][$i],
                ]);

                $product = Product::find($tempSalesData['product_ids'][$i]);
                $product->stok -= $tempSalesData['quantities'][$i];
                $product->save();
            }

            if ($memberId) {
                $member = Member::find($memberId);

                if ($usePoints && $pointsUsed > 0) {
                    Point::create([
                        'member_id' => $memberId,
                        'sales_id' => $sales->id,
                        'points_earned' => 0,
                        'points_used' => $pointsUsed,
                    ]);

                    $member->points -= $pointsUsed;
                    $member->save();
                }

                $pointsEarned = floor($totalAmount / 10000);
                if ($pointsEarned > 0) {
                    Point::create([
                        'member_id' => $memberId,
                        'sales_id' => $sales->id,
                        'points_earned' => $pointsEarned,
                        'points_used' => 0,
                    ]);

                    $member->points += $pointsEarned;
                    $member->save();
                }
            }

            DB::commit();

            Session::forget(['temp_sales', 'member_id', 'use_points', 'points_used']);

            return redirect()->route('cashier.pembelian.invoice', $sales->id)
                ->with('success', 'Transaksi berhasil disimpan')
                ->with('clearCart', true);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cashier.pembelian.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        $sales = Sales::with(['salesDetails.product', 'member'])->findOrFail($id);
        return view('cashier.pembelian.invoice', compact('sales'));
    }

    public function show(Sales $sales)
    {
        $sales->load(['salesDetails.product', 'member']);
        return view('cashier.pembelian.show', compact('sales'));
    }

    public function getDetailJson($id)
    {
        $userId = Auth::id();

        $sales = Sales::with(['salesDetails.product', 'member'])
            ->where('employee_id', $userId)
            ->findOrFail($id);

        return response()->json([
            'status' => $sales->status,
            'total_amount' => $sales->total_amount,
            'sales_details' => $sales->salesDetails,
            'member' => $sales->member ? [
                'name' => $sales->member->name,
                'phone_number' => $sales->member->phone_number,
                'points' => $sales->member->points,
                'created_at' => $sales->member->created_at->format('d F Y'),
            ] : null
        ]);
    }

    public function getDetail($id)
    {
        $userId = Auth::id();

        $sales = Sales::with(['salesDetails.product', 'member'])
            ->where('employee_id', $userId)
            ->findOrFail($id);

        $pointsUsed = 0;
        $pointsEarned = 0;

        try {
            if (class_exists('App\Models\Point')) {
                $points = \App\Models\Point::where('sales_id', $id)->get();
                if ($points) {
                    $pointsUsed = $points->sum('points_used');
                    $pointsEarned = $points->sum('points_earned');
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error fetching points: " . $e->getMessage());
        }

        $salesData = $sales->toArray();
        $salesData['points_used'] = $pointsUsed;
        $salesData['points_earned'] = $pointsEarned;

        return response()->json($salesData);
    }
}

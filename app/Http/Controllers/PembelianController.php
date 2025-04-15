<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function getPembelianCashier(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $isExport = $request->has('export');
        $userId = Auth::id();
        $cashierName = Auth::user()->name;

        $query = Sales::with('member')
            ->where('employee_id', $userId)
            ->when($search, function ($query, $search) {
                return $query->whereHas('member', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc');

        if ($isExport && $request->ajax()) {
            $allSales = $query->get();

            $salesData = $allSales->map(function ($sale) use ($cashierName) {
                $sale->cashier_name = $cashierName;
                return $sale;
            });

            return response()->json($salesData);
        }

        $pembelian = $query->paginate($entries);

        return view('cashier.pembelian.index', compact('pembelian', 'search', 'entries', 'cashierName'));
    }

    public function getPembelianAdmin(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $isExport = $request->has('export');

        $query = Sales::with('member', 'employee')
            ->when($search, function ($query, $search) {
                return $query->whereHas('member', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc');

        if ($isExport && $request->ajax()) {
            $allSales = $query->get();

            $salesData = $allSales->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'member' => $sale->member ? ['name' => $sale->member->name] : null,
                    'created_at' => $sale->created_at,
                    'total_amount' => $sale->total_amount,
                    'employee' => $sale->employee ? ['name' => $sale->employee->name] : null
                ];
            });

            return response()->json($salesData);
        }

        $pembelian = $query->paginate($entries);
        $cashierName = Auth::user()->name;

        return view('admin.pembelian.index-pembelian', compact('pembelian', 'search', 'entries', 'cashierName'));
    }

    public function getDetailPembelianCashier($id)
    {
        try {
            $userId = Auth::id();
            $sales = Sales::with(['salesDetails.product', 'member'])
                ->where('employee_id', $userId)
                ->findOrFail($id);

            Log::info('Sales data: ' . json_encode($sales->toArray()));

            $pointsUsed = 0;
            $pointsEarned = 0;

            if (class_exists('App\Models\Point')) {
                $points = App\Models\Point::where('sales_id', $id)->get();
                $pointsUsed = $points->sum('points_used');
                $pointsEarned = $points->sum('points_earned');
            }

            $salesData = $sales->toArray();
            $salesData['points_used'] = $pointsUsed;
            $salesData['points_earned'] = $pointsEarned;

            foreach ($salesData['sales_details'] as $key => $detail) {
                if (!isset($detail['product']) || !isset($detail['product']['name'])) {
                    $product = App\Models\Product::find($detail['product_id']);
                    if ($product) {
                        $salesData['sales_details'][$key]['product'] = $product->toArray();
                    } else {
                        $salesData['sales_details'][$key]['product'] = [
                            'name' => 'Produk #' . $detail['product_id'],
                            'id' => $detail['product_id']
                        ];
                    }
                }
            }

            return response()->json($salesData);
        } catch (Exception $e) {
            Log::error('Error in getPurchaseDetail: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetailPembelianAdmin($id)
    {
        try {
            $sales = Sales::with(['salesDetails.product', 'member'])
                ->findOrFail($id);

            Log::info('Sales data: ' . json_encode($sales->toArray()));

            $pointsUsed = 0;
            $pointsEarned = 0;

            if (class_exists('App\Models\Point')) {
                $points = App\Models\Point::where('sales_id', $id)->get();
                $pointsUsed = $points->sum('points_used');
                $pointsEarned = $points->sum('points_earned');
            }

            $salesData = $sales->toArray();
            $salesData['points_used'] = $pointsUsed;
            $salesData['points_earned'] = $pointsEarned;

            foreach ($salesData['sales_details'] as $key => $detail) {
                if (!isset($detail['product']) || !isset($detail['product']['name'])) {
                    $product = App\Models\Product::find($detail['product_id']);
                    if ($product) {
                        $salesData['sales_details'][$key]['product'] = $product->toArray();
                    } else {
                        $salesData['sales_details'][$key]['product'] = [
                            'name' => 'Produk #' . $detail['product_id'],
                            'id' => $detail['product_id']
                        ];
                    }
                }
            }

            return response()->json($salesData);
        } catch (Exception $e) {
            Log::error('Error in getDetailPembelianAdmin: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportPembelianPDF($id)
    {
        try {
            $sales = Sales::with(['salesDetails.product', 'member', 'employee'])
                ->findOrFail($id);

            $pointsUsed = 0;
            $pointsEarned = 0;

            if (class_exists('App\Models\Point')) {
                $points = App\Models\Point::where('sales_id', $id)->get();
                $pointsUsed = $points->sum('points_used');
                $pointsEarned = $points->sum('points_earned');
            }

            $filename = 'Invoice-' . $id . '.pdf';

            foreach ($sales->salesDetails as $detail) {
                if (!$detail->product) {
                    $product = App\Models\Product::find($detail->product_id);
                    if ($product) {
                        $detail->product = $product;
                    }
                }
            }

            $pdf = PDF::loadView('admin.pembelian.pdf', [
                'sales' => $sales,
                'pointsUsed' => $pointsUsed,
                'pointsEarned' => $pointsEarned
            ]);

            return $pdf->download($filename);
        } catch (Exception $e) {
            Log::error('Error in exportPembelianPDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function exportAllPembelian(Request $request)
    {
        $search = $request->input('search');

        $pembelian = Sales::with('member', 'employee')
            ->when($search, function ($query, $search) {
                return $query->whereHas('member', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        foreach ($pembelian as $index => $item) {
            $data[] = [
                'No' => $index + 1,
                'Nama Pelanggan' => $item->member->name ?? 'Non-member',
                'Tanggal Penjualan' => $item->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i'),
                'Total Harga' => 'Rp ' . number_format($item->total_amount, 0, ',', '.'),
                'Dibuat Oleh' => $item->employee->name ?? '-',
            ];
        }

        return response()->json($data);
    }
}

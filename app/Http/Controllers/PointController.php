<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PointController extends Controller
{
    public function create()
    {
        $memberId = Session::get('member_id');
        $tempSalesData = Session::get('temp_sales');

        if (!$memberId || !$tempSalesData) {
            return redirect()->route('cashier.pembelian.create')
                ->with('error', 'Data tidak lengkap, silakan ulangi proses pembelian.');
        }

        $member = Member::findOrFail($memberId);

        $hasPreviewsSales = Point::where('member_id', $memberId)->exists();

        return view('cashier.pembelian.member', compact('member', 'tempSalesData', 'hasPreviewsSales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'use_points' => 'nullable|boolean',
            'points_used' => 'nullable|integer|min:0',
        ]);

        $memberId = Session::get('member_id');
        $member = Member::findOrFail($memberId);

        if ($request->use_points && $request->points_used > $member->points) {
            return redirect()->back()->with('error', 'Jumlah poin yang digunakan melebihi poin yang tersedia.');
        }

        Session::put('use_points', $request->use_points ? true : false);
        Session::put('points_used', $request->use_points ? $request->points_used : 0);

        return redirect()->route('cashier.pembelian.finalize');
    }
}

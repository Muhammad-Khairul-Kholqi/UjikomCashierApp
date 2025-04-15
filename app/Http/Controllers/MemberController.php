<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);

        $members = Member::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate($entries);

        return view('cashier.member.index-member', compact('members', 'search', 'entries'));
    }

    public function create()
    {
        return view('cashier.member.create-member');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        $validated['points'] = 30;
        $validated['created_by'] = Auth::id();

        $member = Member::create($validated);

        return redirect()->route('cashier.member.index')
            ->with('success', 'Member berhasil ditambahkan!');
    }

    public function show(Member $member)
    {
        return view('cashier.member.show-member', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('cashier.member.edit-member', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'points' => 'required|integer|min:0',
        ]);

        $member->update($validated);

        return redirect()->route('cashier.member.index')
            ->with('success', 'Member berhasil diperbarui!');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('cashier.member.index')
            ->with('success', 'Member berhasil dihapus!');
    }
}

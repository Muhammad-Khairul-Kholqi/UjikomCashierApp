<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function indexProdukCashier(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $export = $request->input('export') === 'true';

        $query = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', "%$search%");
        });

        if ($export && $request->ajax()) {
            $allProducts = $query->get(['id', 'nama_produk', 'harga', 'stok']);
            return response()->json($allProducts);
        }

        $produk = $query->paginate($entries);

        return view('cashier.produk', compact('produk', 'search', 'entries'));
    }

    public function indexProdukAdmin(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $export = $request->input('export') === 'true';

        $query = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', "%$search%");
        });

        if ($export && $request->ajax()) {
            $allProducts = $query->get(['id', 'nama_produk', 'harga', 'stok']);
            return response()->json($allProducts);
        }

        $produk = $query->paginate($entries);

        return view('admin.produk.index-produk', compact('produk', 'search', 'entries'));
    }

    public function create()
    {
        return view('admin.produk.tambah-produk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:1000',
            'stok' => 'required|integer|min:1',
            'foto_produk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('foto_produk')->store('produk', 'public');

        Product::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto_produk' => $path,
        ]);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $produk) {}

    public function edit(Product $produk)
    {
        return view('admin.produk.edit-produk', compact('produk'));
    }

    public function update(Request $request, Product $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:1000',
            'stok' => 'required|integer|min:1',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_produk')) {
            if ($produk->foto_produk) {
                \Storage::disk('public')->delete($produk->foto_produk);
            }

            $path = $request->file('foto_produk')->store('produk', 'public');
            $produk->foto_produk = $path;
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $produk)
    {
        if ($produk->foto_produk) {
            \Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil dihapus!');
    }
}

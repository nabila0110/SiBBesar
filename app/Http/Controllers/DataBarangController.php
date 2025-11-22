<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;

class DataBarangController extends Controller
{
    // Menampilkan data barang + pencarian
    public function index(Request $request)
    {
        $keyword = $request->search;

        $barang = Barang::with('supplier')
            ->when($keyword, function ($query, $keyword) {
                $query->where('nama', 'like', "%$keyword%")
                      ->orWhere('kode', 'like', "%$keyword%");
            })->orderBy('id', 'desc')->paginate(5);

        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('data-barang.index', compact('barang', 'keyword', 'suppliers'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:barang,kode|max:50',
            'nama' => 'required|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // Edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json($barang);
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $request->validate([
            'kode' => 'required|unique:barang,kode,' . $id . '|max:50',
            'nama' => 'required|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    // Hapus barang
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
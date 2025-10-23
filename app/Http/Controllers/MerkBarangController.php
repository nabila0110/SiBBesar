<?php

namespace App\Http\Controllers;

use App\Models\MerkBarang;
use Illuminate\Http\Request;

class MerkBarangController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');
        $merks = MerkBarang::when($search, function ($query) use ($search) {
            return $query->where('nama_merk', 'like', '%' . $search . '%');
        })->paginate(10);
        return view('persediaan.merk_barang.index', compact('merks', 'search'));
    }

    public function create() {
        return view('persediaan.merk_barang.form');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_merk' => 'required|unique:merk_barangs,nama_merk',
        ]);

        MerkBarang::create($request->all());
        return redirect()->route('merk-barang.index')->with('success', 'Merk berhasil ditambahkan.');
    }

    public function edit($id) {
        $merkBarang = MerkBarang::findOrFail($id);
        return view('persediaan.merk_barang.edit', compact('merkBarang'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama_merk' => 'required|unique:merk_barangs,nama_merk,' . $id,
        ]);

        $merkBarang = MerkBarang::findOrFail($id);
        $merkBarang->update($request->all());
        return redirect()->route('merk-barang.index')->with('success', 'Merk berhasil diperbarui.');
    }

    public function destroy($id) {
        MerkBarang::findOrFail($id)->delete();
        return back()->with('success', 'Merk berhasil dihapus.');
    }
}

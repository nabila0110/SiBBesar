<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');
        $jenisBarangs = JenisBarang::when($search, function ($query) use ($search) {
            return $query->where('nama_jenis', 'like', '%' . $search . '%');
        })->paginate(10);
        return view('persediaan.jenis_barang.index', compact('jenisBarangs', 'search'));
    }

    public function create() {
        return view('persediaan.jenis_barang.form');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_barangs,nama_jenis',
        ]);

        JenisBarang::create($request->all());
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis Barang berhasil ditambahkan.');
    }

    public function edit($id) {
        $jenisBarang = JenisBarang::findOrFail($id);
        return view('persediaan.jenis_barang.edit', compact('jenisBarang'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_barangs,nama_jenis,' . $id,
        ]);

        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update($request->all());
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis Barang berhasil diperbarui.');
    }

    public function destroy($id) {
        JenisBarang::findOrFail($id)->delete();
        return back()->with('success', 'Jenis Barang berhasil dihapus.');
    }
}

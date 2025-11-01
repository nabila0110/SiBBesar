<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);

        $jenisBarangs = JenisBarang::when($search, function ($q) use ($search) {
            $q->where('nama_jenis', 'like', "%{$search}%");
        })->orderBy('id', 'desc')->paginate($perPage);

        return view('jenis-barang.index', compact('jenisBarangs', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_barangs,nama_jenis',
        ]);

        JenisBarang::create($request->only('nama_jenis'));
        return redirect()->route('jenis-barang.index')->with('success', 'Jenis barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $jenisBarang = JenisBarang::findOrFail($id);
    //     return view('jenis-barang.show', compact('jenisBarang'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        return view('jenis-barang.edit', compact('jenisBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_barangs,nama_jenis,' . $id,
        ]);

        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update($request->only('nama_jenis'));
        return redirect()->route('jenis-barang.index')->with('success', 'Jenis barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        JenisBarang::findOrFail($id)->delete();
        return back()->with('success', 'Jenis barang berhasil dihapus.');
    }
}
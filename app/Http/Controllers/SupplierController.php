<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');
        $suppliers = Supplier::withCount('barangs')
            ->when($search, function ($query) use ($search) {
                return $query->where('nama_supplier', 'like', '%' . $search . '%')
                             ->orWhere('kode_supplier', 'like', '%' . $search . '%');
            })->paginate(10);
        return view('supplier.index', compact('suppliers', 'search'));
    }

    public function create() {
        return view('supplier.form');
    }

    public function store(Request $request) {
        $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required',
        ]);

        Supplier::create($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        
        // Jika request AJAX, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($supplier);
        }
        
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier,' . $id,
            'nama_supplier' => 'required',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id) {
        Supplier::findOrFail($id)->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}

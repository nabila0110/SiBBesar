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
                             ->orWhere('email', 'like', '%' . $search . '%')
                             ->orWhere('telepon', 'like', '%' . $search . '%');
            })->paginate(5);
        return view('supplier.index', compact('suppliers', 'search'));
    }

    public function create() {
        return view('supplier.form');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_supplier' => 'required|max:100',
            'email' => 'nullable|email|max:50',
            'alamat' => 'nullable|max:255',
            'telepon' => 'nullable|max:20',
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
            'nama_supplier' => 'required|max:100',
            'email' => 'nullable|email|max:50',
            'alamat' => 'nullable|max:255',
            'telepon' => 'nullable|max:20',
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

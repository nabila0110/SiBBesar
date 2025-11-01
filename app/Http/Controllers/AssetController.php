<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with('account')->orderBy('created_at', 'desc')->get();
        $accounts = \App\Models\Account::where('type', 'asset')->orderBy('name')->get();
        return view('asset.index', [
            'assets' => $assets,
            'accounts' => $accounts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asset.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'description' => 'nullable|string',
            'purchase_price' => 'required',
            'depreciation_rate' => 'required|numeric|min:0|max:100',
            'accumulated_depreciation' => 'required',
            'account_id' => 'required|exists:accounts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Clean currency format and convert to numeric values
            $purchase_price = (float) str_replace(['Rp', '.', ',', ' '], '', $request->purchase_price);
            $accumulated_depreciation = (float) str_replace(['Rp', '.', ',', ' '], '', $request->accumulated_depreciation);

            // Generate asset number
            $lastAsset = Asset::orderBy('id', 'desc')->first();
            $assetNo = 'AST' . str_pad(($lastAsset ? ($lastAsset->id + 1) : 1), 5, '0', STR_PAD_LEFT);

            $asset = Asset::create([
                'asset_no' => $assetNo,
                'asset_name' => $request->asset_name,
                'purchase_date' => $request->purchase_date,
                'description' => $request->description,
                'account_id' => $request->account_id,
                'purchase_price' => $purchase_price,
                'depreciation_rate' => $request->depreciation_rate,
                'accumulated_depreciation' => $accumulated_depreciation,
                'book_value' => $purchase_price - $accumulated_depreciation,
                'status' => 'active'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Asset berhasil ditambahkan',
                'data' => $asset
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('asset.show', [
            'asset' => $asset
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('asset.edit', [
            'asset' => $asset
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'description' => 'nullable|string',
            'purchase_price' => 'required',
            'depreciation_rate' => 'required|numeric|min:0|max:100',
            'accumulated_depreciation' => 'required',
            'account_id' => 'required|exists:accounts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $asset = Asset::findOrFail($id);
            
            // Clean currency format and convert to numeric values
            $purchase_price = (float) str_replace(['Rp', '.', ',', ' '], '', $request->purchase_price);
            $accumulated_depreciation = (float) str_replace(['Rp', '.', ',', ' '], '', $request->accumulated_depreciation);

            $asset->update([
                'asset_name' => $request->asset_name,
                'purchase_date' => $request->purchase_date,
                'description' => $request->description,
                'account_id' => $request->account_id,
                'purchase_price' => $purchase_price,
                'depreciation_rate' => $request->depreciation_rate,
                'accumulated_depreciation' => $accumulated_depreciation,
                'book_value' => $purchase_price - $accumulated_depreciation
            ]);

            // If request expects JSON (AJAX), return JSON; otherwise redirect back to index with flash
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Asset berhasil diperbarui',
                    'data' => $asset
                ]);
            }

            return redirect()->route('asset.index')->with('success', 'Asset berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui asset',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui asset: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Asset berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $assets = Asset::paginate(20);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
            'acquired_at' => 'nullable|date',
        ]);

        // Ensure there's an account to attach to (migrations require account_id)
        $account = Account::first() ?: Account::factory()->create();

        $assetPayload = [
            'asset_no' => 'AST-'.Str::upper(Str::random(8)),
            'account_id' => $account->id,
            'asset_name' => $data['name'],
            'description' => $data['name'],
            'purchase_date' => $data['acquired_at'] ?? now()->format('Y-m-d'),
            'purchase_price' => $data['value'],
            'depreciation_rate' => 0.00,
            'accumulated_depreciation' => 0.00,
            'book_value' => $data['value'],
            'status' => 'active',
        ];

        Asset::create($assetPayload);
        return redirect()->route('assets.index')->with('success', 'Asset created.');
    }
}

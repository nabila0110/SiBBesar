<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AssetController extends BaseController
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

        Asset::create($data);
        return redirect()->route('assets.index')->with('success', 'Asset created.');
    }
}

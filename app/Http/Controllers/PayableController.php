<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PayableController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Payable::paginate(20);
        return view('payables.index', compact('items'));
    }

    public function create()
    {
        return view('payables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor' => 'required|string',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
        ]);

        Payable::create($data);
        return redirect()->route('payables.index')->with('success', 'Payable created.');
    }
}

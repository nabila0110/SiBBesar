<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ReceivableController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Receivable::paginate(20);
        return view('receivables.index', compact('items'));
    }

    public function create()
    {
        return view('receivables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer' => 'required|string',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
        ]);

        Receivable::create($data);
        return redirect()->route('receivables.index')->with('success', 'Receivable created.');
    }
}

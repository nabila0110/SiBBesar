<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayableController extends Controller
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

        $account = Account::first() ?: Account::factory()->create();

        $payload = [
            'invoice_no' => 'INV-PAY-'.Str::upper(Str::random(8)),
            'account_id' => $account->id,
            'vendor_name' => $data['vendor'],
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => $data['due_date'] ?? now()->format('Y-m-d'),
            'amount' => $data['amount'],
            'paid_amount' => 0.00,
            'remaining_amount' => $data['amount'],
            'status' => 'outstanding',
        ];

        Payable::create($payload);
        return redirect()->route('payables.index')->with('success', 'Payable created.');
    }
}

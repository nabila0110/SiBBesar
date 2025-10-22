<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReceivableController extends Controller
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

        $account = Account::first() ?: Account::factory()->create();

        $payload = [
            'invoice_no' => 'INV-REC-'.Str::upper(Str::random(8)),
            'account_id' => $account->id,
            'customer_name' => $data['customer'],
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => $data['due_date'] ?? now()->format('Y-m-d'),
            'amount' => $data['amount'],
            'paid_amount' => 0.00,
            'remaining_amount' => $data['amount'],
            'status' => 'outstanding',
        ];

        Receivable::create($payload);
        return redirect()->route('receivables.index')->with('success', 'Receivable created.');
    }
}

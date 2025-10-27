<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Receivable;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $piutang = Receivable::orderBy('invoice_date', 'desc')->paginate(25);
        $accounts = Account::orderBy('name')->get();
        return view('piutang.index', compact('piutang', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::orderBy('name')->get();
        return view('piutang.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_no' => 'required|string|max:255|unique:receivables,invoice_no',
            'account_id' => 'required|exists:accounts,id',
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $paid = isset($data['paid_amount']) ? (float)$data['paid_amount'] : 0.0;
        $amount = (float)$data['amount'];
        $remaining = $amount - $paid;
        $status = $remaining > 0 ? 'open' : 'paid';

        $data['paid_amount'] = $paid;
        $data['remaining_amount'] = $remaining;
        $data['status'] = $status;

        Receivable::create($data);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Receivable::findOrFail($id);
        return view('piutang.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Receivable::findOrFail($id);
        $accounts = Account::orderBy('name')->get();
        return view('piutang.edit', compact('item', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Receivable::findOrFail($id);

        $data = $request->validate([
            'invoice_no' => 'required|string|max:255|unique:receivables,invoice_no,' . $item->id,
            'account_id' => 'required|exists:accounts,id',
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $paid = isset($data['paid_amount']) ? (float)$data['paid_amount'] : 0.0;
        $amount = (float)$data['amount'];
        $remaining = $amount - $paid;
        $status = $remaining > 0 ? 'open' : 'paid';

        $data['paid_amount'] = $paid;
        $data['remaining_amount'] = $remaining;
        $data['status'] = $status;

        $item->update($data);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Receivable::findOrFail($id);
        $item->delete();
        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HutangController extends Controller
{
    /**
     * Convert dd/mm/yyyy or Y-m-d format to Y-m-d
     */
    private function parseDate($date)
    {
        if (!$date) return null;
        try {
            // Try dd/mm/yyyy format first
            if (strpos($date, '/') !== false) {
                return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            }
            // Otherwise use default Laravel date parsing
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hutang = Payable::orderBy('invoice_date', 'desc')->paginate(10);
        $accounts = Account::orderBy('name')->get();
        return view('hutang.index', compact('hutang', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::orderBy('name')->get();
        return view('hutang.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string|max:50|unique:payables,invoice_no',
            'account_id' => 'required|exists:accounts,id',
            'vendor_name' => 'required|string|max:255',
            'invoice_date' => 'required|string',
            'due_date' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $invoiceDate = $this->parseDate($request->invoice_date);
        $dueDate = $this->parseDate($request->due_date);

        if (!$invoiceDate || !$dueDate) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan dd/mm/yyyy'])->withInput();
        }

        // Validate that due_date is not before invoice_date
        if (strtotime($dueDate) < strtotime($invoiceDate)) {
            return back()->withErrors(['due_date' => 'Tanggal jatuh tempo tidak boleh lebih awal dari tanggal invoice'])->withInput();
        }

        $paid = $request->input('paid_amount', 0);
        $amount = $request->input('amount', 0);

        Payable::create([
            'invoice_no' => $request->invoice_no,
            'account_id' => $request->account_id,
            'vendor_name' => $request->vendor_name,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'amount' => $amount,
            'paid_amount' => $paid,
            'remaining_amount' => max(0, $amount - $paid),
            'status' => ($paid >= $amount) ? 'paid' : 'outstanding',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('hutang.index')
            ->with('success', 'Hutang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hutang = Payable::findOrFail($id);
        return view('hutang.show', compact('hutang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hutang = Payable::findOrFail($id);
        return view('hutang.edit', compact('hutang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hutang = Payable::findOrFail($id);

        $request->validate([
            'invoice_no' => 'required|string|max:50|unique:payables,invoice_no,' . $hutang->id,
            'account_id' => 'required|exists:accounts,id',
            'vendor_name' => 'required|string|max:255',
            'invoice_date' => 'required|string',
            'due_date' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $invoiceDate = $this->parseDate($request->invoice_date);
        $dueDate = $this->parseDate($request->due_date);

        if (!$invoiceDate || !$dueDate) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan dd/mm/yyyy'])->withInput();
        }

        // Validate that due_date is not before invoice_date
        if (strtotime($dueDate) < strtotime($invoiceDate)) {
            return back()->withErrors(['due_date' => 'Tanggal jatuh tempo tidak boleh lebih awal dari tanggal invoice'])->withInput();
        }

        $paid = $request->input('paid_amount', 0);
        $amount = $request->input('amount', 0);

        $hutang->update([
            'invoice_no' => $request->invoice_no,
            'account_id' => $request->account_id,
            'vendor_name' => $request->vendor_name,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'amount' => $amount,
            'paid_amount' => $paid,
            'remaining_amount' => max(0, $amount - $paid),
            'status' => ($paid >= $amount) ? 'paid' : 'outstanding',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('hutang.index')
            ->with('success', 'Hutang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hutang = Payable::findOrFail($id);
        $hutang->delete();

        return redirect()->route('hutang.index')
            ->with('success', 'Hutang berhasil dihapus.');
    }
}

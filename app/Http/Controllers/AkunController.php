<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\AccountCategory;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $accounts = Account::with('category')->select(['code', 'name', 'group', 'type', 'account_category_id']);
            
            return response()->json([
                'data' => $accounts->get()->map(function($account) {
                    return [
                        'code' => $account->code,
                        'name' => $account->name,
                        'group' => $account->group,
                        'type' => $account->type
                    ];
                })
            ]);
        }

        $accounts = Account::with('category')->orderBy('code')->get();
        $categories = AccountCategory::where('is_active', true)->orderBy('code')->get();

        return view('akun.index', compact('accounts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('akun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'code' => 'required|unique:accounts,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'description' => 'nullable|string'
        ], [
            'account_category_id.required' => 'Kategori akun harus dipilih',
            'account_category_id.exists' => 'Kategori akun tidak valid',
            'code.required' => 'Kode akun harus diisi',
            'code.unique' => 'Kode akun sudah digunakan',
            'name.required' => 'Nama akun harus diisi',
            'type.required' => 'Tipe akun harus dipilih',
            'type.in' => 'Tipe akun tidak valid'
        ]);
        
        $account = Account::create([
            'account_category_id' => $validated['account_category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'group' => ucfirst($validated['type']) . 's', // Auto-generate group from type
            'description' => $validated['description'] ?? null,
            'expense_type' => null,
            'balance_debit' => 0,
            'balance_credit' => 0,
            'is_active' => true
        ]);

        if ($request->ajax()) {
            return response()->json($account);
        }
        
        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        return response()->json($account);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        return response()->json($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();

        $validated = $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'code' => 'required|unique:accounts,code,' . $account->id,
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'description' => 'nullable|string'
        ], [
            'account_category_id.required' => 'Kategori akun harus dipilih',
            'account_category_id.exists' => 'Kategori akun tidak valid',
            'code.required' => 'Kode akun harus diisi',
            'code.unique' => 'Kode akun sudah digunakan',
            'name.required' => 'Nama akun harus diisi',
            'type.required' => 'Tipe akun harus dipilih',
            'type.in' => 'Tipe akun tidak valid'
        ]);

        $account->update([
            'account_category_id' => $validated['account_category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'group' => ucfirst($validated['type']) . 's', // Auto-generate group from type
            'description' => $validated['description'] ?? null,
            'expense_type' => null
        ]);

        if ($request->ajax()) {
            return response()->json($account);
        }

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::where('code', $id)->firstOrFail();
        
        // Check if account is used in assets
        if ($account->assets()->exists()) {
            return response()->json([
                'message' => 'Akun tidak dapat dihapus karena digunakan oleh aset'
            ], 422);
        }
        
        // Check if account has journal entries
        if ($account->journals()->exists()) {
            return response()->json([
                'message' => 'Akun tidak dapat dihapus karena sudah memiliki transaksi'
            ], 422);
        }

        $account->delete();
        return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    /**
     * Store opening balance for an account.
     */
    public function openBalance(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|exists:accounts,code',
            'type' => 'required|in:debit,kredit',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $account = Account::where('code', $validated['account_code'])->firstOrFail();
        
        // Create opening balance journal entry (sudah double entry di table journals)
        \App\Models\Journal::create([
            'journal_no' => \App\Models\Journal::generateJournalNo(),
            'account_id' => $account->id,
            'transaction_date' => $validated['date'],
            'description' => $validated['description'] ?? 'Saldo Awal ' . $account->name,
            'reference' => 'SALDO-AWAL',
            'debit' => $validated['type'] === 'debit' ? $validated['amount'] : 0,
            'kredit' => $validated['type'] === 'kredit' ? $validated['amount'] : 0,
            'created_by' => Auth::id()
        ]);

        // Update account balance
        if ($validated['type'] === 'debit') {
            $account->increment('balance_debit', $validated['amount']);
        } else {
            $account->increment('balance_credit', $validated['amount']);
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Saldo awal berhasil disimpan',
                'account' => $account->fresh()
            ]);
        }

        return redirect()->route('akun.index')->with('success', 'Saldo awal berhasil disimpan');
    }

    // ============ KATEGORI MANAGEMENT ============
    
    public function kategori()
    {
        $categories = AccountCategory::orderBy('code')->get();
        return view('akun.kategori', compact('categories'));
    }

    public function getCategoriesByType(Request $request)
    {
        $type = $request->get('type');
        $categories = AccountCategory::where('type', $type)
                                    ->where('is_active', true)
                                    ->orderBy('code')
                                    ->get();
        return response()->json($categories);
    }

    public function showKategori($id)
    {
        $category = AccountCategory::findOrFail($id);
        return response()->json($category);
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:account_categories,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Kode kategori harus diisi',
            'code.unique' => 'Kode kategori sudah digunakan',
            'name.required' => 'Nama kategori harus diisi',
            'type.required' => 'Tipe kategori harus dipilih',
            'type.in' => 'Tipe kategori tidak valid'
        ]);

        $category = AccountCategory::create($validated);
        
        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ]);
    }

    public function updateKategori(Request $request, $id)
    {
        $category = AccountCategory::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|unique:account_categories,code,' . $id,
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Kode kategori harus diisi',
            'code.unique' => 'Kode kategori sudah digunakan',
            'name.required' => 'Nama kategori harus diisi',
            'type.required' => 'Tipe kategori harus dipilih',
            'type.in' => 'Tipe kategori tidak valid'
        ]);

        $category->update($validated);
        
        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ]);
    }

    public function destroyKategori($id)
    {
        $category = AccountCategory::findOrFail($id);
        
        // Check if category has accounts
        if ($category->accounts()->exists()) {
            return response()->json([
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki akun'
            ], 422);
        }
        
        $category->delete();
        
        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}

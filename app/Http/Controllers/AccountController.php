<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('category')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $categories = AccountCategory::all();
        return view('accounts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'code' => 'required|unique:accounts,code|max:20',
            'name' => 'required|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description' => 'nullable',
        ]);

        Account::create($validated);
        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully');
    }

    public function edit(Account $account)
    {
        $categories = AccountCategory::all();
        return view('accounts.edit', compact('account', 'categories'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'account_category_id' => 'required|exists:account_categories,id',
            'code' => 'required|max:20|unique:accounts,code,' . $account->id,
            'name' => 'required|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description' => 'nullable',
        ]);

        $account->update($validated);
        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully');
    }
}
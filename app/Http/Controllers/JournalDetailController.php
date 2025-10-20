<?php

namespace App\Http\Controllers;

use App\Models\JournalDetail;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class JournalDetailController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Journal $journal)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);

        $data['journal_id'] = $journal->id;
        JournalDetail::create($data);

        return redirect()->route('journals.show', $journal)->with('success', 'Line item added.');
    }

    public function update(Request $request, JournalDetail $journalDetail)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'debit' => 'nullable|numeric',
            'credit' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);

        $journalDetail->update($data);
        return redirect()->route('journals.show', $journalDetail->journal_id)->with('success', 'Line item updated.');
    }

    public function destroy(JournalDetail $journalDetail)
    {
        $journalId = $journalDetail->journal_id;
        $journalDetail->delete();
        return redirect()->route('journals.show', $journalId)->with('success', 'Line item removed.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of piutang (IN + TIDAK_LUNAS)
     */
    public function index(Request $request)
    {
        $query = Journal::with(['account', 'creator'])
            ->where('type', 'in')
            ->where('payment_status', 'tidak_lunas')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->input('dari_tanggal') != '') {
            $query->where('transaction_date', '>=', $request->input('dari_tanggal'));
        }
        if ($request->has('sampai_tanggal') && $request->input('sampai_tanggal') != '') {
            $query->where('transaction_date', '<=', $request->input('sampai_tanggal'));
        }

        $journals = $query->paginate(6);

        return view('piutang.index', compact('journals'));
    }
}

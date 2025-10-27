<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function posisi()
    {
        return view('laporan-posisi-keuangan');
    }

    public function labaRugi()
    {
        return view('laporan-laba-rugi');
    }
}

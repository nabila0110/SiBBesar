<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PPh21Controller extends Controller
{
    /**
     * Menampilkan halaman kalkulator PPh 21
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pph21.index');
    }

    /**
     * Menghitung PPh 21 berdasarkan data yang dikirim
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'npwp' => 'boolean',
            'status_tanggungan' => 'required|string|in:TK,K,K/1,K/2,K/3',
            'gaji_pokok' => 'required|numeric|min:0',
            'thr' => 'required|numeric|min:0',
            'tanggungan' => 'required|numeric|min:0'
        ]);

        // Ambil data dari request
        $gajiPokok = floatval($validated['gaji_pokok']);
        $thr = floatval($validated['thr']);
        $tanggungan = floatval($validated['tanggungan']);
        $npwp = $validated['npwp'] ?? false;
        $statusTanggungan = $validated['status_tanggungan'];

        // 1. Hitung Penghasilan Bruto
        $gajiTahunan = $gajiPokok * 12;
        $bruto = $gajiTahunan + $thr;

        // 2. Hitung Biaya Jabatan (5% dari bruto, maksimal 6 juta per tahun)
        $biayaJabatan = min($bruto * 0.05, 6000000);

        // 3. Hitung Penghasilan Netto
        $netto = $bruto - $biayaJabatan;

        // 4. Hitung PTKP (Penghasilan Tidak Kena Pajak)
        $ptkp = $this->hitungPTKP($statusTanggungan);

        // 5. Hitung PKP (Penghasilan Kena Pajak)
        $pkp = max($netto - $ptkp, 0);
        $pkpBulat = floor($pkp / 1000) * 1000; // Pembulatan ke bawah per 1000

        // 6. Hitung PPh Progresif
        $pphProgresif = $this->hitungPPhProgresif($pkpBulat);

        // 7. Jika tidak punya NPWP, tarif naik 20%
        $pphFinal = $npwp ? $pphProgresif['total'] : $pphProgresif['total'] * 1.2;

        // 8. Hitung Gaji Setelah Pajak
        $gajiSetelahPPhTahun = $bruto - $pphFinal;
        $gajiSetelahPPhBulan = $gajiSetelahPPhTahun / 12;

        // 9. Hitung Ratio Pajak terhadap Gaji
        $ratio = $gajiPokok > 0 ? ($pphFinal / $gajiTahunan * 100) : 0;

        // 10. Susun hasil perhitungan
        $hasil = [
            // Kesimpulan
            'pajakTahun' => round($pphFinal),
            'gajiSetelahPPhTahun' => round($gajiSetelahPPhTahun),
            'gajiSetelahPPhBulan' => round($gajiSetelahPPhBulan),
            'ratio' => round($ratio, 2),

            // Rincian
            'gaji' => $gajiPokok,
            'gajiTahun' => $gajiTahunan,
            'thr' => $thr,
            'tanggungan' => $tanggungan,

            // Perhitungan
            'bruto' => round($bruto),
            'biayaJabatan' => round($biayaJabatan),
            'netto' => round($netto),

            // PPh Terhutang (5 layer progresif)
            'pph5' => round($pphProgresif['layer1']),
            'pph15' => round($pphProgresif['layer2']),
            'pph25' => round($pphProgresif['layer3']),
            'pph30' => round($pphProgresif['layer4']),
            'pph35' => round($pphProgresif['layer5']),
            'totalPPh' => round($pphFinal),

            // Data tambahan
            'ptkp' => $ptkp,
            'pkp' => round($pkpBulat),
            'multiplier_npwp' => $npwp ? 1 : 1.2
        ];

        return response()->json([
            'success' => true,
            'message' => 'Perhitungan PPh 21 berhasil',
            'data' => $hasil
        ]);
    }

    /**
     * Menghitung PTKP berdasarkan status tanggungan
     *
     * @param  string  $statusTanggungan
     * @return float
     */
    private function hitungPTKP($statusTanggungan)
    {
        // PTKP 2024 (sesuaikan dengan tahun berlaku)
        $ptkpDasar = 54000000; // TK/0 (Tidak Kawin, 0 tanggungan)
        $ptkpKawin = 4500000;  // Tambahan untuk menikah
        $ptkpPerTanggungan = 4500000; // Per tanggungan (max 3)

        // Default: TK (Tidak Kawin)
        $totalPTKP = $ptkpDasar;

        // Jika status kawin
        if ($statusTanggungan === 'K') {
            // K/0 (Kawin, 0 tanggungan)
            $totalPTKP = $ptkpDasar + $ptkpKawin;
        } elseif (strpos($statusTanggungan, 'K/') === 0) {
            // K/1, K/2, K/3, dst
            $parts = explode('/', $statusTanggungan);
            $jumlahTanggungan = isset($parts[1]) ? intval($parts[1]) : 0;
            
            // Batasi maksimal 3 tanggungan
            $jumlahTanggungan = min($jumlahTanggungan, 3);
            
            $totalPTKP = $ptkpDasar + $ptkpKawin + ($jumlahTanggungan * $ptkpPerTanggungan);
        }

        return $totalPTKP;
    }

    /**
     * Menghitung PPh progresif berdasarkan PKP
     *
     * @param  float  $pkp
     * @return array
     */
    private function hitungPPhProgresif($pkp)
    {
        // Tarif PPh Progresif Pasal 17 (Per UU HPP)
        $layers = [
            ['batas' => 60000000, 'tarif' => 0.05],      // Layer 1: 0 - 60 juta (5%)
            ['batas' => 250000000, 'tarif' => 0.15],     // Layer 2: 60 juta - 250 juta (15%)
            ['batas' => 500000000, 'tarif' => 0.25],     // Layer 3: 250 juta - 500 juta (25%)
            ['batas' => 5000000000, 'tarif' => 0.30],    // Layer 4: 500 juta - 5 miliar (30%)
            ['batas' => PHP_FLOAT_MAX, 'tarif' => 0.35]  // Layer 5: > 5 miliar (35%)
        ];

        $pphPerLayer = [0, 0, 0, 0, 0];
        $sisaPkp = $pkp;
        $batasKumulatif = 0;

        foreach ($layers as $index => $layer) {
            if ($sisaPkp <= 0) break;

            $batasLayer = $layer['batas'] - $batasKumulatif;
            $penghasilanDiLayer = min($sisaPkp, $batasLayer);
            
            $pphPerLayer[$index] = $penghasilanDiLayer * $layer['tarif'];
            
            $sisaPkp -= $penghasilanDiLayer;
            $batasKumulatif += $batasLayer;
        }

        return [
            'layer1' => $pphPerLayer[0],  // 5%
            'layer2' => $pphPerLayer[1],  // 15%
            'layer3' => $pphPerLayer[2],  // 25%
            'layer4' => $pphPerLayer[3],  // 30%
            'layer5' => $pphPerLayer[4],  // 35%
            'total' => array_sum($pphPerLayer)
        ];
    }

    /**
     * Export hasil perhitungan ke PDF (opsional)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Request $request)
    {
        // TODO: Implementasi export ke PDF menggunakan library seperti DomPDF atau TCPDF
        // Contoh: return PDF::loadView('pph21.pdf', $data)->download('pph21.pdf');
        
        return response()->json([
            'success' => false,
            'message' => 'Fitur export PDF belum diimplementasikan'
        ], 501);
    }

    /**
     * Simpan history perhitungan (opsional)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveHistory(Request $request)
    {
        // TODO: Implementasi save ke database
        // Contoh: PPh21History::create($request->all());
        
        return response()->json([
            'success' => false,
            'message' => 'Fitur save history belum diimplementasikan'
        ], 501);
    }
}
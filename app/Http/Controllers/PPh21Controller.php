<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PPh21Controller extends Controller
{
    public function index()
    {
        return view('pph21.index');
    }

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

        // 1. Penghasilan Bruto
        $gajiTahunan = $gajiPokok * 12;
        $bruto = $gajiTahunan + $thr;

        // 2. Biaya Jabatan (5% dari bruto, max 6 juta)
        $biayaJabatan = min($bruto * 0.05, 6000000);

        // 3. Penghasilan Netto
        $netto = $bruto - $biayaJabatan;

        // 4. PTKP
        $ptkp = $this->hitungPTKP($statusTanggungan);

        // 5. PKP
        $pkp = max($netto - $ptkp, 0);
        $pkpBulat = floor($pkp / 1000) * 1000;

        // 6. PPh Progresif (sesuai NPWP atau tidak)
        $pphProgresif = $this->hitungPPhProgresif($pkpBulat, $npwp);

        // 7. PPh Final
        $pphFinal = $pphProgresif['total'];

        // 8. Gaji Setelah Pajak
        $gajiSetelahPPhTahun = $bruto - $pphFinal;
        $gajiSetelahPPhBulan = $gajiSetelahPPhTahun / 12;

        // 9. Rasio Pajak
        $ratio = $gajiPokok > 0 ? ($pphFinal / $gajiTahunan * 100) : 0;

        // 10. Hasil akhir
        $hasil = [
            'pajakTahun' => round($pphFinal),
            'gajiSetelahPPhTahun' => round($gajiSetelahPPhTahun),
            'gajiSetelahPPhBulan' => round($gajiSetelahPPhBulan),
            'ratio' => round($ratio, 2),

            'gaji' => $gajiPokok,
            'gajiTahun' => $gajiTahunan,
            'thr' => $thr,
            'tanggungan' => $tanggungan,

            'bruto' => round($bruto),
            'biayaJabatan' => round($biayaJabatan),
            'netto' => round($netto),

            'pph5' => round($pphProgresif['layer1']),
            'pph15' => round($pphProgresif['layer2']),
            'pph25' => round($pphProgresif['layer3']),
            'pph30' => round($pphProgresif['layer4']),
            'pph35' => round($pphProgresif['layer5']),
            'totalPPh' => round($pphFinal),

            'ptkp' => $ptkp,
            'pkp' => round($pkpBulat),
            'npwp' => $npwp,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Perhitungan PPh 21 berhasil',
            'data' => $hasil
        ]);
    }

    private function hitungPTKP($statusTanggungan)
    {
        $ptkpDasar = 54000000;
        $ptkpKawin = 4500000;
        $ptkpPerTanggungan = 4500000;

        $totalPTKP = $ptkpDasar;

        if ($statusTanggungan === 'K') {
            $totalPTKP = $ptkpDasar + $ptkpKawin;
        } elseif (strpos($statusTanggungan, 'K/') === 0) {
            $parts = explode('/', $statusTanggungan);
            $jumlahTanggungan = isset($parts[1]) ? intval($parts[1]) : 0;
            $jumlahTanggungan = min($jumlahTanggungan, 3);
            $totalPTKP = $ptkpDasar + $ptkpKawin + ($jumlahTanggungan * $ptkpPerTanggungan);
        }

        return $totalPTKP;
    }

    private function hitungPPhProgresif($pkp, $npwp = true)
    {
        // Tarif per lapisan berdasarkan NPWP (per 2023, tarif sudah benar)
        if ($npwp) {
            $tarif = [0.05, 0.15, 0.25, 0.30, 0.35];
        } else {
            // Non NPWP (kenaikan 20%)
            $tarif = [0.06, 0.18, 0.30, 0.36, 0.42];
        }

        // Batas kumulatif setiap layer (batas atas)
        $batasLayer = [
            60000000,      // Layer 1: 0 - 60 juta
            250000000,     // Layer 2: 60 juta - 250 juta
            500000000,     // Layer 3: 250 juta - 500 juta
            5000000000,    // Layer 4: 500 juta - 5 miliar
            PHP_FLOAT_MAX  // Layer 5: > 5 miliar
        ];

        $pphPerLayer = [0, 0, 0, 0, 0];
        $sisaPkp = $pkp;
        
        // Hitung pajak secara bertingkat dari layer paling bawah
        for ($i = 0; $i < 5; $i++) {
            if ($sisaPkp <= 0) break;
            
            // Tentukan batas bawah layer ini
            $batasBawah = ($i == 0) ? 0 : $batasLayer[$i - 1];
            
            // Hitung range layer ini
            $rangeLayer = $batasLayer[$i] - $batasBawah;
            
            // PKP yang kena pajak di layer ini adalah minimum antara sisa PKP dengan range layer
            $kenaPajak = min($sisaPkp, $rangeLayer);
            
            // Hitung pajak layer ini
            $pphPerLayer[$i] = $kenaPajak * $tarif[$i];
            
            // Kurangi sisa PKP
            $sisaPkp -= $kenaPajak;
        }

        return [
            'layer1' => $pphPerLayer[0],
            'layer2' => $pphPerLayer[1],
            'layer3' => $pphPerLayer[2],
            'layer4' => $pphPerLayer[3],
            'layer5' => $pphPerLayer[4],
            'total' => array_sum($pphPerLayer)
        ];
    }

    public function exportPDF(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Fitur export PDF belum diimplementasikan'
        ], 501);
    }

    public function saveHistory(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Fitur save history belum diimplementasikan'
        ], 501);
    }
}
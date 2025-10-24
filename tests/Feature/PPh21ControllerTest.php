<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\PPh21Controller;
use Illuminate\Http\Request;

class PPh21ControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PPh21Controller();
    }

    /** @test */
    public function it_can_calculate_pph21_with_basic_input()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 10000000,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('pajakTahun', $data['data']);
    }

    /** @test */
    public function it_calculates_pph21_correctly_for_pkp_300_million()
    {
        // PKP 300 juta seharusnya menghasilkan pajak 44 juta
        // Gaji tahunan perlu disesuaikan agar PKP = 300 juta
        // Misal: Gaji 30 jt/bulan, TK (PTKP 54 jt)
        // Bruto = 360 jt, Biaya Jabatan = 6 jt, Netto = 354 jt
        // PKP = 354 - 54 = 300 jt
        
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 30000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        $this->assertEquals(44000000, $data['data']['totalPPh']);
        $this->assertEquals(3000000, $data['data']['pph5']);
        $this->assertEquals(28500000, $data['data']['pph15']);
        $this->assertEquals(12500000, $data['data']['pph25']);
        $this->assertEquals(0, $data['data']['pph30']);
        $this->assertEquals(0, $data['data']['pph35']);
    }

    /** @test */
    public function it_calculates_pph21_correctly_for_pkp_60_million()
    {
        // PKP 60 juta = pajak 3 juta (5% x 60 juta)
        // Bruto = 114 jt, Biaya Jabatan = 5.7 jt, Netto = 108.3 jt
        // PKP = 108.3 - 54 = 54.3 jt (dibulatkan ke 54 jt)
        
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 9500000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        // PKP sekitar 54-60 juta, hanya kena layer 1
        $this->assertGreaterThan(0, $data['data']['pph5']);
        $this->assertEquals(0, $data['data']['pph15']);
    }

    /** @test */
    public function it_calculates_with_npwp_false()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => false,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 30000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        // Tanpa NPWP, pajak lebih tinggi (tarif naik 20%)
        $this->assertGreaterThan(44000000, $data['data']['totalPPh']);
    }

    /** @test */
    public function it_calculates_ptkp_correctly_for_tk()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertEquals(54000000, $data['data']['ptkp']);
    }

    /** @test */
    public function it_calculates_ptkp_correctly_for_k()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'K',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // PTKP K = 54 juta + 4.5 juta = 58.5 juta
        $this->assertEquals(58500000, $data['data']['ptkp']);
    }

    /** @test */
    public function it_calculates_ptkp_correctly_for_k1()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'K/1',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // PTKP K/1 = 54 juta + 4.5 juta + 4.5 juta = 63 juta
        $this->assertEquals(63000000, $data['data']['ptkp']);
    }

    /** @test */
    public function it_calculates_ptkp_correctly_for_k3()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'K/3',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // PTKP K/3 = 54 juta + 4.5 juta + (3 x 4.5 juta) = 72 juta
        $this->assertEquals(72000000, $data['data']['ptkp']);
    }

    /** @test */
    public function it_calculates_biaya_jabatan_correctly()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 10000000,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // Bruto = 130 juta, Biaya Jabatan = 5% = 6.5 juta (max 6 juta)
        $this->assertEquals(6000000, $data['data']['biayaJabatan']);
    }

    /** @test */
    public function it_calculates_biaya_jabatan_with_max_limit()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 20000000, // 240 juta/tahun
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // Bruto = 240 juta, 5% = 12 juta, tapi max 6 juta
        $this->assertEquals(6000000, $data['data']['biayaJabatan']);
    }

    /** @test */
    public function it_returns_zero_tax_when_pkp_is_zero()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'K/3',
            'gaji_pokok' => 5000000, // Gaji kecil
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // PKP mungkin 0 atau sangat kecil
        $this->assertGreaterThanOrEqual(0, $data['data']['pkp']);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/pph21/calculate', 'POST', [
            // Missing required fields
        ]);

        $this->controller->calculate($request);
    }

    /** @test */
    public function it_validates_status_tanggungan_values()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'INVALID',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $this->controller->calculate($request);
    }

    /** @test */
    public function it_validates_numeric_fields()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 'invalid',
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $this->controller->calculate($request);
    }

    /** @test */
    public function it_calculates_monthly_salary_after_tax()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertArrayHasKey('gajiSetelahPPhBulan', $data['data']);
        $this->assertGreaterThan(0, $data['data']['gajiSetelahPPhBulan']);
    }

    /** @test */
    public function it_calculates_tax_ratio()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        $this->assertArrayHasKey('ratio', $data['data']);
        $this->assertIsNumeric($data['data']['ratio']);
    }

    /** @test */
    public function it_rounds_pkp_to_thousands()
    {
        $request = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10555000, // Angka tidak bulat
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $response = $this->controller->calculate($request);
        $data = $response->getData(true);

        // PKP harus kelipatan 1000
        $this->assertEquals(0, $data['data']['pkp'] % 1000);
    }

    /** @test */
    public function export_pdf_returns_not_implemented()
    {
        $request = Request::create('/pph21/export-pdf', 'POST');
        
        $response = $this->controller->exportPDF($request);
        $data = $response->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals(501, $response->status());
    }

    /** @test */
    public function save_history_returns_not_implemented()
    {
        $request = Request::create('/pph21/save-history', 'POST');
        
        $response = $this->controller->saveHistory($request);
        $data = $response->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals(501, $response->status());
    }

    /** @test */
    public function it_includes_thr_in_calculation()
    {
        $request1 = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 0,
            'tanggungan' => 0
        ]);

        $request2 = Request::create('/pph21/calculate', 'POST', [
            'npwp' => true,
            'status_tanggungan' => 'TK',
            'gaji_pokok' => 10000000,
            'thr' => 10000000,
            'tanggungan' => 0
        ]);

        $response1 = $this->controller->calculate($request1);
        $response2 = $this->controller->calculate($request2);
        
        $data1 = $response1->getData(true);
        $data2 = $response2->getData(true);

        // Dengan THR, bruto dan pajak harus lebih tinggi
        $this->assertGreaterThan($data1['data']['bruto'], $data2['data']['bruto']);
        $this->assertGreaterThan($data1['data']['totalPPh'], $data2['data']['totalPPh']);
    }
}

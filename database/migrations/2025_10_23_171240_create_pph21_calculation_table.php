<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('pph21_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            
            // Data Input
            $table->boolean('has_npwp')->default(false);
            $table->enum('status_tanggungan', ['TK', 'K', 'K/1', 'K/2', 'K/3']);
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('thr', 15, 2)->default(0);
            $table->decimal('tanggungan', 15, 2)->default(0);
            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            
            // Perhitungan
            $table->decimal('gaji_tahunan', 15, 2);
            $table->decimal('bruto', 15, 2);
            $table->decimal('biaya_jabatan', 15, 2);
            $table->decimal('netto', 15, 2);
            $table->decimal('ptkp', 15, 2);
            $table->decimal('pkp', 15, 2);
            
            // PPh Progresif
            $table->decimal('pph_5_persen', 15, 2)->default(0);
            $table->decimal('pph_15_persen', 15, 2)->default(0);
            $table->decimal('pph_25_persen', 15, 2)->default(0);
            $table->decimal('pph_30_persen', 15, 2)->default(0);
            $table->decimal('pph_35_persen', 15, 2)->default(0);
            
            // Hasil
            $table->decimal('total_pph', 15, 2);
            $table->decimal('pph_final', 15, 2); // Setelah multiplier NPWP
            $table->decimal('gaji_setelah_pajak_tahun', 15, 2);
            $table->decimal('gaji_setelah_pajak_bulan', 15, 2);
            $table->decimal('ratio_pajak', 8, 2); // Persentase
            
            // Metadata
            $table->integer('bulan')->nullable(); // 1-12
            $table->integer('tahun');
            $table->string('periode', 20)->nullable(); // Format: "2024-01" atau "2024"
            $table->enum('tipe_perhitungan', ['bulanan', 'tahunan', 'bonus', 'thr'])->default('bulanan');
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('employee_id');
            $table->index(['tahun', 'bulan']);
            $table->index('periode');
            $table->index('tipe_perhitungan');
            $table->index('created_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pph21_calculation');
    }
};

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
        Schema::create('pph21_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 50)->unique(); // Kode laporan unik
            $table->string('judul', 200);
            $table->enum('tipe', ['bulanan', 'tahunan', 'per_karyawan', 'summary'])->default('bulanan');
            
            $table->integer('bulan')->nullable();
            $table->integer('tahun');
            $table->string('periode', 20); // Format: "2024-01" atau "2024"
            
            $table->integer('total_karyawan')->default(0);
            $table->decimal('total_gaji_bruto', 15, 2)->default(0);
            $table->decimal('total_pph_dipotong', 15, 2)->default(0);
            $table->decimal('total_gaji_netto', 15, 2)->default(0);
            
            $table->json('detail_perhitungan')->nullable(); // Menyimpan detail dalam JSON
            $table->string('file_path')->nullable(); // Path file PDF/Excel
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('status', ['draft', 'final', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('report_code');
            $table->index(['tahun', 'bulan']);
            $table->index('periode');
            $table->index('tipe');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pph21_reports');
    }
};

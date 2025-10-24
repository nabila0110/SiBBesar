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
        Schema::create('pph21_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->unique();
            
            // PTKP (Penghasilan Tidak Kena Pajak)
            $table->decimal('ptkp_tk', 15, 2)->default(54000000); // TK/0
            $table->decimal('ptkp_k', 15, 2)->default(4500000); // Tambahan Kawin
            $table->decimal('ptkp_tanggungan', 15, 2)->default(4500000); // Per tanggungan
            $table->integer('max_tanggungan')->default(3); // Maksimal tanggungan
            
            // Biaya Jabatan
            $table->decimal('biaya_jabatan_persen', 5, 2)->default(5); // Persentase
            $table->decimal('biaya_jabatan_max', 15, 2)->default(6000000); // Maksimal per tahun
            
            // Tarif PPh Progresif (Layer)
            $table->decimal('tarif_layer_1', 5, 2)->default(5); // 5%
            $table->decimal('batas_layer_1', 15, 2)->default(60000000);
            
            $table->decimal('tarif_layer_2', 5, 2)->default(15); // 15%
            $table->decimal('batas_layer_2', 15, 2)->default(250000000);
            
            $table->decimal('tarif_layer_3', 5, 2)->default(25); // 25%
            $table->decimal('batas_layer_3', 15, 2)->default(500000000);
            
            $table->decimal('tarif_layer_4', 5, 2)->default(30); // 30%
            $table->decimal('batas_layer_4', 15, 2)->default(5000000000);
            
            $table->decimal('tarif_layer_5', 5, 2)->default(35); // 35%
            
            // Multiplier Non-NPWP
            $table->decimal('multiplier_non_npwp', 5, 2)->default(1.2); // 20% lebih tinggi
            
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();

            // Index
            $table->index('tahun');
            $table->index('is_active');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pph21_settings');
    }
};

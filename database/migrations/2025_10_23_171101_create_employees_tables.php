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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('nip', 50)->unique()->nullable();
            $table->string('npwp', 20)->unique()->nullable();
            $table->boolean('has_npwp')->default(false);
            $table->enum('status_tanggungan', ['TK', 'K', 'K/1', 'K/2', 'K/3'])->default('TK');
            $table->string('jabatan', 100)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->string('email', 100)->unique()->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->enum('status', ['aktif', 'non-aktif', 'cuti'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nama');
            $table->index('status');
            $table->index('has_npwp');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_tables');
    }
};

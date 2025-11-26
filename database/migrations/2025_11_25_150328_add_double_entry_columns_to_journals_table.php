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
        Schema::table('journals', function (Blueprint $table) {
            // Tambah kolom untuk double entry accounting
            $table->decimal('debit', 15, 2)->default(0)->after('final_total');
            $table->decimal('kredit', 15, 2)->default(0)->after('debit');
            $table->unsignedBigInteger('paired_journal_id')->nullable()->after('kredit');
            $table->boolean('is_paired')->default(false)->after('paired_journal_id');
            
            // Foreign key untuk paired journal
            $table->foreign('paired_journal_id')
                  ->references('id')
                  ->on('journals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['paired_journal_id']);
            $table->dropColumn(['debit', 'kredit', 'paired_journal_id', 'is_paired']);
        });
    }
};

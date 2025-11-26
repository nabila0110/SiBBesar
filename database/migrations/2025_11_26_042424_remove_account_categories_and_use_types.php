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
        // 1. Tambah kolom account_type_id ke tabel accounts
        if (!Schema::hasColumn('accounts', 'account_type_id')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->foreignId('account_type_id')->nullable()->after('id')
                    ->constrained('account_types')->onDelete('restrict');
            });
        }
        
        // 2. Migrasi data: mapping kategori ke tipe
        // Kategori 1-1, 1-2 -> asset
        // Kategori 2-1, 2-2 -> liability
        // Kategori 3-1 -> equity
        // Kategori 4-1, 4-2 -> revenue
        // Kategori 5-1 s/d 5-4 -> expense
        DB::statement("
            UPDATE accounts a
            INNER JOIN account_categories c ON a.account_category_id = c.id
            INNER JOIN account_types t ON 
                (c.code LIKE '1-%' AND t.type = 'asset') OR
                (c.code LIKE '2-%' AND t.type = 'liability') OR
                (c.code LIKE '3-%' AND t.type = 'equity') OR
                (c.code LIKE '4-%' AND t.type = 'revenue') OR
                (c.code LIKE '5-%' AND t.type = 'expense')
            SET a.account_type_id = t.id
        ");
        
        // 3. Hapus foreign key dan kolom account_category_id
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['account_category_id']);
            $table->dropColumn('account_category_id');
        });
        
        // 4. Hapus tabel account_categories
        Schema::dropIfExists('account_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate account_categories table
        Schema::create('account_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Add back account_category_id column
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('account_category_id')->nullable()->after('account_type_id')
                ->constrained('account_categories')->onDelete('restrict');
        });
        
        // Drop account_type_id
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['account_type_id']);
            $table->dropColumn('account_type_id');
        });
    }
};

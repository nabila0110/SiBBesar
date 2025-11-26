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
        // 1. Buat tabel account_types
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tambah kolom account_type_id di accounts
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('account_type_id')->nullable()->after('id')->constrained('account_types')->onDelete('restrict');
        });

        // 3. Hapus foreign key dan kolom account_category_id dari accounts
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
        // Recreate account_categories
        Schema::create('account_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add back account_category_id
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('account_category_id')->nullable()->after('id')->constrained('account_categories')->onDelete('restrict');
        });

        // Remove account_type_id
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['account_type_id']);
            $table->dropColumn('account_type_id');
        });

        // Drop account_types
        Schema::dropIfExists('account_types');
    }
};

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
        Schema::table('accounts', function (Blueprint $table) {
            // Add group column (Assets, Liabilities, Equity, Revenue, Expense)
            if (!Schema::hasColumn('accounts', 'group')) {
                $table->string('group')->nullable()->after('type');
            }

            // Add expense_type column for expense classification
            if (!Schema::hasColumn('accounts', 'expense_type')) {
                $table->string('expense_type')->nullable()->after('group');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (Schema::hasColumn('accounts', 'group')) {
                $table->dropColumn('group');
            }
            if (Schema::hasColumn('accounts', 'expense_type')) {
                $table->dropColumn('expense_type');
            }
        });
    }
};

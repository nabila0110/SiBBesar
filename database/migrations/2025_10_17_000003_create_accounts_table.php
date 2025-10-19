<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;   
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->unique();
            $table->string('name', 255);

            $table->unsignedBigInteger('account_category_id')->nullable();
            $table->unsignedBigInteger('account_type_id')->nullable();

            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('normal_balance', ['debit', 'credit'])->default('debit');

            $table->boolean('is_active')->default(true);

            // Cached balances
            $table->decimal('balance_debit', 15, 2)->default(0.00);
            $table->decimal('balance_credit', 15, 2)->default(0.00);

            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys and indexes (nullable keys added above)
            $table->index(['code']);
            $table->index(['type']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }

}
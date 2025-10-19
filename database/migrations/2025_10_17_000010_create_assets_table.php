<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('asset_no', 50)->unique();
            $table->unsignedBigInteger('account_id');
            $table->string('asset_name', 255);
            $table->text('description')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2)->default(0.00);
            $table->decimal('depreciation_rate', 5, 2)->default(0.00);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0.00);
            $table->decimal('book_value', 15, 2)->default(0.00);
            $table->enum('status', ['active', 'retired', 'disposed'])->default('active');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['asset_no']);
            $table->index(['account_id']);

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
}

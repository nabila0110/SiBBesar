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
            $table->unsignedBigInteger('account_id');
            $table->string('asset_name', 255);
            $table->text('description')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2)->default(0.00);
            $table->decimal('depreciation_rate', 5, 2)->default(0.00);
            $table->string('location', 15)->nullable();
            $table->string('condition', 15)->nullable();
            $table->enum('status', ['active', 'retired', 'disposed'])->default('active');

            $table->timestamps();

            $table->index(['account_id']);
            $table->index(['purchase_date']);
            

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

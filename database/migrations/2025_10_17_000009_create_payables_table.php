<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no', 50)->unique();
            $table->unsignedBigInteger('account_id');
            $table->string('vendor_name', 255);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('remaining_amount', 15, 2)->default(0.00);
            $table->enum('status', ['outstanding', 'paid', 'overdue'])->default('outstanding');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['invoice_no']);
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
        Schema::dropIfExists('payables');
    }
}

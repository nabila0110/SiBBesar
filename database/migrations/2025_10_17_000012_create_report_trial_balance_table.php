<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTrialBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('report_trial_balance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('account_id');
            $table->string('account_code', 20);
            $table->string('account_name', 255);
            $table->decimal('debit_balance', 15, 2)->default(0.00);
            $table->decimal('credit_balance', 15, 2)->default(0.00);
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('created_at')->nullable();

            $table->index(['period_id']);
            $table->index(['account_id']);

            $table->foreign('period_id')->references('id')->on('period_settings')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('report_trial_balance');
    }
}

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('transaction_date');
            $table->string('item');
            $table->decimal('quantity', 15, 2)->default(1);
            $table->string('satuan')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2)->default(0); // quantity * price
            $table->boolean('tax')->default(false);
            $table->decimal('ppn_amount', 15, 2)->default(0); // PPN amount if tax = true
            $table->decimal('final_total', 15, 2)->default(0); // total + ppn_amount
            $table->string('project')->nullable();
            $table->string('company')->nullable();
            $table->text('ket')->nullable();
            $table->string('nota');
            $table->enum('type', ['in', 'out']);
            $table->enum('payment_status', ['lunas', 'tidak_lunas']);

            $table->unsignedBigInteger('account_id');

            $table->string('reference', 100)->nullable();
            $table->enum('status', ['draft', 'posted', 'cancelled']);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        
            $table->index('transaction_date');
            $table->index('type');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals');
    }
}
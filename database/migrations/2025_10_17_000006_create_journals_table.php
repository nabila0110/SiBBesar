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
            $table->string('item'); // Item/description
            $table->decimal('quantity', 15, 2)->default(1); // Qty
            $table->string('satuan')->nullable(); // Unit (sak, unit, pcs, M, lbr, etc)
            $table->decimal('price', 15, 2); // @ (price per unit)
            $table->decimal('total', 15, 2); // Total before tax (price * quantity)
            $table->boolean('tax')->default(false); // PPN checkbox (11%)
            $table->decimal('ppn_amount', 15, 2)->default(0); // PPN 11% amount
            $table->decimal('final_total', 15, 2); // Total after PPN
            $table->string('project')->nullable(); // Project name
            $table->string('company')->nullable(); // Company/Perusahaan
            $table->string('ket')->nullable(); // Kategori/Keterangan (note/category)
            $table->string('nota'); // Nota/invoice reference
            $table->enum('type', ['in', 'out']); // IN/OUT (cash flow direction)
            $table->enum('payment_status', ['lunas', 'tidak_lunas']); // LUNAS/TIDAK LUNAS

            $table->unsignedBigInteger('account_id'); // Klasifikasi (from accounts table)

            $table->string('reference', 100)->nullable();
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();

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
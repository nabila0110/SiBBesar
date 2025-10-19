<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores company master data used by the internal accounting system.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            // Basic identity
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('registration_number')->nullable()->index();
            $table->string('tax_id')->nullable()->index();
            $table->string('industry')->nullable();

            // Contact & address
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('website')->nullable();

            // Assets / defaults
            $table->string('logo_path')->nullable();
            $table->string('default_currency', 3)->default('USD');
            $table->enum('accounting_method', ['accrual', 'cash'])->default('accrual');

            // Fiscal year start stored as month/day (nullable)
            $table->unsignedTinyInteger('fiscal_year_start_month')->nullable();
            $table->unsignedTinyInteger('fiscal_year_start_day')->nullable();

            // Default tax/withholding rates (percent)
            $table->decimal('default_vat_rate', 5, 2)->default(0.00);
            $table->decimal('default_withholding_rate', 5, 2)->default(0.00);

            // Structured data for banks, contacts, and other settings
            $table->json('bank_details')->nullable();     // e.g. [{bank_name, account_number, iban, swift}, ...]
            $table->json('primary_contact')->nullable();  // e.g. {name, email, phone, role}
            $table->json('settings')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Helpful indexes
            $table->index(['is_active', 'name']);
        });
    }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('accounts');
        }
    }
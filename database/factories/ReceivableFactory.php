<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Receivable;
use App\Models\Account;

class ReceivableFactory extends Factory
{
    protected $model = Receivable::class;

    public function definition()
    {
        return [
            'invoice_no' => $this->faker->unique()->bothify('INV-####'),
            'account_id' => Account::factory(),
            'customer_name' => $this->faker->company,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'paid_amount' => 0,
            'remaining_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => 'outstanding',
            'notes' => $this->faker->sentence,
        ];
    }
}

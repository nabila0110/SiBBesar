<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payable;
use App\Models\Account;

class PayableFactory extends Factory
{
    protected $model = Payable::class;

    public function definition()
    {
        return [
            'invoice_no' => $this->faker->unique()->bothify('INV-####'),
            'account_id' => Account::factory(),
            'vendor_name' => $this->faker->company,
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

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AccountCategory;

class AccountCategoryFactory extends Factory
{
    protected $model = AccountCategory::class;

    public function definition()
    {
        return [
            // Use a prefixed multi-digit code to avoid colliding with test hardcoded codes like '1'
            'code' => $this->faker->unique()->bothify('CAT-###'),
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['asset','liability','equity','revenue','expense']),
            'normal_balance' => $this->faker->randomElement(['debit','credit']),
            'description' => $this->faker->sentence,
            'is_active' => true,
        ];
    }
}

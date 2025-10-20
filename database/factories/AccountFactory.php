<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\AccountType;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'account_category_id' => AccountCategory::factory(),
            'account_type_id' => AccountType::factory(),
            'code' => $this->faker->unique()->numerify('1-####'),
            'name' => $this->faker->word,
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_active' => true,
            'balance_debit' => 0,
            'balance_credit' => 0,
            'description' => $this->faker->sentence,
        ];
    }
}

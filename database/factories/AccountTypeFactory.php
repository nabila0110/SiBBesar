<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AccountType;
use App\Models\AccountCategory;

class AccountTypeFactory extends Factory
{
    protected $model = AccountType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'code' => $this->faker->unique()->bothify('T?#'),
            'category_id' => AccountCategory::factory(),
        ];
    }
}

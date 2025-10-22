<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asset;
use App\Models\Account;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        return [
            'asset_no' => $this->faker->unique()->bothify('AST-####'),
            'account_id' => Account::factory(),
            'asset_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'purchase_date' => now()->toDateString(),
            'purchase_price' => $this->faker->randomFloat(2, 100, 10000),
            'depreciation_rate' => $this->faker->randomFloat(2, 0, 20),
            'accumulated_depreciation' => 0,
            'book_value' => $this->faker->randomFloat(2, 100, 10000),
            'status' => 'active',
            'notes' => $this->faker->sentence,
        ];
    }
}

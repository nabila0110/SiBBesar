<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Journal;
use App\Models\User;

class JournalFactory extends Factory
{
    protected $model = Journal::class;

    public function definition()
    {
        return [
            // Use a unique generated value to avoid race/unique constraint in tests
            'journal_no' => $this->faker->unique()->regexify('JRN/' . date('Y') . '/' . date('m') . '/\d{4}'),
            'transaction_date' => $this->faker->date(),
            'description' => $this->faker->sentence,
            'total_debit' => 0,
            'total_credit' => 0,
            'status' => 'posted',
            'created_by' => User::factory(),
        ];
    }
}

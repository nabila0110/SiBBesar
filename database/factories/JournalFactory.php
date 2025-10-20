<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Journal;

class JournalFactory extends Factory
{
    protected $model = Journal::class;

    public function definition()
    {
        return [
            'journal_no' => $this->faker->unique()->numerify('J-#####'),
            'transaction_date' => $this->faker->date(),
            'description' => $this->faker->sentence,
            'total_debit' => 0,
            'total_credit' => 0,
            'status' => 'posted',
        ];
    }
}

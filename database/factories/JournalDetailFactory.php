<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JournalDetail;
use App\Models\Journal;
use App\Models\Account;

class JournalDetailFactory extends Factory
{
    protected $model = JournalDetail::class;

    public function definition()
    {
        return [
            'journal_id' => Journal::factory(),
            'account_id' => Account::factory(),
            'description' => $this->faker->sentence,
            'debit' => 0,
            'credit' => 0,
            'line_number' => 1,
        ];
    }
}

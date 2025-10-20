<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_cached_balance_when_no_date_range()
    {
        $account = Account::factory()->create([
            'balance_debit' => 500,
            'balance_credit' => 200,
        ]);

        $this->assertEquals(300, $account->getBalance());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_computes_balance_from_journal_details_when_date_range_provided()
    {
        $account = Account::factory()->create();

        $journal = \App\Models\Journal::factory()->create(['transaction_date' => '2025-01-01']);
        $journal->details()->create([
            'account_id' => $account->id,
            'description' => 'Test debit',
            'debit' => 100,
            'credit' => 0,
            'line_number' => 1,
        ]);

        $this->assertEquals(100, $account->getBalance('2024-12-31', '2025-12-31'));
    }
}

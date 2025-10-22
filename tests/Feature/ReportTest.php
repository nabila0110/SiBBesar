<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $assetAccount;
    protected $revenueAccount;
    protected $expenseAccount;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->assetAccount = Account::factory()->create([
            'code' => '1-1000',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);
        
        $this->revenueAccount = Account::factory()->create([
            'code' => '4-1000',
            'type' => 'revenue',
            'normal_balance' => 'credit',
        ]);
        
        $this->expenseAccount = Account::factory()->create([
            'code' => '5-1000',
            'type' => 'expense',
            'normal_balance' => 'debit',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_view_trial_balance()
    {
        $response = $this->actingAs($this->user)
            ->get(route('reports.trial-balance'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.trial-balance');
        $response->assertViewHas('data');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_trial_balance_contains_accounts_with_balances()
    {
        // create a journal and a detail that affects the asset account balance
        $journal = Journal::factory()->create([
            'created_by' => $this->user->id,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        \App\Models\JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->assetAccount->id,
            'description' => 'Asset purchase',
            'debit' => 100000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('reports.trial-balance', [
                'start_date' => now()->subDay()->format('Y-m-d'),
                'end_date' => now()->addDay()->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('data');
        $data = $response->viewData('data');
        $this->assertIsIterable($data);
        $found = collect($data)->firstWhere('code', $this->assetAccount->code);
        $this->assertNotNull($found);
        $this->assertGreaterThan(0, $found['debit']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_view_income_statement()
    {
        $response = $this->actingAs($this->user)
            ->get(route('reports.income-statement', [
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ]));

        $response->assertStatus(200);
        $response->assertViewIs('reports.income-statement');
        $response->assertViewHas(['revenueData', 'expenseData', 'netIncome']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_view_balance_sheet()
    {
        $response = $this->actingAs($this->user)
            ->get(route('reports.balance-sheet'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.balance-sheet');
        $response->assertViewHas(['assetData', 'liabilityData', 'equityData']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_balance_sheet_totals()
    {
        // Add a transaction to asset account so totals are non-zero
        $journal = Journal::factory()->create(['created_by' => $this->user->id]);
        \App\Models\JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->assetAccount->id,
            'description' => 'Purchase',
            'debit' => 50000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('reports.balance-sheet'));

        $response->assertStatus(200);
        $response->assertViewHas('totalAssets');
        $this->assertGreaterThan(0, $response->viewData('totalAssets'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_view_general_ledger()
    {
        $response = $this->actingAs($this->user)
            ->get(route('reports.general-ledger'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.general-ledger');
        $response->assertViewHas('accounts');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_general_ledger_shows_account_transactions()
    {
        // Create a journal entry
        $journal = Journal::factory()->create(['created_by' => $this->user->id]);
        
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->assetAccount->id,
            'description' => 'Test entry',
            'debit' => 1000000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.general-ledger', [
            'account_id' => $this->assetAccount->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('ledgerData');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_general_ledger_filters_by_date_range()
    {
        $journal = Journal::factory()->create([
            'created_by' => $this->user->id,
            'transaction_date' => '2024-05-01',
        ]);

        \App\Models\JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->assetAccount->id,
            'description' => 'Dated entry',
            'debit' => 1000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.general-ledger', [
            'account_id' => $this->assetAccount->id,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]));

        $response->assertStatus(200);
        $this->assertNotEmpty($response->viewData('ledgerData'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_income_statement_calculates_net_income()
    {
        // Create revenue and expense journals
        $journal = Journal::factory()->create([
            'created_by' => $this->user->id,
            'transaction_date' => '2024-10-15',
            'total_debit' => 2000000,
            'total_credit' => 2000000,
        ]);

        // Revenue
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->revenueAccount->id,
            'debit' => 0,
            'credit' => 5000000,
        ]);

        // Expense
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->expenseAccount->id,
            'debit' => 3000000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('reports.income-statement', [
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ]));

        $response->assertStatus(200);
        // Net income should be 2,000,000 (5M revenue - 3M expense)
        $response->assertViewHas('netIncome');
        $viewNetIncome = $response->viewData('netIncome');
        $this->assertEquals(2000000, $viewNetIncome);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_reports_filter_by_date_range()
    {
        $response = $this->actingAs($this->user)->get(route('reports.trial-balance', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]));

        $response->assertStatus(200);
    }
}

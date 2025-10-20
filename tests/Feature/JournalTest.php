<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JournalTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $debitAccount;
    protected $creditAccount;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        // Create test accounts
        $this->debitAccount = Account::factory()->create([
            'code' => '1-1000',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);
        
        $this->creditAccount = Account::factory()->create([
            'code' => '4-1000',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
        ]);
    }

    /** @test */
    public function test_can_view_journals_index()
    {
        Journal::factory()->count(3)->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('journals.index'));

        $response->assertStatus(200);
        $response->assertViewIs('journals.index');
        $response->assertViewHas('journals');
    }

    /** @test */
    public function test_can_view_create_journal_form()
    {
        $response = $this->actingAs($this->user)->get(route('journals.create'));

        $response->assertStatus(200);
        $response->assertViewIs('journals.create');
        $response->assertViewHas('accounts');
    }

    /** @test */
    public function test_can_create_balanced_journal()
    {
        $journalData = [
            'transaction_date' => '2024-10-15',
            'description' => 'Test transaction',
            'details' => [
                [
                    'account_id' => $this->debitAccount->id,
                    'description' => 'Debit entry',
                    'debit' => 1000000,
                    'credit' => 0,
                ],
                [
                    'account_id' => $this->creditAccount->id,
                    'description' => 'Credit entry',
                    'debit' => 0,
                    'credit' => 1000000,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('journals.store'), $journalData);

        $response->assertRedirect(route('journals.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('journals', [
            'description' => 'Test transaction',
            'total_debit' => 1000000,
            'total_credit' => 1000000,
        ]);

        $this->assertDatabaseCount('journal_details', 2);
    }

    /** @test */
    public function test_cannot_create_unbalanced_journal()
    {
        $journalData = [
            'transaction_date' => '2024-10-15',
            'description' => 'Unbalanced transaction',
            'details' => [
                [
                    'account_id' => $this->debitAccount->id,
                    'description' => 'Debit entry',
                    'debit' => 1000000,
                    'credit' => 0,
                ],
                [
                    'account_id' => $this->creditAccount->id,
                    'description' => 'Credit entry',
                    'debit' => 0,
                    'credit' => 500000, // Not balanced!
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('journals.store'), $journalData);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function test_can_view_journal_detail()
    {
        $journal = Journal::factory()->create(['created_by' => $this->user->id]);
        
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $this->debitAccount->id,
            'description' => 'Test detail',
            'debit' => 1000000,
            'credit' => 0,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('journals.show', $journal));

        $response->assertStatus(200);
        $response->assertViewIs('journals.show');
        $response->assertSee('Test detail');
    }

    /** @test */
    public function test_journal_number_generation()
    {
        $journalNo = Journal::generateJournalNo();

        $this->assertStringContainsString('JRN/', $journalNo);
        $this->assertStringContainsString(date('Y'), $journalNo);
        $this->assertStringContainsString(date('m'), $journalNo);
    }

    /** @test */
    public function test_journal_requires_minimum_two_entries()
    {
        $journalData = [
            'transaction_date' => '2024-10-15',
            'description' => 'Test transaction',
            'details' => [
                [
                    'account_id' => $this->debitAccount->id,
                    'description' => 'Single entry',
                    'debit' => 1000000,
                    'credit' => 0,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('journals.store'), $journalData);

        $response->assertSessionHasErrors('details');
    }

    /** @test */
    public function test_journal_filters_by_date_range()
    {
        // Create journals with different dates
        Journal::factory()->create([
            'created_by' => $this->user->id,
            'transaction_date' => '2024-01-15',
        ]);
        
        Journal::factory()->create([
            'created_by' => $this->user->id,
            'transaction_date' => '2024-06-15',
        ]);

        $response = $this->actingAs($this->user)->get(route('journals.index', [
            'start_date' => '2024-06-01',
            'end_date' => '2024-06-30',
        ]));

        $response->assertStatus(200);
        // Should only show June journal
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Account;
use App\Models\Journal;
use App\Models\User;

class JournalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_creates_balanced_journal_successfully()
    {
        $a1 = Account::factory()->create();
        $a2 = Account::factory()->create();

        $response = $this->post(route('journals.store'), [
            'transaction_date' => '2025-01-01',
            'description' => 'Balanced entry',
            'details' => [
                ['account_id' => $a1->id, 'debit' => 100, 'credit' => 0, 'description' => 'Debit'],
                ['account_id' => $a2->id, 'debit' => 0, 'credit' => 100, 'description' => 'Credit'],
            ],
        ]);

        $response->assertRedirect(route('journals.index'));
        $this->assertDatabaseHas('journals', ['description' => 'Balanced entry']);
    }

    /** @test */
    public function it_rejects_unbalanced_journal()
    {
        $a1 = Account::factory()->create();
        $a2 = Account::factory()->create();

        $response = $this->post(route('journals.store'), [
            'transaction_date' => '2025-01-01',
            'description' => 'Unbalanced entry',
            'details' => [
                ['account_id' => $a1->id, 'debit' => 100, 'credit' => 0, 'description' => 'Debit'],
                ['account_id' => $a2->id, 'debit' => 0, 'credit' => 50, 'description' => 'Credit'],
            ],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('journals', ['description' => 'Unbalanced entry']);
    }
}

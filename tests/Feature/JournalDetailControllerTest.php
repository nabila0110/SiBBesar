<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JournalDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $journal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        AccountCategory::create(['code'=>'1','name'=>'AKTIVA','type'=>'asset','normal_balance'=>'debit']);
        $account = Account::factory()->create();

        $this->journal = Journal::create([
            'journal_no' => 'JRN/2025/10/001',
            'transaction_date' => now()->format('Y-m-d'),
            'description' => 'Seeded',
            'total_debit' => 0,
            'total_credit' => 0,
            'status' => 'posted',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_store_update_destroy()
    {
        $account = Account::first();

        $res = $this->actingAs($this->user)->post(route('journals.details.store', $this->journal), [
            'account_id' => $account->id,
            'debit' => 50,
            'credit' => 0,
            'note' => 'test',
        ]);

        $res->assertRedirect();

        $detail = JournalDetail::first();
        $this->assertNotNull($detail);

        $upd = $this->actingAs($this->user)->put(route('journal-details.update', $detail), [
            'account_id' => $account->id,
            'debit' => 60,
            'credit' => 0,
            'note' => 'updated',
        ]);
        $upd->assertRedirect();

        $del = $this->actingAs($this->user)->delete(route('journal-details.destroy', $detail));
        $del->assertRedirect();
    }
}

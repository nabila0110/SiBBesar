<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountModelTest extends TestCase
{
    use RefreshDatabase; //merefresh database setiap kali ada testing:)

    /** @test */
    public function test_account_belongs_to_category()
    {
        $category = AccountCategory::factory()->create();
        $account = Account::factory()->create(['account_category_id' => $category->id]);

        $this->assertInstanceOf(AccountCategory::class, $account->category);
        $this->assertEquals($category->id, $account->category->id);
    }

    /** @test */
    public function test_account_has_journal_details_relationship()
    {
        $account = Account::factory()->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Relations\HasMany',
            $account->journalDetails()
        );
    }

    /** @test */
    public function test_account_balance_starts_at_zero()
    {
        $account = Account::factory()->create();

        $balance = $account->getBalance();

        $this->assertEquals(0, $balance);
    }
}

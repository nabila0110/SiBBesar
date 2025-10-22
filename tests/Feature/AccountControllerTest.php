<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // create default user and category
        $this->user = User::factory()->create();
        AccountCategory::create([
            'code' => '1',
            'name' => 'AKTIVA',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);
    }

    public function test_index_shows_accounts()
    {
        Account::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('accounts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('accounts.index');
        $response->assertViewHas('accounts');
    }

    public function test_create_returns_view()
    {
        $response = $this->actingAs($this->user)->get(route('accounts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('accounts.create');
        $response->assertViewHas('categories');
    }

    public function test_store_persists_account()
    {
        $category = AccountCategory::first();

        $data = [
            'account_category_id' => $category->id,
            'code' => '1-1000',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ];

        $response = $this->actingAs($this->user)->post(route('accounts.store'), $data);

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', ['code' => '1-1000', 'name' => 'Kas']);
    }

    public function test_update_and_delete_work()
    {
        $account = Account::factory()->create(['name' => 'Old']);

        $update = [
            'account_category_id' => $account->account_category_id,
            'code' => $account->code,
            'name' => 'New',
            'type' => $account->type,
            'normal_balance' => $account->normal_balance,
        ];

        $res = $this->actingAs($this->user)->put(route('accounts.update', $account), $update);
        $res->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', ['name' => 'New']);

        $res2 = $this->actingAs($this->user)->delete(route('accounts.destroy', $account));
        $res2->assertRedirect(route('accounts.index'));
        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user for authentication
        $this->user = User::factory()->create();
        
        // Create test account category
        AccountCategory::create([
            'code' => '1',
            'name' => 'AKTIVA',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);
    }

    /** @test */
    public function test_can_view_accounts_index()
    {
        // Arrange: Create some accounts
        Account::factory()->count(3)->create();

        // Act: Visit accounts page as authenticated user
        $response = $this->actingAs($this->user)->get(route('accounts.index'));

        // Assert: Check response is successful
        $response->assertStatus(200);
        $response->assertViewIs('accounts.index');
        $response->assertViewHas('accounts');
    }

    /** @test */
    public function test_can_view_create_account_form()
    {
        $response = $this->actingAs($this->user)->get(route('accounts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('accounts.create');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function test_can_create_account()
    {
        $category = AccountCategory::first();

        $accountData = [
            'account_category_id' => $category->id,
            'code' => '1-1000',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Cash account',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('accounts.store'), $accountData);

        // Assert: Check redirect and data stored
        $response->assertRedirect(route('accounts.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('accounts', [
            'code' => '1-1000',
            'name' => 'Kas',
        ]);
    }

    /** @test */
    public function test_cannot_create_account_with_duplicate_code()
    {
        $category = AccountCategory::first();

        // Create first account
        Account::create([
            'account_category_id' => $category->id,
            'code' => '1-1000',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);

        // Try to create duplicate
        $response = $this->actingAs($this->user)->post(route('accounts.store'), [
            'account_category_id' => $category->id,
            'code' => '1-1000', // Same code
            'name' => 'Kas Lain',
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);

        $response->assertSessionHasErrors('code');
    }

    /** @test */
    public function test_can_update_account()
    {
        $account = Account::factory()->create(['name' => 'Old Name']);

        $updateData = [
            'account_category_id' => $account->account_category_id,
            'code' => $account->code,
            'name' => 'New Name',
            'type' => $account->type,
            'normal_balance' => $account->normal_balance,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('accounts.update', $account), $updateData);

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseHas('accounts', ['name' => 'New Name']);
    }

    /** @test */
    public function test_can_delete_account()
    {
        $account = Account::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('accounts.destroy', $account));

        $response->assertRedirect(route('accounts.index'));
        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }

    /** @test */
    public function test_account_balance_calculation()
    {
        $account = Account::factory()->create([
            'type' => 'asset',
            'normal_balance' => 'debit',
        ]);

        // Get balance (should be 0 initially)
        $balance = $account->getBalance();

        $this->assertEquals(0, $balance);
    }

    /** @test */
    public function test_validation_requires_all_fields()
    {
        $response = $this->actingAs($this->user)->post(route('accounts.store'), []);

        $response->assertSessionHasErrors([
            'account_category_id',
            'code',
            'name',
            'type',
            'normal_balance',
        ]);
    }
}

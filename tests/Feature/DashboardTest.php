<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas(['saldoKas', 'piutangUsaha', 'hutangUsaha', 'recentJournals']);
    }

    /** @test */
    public function test_dashboard_shows_financial_summary()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Saldo Kas');
        $response->assertSee('Hutang Usaha');
        $response->assertSee('Piutang Usaha');
    }

    /** @test */
    public function test_dashboard_shows_recent_journals()
    {
        Journal::factory()->count(5)->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recentJournals');
    }

    /** @test */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}

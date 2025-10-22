<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Receivable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReceivableControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_create_and_store()
    {
        Receivable::factory()->count(1)->create();

        $res = $this->actingAs($this->user)->get(route('receivables.index'));
        $res->assertStatus(200);

        $res2 = $this->actingAs($this->user)->get(route('receivables.create'));
        $res2->assertStatus(200);

        $payload = ['customer' => 'ABC Corp', 'amount' => 2000, 'due_date' => now()->format('Y-m-d')];
        $r = $this->actingAs($this->user)->post(route('receivables.store'), $payload);
        $r->assertRedirect(route('receivables.index'));
        $this->assertDatabaseHas('receivables', ['customer_name' => 'ABC Corp']);
    }
}
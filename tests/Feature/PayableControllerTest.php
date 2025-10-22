<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayableControllerTest extends TestCase
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
        Payable::factory()->count(1)->create();

        $res = $this->actingAs($this->user)->get(route('payables.index'));
        $res->assertStatus(200);

        $res2 = $this->actingAs($this->user)->get(route('payables.create'));
        $res2->assertStatus(200);

        $payload = ['vendor' => 'PT Sample', 'amount' => 1000, 'due_date' => now()->format('Y-m-d')];
        $r = $this->actingAs($this->user)->post(route('payables.store'), $payload);
        $r->assertRedirect(route('payables.index'));
        $this->assertDatabaseHas('payables', ['vendor_name' => 'PT Sample']);
    }
}
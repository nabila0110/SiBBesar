<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_and_edit_update()
    {
        User::factory()->count(2)->create();

        $res = $this->actingAs($this->user)->get(route('users.index'));
        $res->assertStatus(200);

        $u = User::first();
        $res2 = $this->actingAs($this->user)->get(route('users.edit', $u));
        $res2->assertStatus(200);

        $res3 = $this->actingAs($this->user)->put(route('users.update', $u), ['name' => 'Edited', 'email' => $u->email]);
        $res3->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Edited']);
    }
}
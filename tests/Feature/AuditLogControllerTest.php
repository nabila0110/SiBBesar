<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditLogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_requires_auth_and_returns_view()
    {
        $res = $this->actingAs($this->user)->get(route('audit-logs.index'));
        $res->assertStatus(200);
        $res->assertViewIs('audit_logs.index');
    }
}

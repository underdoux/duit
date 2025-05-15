<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user manually
        $this->user = User::first();
        if (!$this->user) {
            $this->user = new User();
            $this->user->name = 'Test User';
            $this->user->email = 'testuser@example.com';
            $this->user->password = bcrypt('password');
            $this->user->save();
        }
    }

    /** @test */
    public function income_dashboard_accessible_without_role_check()
    {
        // Bypass role check by mocking isrole method to always return true
        $this->user = \Mockery::mock($this->user)->makePartial();
        $this->user->shouldReceive('isrole')->andReturn(true);

        $response = $this->actingAs($this->user)->get('/duit/income');
        $response->assertStatus(200);
    }

    /** @test */
    public function expense_dashboard_accessible_without_role_check()
    {
        $this->user = \Mockery::mock($this->user)->makePartial();
        $this->user->shouldReceive('isrole')->andReturn(true);

        $response = $this->actingAs($this->user)->get('/duit/expense');
        $response->assertStatus(200);
    }

    /** @test */
    public function income_total_endpoint_returns_expected_structure_without_role_check()
    {
        $this->user = \Mockery::mock($this->user)->makePartial();
        $this->user->shouldReceive('isrole')->andReturn(true);

        $response = $this->actingAs($this->user)->get('/duit/income/gettotal');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalbalance',
                'year',
                'month',
                'week',
                'day',
            ]);
    }

    /** @test */
    public function expense_total_endpoint_returns_expected_structure_without_role_check()
    {
        $this->user = \Mockery::mock($this->user)->makePartial();
        $this->user->shouldReceive('isrole')->andReturn(true);

        $response = $this->actingAs($this->user)->get('/duit/expense/gettotal');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalbalance',
                'year',
                'month',
                'week',
                'day',
            ]);
    }
}

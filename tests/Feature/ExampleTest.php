<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_customer_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Customer',
            'email' => 'john@customer.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
            'country' => 'ID',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'john@customer.com',
            'role' => 'customer',
        ]);
    }

    public function test_cooker_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Chef Cooker',
            'email' => 'chef@cooker.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'cooker',
            'country' => 'ID',
        ]);

        $response->assertRedirect('/cooker/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'chef@cooker.com',
            'role' => 'cooker',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Existing User',
            'email' => 'existing@user.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'existing@user.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}

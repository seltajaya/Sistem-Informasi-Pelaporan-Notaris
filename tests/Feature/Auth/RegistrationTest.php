<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $region = \App\Models\Region::create(['name' => 'MPD 1']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'region_id' => $region->id,
        ]);

        $this->assertAuthenticated();
        $this->assertSame('notaris', \App\Models\User::first()->role);
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}

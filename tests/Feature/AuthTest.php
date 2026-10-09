<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/dashboard/invitations/create')->assertRedirect('/login');
        $this->post('/dashboard/invitations', $this->invitationData())->assertRedirect('/login');
    }

    public function test_login_and_register_pages_are_displayed(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk');
        $this->get('/register')->assertOk()->assertSee('Daftar');
    }

    public function test_user_can_register_and_is_logged_in(): void
    {
        $this->post('/register', [
            'name' => 'Sevan', 'email' => 'sevan@example.com',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/dashboard');

        $user = User::sole();
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame('rahasia123', $user->password);
    }

    public function test_register_rejects_duplicate_email_and_mismatched_password(): void
    {
        User::factory()->create(['email' => 'sevan@example.com']);

        $this->post('/register', [
            'name' => 'Sevan', 'email' => 'sevan@example.com',
            'password' => 'rahasia123', 'password_confirmation' => 'berbeda123',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->get('/login')->assertRedirect('/dashboard');

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_google_button_is_displayed_and_redirects_to_google(): void
    {
        config(['services.google.client_id' => 'id-tes', 'services.google.client_secret' => 'rahasia-tes']);

        $this->get('/login')->assertSee('Lanjutkan dengan Google')->assertSee(route('auth.google'));
        $this->get('/register')->assertSee('Lanjutkan dengan Google');

        $this->assertStringStartsWith(
            'https://accounts.google.com/',
            $this->get('/auth/google')->assertRedirect()->headers->get('Location')
        );
    }

    public function test_new_user_can_register_with_google(): void
    {
        $this->fakeGoogleUser();

        $this->get('/auth/google/callback')->assertRedirect('/dashboard');

        $user = User::sole();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('sevan@example.com', $user->email);
        $this->assertSame('google-123', $user->google_id);
        $this->assertNull($user->password);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_google_login_links_existing_account_without_duplicating(): void
    {
        $user = User::factory()->create(['email' => 'sevan@example.com']);

        $this->fakeGoogleUser();
        $this->get('/auth/google/callback')->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-123', User::sole()->google_id);

        // Login berikutnya dikenali lewat google_id, walau email di Google berubah
        $this->post('/logout');
        $this->fakeGoogleUser(['email' => 'baru@example.com']);
        $this->get('/auth/google/callback')->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::count());
    }

    public function test_google_login_rejects_unverified_email_for_existing_account(): void
    {
        User::factory()->create(['email' => 'sevan@example.com']);

        $this->fakeGoogleUser(['email_verified' => false]);

        $this->get('/auth/google/callback')
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertNull(User::sole()->google_id);
    }

    public function test_failed_google_callback_returns_to_login(): void
    {
        Socialite::shouldReceive('driver->user')->andThrow(new \RuntimeException('dibatalkan'));

        $this->get('/auth/google/callback')
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame(0, User::count());
    }

    public function test_google_account_without_password_cannot_login_with_password(): void
    {
        $user = User::factory()->create(['password' => null, 'google_id' => 'google-123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'apa-saja'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // Memalsukan balasan Google supaya tes tidak memanggil server Google
    private function fakeGoogleUser(array $overrides = []): void
    {
        $data = $overrides + [
            'id' => 'google-123', 'name' => 'Sevan', 'email' => 'sevan@example.com', 'email_verified' => true,
        ];

        $googleUser = (new SocialiteUser)->setRaw($data)->map($data);

        Socialite::shouldReceive('driver->user')->andReturn($googleUser);
    }
}

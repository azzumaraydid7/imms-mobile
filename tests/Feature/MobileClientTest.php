<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * The app owns no identity data — every screen is driven by the IMMS API, so the
 * client is stubbed here and the contract is asserted from the API's side.
 */
class MobileClientTest extends TestCase
{
    private const API = 'https://api.imjpj.my';

    /**
     * @return array<string, mixed>
     */
    private function userPayload(): array
    {
        return [
            'id' => 1,
            'uuid' => 'uuid-1',
            'name' => 'Jane Driver',
            'email' => 'jane@imjpj.my',
            'phone' => null,
            'status' => 'active',
            'email_verified' => true,
            'last_login_at' => '2026-11-01T09:00:00+00:00',
            'roles' => ['admin'],
            'permissions' => ['users.create', 'users.view', 'bookings.view'],
        ];
    }

    private function fakeSuccessfulIdentity(): void
    {
        Http::fake([
            self::API.'/login' => Http::response([
                'token' => '1|plain-text-token',
                'token_type' => 'Bearer',
                'user' => $this->userPayload(),
            ]),
            self::API.'/user' => Http::response(['user' => $this->userPayload()]),
            self::API.'/logout' => Http::response(['message' => 'Logged out successfully.']),
        ]);
    }

    public function test_login_screen_is_shown(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Welcome back');
    }

    public function test_dashboard_requires_a_token(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_user_can_sign_in_with_valid_credentials(): void
    {
        $this->fakeSuccessfulIdentity();

        $this->post('/login', ['email' => 'jane@imjpj.my', 'password' => 'password'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('imms.token', '1|plain-text-token');

        // The address is normalised before it reaches the API.
        Http::assertSent(fn ($request) => $request['email'] === 'jane@imjpj.my'
            && $request['password'] === 'password'
            && $request['platform'] === 'mobile');
    }

    public function test_email_is_normalised_before_it_reaches_the_api(): void
    {
        $this->fakeSuccessfulIdentity();

        $this->post('/login', ['email' => '  JANE@IMJPJ.MY  ', 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        Http::assertSent(fn ($request) => $request['email'] === 'jane@imjpj.my');
    }

    public function test_invalid_credentials_are_reported(): void
    {
        Http::fake([
            self::API.'/login' => Http::response([
                'message' => 'These credentials do not match our records.',
                'errors' => ['email' => ['These credentials do not match our records.']],
            ], 422),
        ]);

        $this->from('/')
            ->post('/login', ['email' => 'jane@imjpj.my', 'password' => 'wrong'])
            ->assertRedirect('/')
            ->assertSessionHasErrors('email');

        $this->assertNull(session('imms.token'));
    }

    public function test_unreachable_api_is_reported_without_crashing(): void
    {
        Http::fake([
            self::API.'/login' => fn () => throw new \Illuminate\Http\Client\ConnectionException('timed out'),
        ]);

        $this->from('/')
            ->post('/login', ['email' => 'jane@imjpj.my', 'password' => 'password'])
            ->assertRedirect('/')
            ->assertSessionHasErrors('email');
    }

    public function test_dashboard_renders_roles_and_grouped_permissions(): void
    {
        $this->fakeSuccessfulIdentity();

        $this->withSession(['imms.token' => '1|plain-text-token'])
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Jane Driver')
            ->assertSee('jane@imjpj.my')
            ->assertSee('Admin')
            ->assertSee('admin')
            // Permission groups are derived from the slug namespaces.
            ->assertSee('users')
            ->assertSee('bookings')
            ->assertSee('users.view')
            ->assertSee('bookings.view');
    }

    public function test_dashboard_sends_the_sanctum_token(): void
    {
        $this->fakeSuccessfulIdentity();

        $this->withSession(['imms.token' => '1|plain-text-token'])->get('/dashboard')->assertOk();

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer 1|plain-text-token'));
    }

    public function test_expired_token_returns_to_login(): void
    {
        Http::fake([
            self::API.'/user' => Http::response(['message' => 'Unauthenticated.'], 401),
        ]);

        $this->withSession(['imms.token' => 'stale-token'])
            ->get('/dashboard')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_user_can_sign_out(): void
    {
        $this->fakeSuccessfulIdentity();

        $this->withSession(['imms.token' => '1|plain-text-token'])
            ->post('/logout')
            ->assertRedirect(route('login'))
            ->assertSessionMissing('imms.token');
    }

    public function test_sign_out_clears_the_session_even_when_the_api_fails(): void
    {
        Http::fake([
            self::API.'/logout' => Http::response(['message' => 'Unauthenticated.'], 401),
        ]);

        $this->withSession(['imms.token' => '1|plain-text-token'])
            ->post('/logout')
            ->assertRedirect(route('login'))
            ->assertSessionMissing('imms.token');
    }
}

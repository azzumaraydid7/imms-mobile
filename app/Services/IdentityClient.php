<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Talks to the IMMS API on api.imjpj.my.
 *
 * Every call is server-to-server from the on-device PHP runtime, so no CORS
 * handling or token storage in the webview is involved — the Sanctum token is
 * held in the local session and attached here.
 */
class IdentityClient
{
    public function __construct(private readonly ?string $token = null) {}

    public static function make(?string $token = null): self
    {
        return new self($token);
    }

    /**
     * Exchange credentials for a Sanctum token.
     *
     * @param  array<string, string>  $device
     * @return array{token: string, token_type: string, user: array<string, mixed>}
     */
    public function login(string $email, string $password, array $device = []): array
    {
        return $this->request()
            ->post('/login', array_merge([
                'email' => $email,
                'password' => $password,
            ], $device))
            ->throw()
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function user(): array
    {
        return $this->request()->get('/user')->throw()->json('user');
    }

    public function logout(): void
    {
        $this->request()->post('/logout')->throw();
    }

    private function request(): PendingRequest
    {
        $request = Http::baseUrl(config('imms.api_url'))
            ->acceptJson()
            ->timeout(config('imms.timeout'))
            ->asJson();

        return $this->token === null ? $request : $request->withToken($this->token);
    }
}

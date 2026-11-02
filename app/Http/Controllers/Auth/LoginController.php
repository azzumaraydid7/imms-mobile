<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\IdentityClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the sign-in screen — or skip straight to the deck when a token is
     * already held in the session.
     */
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('imms.token')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Normalised here as well as on the API so the two never disagree on
        // how an address is spelled.
        $email = trim(mb_strtolower($credentials['email']));

        try {
            $response = IdentityClient::make()->login($email, $credentials['password'], [
                'device_name' => config('app.name'),
                'platform' => 'mobile',
                'app_version' => (string) config('nativephp.version', ''),
            ]);
        } catch (RequestException $exception) {
            throw ValidationException::withMessages([
                'email' => $exception->response->json('errors.email.0')
                    ?? $exception->response->json('message')
                    ?? 'These credentials do not match our records.',
            ]);
        } catch (ConnectionException) {
            throw ValidationException::withMessages([
                'email' => 'Cannot reach the IMJPJ API at '.config('imms.api_url').'.',
            ]);
        }

        $request->session()->put('imms.token', $response['token']);
        $request->session()->put('imms.user', $response['user']);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Revoke the token server-side, then drop the local session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $token = $request->session()->get('imms.token');

        if (is_string($token)) {
            try {
                IdentityClient::make($token)->logout();
            } catch (RequestException|ConnectionException $exception) {
                // The token may already be revoked, or the API unreachable. The
                // local session is cleared either way so the user is never
                // stranded on a screen they cannot leave.
                Log::info('API logout failed; clearing local session anyway.', [
                    'reason' => $exception->getMessage(),
                ]);
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

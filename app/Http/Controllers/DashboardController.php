<?php

namespace App\Http\Controllers;

use App\Services\IdentityClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Pull the authenticated user from the API and render the deck.
     *
     * The API returns role and permission *slugs* rather than hydrated records,
     * so the permission groups are derived from the slug namespaces
     * (`users.view` → `users`).
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $token = $request->session()->get('imms.token');

        try {
            $user = IdentityClient::make($token)->user();
        } catch (RequestException $exception) {
            if ($exception->response->status() === 401) {
                $request->session()->invalidate();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your session expired. Please sign in again.',
                ]);
            }

            throw $exception;
        }

        $request->session()->put('imms.user', $user);

        $roles = array_values($user['roles'] ?? []);

        $permissions = collect($user['permissions'] ?? [])
            ->filter(fn ($permission) => is_string($permission))
            ->sort()
            ->values();

        $permissionGroups = $permissions->groupBy(
            fn (string $slug) => Str::contains($slug, '.') ? Str::before($slug, '.') : 'general'
        );

        $modules = config('navigation.modules', []);
        $liveModules = count(array_filter($modules, fn (array $module) => $module['status'] === 'live'));

        $hour = (int) now()->format('G');

        return view('dashboard.mobile', [
            'user' => $user,
            'roles' => $roles,
            'greeting' => $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening'),
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups,
            'permissionGroupCount' => $permissionGroups->count(),
            'modules' => $modules,
            'liveModules' => $liveModules,
            'stats' => [
                [
                    'label' => 'Roles',
                    'value' => count($roles),
                    'caption' => $roles === [] ? 'unassigned' : implode(' · ', $roles),
                ],
                [
                    'label' => 'Permissions',
                    'value' => $permissions->count(),
                    'caption' => $permissionGroups->count().' '.Str::plural('group', $permissionGroups->count()),
                ],
                [
                    'label' => 'Modules',
                    'value' => count($modules),
                    'caption' => $liveModules.' live · '.(count($modules) - $liveModules).' scaffolded',
                ],
                [
                    'label' => 'Runtime',
                    'value' => 'Mobile',
                    'caption' => config('nativephp-internal.running') ? 'native shell' : 'browser session',
                ],
            ],
        ]);
    }
}

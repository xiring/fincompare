<?php
namespace Src\Auth\Presentation\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Src\Auth\Domain\Entities\User;

/**
 * SocialLoginController controller.
 *
 * @package Src\Auth\Presentation\Controllers
 */
class SocialLoginController extends Controller
{
    protected array $allowedProviders = ['google','github'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->allowedProviders, true), 404);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->allowedProviders, true), 404);
        $socialUser = Socialite::driver($provider)->user();

        $user = User::query()->firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User'),
                'password' => Str::password(32),
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($user, 'hasRole') && ! $user->hasRole('consumer')) {
            $user->syncRoles(['consumer']);
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }
}



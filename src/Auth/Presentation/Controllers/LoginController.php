<?php
namespace Src\Auth\Presentation\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Auth\Application\Actions\AuthenticateUserAction;
use Src\Auth\Application\Actions\LogoutUserAction;
use Src\Auth\Presentation\Requests\LoginRequest;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        if ($request->user()) return redirect()->intended('/dashboard');
        return view('auth.login');
    }

    public function store(LoginRequest $request, AuthenticateUserAction $auth): RedirectResponse
    {
        $valid = $request->validated();
        $remember = (bool)($valid['remember'] ?? false);
        if (! $auth->execute($valid['email'], $valid['password'], $remember)) {
            return back()->withErrors(['email' => __('auth.failed')])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function destroy(Request $request, LogoutUserAction $logout): RedirectResponse
    {
        $logout->execute();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}



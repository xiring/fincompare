<?php
namespace Src\Auth\Presentation\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Auth\Application\Actions\UpdateUserPasswordAction;
use Src\Auth\Application\Actions\UpdateUserProfileAction;
use Src\Auth\Presentation\Requests\UpdatePasswordRequest;
use Src\Auth\Presentation\Requests\UpdateProfileRequest;

/**
 * ProfileController controller.
 *
 * @package Src\Auth\Presentation\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [ 'user' => $request->user() ]);
    }

    public function update(UpdateProfileRequest $request, UpdateUserProfileAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated());
        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(UpdatePasswordRequest $request, UpdateUserPasswordAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated()['current_password'], $request->validated()['password']);
        return back()->with('status', 'password-updated');
    }
}



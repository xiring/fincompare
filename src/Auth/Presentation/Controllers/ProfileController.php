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
 */
class ProfileController extends Controller
{
    /**
     * Show the current user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(UpdateProfileRequest $request, UpdateUserProfileAction $action)
    {
        $user = $action->execute($request->user(), $request->validated());

        return response()->json($user);
    }

    public function updatePassword(UpdatePasswordRequest $request, UpdateUserPasswordAction $action)
    {
        $action->execute($request->user(), $request->validated()['current_password'], $request->validated()['password']);

        return response()->json(['message' => 'Password updated successfully']);
    }
}

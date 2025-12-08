<?php

namespace Src\Settings\Presentation\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Src\Settings\Application\Actions\UpdateSiteSettingAction;
use Src\Settings\Application\DTOs\SiteSettingDTO;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;
use Src\Settings\Presentation\Requests\UpdateSiteSettingRequest;

/**
 * SiteSettingController controller.
 */
class SiteSettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(SiteSettingRepositoryInterface $repository): JsonResponse
    {
        $settings = $repository->get();

        return response()->json([
            'data' => $settings->toArray()
        ]);
    }

    public function update(UpdateSiteSettingRequest $request, UpdateSiteSettingAction $action): JsonResponse|RedirectResponse
    {
        $data = collect($request->validated())->except(['logo', 'favicon'])->toArray();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('uploads/settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('uploads/settings', 'public');
        }

        $dto = SiteSettingDTO::fromArray($data);
        $updatedSettings = $action->execute($dto);

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'data' => $updatedSettings->toArray(),
                'message' => 'Settings updated successfully'
            ]);
        }

        return back()->with('status', 'settings-updated');
    }
}

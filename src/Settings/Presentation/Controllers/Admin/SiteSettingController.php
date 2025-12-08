<?php

namespace Src\Settings\Presentation\Controllers\Admin;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(SiteSettingRepositoryInterface $repository)
    {
        $settings = $repository->get();
    }

    public function update(UpdateSiteSettingRequest $request, UpdateSiteSettingAction $action): RedirectResponse
    {
        $data = collect($request->validated())->except(['logo', 'favicon'])->toArray();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('uploads/settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('uploads/settings', 'public');
        }

        $dto = SiteSettingDTO::fromArray($data);
        $action->execute($dto);

        return back()->with('status', 'settings-updated');
    }
}

<?php

namespace Src\Shared\Presentation\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;
use Src\Shared\Application\Actions\CreateContactMessageAction;
use Src\Shared\Application\DTOs\ContactMessageDTO;
use Src\Shared\Application\Mail\ContactMessageReceived;
use Src\Shared\Presentation\Requests\StoreContactMessageRequest;

/**
 * ContactController controller.
 */
class ContactController extends Controller
{
    public function store(
        StoreContactMessageRequest $request,
        CreateContactMessageAction $action,
        SiteSettingRepositoryInterface $siteSettings
    ): RedirectResponse|JsonResponse {
        $dto = ContactMessageDTO::fromArray($request->validated());
        $action->execute($dto);

        $adminEmail = $siteSettings->get()->email_address ?: config('mail.from.address');
        if (! empty($adminEmail)) {
            Mail::to($adminEmail)->queue((new ContactMessageReceived($dto)));
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Thank you! Your message has been sent.',
                'status' => 'success'
            ]);
        }

        return back()->with('status', 'message-sent');
    }
}

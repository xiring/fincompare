<?php
namespace Src\Shared\Presentation\Controllers\Public;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Src\Shared\Application\Actions\CreateContactMessageAction;
use Src\Shared\Application\DTOs\ContactMessageDTO;
use Src\Shared\Presentation\Requests\StoreContactMessageRequest;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;
use Src\Shared\Application\Mail\ContactMessageReceived;

/**
 * ContactController controller.
 *
 * @package Src\Shared\Presentation\Controllers\Public
 */
class ContactController extends Controller
{
    public function store(
        StoreContactMessageRequest $request,
        CreateContactMessageAction $action,
        SiteSettingRepositoryInterface $siteSettings
    ): RedirectResponse {
        $dto = ContactMessageDTO::fromArray($request->validated());
        $action->execute($dto);

        $adminEmail = $siteSettings->get()->email_address ?: config('mail.from.address');
        if (!empty($adminEmail)) {
            Mail::to($adminEmail)->queue((new ContactMessageReceived($dto)));
        }

        return back()->with('status', 'message-sent');
    }
}



<?php

namespace Src\Settings\Presentation\View\Composers;

use Illuminate\View\View;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;

/**
 * SiteSettingComposer view composer.
 */
class SiteSettingComposer
{
    public function __construct(private readonly SiteSettingRepositoryInterface $repository) {}

    public function compose(View $view): void
    {
        static $cachedSettings = null;
        if ($cachedSettings === null) {
            $cachedSettings = $this->repository->get();
        }
        $view->with('siteSettings', $cachedSettings);
    }
}

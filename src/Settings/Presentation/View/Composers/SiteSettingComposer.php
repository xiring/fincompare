<?php
namespace Src\Settings\Presentation\View\Composers;

use Illuminate\View\View;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;

class SiteSettingComposer
{
    public function __construct(private readonly SiteSettingRepositoryInterface $repository)
    {
    }

    public function compose(View $view): void
    {
        $settings = $this->repository->get();
        $view->with('siteSettings', $settings);
    }
}



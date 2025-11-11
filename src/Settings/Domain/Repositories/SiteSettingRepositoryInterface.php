<?php

namespace Src\Settings\Domain\Repositories;

use Src\Settings\Domain\Entities\SiteSetting;

/**
 * SiteSettingRepositoryInterface interface.
 */
interface SiteSettingRepositoryInterface
{
    public function get(): SiteSetting;

    public function update(array $data): SiteSetting;
}

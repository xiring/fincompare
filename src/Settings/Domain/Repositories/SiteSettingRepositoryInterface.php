<?php
namespace Src\Settings\Domain\Repositories;

use Src\Settings\Domain\Entities\SiteSetting;

interface SiteSettingRepositoryInterface
{
    public function get(): SiteSetting;

    public function update(array $data): SiteSetting;
}



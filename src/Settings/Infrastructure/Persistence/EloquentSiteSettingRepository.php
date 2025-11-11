<?php
namespace Src\Settings\Infrastructure\Persistence;

use Src\Settings\Domain\Entities\SiteSetting;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;

class EloquentSiteSettingRepository implements SiteSettingRepositoryInterface
{
    public function get(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate(['id' => 1], [
            'site_name' => 'FinCompare',
        ]);
    }

    public function update(array $data): SiteSetting
    {
        $settings = $this->get();
        $settings->fill($data);
        $settings->save();
        return $settings;
    }
}



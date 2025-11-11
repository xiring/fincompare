<?php

namespace Src\Settings\Infrastructure\Persistence;

use Illuminate\Support\Facades\Cache;
use Src\Settings\Domain\Entities\SiteSetting;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;

/**
 * EloquentSiteSettingRepository repository.
 */
class EloquentSiteSettingRepository implements SiteSettingRepositoryInterface
{
    private const CACHE_KEY = 'site_settings.single';

    public function get(): SiteSetting
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return SiteSetting::query()->firstOrCreate(['id' => 1], [
                'site_name' => 'FinCompare',
            ]);
        });
    }

    public function update(array $data): SiteSetting
    {
        $settings = $this->get();
        $settings->fill($data);
        $settings->save();
        // Invalidate cache so subsequent reads get fresh data
        Cache::forget(self::CACHE_KEY);
        // Optionally repopulate cache immediately
        Cache::forever(self::CACHE_KEY, $settings->fresh());

        return $settings;
    }
}

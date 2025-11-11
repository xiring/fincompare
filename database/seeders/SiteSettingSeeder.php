<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Settings\Domain\Entities\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->firstOrCreate(['id' => 1], [
            'site_name' => 'FinCompare',
            'site_slogon' => null,
            'favicon' => null,
            'logo' => null,
            'seo_titl' => null,
            'seo_keyword' => null,
            'seo_description' => null,
            'email_address' => null,
            'contact_number' => null,
            'map_url' => null,
            'facebook_url' => null,
            'instgram_url' => null,
            'twitter_url' => null,
        ]);
    }
}



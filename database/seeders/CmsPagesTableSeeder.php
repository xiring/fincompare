<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Content\Domain\Entities\CmsPage;

class CmsPagesTableSeeder extends Seeder
{
    public function run(): void
    {
        CmsPage::factory()->count(5)->create();
    }
}

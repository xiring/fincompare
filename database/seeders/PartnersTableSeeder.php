<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Partners\Domain\Entities\Partner;

class PartnersTableSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Alpha Finance', 'website_url' => 'https://alpha.example', 'contact_email' => 'alpha@example.com', 'contact_phone' => '+1-111-111-1111'],
            ['name' => 'Bravo Bank', 'website_url' => 'https://bravo.example', 'contact_email' => 'bravo@example.com', 'contact_phone' => '+1-222-222-2222'],
            ['name' => 'Charlie Credit', 'website_url' => 'https://charlie.example', 'contact_email' => 'charlie@example.com', 'contact_phone' => '+1-333-333-3333'],
        ];
        foreach ($partners as $p) {
            Partner::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                array_merge($p, [
                    'slug' => Str::slug($p['name']),
                    'logo_path' => null,
                    'status' => 'active',
                ])
            );
        }
    }
}

<?php
namespace Src\Settings\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * SiteSetting class.
 *
 * @package Src\Settings\Domain\Entities
 */
class SiteSetting extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'site_slogon',
        'favicon',
        'logo',
        'seo_titl',
        'seo_keyword',
        'seo_description',
        'email_address',
        'contact_number',
        'address',
        'map_url',
        'facebook_url',
        'instgram_url',
        'twitter_url',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('site_settings');
    }
}



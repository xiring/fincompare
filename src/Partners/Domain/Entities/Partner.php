<?php

namespace Src\Partners\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Src\Catalog\Domain\Entities\Product;

/**
 * Partner class.
 */
class Partner extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'slug', 'logo_path', 'website_url', 'contact_email', 'contact_phone', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('partners');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

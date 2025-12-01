<?php

namespace Src\Forms\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Catalog\Domain\Entities\ProductCategory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Form class.
 */
class Form extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'status', 'type'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('forms');
    }

    public function productCategoriesAsPreForm(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'pre_form_id');
    }

    public function productCategoriesAsPostForm(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'post_form_id');
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(FormInput::class)->orderBy('sort_order');
    }
}


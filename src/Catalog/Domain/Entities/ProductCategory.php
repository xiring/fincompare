<?php

namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Forms\Domain\Entities\Form;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * ProductCategory class.
 */
class ProductCategory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'pre_form_id', 'post_form_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('product_categories');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function preForm(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'pre_form_id');
    }

    public function postForm(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'post_form_id');
    }

    public function getUrlAttribute(): string
    {
        return route('products.public.index', ['category_id' => $this->id]);
    }
}

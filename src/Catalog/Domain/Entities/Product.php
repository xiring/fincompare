<?php

namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Src\Partners\Domain\Entities\Partner;

/**
 * Product class.
 */
class Product extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['partner_id', 'product_category_id', 'name', 'slug', 'description', 'image', 'is_featured', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('products');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function getAttributeHighlightsAttribute(): array
    {
        $highlights = [];
        $attributeValues = $this->attributeValues()->with('attribute')->get();

        foreach ($attributeValues as $av) {
            $slug = strtolower($av->attribute->slug ?? '');
            $value = $av->getScalarValue();

            if ($slug === 'interest_rate' || $slug === 'interest-rate') {
                $highlights['interest_rate'] = is_numeric($value) ? number_format((float) $value, 2).'%' : ($value ?? '—');
            } elseif ($slug === 'max_amount' || $slug === 'max-amount') {
                $highlights['max_amount'] = is_numeric($value) ? '$'.number_format((float) $value) : ($value ?? '—');
            }
        }

        return $highlights;
    }
}

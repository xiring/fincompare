<?php
namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['product_category_id','name','slug','data_type','unit','is_filterable','is_required','sort_order'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('attributes');
    }

    public function productCategory(): BelongsTo { return $this->belongsTo(ProductCategory::class); }
    public function values(): HasMany { return $this->hasMany(ProductAttributeValue::class); }
}

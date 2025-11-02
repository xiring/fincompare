<?php
namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Partners\Domain\Entities\Partner;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['partner_id','product_category_id','name','slug','description','is_featured','status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('products');
    }

    public function partner(): BelongsTo { return $this->belongsTo(Partner::class); }
    public function productCategory(): BelongsTo { return $this->belongsTo(ProductCategory::class); }
    public function attributeValues(): HasMany { return $this->hasMany(ProductAttributeValue::class); }
}

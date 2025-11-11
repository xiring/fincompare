<?php
namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductAttributeValue class.
 *
 * @package Src\Catalog\Domain\Entities
 */
class ProductAttributeValue extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['product_id','attribute_id','value_text','value_number','value_boolean','value_json'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('product_attribute_values');
    }

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function attribute(): BelongsTo { return $this->belongsTo(Attribute::class); }

    public function getScalarValue(): mixed
    {
        if (!is_null($this->value_number)) return $this->value_number;
        if (!is_null($this->value_boolean)) return (bool)$this->value_boolean;
        if (!is_null($this->value_text)) return $this->value_text;
        if (!is_null($this->value_json)) return $this->value_json;
        return null;
    }
}

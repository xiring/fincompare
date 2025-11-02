<?php
namespace Src\Catalog\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name','slug','description','is_active'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('product_categories');
    }

    public function attributes(): HasMany { return $this->hasMany(Attribute::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
}

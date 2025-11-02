<?php
namespace Src\Leads\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Entities\Product;

class Lead extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['product_category_id','product_id','full_name','email','mobile_number','message','status','source','meta'];
    protected $casts = ['meta'=>'array'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('leads');
    }

    public function productCategory(): BelongsTo { return $this->belongsTo(ProductCategory::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}

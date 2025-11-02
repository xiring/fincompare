<?php
namespace Src\Content\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Src\Shared\Domain\ValueObjects\SeoMeta;

class CmsPage extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title','slug','seo_title','seo_description','seo_keywords','content','status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('cms_pages');
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function seo(): Attribute
    {
        return Attribute::get(function () {
            return new SeoMeta(
                title: $this->seo_title,
                description: $this->seo_description,
                keywords: $this->seo_keywords,
            );
        })->set(function ($value) {
            if ($value instanceof SeoMeta) {
                return $value->toArray();
            }
            if (is_array($value)) {
                return SeoMeta::fromArray($value)->toArray();
            }
            return [];
        });
    }
}



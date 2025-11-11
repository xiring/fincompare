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
use Src\Shared\Infrastructure\Casts\TagsCast;

/**
 * BlogPost class.
 *
 * @package Src\Content\Domain\Entities
 */
class BlogPost extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title','slug','category','content','featured_image','status',
        'seo_title','seo_description','seo_keywords','tags'
    ];

    protected $casts = [
        'tags' => TagsCast::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('blog_posts');
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



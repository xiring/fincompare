<?php
namespace Src\Content\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title','slug','category','content','featured_image','status',
        'seo_title','seo_description','seo_keywords','tags'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('blog_posts');
    }
}



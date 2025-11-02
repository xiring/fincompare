<?php
namespace Src\Content\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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
}



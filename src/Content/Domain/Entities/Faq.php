<?php

namespace Src\Content\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Faq class.
 */
class Faq extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = ['question', 'answer'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('faqs');
    }
}

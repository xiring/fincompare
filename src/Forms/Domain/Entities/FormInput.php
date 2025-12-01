<?php

namespace Src\Forms\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * FormInput class.
 */
class FormInput extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'form_id', 'label', 'name', 'type', 'options', 'placeholder',
        'help_text', 'is_required', 'validation_rules', 'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('form_inputs');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}


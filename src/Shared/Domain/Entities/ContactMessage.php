<?php
namespace Src\Shared\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ContactMessage class.
 *
 * @package Src\Shared\Domain\Entities
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'message',
    ];
}



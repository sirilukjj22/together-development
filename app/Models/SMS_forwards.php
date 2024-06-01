<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS_forwards extends Model
{
    use HasFactory;

    protected $table = 'sms_forward';
    protected $fillable = [
        'messages',
        'is_status',
        'created_at',
        'updated_at',
    ];
}

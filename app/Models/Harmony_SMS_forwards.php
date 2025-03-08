<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_SMS_forwards extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'sms_forward';
    protected $fillable = [
        'messages',
        'sender',
        'chanel',
        'is_status',
        'created_at',
        'updated_at',
    ];
}

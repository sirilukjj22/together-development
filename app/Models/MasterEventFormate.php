<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterEventFormate extends Model
{
    use HasFactory;
    protected $table = 'master_event_formates';
    protected $fillable = [
        'code',
        'name_th',
        'name_en',
        'detail_th',
        'detail_en',
    ];
}

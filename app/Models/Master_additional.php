<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_additional extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_additional';
    protected $fillable = [
        'code',
        'description',
        'type',
    ];
}

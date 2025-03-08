<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_promotion extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_promotion';
    protected $fillable = [
        'name',
        'status',
        'type',
    ];
}

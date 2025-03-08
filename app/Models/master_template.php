<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_template extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_template';
    protected $fillable = [
        'CodeTemplate',
        'name',
        'status',
    ];
}

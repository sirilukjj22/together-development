<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log_company extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'log_company';
    protected $fillable = [
        'Category',
        'content',
        'Company_ID',
        'Created_by',
        'type',

    ];
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Created_by');
    }
}

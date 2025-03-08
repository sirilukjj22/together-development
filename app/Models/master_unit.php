<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_unit extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_units';
    protected $fillable = [
        'Product_ID',
        'name_th',
        'name_en',
        'create_by',
    ];
    public function  user_create_id()
    {
        return $this->hasOne(User::class, 'id', 'create_by');
    }
}

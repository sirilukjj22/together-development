<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_quantity extends Model
{
    use HasFactory;
    protected $table = 'master_quantities';
    protected $fillable = [
        'Product_ID',
        'name_th',
        'name_en',
        'create_by'
    ];
    public function  user_create_id()
    {
        return $this->hasOne(User::class, 'id', 'create_by');
    }
}

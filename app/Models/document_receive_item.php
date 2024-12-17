<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_receive_item extends Model
{
    use HasFactory;
    protected $table = 'document_receive_item';
    protected $fillable = [
        'receive_id',
        'detail',
        'amount',
    ];
}

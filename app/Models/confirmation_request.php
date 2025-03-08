<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class confirmation_request extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'confirmation_requests';
    protected $fillable = [
        'requester_id',
        'confirmer_id',
        'request_time',
        'expiration_time	',
        'status',
    ];

    public function  requestername()
    {
        return $this->hasOne(User::class, 'id','requester_id');
    }
}

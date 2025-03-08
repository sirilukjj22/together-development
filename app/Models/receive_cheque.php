<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receive_cheque extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'receive_cheque';
    protected $fillable = [
        'branch',
        'Cheque_ID',
        'refer_proposal',
        'bank_cheque',
        'receive_payment',
        'amount',
        'cheque_number',
        'deduct_by',
        'deduct_date',
        'issue_date',
        'Operated_by',
    ];
    public function  bank()
    {
        return $this->hasOne(Masters::class, 'id', 'bank_cheque');
    }
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Operated_by');
    }
    public function  userDeduct()
    {
        return $this->hasOne(User::class, 'id','deduct_by');
    }
}

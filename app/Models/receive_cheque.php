<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receive_cheque extends Model
{
    use HasFactory;
    protected $table = 'receive_cheque';
    protected $fillable = [
        'refer_invoice',
        'refer_proposal',
        'bank_cheque',
        'bank_received',
        'cheque_number',
        'amount',
        'receive_date',
        'issue_date',
        'status',
    ];
    public function  bank()
    {
        return $this->hasOne(Masters::class, 'id', 'bank_cheque');
    }
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Operated_by');
    }
}

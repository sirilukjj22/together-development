<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_document_email extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_document_email';
    protected $fillable = [
        'Title',
        'detail',
        'files',
        'comment',
        'email',
        'status',
    ];
}

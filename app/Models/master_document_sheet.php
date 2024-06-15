<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_document_sheet extends Model
{
    use HasFactory;
    protected $table = 'master_document_sheet';
    protected $fillable = [
        'topic',
        'status',
        'name_th',
        'name_en',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'imported_at',
        'successful',
        'error_message',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
    ];

}

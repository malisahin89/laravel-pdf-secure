<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfFile extends Model
{
    use HasFactory;
    protected $table = 'pdf_file';

    protected $fillable = ['unikey', 'user_id', 'file_name', 'file_path'];
}

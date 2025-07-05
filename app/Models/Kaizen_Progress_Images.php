<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaizen_Progress_Images extends Model
{
    use HasFactory;

    protected $table = 'kaizen_progress_images'; // Nama tabel di database

    /**
     * Kolom yang dapat diisi menggunakan mass assignment
     */
    protected $fillable = [
        'kaizen_progress_image_id',
        'kaizen_id',
        'filename',
        'user_id',
        'upload_date',
    ];

   
}

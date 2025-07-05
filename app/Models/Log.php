<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs_3'; // Sesuai dengan nama tabel

    protected $primaryKey = 'log_id'; // Primary key tabel

    public $timestamps = false; // Karena sudah ada `log_date`

    protected $fillable = [
        'site_id',
        'user_id',
        'action',
        'data',
        'log_date',
        'browser',
        'ip_address',
        'url'
    ];
    
    
    protected $casts = [
        'data' => 'array', // Otomatis mengonversi JSON ke array saat diakses
    ];
}

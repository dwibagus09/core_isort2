<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas'; // Nama tabel di database

    /**
     * Kolom yang dapat diisi menggunakan mass assignment
     */
    protected $fillable = [
        'site_id',
        'department_id',
        'area_name',
        'sort_order',
    ];

    /**
     * Relasi ke tabel sites
     */
    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }

    /**
     * Relasi ke tabel departments
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}

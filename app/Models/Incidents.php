<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidents extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'incidents';

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['location_name'];

    public $timestamps = false;
    
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
    
    /**
     * Relasi ke tabel departments
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    
    // Incident.php
public function modus()
{
    return $this->belongsTo(Modus::class);
}
    
    
    
    
}

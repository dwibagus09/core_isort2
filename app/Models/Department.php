<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'departments';

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    protected $fillable = ['site_name'];

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }

    public function kaizens()
    {
        return $this->hasMany(Kaizen::class,'department_id');
    }
}

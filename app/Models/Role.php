<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'roles';
    protected $primaryKey = 'role_id'; // Kolom primary key di tabel
    protected $fillable = ['role'];
    public $timestamps = false; // Nonaktifkan timestamps
}

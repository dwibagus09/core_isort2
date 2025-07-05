<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access_Control extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'access_control';
    
    
    protected $fillable = ['role_id', 'module_id', 'read_access', 'write_access'];
    public $timestamps = false; // Nonaktifkan timestamps
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access_Control_Module extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'access_control_modules';
    protected $primaryKey = 'module_id'; 
    protected $fillable = ['menu_name', 'submenu_name', 'url'];
    public $timestamps = false; // Nonaktifkan timestamps
}

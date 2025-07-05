<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist_template extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'checklist_templates';
    protected $primaryKey = 'template_id'; 

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['site_name'];
    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'category_id',
        'template_name',
    ];

    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }
}

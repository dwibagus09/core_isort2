<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory_Checklist extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'checklist_subcategories';
    protected $primaryKey = 'subcategory_id'; 

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['site_name'];
    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'category_id',
        'subcategory_name',
        'sort_order',
    ];

    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }
}

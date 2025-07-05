<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist_templateitem extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'checklist_template_items';
    protected $primaryKey = 'item_id'; 

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['site_name'];
    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'site_id',
        'template_id',
        'category_id',
        'subcategory_id',
        'item_name',
        'sort_order',
    ];

    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }
}

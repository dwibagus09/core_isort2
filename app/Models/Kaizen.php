<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaizen extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'kaizen';
    protected $primaryKey = 'kaizen_id';

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['site_name'];
    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
    'kaizen_id', // Meskipun auto-increment, bisa dimasukkan jika diperlukan
    'picture',
    'location',
    'description',
    'issue_date',
    'user_id',
    'department_id',
    'solved_picture',
    'solved_date',
    'issue_type_id',
    'status',
    'keterangan',
    'mod_report_id',
    'site_id',
    'kejadian_id',
    'modus_id',
    'floor_id',
    'manpower_id',
    'area_id',
    'issue_type2_id'
];

        public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function locations()
    {
        return $this->belongsTo(Locations::class, 'location');
    }

    public function location()
    {
        return $this->belongsTo(Locations::class, 'location');
    }

    public function kaizenType()
    {
        return $this->belongsTo(Kaizen_Type::class, 'kaizen_type_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // Di Model Kaizen.php
    public function progressImages()
    {
        return $this->hasMany(Kaizen_Progress_Images::class, 'kaizen_id');
    }

    public function commentKaizens()
    {
        return $this->hasMany(Comments::class, 'kaizen_id');
    }

    // Relasi ke Site
    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }

    public function modus() {
      return $this->belongsTo(Modus::class, 'modus_id');
    }

    public function incident() {
        return $this->belongsTo(Incidents::class,'kejadian_id');
    }

}

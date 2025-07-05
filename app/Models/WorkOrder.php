<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    // Jika tabel tidak bernama "sites", tentukan nama tabel di sini
    protected $table = 'work_order';
    protected $primaryKey = 'wo_id'; 

    // Jika Anda ingin menentukan atribut yang bisa diisi, gunakan $fillable
    //protected $fillable = ['site_name'];
    // Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'category_id',
        'kaizen_id',
        'start_scheduled_date',
        'end_scheduled_date',
        'expected_work_time',
        'expected_work_time2',
        'worker',
        'assigned_remark',
        'assigned_date',
        'executed_date',
        'finish_date',
        'approved',
        'approved_date'
    ];
    
    protected $casts = [
        'start_scheduled_date' => 'datetime',
        'end_scheduled_date' => 'datetime',
        'assigned_date' => 'datetime',
        'executed_date' => 'datetime',
        'finish_date' => 'datetime',
        'approved_date' => 'datetime',
        'expected_work_time2' => 'integer' // untuk enum 0=min, 1=hour, 2=day
    ];

    // Jika Anda ingin menambahkan aksesor untuk expected_work_time2
    public function getTimeUnitAttribute()
    {
        switch ($this->expected_work_time2) {
            case 0: return 'minutes';
            case 1: return 'hours';
            case 2: return 'days';
            default: return 'unknown';
        }
    }

    // Jika Anda ingin menambahkan aksesor untuk status approved
    public function getStatusAttribute()
    {
        return $this->approved == 1 ? 'Approved' : 'Pending';
    }

    // Relasi ke Site
    public function site()
    {
        return $this->belongsTo(Sites::class, 'site_id');
    }
    
        public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
       public function kaizen()
    {
        return $this->belongsTo(Kaizen::class, 'kaizen_id');
    }
    // Di Model Kaizen.php
    public function progressImages()
    {
        return $this->hasMany(Kaizen_Progress_Images::class, 'kaizen_id');
    }
    
    public function user()
{
    return $this->belongsTo(User::class, 'worker', 'user_id');
}
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaizen_Type extends Model
{
    use HasFactory;

    protected $table = 'kaizen_type'; // Nama tabel di database
    protected $primaryKey = 'kaizen_type_id'; 
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function site()
    {
        return $this->belongsTo(Sites::class, 'department_id');
    }
}

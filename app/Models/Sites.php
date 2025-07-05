<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{
    use HasFactory;
    protected $primaryKey = 'site_id';
    protected $table = 'sites';
    protected $fillable = ['site_name','site_fullname','site_address','initial'];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FPG extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'fpg';
    protected $primaryKey = 'id_fpg';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panduan extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'panduan';
    protected $primaryKey = 'id_panduan';
}

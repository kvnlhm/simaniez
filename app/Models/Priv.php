<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priv extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'priv';
    protected $primaryKey = 'id_priv';

}

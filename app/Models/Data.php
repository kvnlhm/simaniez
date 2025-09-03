<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'data';
    // protected $table = 'data_backup';
    protected $primaryKey = 'id_data';
    
    protected $fillable = ['tanggal', 'waktu', 'item', 'inisial'];
}

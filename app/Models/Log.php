<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'log';
    protected $primaryKey = 'id_log';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

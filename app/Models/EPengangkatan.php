<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPengangkatan extends Model
{
    use HasFactory;

    protected $table = 'connect_e_pengangkatan';
    protected $guarded = ['id_pengangkatan'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPengangkatan extends Model
{
    use HasFactory;
    protected $table = 'connect_n_pengangkatan';
    protected $guarded = ['id_n_pengangkatan'];
}

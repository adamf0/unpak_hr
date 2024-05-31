<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengangkatan extends Model
{
   use HasFactory;
   protected $table = 'n_pengangkatan';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

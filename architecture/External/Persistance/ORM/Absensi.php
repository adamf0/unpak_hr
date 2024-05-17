<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
   use HasFactory;
   protected $table = 'absen';
   protected $fillable = ['*'];
}

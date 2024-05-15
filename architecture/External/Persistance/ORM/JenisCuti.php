<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
   use HasFactory;
   protected $table = 'jenis_cuti';
   protected $fillable = ['*'];
}

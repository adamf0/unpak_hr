<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
   use HasFactory;
   protected $table = 'cuti';
   protected $fillable = ['*'];

   function JenisCuti(){
      return $this->hasOne(JenisCuti::class, 'id', 'id_jenis_cuti'); 
   }
}

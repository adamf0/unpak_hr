<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
   use HasFactory;
   protected $table = 'izin';
   protected $fillable = ['*'];

   public function JenisIzin(){
      return $this->hasOne(JenisIzin::class, 'id', 'id_jenis_izin');
   }
}

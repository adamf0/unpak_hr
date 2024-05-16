<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPPD extends Model
{
   use HasFactory;
   protected $table = 'sppd';
   protected $fillable = ['*'];

   public function JenisSPPD(){
      return $this->hasOne(JenisSPPD::class, 'id' ,'id_jenis_sppd');
   }
}

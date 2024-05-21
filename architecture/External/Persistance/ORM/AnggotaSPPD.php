<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaSPPD extends Model
{
   use HasFactory;
   protected $table = 'sppd_anggota';
   protected $fillable = ['*'];

   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
   public function Dosen(){
      return $this->hasOne(Dosen::class, 'NIDN' ,'nidn');
   }
}
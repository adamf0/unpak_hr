<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlaimAbsen extends Model
{
   use HasFactory;
   protected $table = 'klaim_absen';
   protected $fillable = ['*'];

   function Presensi(){
      return $this->hasOne(Absensi::class, 'id', 'id_presensi'); 
   }
   public function Dosen(){
      return $this->hasOne(Dosen::class, 'NIDN', 'nidn'); 
   }
   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
}

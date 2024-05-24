<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
   use HasFactory;
   protected $table = 'absen';
   protected $fillable = ['*'];

   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
   public function Dosen(){
      return $this->hasOne(Dosen::class, 'NIDN' ,'nidn');
   }
}

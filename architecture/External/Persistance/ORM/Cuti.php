<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cuti extends Model
{
   use HasFactory;
   protected $table = 'cuti';
   protected $fillable = ['*'];

   function JenisCuti(){
      return $this->hasOne(JenisCuti::class, 'id', 'id_jenis_cuti'); 
   }
   public function Dosen(){
      return $this->hasOne(Dosen::class, 'nidn', 'nidn'); 
   }
   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
}

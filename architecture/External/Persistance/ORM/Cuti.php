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
      return $this->hasOne(Dosen::class, 'NIDN', 'nidn'); 
   }
   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
   public function PayrollPegawai(){
      return $this->hasOne(PayrollPegawai::class, 'nip' ,'nip');
   }
   public function PayrollVerifikasi(){
      return $this->hasOne(PayrollPegawai::class, 'nip' ,'verifikasi');
   }
   public function EPribadiRemote(){
      return $this->hasOne(EPribadi::class, 'nip' ,'verifikasi');
   }
}

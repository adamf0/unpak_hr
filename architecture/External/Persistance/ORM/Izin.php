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
      return $this->hasOne(EPribadiRemote::class, 'nip' ,'verifikasi');
   }
}

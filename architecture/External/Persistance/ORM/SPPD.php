<?php

namespace Architecture\External\Persistance\ORM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Metadata\Uses;

class SPPD extends Model
{
   use HasFactory;
   protected $table = 'sppd';
   protected $fillable = ['*'];

   public function JenisSPPD(){
      return $this->hasOne(JenisSPPD::class, 'id' ,'id_jenis_sppd');
   }

   public function Anggota(){
      return $this->hasMany(AnggotaSPPD::class, 'id_sppd' ,'id');
   }

   public function Pegawai(){
      return $this->hasOne(NPribadi::class, 'nip' ,'nip');
   }
   public function Dosen(){
      return $this->hasOne(Dosen::class, 'NIDN' ,'nidn');
   }
   public function SDM(){
      return $this->hasOne(User::class, 'id' ,'id_user');
   }
   public function FileLaporan(){
      return $this->hasMany(FileLaporanSPPD::class, 'id_sppd' ,'id');
   }
}

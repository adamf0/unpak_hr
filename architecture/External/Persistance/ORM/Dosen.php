<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dosen extends Model
{
   use HasFactory;
   protected $table = 'connect_m_dosen';
   protected $connection = 'simak';
   protected $fillable = ['*'];

   public function Fakultas() : HasOne
   {
      return $this->hasOne(Fakultas::class, 'kode_fakultas','kode_fak');
   }

   public function Prodi() : HasOne
   {
      return $this->hasOne(Prodi::class, 'kode_prodi','kode_prodi');
   }

   public function EPribadi() : HasOne
   {
      return $this->hasOne(EPribadi::class, 'nidn','NIDN');
   }
}

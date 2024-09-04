<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PayrollPegawai extends Model
{
   use HasFactory;
   protected $table = 'payroll_m_pegawai';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];

   public function EPribadi():HasOne{
      return $this->hasOne(EPribadi::class, 'nip', 'nip');
   }
}

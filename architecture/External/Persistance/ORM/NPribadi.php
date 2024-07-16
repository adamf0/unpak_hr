<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPribadi extends Model
{
   use HasFactory;
   protected $table = 'n_pribadi';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];

   public function PayrollPegawai(){
      return $this->hasOne(PayrollPegawai::class, 'nip' ,'nip');
   }
}

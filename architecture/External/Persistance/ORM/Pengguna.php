<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
   use HasFactory;
   protected $table = 'pengguna';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];

   function NPribadi(){
      return $this->hasOne(NPribadi::class, 'nip', 'username');
   }
   function Pengangkatan(){
      return $this->hasOne(Pengangkatan::class, 'nip', 'nip');
   }
   function PayrollPegawai(){
      return $this->hasOne(PayrollPegawai::class, 'nip', 'username');
   }
}

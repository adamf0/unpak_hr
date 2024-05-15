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
}

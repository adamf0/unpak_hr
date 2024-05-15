<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSimak extends Model
{
   use HasFactory;
   protected $table = 'user';
   protected $connection = 'simak';
   protected $fillable = ['*'];

   public function Dosen() : HasOne
   {
      return $this->hasOne(Dosen::class, 'NIDN','userid');
   }
}

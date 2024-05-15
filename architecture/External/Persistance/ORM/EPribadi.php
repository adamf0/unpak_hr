<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EPribadi extends Model
{
   use HasFactory;
   protected $table = 'e_pribadi';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];

   public function Payroll() : HasOne
   {
      return $this->hasOne(Payroll::class, 'nip','nip');
   }

   public function Jafung() : HasOne
   {
      return $this->hasOne(Jafung::class, 'nip','nip');
   }
}

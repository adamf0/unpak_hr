<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPribadiRemote extends Model
{
   use HasFactory;
   protected $table = 'connect_e_pribadi';
   protected $fillable = ['*'];
}

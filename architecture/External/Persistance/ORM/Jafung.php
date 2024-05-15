<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jafung extends Model
{
   use HasFactory;
   protected $table = 'e_jafung';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

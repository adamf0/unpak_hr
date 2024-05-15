<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NPribadi extends Model
{
   use HasFactory;
   protected $table = 'n_pribadi';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKalendar extends Model
{
   use HasFactory;
   protected $table = 'master_kalender';
   protected $fillable = ['*'];
}

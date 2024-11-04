<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SlipGaji extends Model
{
   use HasFactory;
   protected $table = 'payroll_publishb';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

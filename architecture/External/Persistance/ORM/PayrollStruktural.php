<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollStruktural extends Model
{
   use HasFactory;
   protected $table = 'payroll_m_struktural';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

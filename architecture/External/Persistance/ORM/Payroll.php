<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
   use HasFactory;
   protected $table = 'payroll_m_pegawai';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
}

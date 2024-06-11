<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLaporanSPPD extends Model
{
   use HasFactory;
   protected $table = 'sppd_file_laporan';
   protected $fillable = ['*'];
}
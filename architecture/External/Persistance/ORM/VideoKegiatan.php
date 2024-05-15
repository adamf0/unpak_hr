<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoKegiatan extends Model
{
   use HasFactory;
   protected $table = 'video_kegiatan';
   protected $fillable = ['*'];
}

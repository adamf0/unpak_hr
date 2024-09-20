<?php

namespace Architecture\External\Persistance\ORM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPengangkatan extends Model
{
   use HasFactory;
   protected $table = 'n_pengangkatan';
   protected $connection = 'simpeg';
   protected $fillable = ['*'];
    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        return is_null($this->tgl_keluar) || trim($this->tgl_keluar) == '' ? 'aktif' : 'keluar';
    }
}

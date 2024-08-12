<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EPribadi extends Model
{
    use HasFactory;

    protected $table = 'connect_e_pribadi';
    protected $guaraded = ['id_pribadi'];

    function pengangkatan(): HasOne
    {
        return $this->hasOne(EPengangkatan::class, 'nip', 'nip')->select('nip', 'unit_kerja');
    }
}

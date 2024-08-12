<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NPribadi extends Model
{
    use HasFactory;

    protected $table = 'connect_n_pribadi';
    protected $guaraded = ['id_n_pribadi'];

    function pengangkatan(): HasOne
    {
        return $this->hasOne(NPengangkatan::class, 'nip', 'nip')->select('nip', 'unit_kerja');
    }
}

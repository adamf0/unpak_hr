<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Klaim extends Model
{
    use HasFactory;

    protected $table = 'klaim_absen';
    protected $guarded = ['id'];

    function absen(): BelongsTo
    {
        return $this->belongsTo(Absen::class, 'id_presensi', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppdAnggota extends Model
{
    use HasFactory;

    protected $table = "sppd_anggota";
    protected $guarded = ['id'];

    function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class, 'id_sppd', 'id');
    }
}

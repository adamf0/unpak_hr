<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CutiDateUnique implements ValidationRule
{
    protected $nidn;
    protected $nip;
    protected $status;

    public function __construct($nidn = null, $nip = null, $status = null)
    {
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->status = $status;
    }
    
    function intersectionIzin($listDate = [], Closure $fail)
    {
        $intersectionDateIzin = DB::table('izin')->whereIn('tanggal_pengajuan', $listDate);
        if (!is_null($this->nidn)) {
            $intersectionDateIzin->where('nidn', $this->nidn);
        }
        if (!is_null($this->nip)) {
            $intersectionDateIzin->where('nip', $this->nip);
        }
        if (!is_null($this->status)) {
            $intersectionDateIzin->where('status', $this->status);
        }
        $intersectionDateIzin = $intersectionDateIzin->count();

        if ($intersectionDateIzin) {
            $fail("The selected dates overlap with an existing izin");
        }
    }
    function intersectionCuti($listDate = [], Closure $fail)
    {
        $sql = "
            WITH RECURSIVE DateRange AS (
                -- Initial Anchor Member
                SELECT id, nidn, nip, tanggal_mulai AS tanggal, `status`
                FROM cuti 
                UNION ALL
                -- Recursive Member
                SELECT cuti.id, cuti.nidn, cuti.nip, tanggal + INTERVAL 1 DAY, cuti.`status`
                FROM DateRange
                JOIN cuti ON DateRange.tanggal < cuti.tanggal_akhir 
            )
            SELECT DISTINCT *
            FROM DateRange 
            where (nidn = :nidn or nip = :nip) and `status` = :status
            ORDER BY tanggal DESC;";

        $results = collect(
            DB::select($sql, [
                'nidn' => $this->nidn,
                'nip' => $this->nip,
                'status' => $this->status,
            ])
        )
        ->pluck('tanggal')
        ->intersect($listDate);

        if($results->count()){
            $fail("The selected dates overlap with an existing cuti");
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start  = Carbon::parse(request('tanggal_mulai'));
        $end    = Carbon::parse(request('tanggal_berakhir'));
        $days   = $end->diffInDays($start);

        $listDate = array_reduce(range(0, $days+1), function ($carry, $i){
            $carry[] = Carbon::parse(request('tanggal_mulai'))->addDays($i)->format('Y-m-d');
            return $carry;
        }, []);
        $this->intersectionIzin($listDate, $fail);
        $this->intersectionCuti($listDate, $fail);
    }
}

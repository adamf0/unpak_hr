<?php

namespace App\Rules;

use Architecture\External\Persistance\ORM\Cuti as ModelCuti;
use Architecture\External\Persistance\ORM\Izin as ModelIzin;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class IzinDateUnique implements ValidationRule
{
    protected $nidn;
    protected $nip;

    public function __construct($nidn = null, $nip = null)
    {
        $this->nidn = $nidn;
        $this->nip = $nip;
    }
    
    function intersectionIzin($listDate = [], Closure $fail)
    {
        $intersectionDateIzin = ModelIzin::whereIn('tanggal_pengajuan', $listDate);

        if (!is_null($this->nidn)) {
            $intersectionDateIzin->where('nidn', $this->nidn);
        }
        if (!is_null($this->nip)) {
            $intersectionDateIzin->where('nip', $this->nip);
        }
        $intersectionDateIzin->where('status', "terima");
        $intersectionDateIzin = $intersectionDateIzin->count();

        if ($intersectionDateIzin) {
            $fail("Tanggal yang dipilih tumpang tindih dengan tanggal izin yang sudah ada");
        }
    }
    function intersectionCuti($listDate = [], Closure $fail)
    {

        $intersectionDateCuti = ModelCuti::where('status', "terima");
        if(count($listDate)>1){
            $start  = reset($listDate);
            $end    = end($listDate);
            $intersectionDateCuti = $intersectionDateCuti->where(fn($query)=>$query->whereBetween('tanggal_mulai', [$start,$end])->orWhereBetween('tanggal_akhir', [$start,$end]));
        } else if(count($listDate)==1){
            $start  = $listDate[0];
            $end    = $listDate[0];
            $intersectionDateCuti = $intersectionDateCuti->where(fn($query)=>$query->whereBetween('tanggal_mulai', [$start,$end])->orWhereBetween('tanggal_akhir', [$start,$end]));
        }
        if (!is_null($this->nidn)) {
            $intersectionDateCuti->where('nidn', $this->nidn);
        }
        if (!is_null($this->nip)) {
            $intersectionDateCuti->where('nip', $this->nip);
        }
        $intersectionDateCuti = $intersectionDateCuti->count();

        if ($intersectionDateCuti) {
            $fail("Tanggal yang dipilih tumpang tindih dengan tanggal cuti yang sudah ada");
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start  = Carbon::parse(request('tanggal_pengajuan'))->setTimezone('Asia/Jakarta');
        $end    = Carbon::parse(request('tanggal_pengajuan'))->setTimezone('Asia/Jakarta');
        $days   = $end->diffInDays($start);

        $listDate = array_reduce(range(0, $days+1), function ($carry, $i){
            $carry[] = Carbon::parse(request('tanggal_pengajuan'))->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d');
            return $carry;
        }, []);

        $this->intersectionCuti($listDate, $fail);
        $this->intersectionIzin($listDate, $fail);
    }
}

<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class IzinDateUnique implements ValidationRule
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

        $intersectionDateCuti = DB::table('Cuti');
        if(count($listDate)>1){
            $start  = reset($listDate);
            $end    = end($listDate);
            $intersectionDateCuti = $intersectionDateCuti->whereBetween('tanggal_mulai', [$start,$end])->orWhereBetween('tanggal_akhir', [$start,$end]);
        } else if(count($listDate)==1){
            $start  = $listDate[0];
            $end    = $listDate[0];
            $intersectionDateCuti = $intersectionDateCuti->whereBetween('tanggal_mulai', [$start,$end])->orWhereBetween('tanggal_akhir', [$start,$end]);
        }
        if (!is_null($this->nidn)) {
            $intersectionDateCuti->where('nidn', $this->nidn);
        }
        if (!is_null($this->nip)) {
            $intersectionDateCuti->where('nip', $this->nip);
        }
        if (!is_null($this->status)) {
            $intersectionDateCuti->where('status', $this->status);
        }
        $intersectionDateCuti = $intersectionDateCuti->count();

        if ($intersectionDateCuti) {
            $fail("The selected dates overlap with an existing cuti");
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start  = Carbon::parse(request('tanggal_pengajuan'));
        $end    = Carbon::parse(request('tanggal_pengajuan'));
        $days   = $end->diffInDays($start);

        $listDate = array_reduce(range(0, $days+1), function ($carry, $i){
            $carry[] = Carbon::parse(request('tanggal_pengajuan'))->addDays($i)->format('Y-m-d');
            return $carry;
        }, []);

        $this->intersectionCuti($listDate, $fail);
        $this->intersectionIzin($listDate, $fail);
    }
}

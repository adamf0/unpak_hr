<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\ValueObject\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Select2PresensiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {
    }

    public function List(Request $request)
    {
        $nidn = $request->has('nidn') ? $request->query('nidn') : null;
        $nip = $request->has('nip') ? $request->query('nip') : null;
        // $level = $request->has('level')? $request->query('level'):null;

        $listPresensi = $this->queryBus->ask(new GetAllPresensiQuery($nidn, $nip, date('Y-m-d')));

        $list_izin = $this->queryBus->ask(new GetAllIzinQuery($nidn, $nip, date('Y-m-d')));
        $listIzin = $list_izin->reduce(function ($list, $item) {
            $list[] = $item->GetTanggalPengajuan()?->toFormat(FormatDate::Default);

            return $list;
        }, []);

        $list_cuti = $this->queryBus->ask(new GetAllCutiQuery($nidn, $nip, date('Y-m-d')));
        $listCuti = $list_cuti->reduce(function ($list, $item) {
            $start  = $item->GetTanggalMulai()->val();
            $end    = $item->GetTanggalAkhir()->val();
            $days   = $end->diffInDays($start);

            $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
                $carry[] = $item->GetTanggalMulai()->val()->addDays($i)->format('Y-m-d');
                return $carry;
            }, []);

            $list[] = $listDate;

            return $list;
        }, []);

        $list_sppd = $this->queryBus->ask(new GetAllSPPDQuery($nidn, $nip, date('Y')));
        $listSppd = $list_sppd->reduce(function ($list, $item) {
            $start  = $item->GetTanggalBerangkat()->val();
            $end    = $item->GetTanggalKembali()->val();
            $days   = $end->diffInDays($start);

            $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
                $carry[] = $item->GetTanggalBerangkat()->val()->addDays($i)->format('Y-m-d');
                return $carry;
            }, []);

            $list[] = $listDate;

            return $list;
        }, []);

        $list_libur = $this->queryBus->ask(new GetAllMasterKalendarQuery(0, 0, date('Y-m-d')));
        $listLibur = $list_libur->reduce(function ($list, $item) {
            if (!empty($item->GetTanggalMulai())) {
                $start  = $item->GetTanggalMulai()->val();
                $end    = empty($item->GetTanggalAkhir()) ? $item->GetTanggalMulai()->val() : $item->GetTanggalAkhir()->val();
                $days   = $end->diffInDays($start);

                $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
                    $carry[] = $item->GetTanggalMulai()->val()->addDays($i)->format('Y-m-d');
                    return $carry;
                }, []);

                $list[] = $listDate;
            }

            return $list;
        }, []);

        $listTanggal = array_merge(Arr::flatten($listCuti), Arr::flatten($listSppd), Arr::flatten($listLibur), $listIzin);
        $listTanggal = array_values(array_unique($listTanggal));

        $rangeTanggal = array_reduce(range(0, 1), function ($carry, $i){
            $carry[] = Carbon::now()->subDays($i)->format('Y-m-d');
            return $carry;
        }, []);

        $list = $listPresensi->filter(function ($item) use ($listTanggal, $rangeTanggal) {
            return !in_array($item->GetTanggal(), $listTanggal) && in_array($item->GetTanggal(),$rangeTanggal);
        })
        ->values()
        ->sortBy('tanggal', SORT_REGULAR, true)
        ->reduce(function ($list, $item) {
            $text = $item->GetTanggal()->toFormat(FormatDate::LDFY);
            if (!is_null($item?->GetAbsenMasuk()) && !is_null($item?->GetAbsenKeluar())) {
                $text .= " (" . $item?->GetAbsenMasuk()?->toFormat(FormatDate::HIS) . " - " . $item?->GetAbsenKeluar()?->toFormat(FormatDate::HIS) . ")";
            } else if (!is_null($item?->GetAbsenMasuk()) && is_null($item?->GetAbsenKeluar())) {
                $text .= " (" . $item?->GetAbsenMasuk()?->toFormat(FormatDate::HIS) . " - saat ini)";
            }

            $list[] = [
                "id" => $item->GetId(),
                "text" => $text,
            ];
            return $list;
        },[]);

        return response()->json(count($list)>=2? array_slice($list,0,2):$list);
    }
}

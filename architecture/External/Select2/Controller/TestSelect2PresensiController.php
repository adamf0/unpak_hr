<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiInRangeQuery;
use Architecture\Application\Izin\List\GetAllIzinInRangeQuery;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarInRangeQuery;
use Architecture\Application\Presensi\List\GetAllPresensiInRangeQuery;
use Architecture\Application\SPPD\List\GetAllSPPDInRangeQuery;
use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\ValueObject\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TestSelect2PresensiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {
    }

    public function List(Request $request)
    {
        try {
            $nidn = $request->has('nidn') ? $request->query('nidn') : null;
            $nip = $request->has('nip') ? $request->query('nip') : null;
            // $level = $request->has('level')? $request->query('level'):null;

            $today = Carbon::today();
            $dateRange = collect(range(1, 2))->reduce(function ($carry, $item) use ($today) {
                $carry[] = $today->copy()->subDays($item)->format('Y-m-d');
                return $carry;
            }, []);

            $listPresensi = $this->queryBus->ask(new GetAllPresensiInRangeQuery($nidn, $nip, $dateRange));

            $list_izin = $this->queryBus->ask(new GetAllIzinInRangeQuery($nidn, $nip, $dateRange));
            // $listIzin = $list_izin->reduce(function ($list, $item) {
            //     $list[] = $item->GetTanggalPengajuan()?->toFormat(FormatDate::Default);

            //     return $list;
            // }, []);

            $list_cuti = $this->queryBus->ask(new GetAllCutiInRangeQuery($nidn, $nip, $dateRange));
            // $listCuti = $list_cuti->reduce(function ($list, $item) {
            //     $start  = $item->GetTanggalMulai()->val();
            //     $end    = $item->GetTanggalAkhir()->val();
            //     $days   = $end->diffInDays($start);

            //     $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
            //         $carry[] = $item->GetTanggalMulai()->val()->addDays($i)->format('Y-m-d');
            //         return $carry;
            //     }, []);

            //     $list[] = $listDate;

            //     return $list;
            // }, []);

            $list_sppd = $this->queryBus->ask(new GetAllSPPDInRangeQuery($nidn, $nip, $dateRange));
            // $listSppd = $list_sppd->reduce(function ($list, $item) {
            //     $start  = $item->GetTanggalBerangkat()->val();
            //     $end    = $item->GetTanggalKembali()->val();
            //     $days   = $end->diffInDays($start);

            //     $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
            //         $carry[] = $item->GetTanggalBerangkat()->val()->addDays($i)->format('Y-m-d');
            //         return $carry;
            //     }, []);

            //     $list[] = $listDate;

            //     return $list;
            // }, []);

            $list_libur = $this->queryBus->ask(new GetAllMasterKalendarInRangeQuery($dateRange));
            // $listLibur = $list_libur->reduce(function ($list, $item) {
            //     if (!empty($item->GetTanggalMulai())) {
            //         $start  = $item->GetTanggalMulai()->val();
            //         $end    = empty($item->GetTanggalAkhir()) ? $item->GetTanggalMulai()->val() : $item->GetTanggalAkhir()->val();
            //         $days   = $end->diffInDays($start);

            //         $listDate = array_reduce(range(0, $days + 1), function ($carry, $i) use ($item) {
            //             $carry[] = $item->GetTanggalMulai()->val()->addDays($i)->format('Y-m-d');
            //             return $carry;
            //         }, []);

            //         $list[] = $listDate;
            //     }

            //     return $list;
            // }, []);

            // $listTanggal = array_merge(Arr::flatten($listCuti), Arr::flatten($listSppd), Arr::flatten($listLibur), $listIzin);
            // $listTanggal = array_values(array_unique($listTanggal));

            // $rangeTanggal = array_reduce(range(1, 2), function ($carry, $i){
            //     $carry[] = Carbon::now()->subDays($i)->format('Y-m-d');
            //     return $carry;
            // }, []);

            // $list = $listPresensi->filter(function ($item) use ($listTanggal, $rangeTanggal) {
            //     return !in_array($item->GetTanggal()->toFormat(FormatDate::Default), $listTanggal) && 
            //             $item->GetAbsenMasuk() instanceof Date && 
            //             in_array($item->GetTanggal()->toFormat(FormatDate::Default),$rangeTanggal);
            // })
            // ->values()
            // ->sortBy('tanggal', SORT_REGULAR, true)
            // ->reduce(function ($list, $item) {
            //     $text = $item->GetTanggal()->toFormat(FormatDate::LDFY);
            //     if (!is_null($item?->GetAbsenMasuk()) && !is_null($item?->GetAbsenKeluar())) {
            //         $text .= " (" . $item?->GetAbsenMasuk()?->toFormat(FormatDate::HIS) . " - " . $item?->GetAbsenKeluar()?->toFormat(FormatDate::HIS) . ")";
            //     } else if (!is_null($item?->GetAbsenMasuk()) && is_null($item?->GetAbsenKeluar())) {
            //         $text .= " (" . $item?->GetAbsenMasuk()?->toFormat(FormatDate::HIS) . " - saat ini)";
            //     }

            //     $list[] = [
            //         "id" => $item->GetId(),
            //         "text" => $text,
            //     ];
            //     return $list;
            // },[]);
        } catch (\Exception $e) {
            throw $e;
        }

        dd(
            $listPresensi,
            $list_cuti,
            $list_izin,
            $list_sppd,
            $list_libur,
        );
        // return response()->json(count($list)>=2? array_slice($list,0,2):$list);
    }
}

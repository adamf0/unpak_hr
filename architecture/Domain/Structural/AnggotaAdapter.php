<?php
namespace Architecture\Domain\Structural;

use Exception;
use Illuminate\Support\Collection;

class AnggotaAdapter implements ListAdapter {
    public function GetReduceFromCollectEntity(Collection $source) {
        return $source->reduce(function ($list, $anggota) {
            $list[] = ["nidn" => $anggota->GetNIDN(), "nip" => $anggota->GetNIP(), "nama" => $anggota->GetNama()];
            return $list;
        }, []);
    }
    public function GetAndMergeDataEntity(Collection $list1,Collection $list2) {
        throw new Exception("function not implement");
    }
}
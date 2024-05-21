<?php
namespace Architecture\Domain\Structural;

use Illuminate\Support\Collection;

interface ListAdapter {
    public function GetReduceFromCollectEntity(Collection $listDatas);
    public function GetAndMergeDataEntity(Collection $list1,Collection $list2);
}
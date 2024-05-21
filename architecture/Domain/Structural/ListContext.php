<?php
namespace Architecture\Domain\Structural;

class ListContext {
    public ?ListAdapter $listAdapter = null;
    
    public function __construct()
    {
        
    }
    public function setAdapter(ListAdapter $listAdapter) {
        $this->listAdapter = $listAdapter;
        return $this;
    }

    public function getList($penelitianInternal,$type='internal') {
        return $this->listAdapter->GetReduceFromCollectEntity($penelitianInternal,$type);
    }
    public function getListAdministrasi($fakultas,$lppm) {
        return $this->listAdapter->GetAndMergeDataEntity($fakultas,$lppm);
    }
}
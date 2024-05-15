<?php

namespace Architecture\Shared;

trait PagingQuery
{
    public $offset=null;
    public $limit=null;

    public function SetOffset($offset=null){
        $this->offset = $offset;
        return $this;
    }
    public function GetOffset(){
        return $this->offset;
    }
    public function SetLimit($limit=null){
        $this->limit = $limit;
        return $this;
    }
    public function GetLimit(){
        return $this->limit;
    }
    public function HasPaging(){
        return !is_null($this->limit) && !is_null($this->offset);
    }
}

<?php

namespace Architecture\Application\MasterKalendar\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllMasterKalendarInRangeQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $dateRange=[],
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetDateRange(){
        return $this->dateRange;
    }
}
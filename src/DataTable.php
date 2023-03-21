<?php

namespace Enjat26\DataTables;

use Enjat26\DataTables\Traits\Builder;
use Enjat26\DataTables\Traits\Eloquent;

class DataTable 
{
    use Eloquent;
    use Builder;
    protected $request;
    protected $query;
    protected $records_total;
    protected $records_filtered;

    public function create($data)
    {
        $array = [];
        $array['draw'] = $this->request['draw'];
        $array['recordsTotal'] = $this->records_total;
        $array['recordsFiltered'] = $this->records_filtered;
        $array['data'] = $data;
        return $array;
    }
}

<?php

namespace Enjat26\DataTables;

use Enjat26\DataTables\Traits\Builder;
use Enjat26\DataTables\Traits\Eloquent;
use \Config\Services;

class DataTable
{
    use Eloquent;
    use Builder;
    protected $request;
    protected $query;
    protected $records_total;
    protected $records_filtered;
    protected $result_row;
    protected $add_column = [];
    protected $data = [];
    protected $connection;

    function __construct()
    {
        $service  = Services::request();
        $this->request = $service->getGet();
    }

    public function addColumn($callback)
    {
        foreach ($this->query->get() as $result) {
            $this->result_row = $result;
            $new_array = call_user_func($callback, $this->result_row);
            $this->data[] = array_merge($result->toArray(), $new_array);
        }
        return $this;
    }

    public function data()
    {
        foreach ($this->query->get() as $result) {
            $this->result_row = $result;
            $this->data[] = $result->toArray();
        }
        return $this;
    }

    public function create()
    {
        $array = [];
        $array['draw'] = $this->request['draw'];
        $array['recordsTotal'] = $this->records_total;
        $array['recordsFiltered'] = $this->records_filtered;
        $array['data'] = $this->data;
        return $array;
    }
}

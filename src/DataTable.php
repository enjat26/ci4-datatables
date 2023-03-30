<?php

namespace Enjat26\DataTables;

use Enjat26\DataTables\Traits\Builder;
use Enjat26\DataTables\Traits\Eloquent;
use \Config\Services;

use function PHPUnit\Framework\isEmpty;

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
    protected $where_builder = [];
    protected $or_where_builder = [];
    protected $start;

    function __construct()
    {
        $service  = Services::request();
        // $this->request = $service->getPost();
        $this->request = $service->getGet();
        $this->start = $this->request['start'];
    }

    public function addColumn($callback)
    {
        $i = $this->start+1;
        if ($this->connection == 'builder') {
            $this->query->orLike($this->or_where_builder);
            $rows = $this->query->get()->getResult();
        } else {
            $rows = $this->query->get();
        }
        foreach ($rows as $result) {
            $this->result_row = $result;
            $this->add_column = call_user_func($callback, $this->result_row);
            if ($this->connection == 'builder') {
                $new_result = (array)$result;
            } else {
                $new_result = $result->toArray();
            }
            $this->data[] = array_merge(['no_' => $i],  $new_result, $this->add_column);
            $i++;
        }
        return $this;
    }

    private function data()
    {
        $i = $this->start+1;
        if ($this->connection == 'builder') {
            // $this->query->where($this->where_builder);
            $this->query->orLike($this->or_where_builder);
            $rows = $this->query->get()->getResult();
        } else {
            $rows = $this->query->get();
        }
        $array = [];
        foreach ($rows as $result) {
            if ($this->connection == 'builder') {
                $new_result = (array)$result;
            } else {
                $new_result = $result->toArray();
            }
            $array[] = array_merge(['no_' => $i], $new_result);
            $i++;
        }
        return $array;
    }

    public function render()
    {
        $array = [];
        $array['draw'] = $this->request['draw'];
        $array['recordsTotal'] = $this->records_total;
        $array['recordsFiltered'] = $this->records_filtered;
        $array['data'] = isEmpty($this->add_column) ? $this->data() : $this->data;
        return $array;
    }
}

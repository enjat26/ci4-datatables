<?php

namespace Enjat26\DataTables\Traits;

trait Builder
{
	public function builder($model)
	{
		$this->connection = 'builder';
		$this->query = $model;
		$this->records_total = $this->query->countAllResults();

		//jika ada filter
		// if (!empty($this->request['search']['value'])) {
		// foreach ($this->request['columns'] as $column) {
		// 	if ($column['searchable'] == 'true') {
		// 		// dd($column['data']);
		// 		// dd($this->request['search']['value']);
		// 		// $this->query->where($column['data'], 'jatnika');
		// 		if (!empty($this->request['search']['value'])) {
		// 			$this->query->orWhere($column['data'], 'like', '%' . $this->request['search']['value'] . '%');
		// 		}
		// 		// die(print_r($column['search']['value']));
		// 		if (!empty($column['search']['value'] != '')) {
		// 			// die(print_r('dsds'));
		// 			$this->query->where($column['data'], $column['search']['value']);
		// 		}
		// 	}
		// }
		// }
		$array_where = [];
		$array_or_where = [];
		foreach ($this->request['columns'] as $column) {
			if ($column['searchable'] == 'true') {
				if (empty($column['search']['value'])) {
					$array_or_where[$column['data']] = $this->request['search']['value'];
				} else {
					$array_where[$column['data']] =  $column['search']['value'];
				}
			}
		}
		// die(print_r($array_or_where));
		$this->where_builder = $array_where;
		$this->or_where_builder = $array_or_where;
		//total record jika ada filter
		$this->records_filtered = $this->query->countAllResults();

		//total record jika ada order
		if (!empty($this->request['order'])) {
			$this->query->orderBy($this->request['columns'][$this->request['order'][0]['column']]['data'], $this->request['order'][0]['dir']);
		}

		if (!empty($this->request['length'])) {
			if ($this->request['length'] != -1) {
				return $this->query->limit($this->request['length'], $this->request['start']);
			}
		}

		// $this->query->where('sppd_type_year', 2023);
		// return [];
	}
}

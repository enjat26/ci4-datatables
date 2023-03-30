<?php

namespace Enjat26\DataTables\Traits;

trait Eloquent
{
	public function eloquent($model)
	{
		$this->connection = 'eloquent';
		$this->query = $model;
		$this->records_total = $this->query->count();

		//jika ada filter
		// if (!empty($this->request['search']['value'])) {



		//tambah kondisi where jika ada tambahan parameter search
		$check = false;
		foreach ($this->request['columns'] as $column) {
			if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
				$array[] = [$column['data'], $column['search']['value']];
				$this->query->where($column['data'], $column['search']['value']);
				$this->query->where(function ($query) {
					foreach ($this->request['columns'] as $column) {
						if ($column['searchable'] == 'true' && empty($column['search']['value'])) {
							$query->orWhere($column['data'], 'like', '%' . $this->request['search']['value'] . '%');
						}
					}
					return $query;
				});
				$this->records_total = $this->query->count();
				$check = true;
			}
		}
		if ($check === false) {
			foreach ($this->request['columns'] as $column) {
				if ($column['searchable'] == 'true') {
					$this->query->orWhere($column['data'], 'like', '%' . $this->request['search']['value'] . '%');
					$this->records_total = $this->query->count();
				}
			}
		}
		// die(print_r($array));
		// die(print_r($this->query));
		// }

		//total record jika ada filter
		$this->records_filtered = $this->query->count();

		//total record jika ada order
		if (!empty($this->request['order'])) {
			$this->query->orderBy($this->request['columns'][$this->request['order'][0]['column']]['data'], $this->request['order'][0]['dir']);
		}

		if (!empty($this->request['length'])) {
			if ($this->request['length'] != -1) {
				// die(print_r($this->request['length']));
				return $this->query->skip($this->request['start'])->take($this->request['length']);
				// return $this->query->get($this->request['length'], $this->request['start']);
			} else {
				return $this->query;
			}
		}
		// dd($this->query->toSql());
		return $this->query;
	}
}

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
		if (!empty($this->request['search']['value'])) {
			foreach ($this->request['columns'] as $column) {
				if ($column['searchable'] == 'true') {
					// dd($column['data']);
					// dd($this->request['search']['value']);
					// $this->query->where($column['data'], 'jatnika');
					$this->query->orWhere($column['data'], 'like', '%' . $this->request['search']['value'] . '%');
				}
			}
		}

		//total record jika ada filter
		$this->records_filtered = $this->query->count();

		//total record jika ada order
		if (!empty($this->request['order'])) {
			$this->query->orderBy($this->request['columns'][$this->request['order'][0]['column']]['data'], $this->request['order'][0]['dir']);
		}
		// dd($this->query->toSql());
		return $this->query->get();
	}
}

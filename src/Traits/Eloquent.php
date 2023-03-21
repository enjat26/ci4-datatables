<?php

namespace Enjat26\DataTables\Traits;
use \Config\Services;
trait Eloquent
{
	public function eloquent($model)
	{
		$service  = Services::request();
		$request  = $service->getGet();
		$this->request = $request;
		$this->query = $model;

		//total record
		$this->records_total = $this->query->count();

		//jika ada filter
		if (!empty($this->request['search']['value'])) {
			foreach ($this->request['columns'] as $column) {
				if ($column['searchable'] == 'true') {
					$this->query->orWhere($column['data'], 'like', '%' . $this->request['search']['value'] . '%');
				}
			}
		}

		//total record jika ada filter
		$this->records_filtered = $this->query->count();

		//total record jika ada order
		if (!empty($request['order'])) {
			$this->query->orderBy($this->request['columns'][$this->request['order'][0]['column']]['data'], $this->request['order'][0]['dir']);
		}
		return $this->query->get();
	}
}

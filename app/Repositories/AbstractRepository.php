<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAttributes(string $attributes): void
    {
        $this->model = $this->model->with($attributes);
    }

    public function filter(string $filter): void
    {
        $filter = explode(';', $filter);
        foreach ($filter as $key => $value) {
            $condition = explode(':', $value);
            $this->model = $this->model->where($condition[0], $condition[1], $condition[2]);
        }
    }

    public function selectParams(string $params): void
    {
        $this->model = $this->model->selectRaw($params);
    }

    public function getAttribute()
    {
        return $this->model->get();
    }

    public function getPaginate(int $page)
    {
        return $this->model->paginate($page);
    }
}

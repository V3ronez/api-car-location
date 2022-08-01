<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{

    public function __construct(Model $brand)
    {
        $this->brand = $brand;
    }

    public function selectAttributes($attributes): void
    {
        $this->brand = $this->brand->with($attributes);
    }

    public function filter($filter): void
    {
        $filter = explode(';', $filter);
        foreach ($filter as $key => $value) {
            $condition = explode(':', $value);
            $this->brand = $this->brand->where($condition[0], $condition[1], $condition[2]);
        }
    }

    public function selectParams($params): void
    {
        $this->brand = $this->brand->selectRaw($params);
    }

    public function getAttribute()
    {
        return $this->brand->get();
    }
}

<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosSelecionados($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getReturn()
    {
        return $this->model->get();
    }

    public function filtro($filtro)
    {
        $filtros = explode(';', $filtro);

        foreach ($filtros as $key => $cond) {
            $c = explode(':', $cond);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }
}

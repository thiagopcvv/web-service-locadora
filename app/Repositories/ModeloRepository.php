<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ModeloRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosSelecionados($atributos)
    {
        $this->model = $this->model->with('marcas:id,' . $atributos);
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getReturn()
    {
        return $this->model->get();
    }
}

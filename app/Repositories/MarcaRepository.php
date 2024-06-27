<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class MarcaRepository
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
}

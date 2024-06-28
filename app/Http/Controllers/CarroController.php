<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCarroRequest;
use App\Models\Carro;
use App\Repositories\CarroRepository;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;

class CarroController extends Controller
{

    protected $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelos:modelo_id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosSelecionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosSelecionados('modelos');
        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $carroRepository->selectAtributos($atributos);
        }

        return $carroRepository->getReturn();
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            "modelo_id" => $request->modelo_id,
            "placa" => $request->placa,
            "disponivel" => $request->disponivel,
            "km" => $request->km
        ]);

        return $carro;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelos')->find($id);

        if ($carro === null) {
            return response()->json(["Erro" => "recurso não encontrado", 404]);
        }

        return $carro;
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->with('modelos')->find($id);

        if ($carro === null) {
            return response()->json(["Erro" => "recurso não encontrado", 404]);
        }

        if ($request->method() === "PATCH") {
            $regrasDinamicas = array();

            foreach ($this->carro->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($this->carro->rules());
        }

        $carro->fill($request->all());

        $carro->save();

        return $carro;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carro = $this->carro->with('modelos')->find($id);

        if ($carro === null) {
            return response()->json(["Erro" => "recurso não encontrado", 404]);
        }

        $carro->delete();

        return response()->json(["Aviso" => "item excluido com sucesso"]);
    }
}

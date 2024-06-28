<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository as RepositoriesModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modeloRepository = new RepositoriesModeloRepository($this->modelo);

        if ($request->has('atributos_carro')) {
            $atributos_carro = 'carros:id,'.$request->atributos_marcas;
            $modeloRepository->selectAtributosSelecionados($atributos_carro);
        } else {
            $modeloRepository->selectAtributosSelecionados("carros");
        }

        if ($request->has('atributos_marcas')) {

            $atributos_marcas = $request->atributos_marcas;
            $modeloRepository->selectAtributosSelecionados($atributos_marcas);
        } else {
            $modeloRepository->selectAtributosSelecionados('marcas');
        }
        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $modeloRepository->selectAtributos($atributos);
        }
        return $modeloRepository->getReturn();
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelo', 'public');

        $modelo = $this->modelo->create([
            "marca_id" => $request->marca_id,
            "nome" => $request->nome,
            "imagem" => $imagem_urn,
            "numero_portas" => $request->numero_portas,
            "lugares" => $request->lugares,
            "air_bag" => $request->air_bag,
            "abs" => $request->abs
        ]);

        return $modelo;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marcas')->find($id);

        if ($modelo === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }
        return $modelo;
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->with('marcas')->find($id);
        if ($modelo === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }

        if ($request->method() === "PATCH") {
            $regrasDinamicas = array();

            foreach ($this->modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());

            if ($request->file('imagem')) {
                Storage::disk('public')->delete($modelo->imagem);
            }
        } else {

            $request->validate($modelo->rules(), $modelo->feedback());
        }

        $img = false;

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $image = $request->file('imagem');
            $imagem_urn = $image->store('imagens', 'public');
            $img = true;
        }

        $modelo->fill($request->all());

        if ($img) {
            $modelo->imagem = $imagem_urn;
        }

        $modelo->save();

        return $modelo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->with('marcas')->find($id);
        if ($modelo === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();


        return ["msg" => "removido com sucesso"];
    }
}

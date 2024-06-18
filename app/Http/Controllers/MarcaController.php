<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class MarcaController extends Controller
{

    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marca = $this->marca->all();
        return $marca;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $regras = [
            "nome" => "required|unique:marcas",
            "imagem" => "required"
        ];

        $feeddback = [
            "require" => "O campo :attribute é obrigatório",
            "nome.unique" => "Essa marca ja é existente"
        ];

        $request->validate($regras, $feeddback);

        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }
        $marca->update($request->all());


        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["Erro" => "recurso não encontrado"], 404);
        }
        $marca->delete();

        return ["msg" => "removido com sucesso"];
    }
}

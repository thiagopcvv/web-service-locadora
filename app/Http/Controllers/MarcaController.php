<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens', 'public');

        $marca = $this->marca->create([
            "nome" => $request->nome,
            "imagem" => $imagem_urn
        ]);
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

        if ($request->method() === "PATCH") {
            $regrasDinamicas = array();

            foreach ($this->marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

            if ($request->file('imagem')) {
                Storage::disk('public')->delete($marca->imagem);
            }

            if ($request->nome == null) {
                $image = $request->file('imagem');
                $imagem_urn = $image->store('imagens', 'public');
                $marca->update([
                    "imagem" => $imagem_urn
                ]);

                return $marca;
            }

            if ($request->file('imagem') == null) {
                $marca->update([
                    "nome" => $request->nome
                ]);

                return $marca;
            }
        } else {

            $request->validate($marca->rules(), $marca->feedback());
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens', 'public');

        $marca->update([
            "nome" => $request->nome,
            "imagem" => $imagem_urn

        ]);

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
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();


        return ["msg" => "removido com sucesso"];
    }
}
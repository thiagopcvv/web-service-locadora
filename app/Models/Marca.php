<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ["nome", "imagem"];

    public function rules()
    {
        return [
            "nome" => "required|unique:marcas,nome",
            "imagem" => "required|file|mimes:png,jpg"
        ];
    }

    public function feedback()
    {
        return [
            "required" => "O campo :attribute é obrigatório",
            "nome.unique" => "Essa marca ja é existente",
            "mimes" => "Esse tipo de arquivo não é compatível, apenas png, jpg"
        ];
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}

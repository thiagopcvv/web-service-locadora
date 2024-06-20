<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'
    ];
    
    public function rules()
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome',
            'imagem' => 'required|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'Esse nome de modelo já existe',
            'marca_id.exists' => 'A marca selecionada não existe',
            'imagem.mimes' => 'Esse tipo de arquivo não é compatível, apenas png, jpg, jpeg',
            'integer' => 'O campo :attribute deve ser um número inteiro',
            'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso'
        ];
    }

    public function marcas(){
        return $this->belongsTo(Marca::class);
    }
}
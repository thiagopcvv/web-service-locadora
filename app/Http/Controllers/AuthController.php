<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $request->validate(["email" => "required", "senha" => "required"]);
        $credenciais = $request->all(['email', 'password']);

        if (!$token = auth('api')->attempt($credenciais)) {
            return response()->json(['error' => 'Sem autorização'], 401);
        }

        return response()->json(['token' => $token], 200);
    }
    public function logout()
    {
        auth('api')->logout();
        return response()->json(["msg" => "logout feito com sucesso"] );
    }
    public function refresh()
    {
        
        $token = auth('api')->refresh(); // Obtém um novo token JWT válido
        return response()->json(['token' => $token], 200);
    }
    public function me()
    {
        return response()->json((auth()->user()));
    }
}
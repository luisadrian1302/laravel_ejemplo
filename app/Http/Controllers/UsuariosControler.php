<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuariosControler extends Controller
{
    public function indexAPI(Request $request)
     {
        $user = $request->user(); // Obtiene al usuario autenticado desde el token

        // Verificar el rol del usuario
        if ($user->rol !== 'superadmin') {
            return response()->json(['message' => 'Acceso denegado. No eres administrador.'], 403);
        }
        // devolver al usuario
        //  Log::info('Iniciando el proceso en el controlador Index categoria.');
         $users = User::all();
        //  Log::info('Proceso finalizado.');
         return $users;
     }

    //
}

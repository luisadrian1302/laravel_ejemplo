<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Obtiene al usuario autenticado desde el token

        // Verificar el rol del usuario
        if ($user->rol !== 'superadmin') {
            return response()->json(['message' => 'Acceso denegado. No eres administrador.'], 403);
        }

        return response()->json(['message' => 'Bienvenido, administrador'], 200);
    }
}


?>
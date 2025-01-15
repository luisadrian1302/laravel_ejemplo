<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('app')->plainTextToken;
            $arr = array(
                'acceso' => 'Ok',
                'error' => '',
                'token' => $token,
                'idUsuario' => $user->id,
                'nombreUsuario' => $user->name
            );

             Log::info('El usuario con el nombre de '.$user->name." con el rol de ".$user->rol." acaba de iniciar sesion en la plataforma");

            return json_encode($arr);
        } else {
            $arr = array(
                'acceso' => '',
                'token' => '',
                'error' => 'No existe el usuario y/o contraseña.',
                'idUsuario' => 0,
                'nombreUsuario' => ''
            );
            Log::info('Autenticación fallida');

            return json_encode($arr);
        }
    }
}

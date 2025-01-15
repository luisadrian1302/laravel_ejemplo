<?php namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


       
        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' =>  $request->rol
        ]);

        // Generar token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;
        
        
        Log::info(' El usuario '.$request->name.' con el rol de user  acaba caba de ser dado de alta en la plataforma');
        

        // Responder con los datos del usuario y el token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    public function getUserApi(Request $request,  $id){
        $id_user = $id;

        $user = $request->user();


        $userGet = User::where('id', $id_user)->first();

        Log::info('El usuario con el nombre de '.$user->name." con el rol de ".$user->rol." acaba de obtener el usuario con el id ".$id_user);

        return json_encode($userGet);
    }

    public function updateAPI(Request $request, $id)
    {

        try {
            $user = $request->user();


            $userUpt = User::findOrFail($id);


            if ( !empty($request->password)){
                # code...
                $userUpt->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' =>  $request->rol
                ]);
            }else{
                $userUpt->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'rol' =>  $request->rol
                ]);
            }

            Log::info('El usuario con el nombre de '.$user->name." con el rol de ".$user->rol." acaba de actualizar el usuario con el id ".$user->id);

            return json_encode($userUpt);
            // Crear el nuevo usuario
      

        }catch (Exception $e) {

        }
    }

    public function deleteAPI(Request $request, $id)
    {

        try {
            $user = $request->user();


            $userUpt = User::find($id);
            $userUpt->delete();



            Log::info('El usuario con el nombre de '.$user->name." con el rol de ".$user->rol." acaba de eliminar al usuario con el id ".$user->id);

            return "ok";
            // Crear el nuevo usuario
      

        }catch (Exception $e) {

        }
    }
}

?>
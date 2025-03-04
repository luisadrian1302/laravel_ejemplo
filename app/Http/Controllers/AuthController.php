<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rol",
     *         in="query",
     *         description="User's role",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

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

        // Responder con los datos del usuario y el token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }





    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     in="header",
     *     description="Enter your Bearer Token here"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/getUser/{id}",
     *     summary="Get user details by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to fetch",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User details fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="luis"),
     *             @OA\Property(property="rol", type="string", example="user"),
     *             @OA\Property(property="email", type="string", example="hola@hola.com"),
     *             @OA\Property(property="email_verified_at", type="string", example=null),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-24T01:02:10.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-24T01:02:10.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */



    public function getUserApi(Request $request,  $id)
    {
        $id_user = $id;
        $user = $request->user();
        $userGet = User::where('id', $id_user)->first();
        return json_encode($userGet);
    }





    /**
     * @OA\Put(
     *     path="/api/getUser/{id}",
     *     summary="Update user details by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Luis Updated"),
     *             @OA\Property(property="email", type="string", example="newemail@hola.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="rol", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User details updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Luis Updated"),
     *             @OA\Property(property="email", type="string", example="newemail@hola.com"),
     *             @OA\Property(property="rol", type="string", example="admin"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-24T01:02:10.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-24T01:02:10.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request, validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation errors")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */

    public function updateAPI(Request $request, $id)
    {

        try {
            $user = $request->user();


            $userUpt = User::findOrFail($id);


            if (!empty($request->password)) {
                # code...
                $userUpt->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' =>  $request->rol
                ]);
            } else {
                $userUpt->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'rol' =>  $request->rol
                ]);
            }


            return json_encode($userUpt);
        } catch (Exception $e) {
        }
    }





    /**
 * @OA\Delete(
 *     path="/api/getUser/{id}",
 *     summary="Delete a user by ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Bad request, validation errors",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Validation errors")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not found")
 *         )
 *     )
 * )
 */

    public function deleteAPI(Request $request, $id)
    {

        try {
            $user = $request->user();
            $userUpt = User::find($id);
            $userUpt->delete();
            return "ok";
            // Crear el nuevo usuario
        } catch (Exception $e) {
        }
    }
}

<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

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
 *     path="/api/verifyAdmin",
 *     summary="Verify if the user is an admin",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response="200",
 *         description="User is an admin",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Bienvenido, administrador")
 *         )
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Access denied if the user is not an admin",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Acceso denegado. No eres administrador.")
 *         )
 *     )
 * )
 */
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
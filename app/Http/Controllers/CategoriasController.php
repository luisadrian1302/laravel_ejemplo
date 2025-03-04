<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriasController extends Controller
{
    //
    //  Mostrar la lista de categorías]

    
     public function index(Request $request)
     {

        $user = $request->user();
        $categories = Categorias::all();

         return view('categories.index', compact('categories'));
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
     *     path="/api/categories",
     *     summary="Get all categories",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="List of all categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nombre", type="string", example="nombre"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-24T02:57:30.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-24T02:57:30.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized, invalid token",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

 
     public function indexAPI(Request $request)
     {
        $user = $request->user();
         $categories = Categorias::all();

        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener todas las categorias ');

         return $categories;
     }





     /**
 * @OA\Get(
 *     path="/api/categories/{id}",
 *     summary="Get category by ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Category found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="nombre", type="string", example="nombre"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-24T02:57:30.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-24T02:57:30.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Error in the server",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Category not found")
 *         )
 *     )
 * )
 */
     public function getAPI(Request $request, $id)
     {
        try {
            $user = $request->user();


            $categoria = Categorias::findOrFail($id);
            Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener la categoria con el id '.$id);

            return $categoria;
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            Log::channel('slack')->error('Ocurrió un error en el servidor: ' . $th->getMessage());
       
            return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

        }

     }
       
     // Mostrar el formulario para crear una nueva categoría
     public function create()
     {
         return view('categories.create');
     }


     
     /**
 * @OA\Post(
 *     path="/api/categories",
 *     summary="Create a new category",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="New Category")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Category created successfully",
 *         @OA\JsonContent(
 *             type="string",
 *             example="Ok"
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The name field is required.")
 *         )
 *     )
 * )
 */
    public function storeAPI(Request $request)
    {
        $user = $request->user();


        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Categorias::create([
            'nombre' => $request->name
        ]);

        return "Ok";
    }



    /**
 * @OA\Put(
 *     path="/api/categories/{id}",
 *     summary="Update a category",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Updated Category Name")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Category updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Ok")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Error occurred while updating category",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The name field is required.")
 *         )
 *     )
 * )
 */
    public function updateAPI(Request $request, $id)
    {

        try {
            $user = $request->user();

            
            $request->validate([
                'name' => 'required|string|max:255'
            ]);
    
            
            $categoria = Categorias::findOrFail($id);
            $categoria->update([
                'nombre' => $request->name
            ]);

            // return "Ok";
            return response()->json(['message' => 'Ok']); 
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

        }

    }




    /**
 * @OA\Delete(
 *     path="/api/categories/{id}",
 *     summary="Delete a category",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Category deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Ok")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Category not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Category not found")
 *         )
 *     )
 * )
 */
    
    public function deleteAPI(Request $request, $id){

        $user = $request->user();


        $categoria = Categorias::find($id);
        $categoria->delete();

        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de eliminar la categoria con el id '.$id);

        return "Ok";

    }
}

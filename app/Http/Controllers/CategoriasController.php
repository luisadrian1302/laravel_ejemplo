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
        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener todas las categorias ');

         return view('categories.index', compact('categories'));
     }
 
     public function indexAPI(Request $request)
     {
        $user = $request->user();
         $categories = Categorias::all();

        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener todas las categorias ');

         return $categories;
     }

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


     // Guardar una nueva categoría
    public function storeAPI(Request $request)
    {
        $user = $request->user();


        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Categorias::create([
            'nombre' => $request->name
        ]);
        
        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de crear una nueva categoria ');


        return "Ok";
    }

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

            Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de actualizar la categoria con el id '.$id);
           


            // return "Ok";
            return response()->json(['message' => 'Ok']); 
        } catch (\Throwable $th) {
            //throw $th;
            Log::channel('slack')->error('Ocurrió un error en el servidor: ' . $th->getMessage());

            return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

        }
       


    }
    
    public function deleteAPI(Request $request, $id){

        $user = $request->user();


        $categoria = Categorias::find($id);
        $categoria->delete();

        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de eliminar la categoria con el id '.$id);

        return "Ok";

    }
}

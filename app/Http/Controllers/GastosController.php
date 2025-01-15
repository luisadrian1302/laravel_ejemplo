<?php

namespace App\Http\Controllers;

use App\Models\Gastos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GastosController extends Controller
{
    
    public function indexAPI(Request $request)
    {
        $user = $request->user();

        // $categories = Gastos::all();

        $categories = Gastos::with(['categoria', 'usuario'])->get();
        // Gasto:)
        Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener todos los gastos ');

        return $categories;
    }

    public function getAPI(Request $request, $id)
    {
       try {

            $user = $request->user();
            
            
            $categoria = Gastos::findOrFail($id);
            Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de obtener el gasto con el id '.$id);

           return $categoria;
           //code...
       } catch (\Throwable $th) {
           //throw $th;
           Log::channel('slack')->error('Ocurrió un error en el servidor: ' . $th->getMessage());
      
           return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

       }

    }
      
   

    // Guardar una nueva categoría
   public function storeAPI(Request $request)
   {

    try {
        //code...
    
        $user = $request->user();

       
       $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        //'id_users' => 'required|integer|exists:users,id',
        'id_categoria' => 'required|integer|exists:categorias,id', // Ajusta "categorias" si el nombre de tu tabla es diferente
        'fecha_gasto' => 'required|date',
        'monto' => 'required|numeric|min:0',
        'descripcion' => 'nullable|string|max:1000',
        ]);


        Gastos::create([
            'name' => $request->name,
            'id_users' => $user->id,
            'id_categoria' => $request->id_categoria,
            'fecha_gasto' => $request->fecha_gasto,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,

        ]);
       
       Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de crear un nuevo gasto');

       return response()->json(['message' => 'Ok']); 


    } catch (\Throwable $th) {
   

    
        //throw $th;
        return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

    
    }
   }

   public function updateAPI(Request $request, $id)
   {

       try {
           
           $user = $request->user();

       
           $request->validate([
            'name' => 'required|string|max:255',
            //'id_users' => 'required|integer|exists:users,id',
            'id_categoria' => 'required|integer|exists:categorias,id', // Ajusta "categorias" si el nombre de tu tabla es diferente
            'fecha_gasto' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:1000',
            ]);
   
           
           $categoria = Gastos::findOrFail($id);
           $categoria->update([
            'name' => $request->name,
            'id_users' => $user->id,
            'id_categoria' => $request->id_categoria,
            'fecha_gasto' => $request->fecha_gasto,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion
           ]);

            Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de actualizar el gasto con el id '.$id);



           // return "Ok";
           return response()->json(['message' => 'Ok']); 
       } catch (\Throwable $th) {
           //throw $th;

           return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 

       }
      


   }
   
   public function deleteAPI(Request $request, $id){


        $user = $request->user();

       $categoria = Gastos::find($id);
       $categoria->delete();


       Log::info(' El usuario '.$user->name.' con el rol '.$user->rol.' acaba de eliminar el gasto con el id '.$id);

       return "Ok";

   }
    //
}

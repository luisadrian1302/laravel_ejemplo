<?php

namespace App\Http\Controllers;

use App\Models\rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    
    

    public function indexAPI()
    {
        $rol = Rol::all();
        return $rol;
    }

    public function getAPI(Request $request, $id)
    {
       try {
           $rol = Rol::findOrFail($id);
           return $rol;
           //code...
       } catch (\Throwable $th) {
           //throw $th;      
           return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 
       }
    }

    // Guardar una nueva categoría
   public function storeAPI(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255'
       ]);
       rol::create([
           'name' => $request->name
       ]);
       return "Ok";
   }

   public function updateAPI(Request $request, $id)
   {
       try {           
           $request->validate([
               'name' => 'required|string|max:255'
           ]);
           $rol = Rol::findOrFail($id);
           $rol->update([
               'name' => $request->name
           ]);
           // return "Ok";
           return response()->json(['message' => 'Ok']); 
       } catch (\Throwable $th) {
           //throw $th;
           return response()->json(['message' => $th->getMessage()])->setStatusCode(400); 
       }
   }
   
   public function deleteAPI(Request $request, $id){


       $rol = Rol::find($id);
       $rol->delete();
       return "Ok";

   }
}

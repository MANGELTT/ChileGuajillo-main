<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clasification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClasificationController extends Controller
{
    public function create(Request $request)
    {
        $profile = Auth::user()->profile;

        if ($profile->typeProfile == "2") {
            // Validar los datos de entrada
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            // Crear un nuevo director
            $clasification = new Clasification();
            $clasification->name = $request->name;
            $clasification->save();

            // Devolver una respuesta de éxito
            return response($clasification, Response::HTTP_CREATED);
        } else {
            return response(["message" => "No estás autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
        }
    }




    public function edit(Request $request, $id){
        $profile = Auth::user()->profile;

        if($profile->typeProfile == "2"){
            $clasificacion = Clasification::find($id);

            if(!$clasificacion){
                return response(["message" => "Clasificacion no econtrado"], Response::HTTP_NOT_FOUND);
            }

            if($request->name == "" ){
                return response(["message" => "No hubo nada que actualizar"], Response::HTTP_OK);
            }
            if($request->name){
                $clasificacion->name = $request->name;
             }
             $clasificacion->save();
             return response(["data" =>$clasificacion], Response::HTTP_OK);
        }
    }


    public function destroy($id){
        $profile = Auth::user()->profile;

        if($profile->typeProfile == "2"){
            $clasification = Clasification::find($id);

            if(!$clasification){
                return response(["message" => "Clasificacion no econtrada"], Response::HTTP_NOT_FOUND);
            }

            $clasification->delete();
            return response(["message" => "Clasificacion Eliminada Exitosamente"], Response::HTTP_OK);
        }
        return response(["message" => "No estas autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
    }

    public function getClas()
    {
        try {
            $clasification = Clasification::all(); // Obtener las clasificaciones
            return response()->json($clasification, 200); // Los devuelve en formato JSON
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al obtener los directores.'], 500);
        }
    }

}

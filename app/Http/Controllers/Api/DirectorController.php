<?php

namespace App\Http\Controllers\Api;

use App\Models\Director;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DirectorController extends Controller
{
    
    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        if($profile->typeProfile == "2"){
        // Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'sex' => 'required|string|max:1',
            'age' => 'required|integer|min:0'
        ]);

        // Crear un nuevo director
        $director = new Director();
        $director->name = $request->name;
        $director->sex = $request->sex;
        $director->age = $request->age;
        $director->save();

        // Devolver una respuesta de éxito
        return response($director, Response::HTTP_CREATED);
        }
    }


    public function edit(Request $request, $id){
        $profile = Auth::user()->profile;

        if($profile->typeProfile == "2"){
            $director = Director::find($id);

            if(!$director){
                return response(["message" => "Director no econtrado"], Response::HTTP_NOT_FOUND);
            }

            if($request->name == "" && $request->sex == "" && $request->age ==""){
                return response(["message" => "No hubo nada que actualizar"], Response::HTTP_OK);
            }
            if($request->name){
                $director->name = $request->name;
             }
             if($request->sex){
                $director->sex = $request->sex;
              }
              if($request->age){
                $director->age = $request->age;
              }
             $director->save();
             return response(["data" => $director], Response::HTTP_OK);
        }
    }

    public function destroy($id){
        $profile = Auth::user()->profile;

        if($profile->typeProfile == "2"){
            $director = Director::find($id);

            if(!$director){
                return response(["message" => "Director no econtrado"], Response::HTTP_NOT_FOUND);
            }

            $director->delete();
            return response(["message" => "Director Eliminado Exitosamente"], Response::HTTP_OK);
        }
        return response(["message" => "No estas autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
    }

    public function getDirectors()
    {
        try {
            $directors = Director::all(); // Obtener todos los directores
            return response()->json($directors, 200); // Devolver los directores en formato JSON
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al obtener los directores.'], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $directors = Director::where('name', 'LIKE', "%$query%")->get();
            return response()->json($directors, 200);
        }

        return response()->json([], 200);
    }


}

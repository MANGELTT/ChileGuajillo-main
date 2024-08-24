<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('clasification_id');

        if (!$query) {
            $movies = Movie::all();
            return response(["data" => $movies], Response::HTTP_OK);
        }

        $movie = Movie::where('clasification_id', $query)->get();
        return response(["data" => $movie], Response::HTTP_OK);
    }

    public function show($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response(["message" => "Recurso no encontrado"], Response::HTTP_NOT_FOUND);
        }
        return response(["data" => $movie], Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $profile = Auth::user()->profile;

        if ($profile->typeProfile == "2") {
            try {
                Log::info('Request Data:', $request->all()); // Log de los datos de la solicitud

                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'releaseDate' => 'required|date',
                    'synopsis' => 'required|string',
                    'urlTrailer' => 'required|url',
                    'image' => 'required|url',
                    'clasification_id' => 'required|integer|exists:clasifications,id',
                    'director_id' => 'required|integer|exists:directors,id',
                    'user_id' => 'required|integer|exists:users,id',
                ]);

                $movie = new Movie();
                $movie->name = $validatedData['name'];
                $movie->releaseDate = $validatedData['releaseDate'];
                $movie->synopsis = $validatedData['synopsis'];
                $movie->urlTrailer = $validatedData['urlTrailer'];
                $movie->image = $validatedData['image'];
                $movie->clasification_id = $validatedData['clasification_id'];
                $movie->director_id = $validatedData['director_id'];
                $movie->user_id = $validatedData['user_id'];
                $movie->save();

                Log::info('Movie created successfully:', $movie->toArray()); // Log de la película creada

                return response(["data" => $movie], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                // Agregar registros detallados del error
                Log::error('Error al crear la película: ' . $e->getMessage());
                return response()->json(['error' => 'Hubo un error al crear la película. ' . $e->getMessage()], 500);
            }
        }
        return response(["message" => "No estás autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
    }

    public function edit(Request $request, $id)
    {
        $profile = Auth::user()->profile;

        if ($profile->typeProfile == "2") {
            $movie = Movie::find($id);

            if (!$movie) {
                return response(["message" => "Recurso no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'releaseDate' => 'nullable|date',
                'synopsis' => 'nullable|string',
                'urlTrailer' => 'nullable|url',
                'image' => 'nullable|url',
                'clasification_id' => 'nullable|integer|exists:clasifications,id',
                'director_id' => 'nullable|integer|exists:directors,id',
            ]);

            $movie->fill($validatedData);
            $movie->save();

            return response(["data" => $movie], Response::HTTP_OK);
        }
        return response(["message" => "No estás autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
    }

    public function destroy($id)
    {
        $profile = Auth::user()->profile;

        if ($profile->typeProfile == "2") {
            $movie = Movie::find($id);

            if (!$movie) {
                return response(["message" => "Recurso no encontrado"], Response::HTTP_NOT_FOUND);
            }

            $movie->delete();
            return response(["message" => "Recurso eliminado exitosamente"], Response::HTTP_OK);
        }
        return response(["message" => "No estás autorizado para realizar esta acción"], Response::HTTP_FORBIDDEN);
    }

    //Metodo Busqueda pelis
    public function search(Request $request)
    {
        $query = $request->input('query');
        $movies = Movie::search($query)->get();
        return response()->json($movies);
    }
}

<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Profile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;




class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|string',
            'profile_id' => 'required|exists:profiles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        //  // Verificar si el profile_id existe en la base de datos MySQL
         $profile = Profile::find($request->profile_id);
         if (!$profile) {
             return response()->json(['message' => 'Perfil no encontrado'], Response::HTTP_NOT_FOUND);
         }

         $movie = Movie::find($request->profile_id);
         if (!$movie) {
             return response()->json(['message' => 'Pelicula no encontrada'], Response::HTTP_NOT_FOUND);
         }


        $review = new Review();
        $review->review_id = Review::getNextSequenceValue('review_id');
        $review->movie_id = $request->movie_id;
        $review->profile_id = $request->profile_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return response()->json(['data' => $review], Response::HTTP_CREATED);
    }

        public function show($id)
    {
                // Verificar si el ID proporcionado es un entero
                if (!is_numeric($id)) {
                    return response()->json(['message' => 'ID inválido'], Response::HTTP_BAD_REQUEST);
                }
        
                $review = Review::where('review_id', (int)$id)->first();
        
                if (!$review) {
                    return response()->json(['message' => 'Reseña no encontrada'], Response::HTTP_NOT_FOUND);
                }
        
                return response()->json(['data' => $review], Response::HTTP_OK);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'movie_id' => 'required|string',
            'profile_id' => 'required|exists:profiles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::where('review_id', (int)$id)->first();

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $review->movie_id = $request->movie_id;
        $review->profile_id = $request->profile_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return response()->json(['data' => $review], Response::HTTP_OK);
    }

    public function getReviewsByMovie($movie_id)
{
    try {
        if (!is_numeric($movie_id)) {
            return response()->json(['message' => 'ID de película inválido'], Response::HTTP_BAD_REQUEST);
        }

        // Obtener las reseñas sin `with` para evitar el error
        $reviews = Review::where('movie_id', (string)$movie_id)->get();

        // Cargar manualmente el perfil y el nombre del usuario
        foreach ($reviews as $review) {
            $profile = Profile::find($review->profile_id);
            if ($profile) {
                $user = User::find($profile->user_id);
                if ($user) {
                    $review->user_name = $user->name; // Agregar el nombre del usuario a la reseña
                } else {
                    $review->user_name = null; // Si el usuario no existe
                }
            } else {
                $review->user_name = null; // Si el perfil no existe
            }
        }

        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'No se encontraron reseñas para esta película'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $reviews], Response::HTTP_OK);
    } catch (\Exception $e) {
        logger()->error("Error al obtener reseñas: " . $e->getMessage());
        return response()->json(['message' => 'Error interno del servidor'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}



    public function destroy($id)
    {
        $review = Review::where('review_id', (int)$id)->first();

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $review->delete();

        return response()->json(['message' => 'Reseña eliminada exitosamente'], Response::HTTP_OK);
    }

}

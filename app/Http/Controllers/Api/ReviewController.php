<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Profile;
use App\Models\Review;
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

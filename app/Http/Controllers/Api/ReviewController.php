<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        //  $profile = Profile::find($request->profile_id);
        //  if (!$profile) {
        //      return response()->json(['message' => 'Perfil no encontrado'], Response::HTTP_NOT_FOUND);
        //  }

        $review = new Review();
        $review->movie_id = $request->movie_id;
        $review->profile_id = $request->profile_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return response()->json(['data' => $review], Response::HTTP_CREATED);
    }
}

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClasificationController;
use App\Http\Controllers\Api\DirectorController;
use App\Http\Controllers\Api\GenderController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ProfileController as ApiProfileController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

//Route principal
Route::get('/',[AuthController::class,'Index']);

//Route para registrar un nuevo usuario
Route::post('/register',[AuthController::class,'Register']);

//Route para Logear un usuario
Route::post('/login',[AuthController::class,'Login']);

//Route para Logear un usuario
Route::get('/loggout',[AuthController::class,'Loggout']);




//Route con middleware para la proteccion de rutas
Route::group(['middleware' => ['auth:sanctum']], function (){

    //Route para obtener la información del usuario en sesión
    Route::get('/user-profile',[AuthController::class, 'GetUserProfile']);

    //Route para obtener todos los perfiles
    Route::get('/profile', [ApiProfileController::class,'index']);
    //Route para obtener un perfil en especifico
    Route::get('/profile/{id}', [ApiProfileController::class,'show']);
    //Route Eliminar un perfil ya existente
    Route::delete('/profile/{id}', [ApiProfileController::class,'destroy']);
    //Route crear un nuevo perfil
    Route::post('/profile', [ApiProfileController::class,'create']);
    //Route Actualizar un perfil ya existente
    Route::put('/profile/{id}', [ApiProfileController::class,'edit']);


    //Route Crear un nuevo recurso 
    Route::post('/movie', [MovieController::class,'create']);
    //Route Editar un recurso en especifico
    Route::put('/movie/{id}', [MovieController::class,'edit']);
    //Route Eliminar un recurso en especifico
    Route::delete('/movie/{id}', [MovieController::class,'destroy']);

    //Route Obtener Todos los generos
    Route::get('/gender', [GenderController::class,'index']);


    //Director
    //Crear
    Route::post('/director',[DirectorController::class,'store']);
    //Editar
    Route::put('/director/{id}',[DirectorController::class,'edit']);
    //Eliminar
    Route::delete('/director/{id}', [DirectorController::class,'destroy']);


    //Clasification
    //Crear
    Route::post('/clasification',[ClasificationController::class,'create']);
    //Editar
    Route::put('/clasificaciones/{id}',[ClasificationController::class,'edit']);
    //Eliminar
    Route::delete('/clasification/{id}', [ClasificationController::class,'destroy']);

    //Ruta Reseñas
    Route::post('/reviews',[ReviewController::class,'store']);

});

    //Ruta para obtener los directores
    Route::get('/director', [DirectorController::class, 'getDirectors']);
    Route::get('/director/search', [DirectorController::class, 'search']); //Julian arregla el buscador porfas
                                                                            //PD: Nunca lo arreglo :,(

    // //Route para obtener la información del usuario en sesión
    // Route::get('/user-profile',[AuthController::class, 'GetUserProfile']);
    
    //Ruta para obtener las Clasificaciones
    Route::get('/clasification', [ClasificationController::class, 'getClas']);

    //Route Obtener Todos los recursos
    Route::get('/movie', [MovieController::class,'index']);

    //Route buscar por peli por nombre
    Route::get('/movies/search', [MovieController::class, 'search']);

    //Route Obtener un recurso en especifico
    Route::get('/movie/{id}', [MovieController::class,'show']);

    //Route Obtener review especifica
    Route::get('/review/{id}', [ReviewController::class, 'show']);
    //Ruta para obtener todas las reseñas de una pelicula
    Route::get('movie/{movie_id}/reviews', [ReviewController::class, 'getReviewsByMovie']);
    //Editar
    Route::put('/review/{id}', [ReviewController::class, 'edit']);
    //Eliminar
    Route::delete('/review/{id}', [ReviewController::class, 'destroy']);



    

    

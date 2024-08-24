<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Requirements Para buscar pelis
use Maize\Searchable\HasSearch;

class Movie extends Model
{
    use HasFactory, HasSearch;

    protected $fillable = [
        'name', 
        'releaseDate', 
        'synopsis', 
        'urlTrailer', 
        'image',
        'clasification_id', 
        'director_id', 
        'user_id'
    ];

     // Define searchable fields
    public function getSearchableAttributes(): array
    {
        return [
            'name' => 10, // Peso de búsqueda
            'synopsis' => 8, // Peso de búsqueda
        ];
    }

    // Relación muchos a uno con la entidad Clasification
    public function clasification()
    {
        return $this->belongsTo(Clasification::class);
    }

    // Relación muchos a uno con la entidad Director
    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    // Relación muchos a uno con la entidad User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $hidden = [];
}

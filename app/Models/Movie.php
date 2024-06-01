<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

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

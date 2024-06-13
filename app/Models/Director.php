<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'sex', 
        'age'
    ];

    //Relación uno a muchos con la entidad Movie
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    protected $hidden = [];
}

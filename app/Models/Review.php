<?php
namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use MongoDB\Client;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Operation\FindOneAndUpdate;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'review';

    protected $fillable = [
        'review_id','movie_id', 'profile_id', 'rating', 'comment',  
    ];

    public static function getNextSequenceValue($sequenceName)
    {
        $client = new Client(env('MONGO_DSN', 'mongodb://127.0.0.1:27017'));
        $collection = $client->selectCollection('Review', 'counters');

        $sequenceDocument = $collection->findOneAndUpdate(
            ['_id' => $sequenceName],
            ['$inc' => ['sequence_value' => 1]],
            ['returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER, 'upsert' => true]
        );

        return $sequenceDocument->sequence_value;
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
}

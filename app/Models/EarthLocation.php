<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class EarthLocation extends Model
{
    protected $fillable = [
        'post_id',
        'place_name',
        'address',
        'latitude',
        'longitude',
    ];

public function post()
{
    return $this->belongsTo(Post::class);
}

}

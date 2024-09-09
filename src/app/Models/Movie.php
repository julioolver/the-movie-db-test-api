<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'provider',
        'title',
        'director',
        'synopsis',
        'duration',
        'year',
        'poster_path',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_movie_status')
            ->withPivot('watched', 'favorite', 'watch_later')
            ->withTimestamps();
    }
}

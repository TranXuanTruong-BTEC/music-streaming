<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'artist_id', 'release_date', 'spotify_id', 'total_tracks'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($album) {
            $album->tracks()->delete();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    //
    use SoftDeletes;

    protected $fillable =['title', 'genre', 'duration', 'director', 'description' ,'age_rating', 'poster', 'actived'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}

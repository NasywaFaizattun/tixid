<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //
    use SoftDeletes;

    // mendaftarkan detail data ( column ) agar data2 tsb bisa diis
    protected $fillable =['name', 'location'];

    // mendefinisikan relasi, karna schedule nya itu many jadi jamak (s)
    public function schedules() {
        // hasMany() : one to many
        // hasOne() : one to one
        return $this->hasMany(Schedules::class);
    }
}

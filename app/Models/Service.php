<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * Attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'url',
        'slug',
    ];

    /**
     * Define one to many relation with routes.
     */
    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}

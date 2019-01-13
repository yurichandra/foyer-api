<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    /**
     * Attributes that are mass assignable.
     */
    protected $fillable = [
        'service_id',
        'path',
        'method',
        'description',
        'slug',
        'aggregate',
        'target',
        'protected',
    ];

    /**
     * Casts attributes.
     */
    protected $casts = [
        'aggregate' => 'boolean',
        'protected' => 'boolean'
    ];

    /**
     * Define this class belongs to service in one many relation.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

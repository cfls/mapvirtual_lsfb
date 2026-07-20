<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'region',
        'excerpt',
        'description',
        'image_url',
        'cloudinary_public_id',
        'cloudinary_cloud_name',
        'pos_x',
        'pos_y',
        'latitude',
        'longitude',
        'sort_order',
    ];

    protected $casts = [
        'pos_x' => 'float',
        'pos_y' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'sort_order' => 'integer',
    ];

    /**
     * Scope ordered the way pins should read on screen (top to bottom).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}

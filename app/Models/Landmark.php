<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'region',
        'province',
        'lsfb_accessible',
        'qr_accessible',
        'age_range',
        'excerpt',
        'description',
        'image_url',
        'website_url',
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
        'lsfb_accessible' => 'boolean',
        'qr_accessible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope ordered the way pins should read on screen (top to bottom).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * "Get directions" link — built from the coordinates already on the
     * model, no extra field/data entry needed.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (! $this->latitude || ! $this->longitude) {
            return null;
        }

        return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
    }
}

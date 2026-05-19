<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteriorDesign extends Model
{
    protected $fillable = [
        'name',
        'image',
        'images',
        'status',
    ];

    protected $appends = [
        'image',
    ];

    /**
     * Get the first image path from the images array or fallback to image column.
     *
     * @return string|null
     */
    public function getImageAttribute()
    {
        $rawImages = $this->attributes['images'] ?? $this->attributes['image'] ?? null;
        if ($rawImages) {
            $decoded = json_decode($rawImages, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded[0] ?? null;
            }
            return $rawImages;
        }
        return null;
    }
}

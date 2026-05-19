<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagePinnedProject extends Model
{
    protected $fillable = [
        'page_slug',
        'display_order',
        'property_id',
        'property_name',
        'property_location',
        'property_builder',
    ];
}

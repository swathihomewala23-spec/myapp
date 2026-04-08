<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'property_cities';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function propertyPlaces()
    {
        return $this->hasMany(PropertyPlace::class, 'city_id');
    }
}

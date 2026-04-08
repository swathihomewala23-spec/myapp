<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'property_countries';
    protected $guarded = [];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    public function propertyPlaces()
    {
        return $this->hasMany(PropertyPlace::class, 'country_id');
    }
}

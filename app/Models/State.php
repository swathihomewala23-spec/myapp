<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'property_states';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }

    public function propertyPlaces()
    {
        return $this->hasMany(PropertyPlace::class, 'state_id');
    }
}

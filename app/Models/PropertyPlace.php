<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPlace extends Model
{
    protected $table = 'property_places';
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}

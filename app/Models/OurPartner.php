<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurPartner extends Model
{
    protected $fillable = [
        'name',
        'projects',
        'image',
        'status',
    ];
}

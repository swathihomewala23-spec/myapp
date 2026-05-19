<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyEnquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'property_id',
        'enquiry_status',
    ];
}

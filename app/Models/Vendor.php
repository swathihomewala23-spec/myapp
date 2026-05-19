<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use Notifiable;

    protected $table = 'vendors';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'username',
        'password',
        'details',
        'photo',
        'status',
        'amount',
        'vendor_request',
        'avg_rating',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
        'is_admin',
        'country',
        'state',
        'city',
        'zip_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}

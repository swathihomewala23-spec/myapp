<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';
    
    protected $fillable = [
        'website_logo',
        'website_favicon',
        'website_title',
        'website_description',
        'email_address',
        'contact_number',
        'address',
        'enable_smtp',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'from_mail',
        'from_name',
    ];

    protected $casts = [
        'enable_smtp' => 'boolean',
    ];

    public static function getInstance()
    {
        return self::first() ?? new self();
    }
}

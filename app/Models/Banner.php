<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'original_name',
        'unique_name',
        'file_path',
        'link',
        'type',
        'status',
    ];
}
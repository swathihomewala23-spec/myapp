<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function info()
    {
        $settings = WebsiteSetting::getInstance();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }
}
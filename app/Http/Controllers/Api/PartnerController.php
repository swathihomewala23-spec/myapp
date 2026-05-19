<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OurPartner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = OurPartner::where('status', true)->get();

        return response()->json([
            'success' => true,
            'data' => $partners
        ]);
    }
}
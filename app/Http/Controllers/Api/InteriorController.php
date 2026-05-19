<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InteriorDesign;
use Illuminate\Http\Request;

class InteriorController extends Controller
{
    public function home(Request $request)
    {
        $perPage = $request->input('pagenation', 10);
        
        $interiors = InteriorDesign::where(function ($query) {
            $query->where('status', 'active')
                  ->orWhere('status', '1')
                  ->orWhere('status', 1)
                  ->orWhere('status', true);
        })->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $interiors
        ]);
    }
}
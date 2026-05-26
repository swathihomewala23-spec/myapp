<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Schema;

class BannerController extends Controller
{
    public function index()
    {
        $query = Banner::query();

        if (Schema::hasColumn('banners', 'status')) {
            $query->where('status', true);
        }

        $banners = $query->get()->map(function (Banner $banner) {
            $banner->image = $banner->image ?? $banner->file_path ?? null;
            return $banner;
        });

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }
}

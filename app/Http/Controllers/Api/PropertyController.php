<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Throwable;

class PropertyController extends Controller
{
    private function resolvePropertiesConnection(): ?string
    {
        $default = config('database.default');

        try {
            if (Schema::connection($default)->hasTable('properties')) {
                return $default;
            }
        } catch (Throwable $e) {
            // ignore
        }

        $env = @parse_ini_file(base_path('.env'), false, INI_SCANNER_RAW) ?: [];
        $dbName = $env['DB_DATABASE'] ?? null;
        $dbUser = $env['DB_USERNAME'] ?? null;
        $dbHost = $env['DB_HOST'] ?? '127.0.0.1';
        $dbPort = $env['DB_PORT'] ?? '3306';

        if (! $dbName || ! $dbUser) {
            return null;
        }

        $connectionName = 'mysql_properties_fallback';
        config([
            "database.connections.$connectionName" => [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $env['DB_PASSWORD'] ?? '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],
        ]);

        DB::purge($connectionName);

        try {
            return Schema::connection($connectionName)->hasTable('properties') ? $connectionName : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    private function buildPropertyQuery(Request $request)
    {
        $connection = $this->resolvePropertiesConnection();
        if (! $connection) {
            return null;
        }

        $query = DB::connection($connection)->table('properties');

        if ($request->filled('city') && is_array($request->input('city'))) {
            $query->whereIn('city', $request->input('city'));
        }

        if ($request->filled('properyType')) {
            if (Schema::connection($connection)->hasColumn('properties', 'property_type')) {
                $query->where('property_type', $request->input('properyType'));
            } elseif (Schema::connection($connection)->hasColumn('properties', 'type')) {
                $query->where('type', $request->input('properyType'));
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            if (is_array($search)) {
                $search = implode(' ', $search);
            }
            if ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('property_name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('full_address', 'like', "%{$search}%");
                });
            }
        }

        if ($request->filled('min_price') && Schema::connection($connection)->hasColumn('properties', 'price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price') && Schema::connection($connection)->hasColumn('properties', 'price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $query->orderByDesc('id');

        return $query;
    }

    private function mapProperty($property)
    {
        $mainImage = $property->display_image ?? $property->main_property_image ?? null;
        if ($mainImage && ! preg_match('/^https?:\/\//', $mainImage)) {
            $mainImage = url('storage/' . ltrim($mainImage, '/'));
        }

        return [
            'id' => $property->id,
            'title' => $property->property_name ?? ($property->content_title ?? 'Untitled Property'),
            'description' => $property->description ?? ($property->content_title ?? ''),
            'mainImage' => $mainImage ?: 'https://via.placeholder.com/640x360?text=No+Image',
            'priceRange' => $property->price_range ?? ($property->price ?? 'Contact for price'),
            'location' => $property->full_address ?? $property->city ?? '',
            'details' => [
                'possessionDate' => $property->possession_date ?? ($property->possessionDate ?? 'TBD'),
                'averagePrice' => $property->price_range ?? ($property->price ?? 'N/A'),
                'possessionStatus' => $property->status ?? ($property->approve_status ?? 'Available'),
            ],
            'isFavourites' => false,
        ];
    }

    public function filter(Request $request)
    {
        $query = $this->buildPropertyQuery($request);
        if (! $query) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to find properties table',
                'data' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'total' => 0,
                ],
            ], 500);
        }

        $perPage = max(1, (int) $request->input('per_page', 10));
        $page = max(1, (int) $request->input('page', 1));

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        $data = $results->through(function ($property) {
            return $this->mapProperty($property);
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $results->currentPage(),
                'total_pages' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
            ],
            'dynamicHeading' => $results->total() . ' results | Properties',
        ]);
    }

    public function show(Request $request, $id)
    {
        $connection = $this->resolvePropertiesConnection();
        if (! $connection) {
            return response()->json(['success' => false, 'message' => 'Unable to find properties table'], 500);
        }

        $property = DB::connection($connection)->table('properties')->where('id', $id)->first();
        if (! $property) {
            return response()->json(['success' => false, 'message' => 'Property not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->mapProperty($property),
        ]);
    }
}

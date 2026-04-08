<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            [
                'name' => 'Swimming Pool', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 6c.6.5 1.2 1 2.5 1C5.8 7 7 6 8.5 6s2.7 1 4 1 2.7-1 4-1 2.7 1 4 1 1.9-.4 2.5-1M2 12c.6.5 1.2 1 2.5 1 1.3 0 2.5-1 4-1s2.7 1 4 1 2.7-1 4-1 2.7 1 4 1 1.9-.4 2.5-1M2 18c.6.5 1.2 1 2.5 1 1.3 0 2.5-1 4-1s2.7 1 4 1 2.7-1 4-1 2.7 1 4 1 1.9-.4 2.5-1"/></svg>',
                'status' => 'Completed',
                'serial_number' => 1
            ],
            [
                'name' => 'Gym & Fitness', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6.5 6.5 11 11M6.5 17.5l11-11M2 21l7-7M15 9l7-7M3 8c0-1.5 1-3 3-3s3 1.5 3 3-1.5 3-3 3-3-1.5-3-3Zm12 8c0-1.5 1-3 3-3s3 1.5 3 3-1.5 3-3 3-3-1.5-3-3Z"/></svg>',
                'status' => 'Completed',
                'serial_number' => 2
            ],
            [
                'name' => '24/7 Security', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM12 8v4M12 16h.01"/></svg>',
                'status' => 'Completed',
                'serial_number' => 3
            ],
            [
                'name' => 'High-speed WiFi', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13a10 10 0 0 1 14 0M8.5 16.5a5 5 0 0 1 7 0M12 20h.01"/></svg>',
                'status' => 'Completed',
                'serial_number' => 4
            ],
            [
                'name' => 'Car Parking', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/></svg>',
                'status' => 'Processing',
                'serial_number' => 5
            ],
            [
                'name' => 'Landscaped Garden', 
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 10V2M3.5 10c0-4.142 3.806-7.5 8.5-7.5s8.5 3.358 8.5 7.5c0 4.142-3.806 7.5-8.5 7.5s-8.5-3.358-8.5-7.5zM12 10c-2.485 0-4.5 1.567-4.5 3.5s2.015 3.5 4.5 3.5 4.5-1.567 4.5-3.5-2.015-3.5-4.5-3.5zM12 17v5"/></svg>',
                'status' => 'Completed',
                'serial_number' => 6
            ],
        ];

        foreach ($amenities as $amenity) {
            \App\Models\Amenity::create($amenity);
        }
    }
}

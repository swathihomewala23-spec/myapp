<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'type' => 'Residential',
                'name' => 'Apartments',
                'status' => 'Active',
                'serial_number' => 1,
            ],
            [
                'type' => 'Residential',
                'name' => 'Independent House / Villa',
                'status' => 'Active',
                'serial_number' => 2,
            ],
            [
                'type' => 'Commercial',
                'name' => 'Office Space',
                'status' => 'Active',
                'serial_number' => 3,
            ],
            [
                'type' => 'Commercial',
                'name' => 'Retail Shop',
                'status' => 'Active',
                'serial_number' => 4,
            ],
            [
                'type' => 'Plot',
                'name' => 'Residential Plot',
                'status' => 'Active',
                'serial_number' => 5,
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

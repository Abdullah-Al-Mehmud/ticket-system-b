<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // ['name' => 'Music', 'status' => 'active'],
            ['name' => 'Sports', 'status' => 'inactive'],
            ['name' => 'Technology', 'status' => 'active'],
            ['name' => 'Theater', 'status' => 'inactive'],
            ['name' => 'Comedy', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

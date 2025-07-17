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
            ['name' => 'Music', 'status' => 'active'],
            ['name' => 'Sports', 'status' => 'inactive'],
            ['name' => 'Technology', 'status' => 'active'],
            ['name' => 'Theater', 'status' => 'inactive'],
            ['name' => 'Comedy', 'status' => 'active'],
            ['name' => 'Art', 'status' => 'active'],
            ['name' => 'Education', 'status' => 'inactive'],
            ['name' => 'Business', 'status' => 'active'],
            ['name' => 'Gaming', 'status' => 'active'],
            ['name' => 'Health', 'status' => 'inactive'],
        ];

        foreach ($categories as $cat) {
            $existing = Category::where('name', $cat['name'])->first();

            if (!$existing) {
                Category::create($cat);
            }
        }
    }
}

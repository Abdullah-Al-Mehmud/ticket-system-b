<?php

namespace Database\Seeders;

use App\Models\Category;
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
            ['name' => 'Sports', 'status' => 'active'],
            ['name' => 'Technology', 'status' => 'active'],
            ['name' => 'Theater', 'status' => 'inactive'],
          
        ];

        foreach ($categories as $cat) {
            $existing = Category::where('name', $cat['name'])->first();

            if (!$existing) {
                Category::create($cat);
            }
        }
    }
}

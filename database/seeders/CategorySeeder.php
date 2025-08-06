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
            ['name' => 'Comedy', 'status' => 'active'],
            ['name' => 'Art', 'status' => 'inactive'],
            ['name' => 'Education', 'status' => 'active'],
            ['name' => 'Business', 'status' => 'active'],
            ['name' => 'Gaming', 'status' => 'active'],
            ['name' => 'Health & Wellness', 'status' => 'inactive'],
            ['name' => 'Food & Drink', 'status' => 'active'],
            ['name' => 'Fashion', 'status' => 'inactive'],
            ['name' => 'Travel & Outdoor', 'status' => 'active'],
            ['name' => 'Film & Media', 'status' => 'active'],
            ['name' => 'Science & Tech', 'status' => 'active'],
            ['name' => 'Literature', 'status' => 'inactive'],
            ['name' => 'Politics', 'status' => 'inactive'],
            ['name' => 'Finance', 'status' => 'active'],
            ['name' => 'Environment', 'status' => 'active'],
            ['name' => 'Photography', 'status' => 'inactive'],
        ];

        foreach ($categories as $cat) {
            $existing = Category::where('name', $cat['name'])->first();

            if (!$existing) {
                Category::create($cat);
            }
        }
    }
}

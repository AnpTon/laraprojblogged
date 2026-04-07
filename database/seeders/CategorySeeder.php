<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        Category::create([
            'id' => 10,
            'name' => 'Uncategorized',
            'description' => 'Posts without a specific category',
        ]);
        
        $categories = [
            ['name' => 'Technology', 'description' => 'Posts about tech, programming, and software'],
            ['name' => 'Cooking', 'description' => 'Recipes and cooking tips'],
            ['name' => 'Lifestyle', 'description' => 'Daily life, wellness, and personal growth'],
            ['name' => 'Travel', 'description' => 'Destinations, tips, and travel stories'],
            ['name' => 'Health', 'description' => 'Fitness, nutrition, and mental health'],
            ['name' => 'Business', 'description' => 'Entrepreneurship, marketing, and finance'],
            ['name' => 'Education', 'description' => 'Learning, teaching, and study tips'],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
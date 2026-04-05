<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('name', 'admin')->first();
        $testUser = User::where('email', 'dwehner@example.com')->first();
        $userAnother= User::where('email', 'laurianne69@example.net')->first();
        
        $techCategory = Category::where('name', 'Technology')->first();
        $cookingCategory = Category::where('name', 'Cooking')->first();
        
        $tag1 = Tag::firstOrCreate(['name' => 'laravel']);
        $tag2 = Tag::firstOrCreate(['name' => 'livewire']);
        $tag3 = Tag::firstOrCreate(['name' => 'recipes']);
        
        $post1 = Post::create([
            'title' => 'Getting Started with Laravel Livewire',
            'body' => 'Livewire makes building dynamic interfaces simple. No JavaScript required. Just PHP and Blade...',
            'user_id' => $admin->id,
            'category_id' => $techCategory->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $post1->tags()->attach([$tag1->id, $tag2->id]);
        
        $post2 = Post::create([
            'title' => 'Homemade Sourdough Bread Recipe',
            'body' => 'Making sourdough at home is easier than you think. Here is my step-by-step guide...',
            'user_id' => $admin->id,
            'category_id' => $cookingCategory->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $post2->tags()->attach($tag3->id);
        
        $post3 = Post::create([
            'title' => 'My First Week Learning Laravel',
            'body' => 'After switching from Django, I have to say Laravel is a breath of fresh air...',
            'user_id' => $testUser->id,
            'category_id' => $techCategory->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $post3->tags()->attach($tag1->id);
        
        Comment::create([
            'body' => 'Great article! Very helpful.',
            'user_id' => $userAnother->id,
            'post_id' => $post1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Comment::create([
            'body' => 'Thanks for sharing this tutorial.',
            'user_id' => $admin->id,
            'post_id' => $post1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Comment::create([
            'body' => 'I tried this recipe and it turned out great!',
            'user_id' => $testUser->id,
            'post_id' => $post2->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
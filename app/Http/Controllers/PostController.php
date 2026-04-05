<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'category', 'comments.user']);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['user_id'] = auth()->id();

        $post = Post::create($validated);

        $tagsJson = $request->input('tags', '[]');
        $tagNames = is_array($tagsJson) ? $tagsJson : json_decode($tagsJson, true) ?? [];
        $tagIds = $this->syncTags($tagNames);
        $post->tags()->sync($tagIds);

        return redirect()->route('dashboard')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $post->load('tags');
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post->update($validated);

        $tagsJson = $request->input('tags', '[]');
        $tagNames = is_array($tagsJson) ? $tagsJson : json_decode($tagsJson, true) ?? [];
        $tagIds = $this->syncTags($tagNames);
        $post->tags()->sync($tagIds);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    private function syncTags(array $tagNames): array
    {
        $tagIds = [];
        foreach ($tagNames as $name) {
            $name = trim($name);
            if (empty($name)) continue;
            $tag = Tag::firstOrCreate(['name' => strtolower($name)]);
            $tagIds[] = $tag->id;
        }
        return $tagIds;
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully.');
    }
}

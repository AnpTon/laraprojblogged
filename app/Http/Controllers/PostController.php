<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function myPosts()
    {
        $myPosts = Post::with(['user', 'category'])->where('user_id', auth()->id())->latest()->get();
        $myComments = Comment::with(['user', 'post'])->where('user_id', auth()->id())->latest()->get();
        $myComments->load('post');
        return view('posts.my-posts', compact('myPosts', 'myComments'));
    }

    public function search()
    {
        $categories = Category::all();
        $selectedTags = [];
        return view('posts.search', compact('categories', 'selectedTags'));
    }

    public function searchResults(Request $request)
    {
        $query = Post::with(['user', 'category', 'tags']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('body', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('tags')) {
            $tagNames = is_array($request->tags) ? $request->tags : json_decode($request->tags, true) ?? [];
            if (!empty($tagNames)) {
                $query->whereHas('tags', function($q) use ($tagNames) {
                    $q->whereIn('name', $tagNames);
                });
            }
        }

        $posts = $query->latest()->get();
        $categories = Category::all();
        $selectedTags = is_array($request->tags) ? $request->tags : json_decode($request->tags, true) ?? [];
        
        return view('posts.search', compact('posts', 'categories', 'selectedTags'));
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
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'You can only edit your own posts.');
        }

        $categories = Category::all();
        $post->load('tags');
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'You can only edit your own posts.');
        }

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
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'You can only delete your own posts.');
        }

        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully.');
    }
}

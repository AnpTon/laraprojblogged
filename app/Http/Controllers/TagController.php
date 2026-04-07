<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->get();
        return view('tags.index', compact('tags'));
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $tags = Tag::where('name', 'like', $query . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($tags);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
    }
}
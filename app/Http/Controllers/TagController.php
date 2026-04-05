<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
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
}
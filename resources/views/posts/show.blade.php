<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Back to Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-2">{{ $post->title }}</h1>
                    <div class="text-sm text-gray-500 mb-4">
                        By {{ $post->user->name }} in {{ $post->category->name }} &bull; {{ $post->created_at->format('M d, Y') }}
                    </div>
                    <div class="prose max-w-none">
                        {{ $post->body }}
                    </div>
                    <div class="mt-4">
                        <strong>Tags:</strong>
                        @foreach($post->tags as $tag)
                            <span class="inline-block bg-gray-200 rounded px-2 py-1 text-sm mr-2">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('posts.edit', $post) }}" class="px-3 py-1 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 border border-yellow-600">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white font-semibold rounded hover:bg-red-600 border border-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Comments ({{ $post->comments->count() }})</h2>
                    
                    @if($post->comments->isEmpty())
                        <p class="text-gray-500">No comments yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="border-b pb-4">
                                    <div class="text-sm text-gray-500 mb-1">
                                        <strong>{{ $comment->user->name }}</strong> &bull; {{ $comment->created_at->format('M d, Y H:i') }}
                                    </div>
                                    <p class="text-gray-900">{{ $comment->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

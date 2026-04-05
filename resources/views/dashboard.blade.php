<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('posts.create') }}" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">
                    Create New Post
                </a>
            </div>

            @if($posts->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-500">No posts yet.</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $post->title }}</h3>
                                    <span class="text-sm text-gray-500">{{ $post->category->name }}</span>
                                </div>
                                <div class="text-sm text-gray-500 mb-3">
                                    By {{ $post->user->name }} &bull; 
                                    @if($post->created_at != $post->updated_at)
                                        Updated {{ $post->updated_at->format('M d, Y') }}
                                    @else
                                        Created {{ $post->created_at->format('M d, Y') }}
                                    @endif
                                </div>
                                @if($post->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
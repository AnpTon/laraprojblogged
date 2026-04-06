<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Posts & Comments') }}
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
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Back to Dashboard
                </a>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">My Posts</h3>
                @if($myPosts->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <p class="text-gray-500">You haven't created any posts yet.</p>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($myPosts as $post)
                            <a href="{{ route('posts.show', $post) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $post->title }}</h4>
                                        <span class="text-sm text-gray-500">{{ $post->category->name }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-3">
                                        @if($post->created_at != $post->updated_at)
                                            Updated {{ $post->updated_at->format('M d, Y') }}
                                        @else
                                            Created {{ $post->created_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">My Comments</h3>
                @if($myComments->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <p class="text-gray-500">You haven't commented on any posts yet.</p>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($myComments as $comment)
                            <a href="{{ route('posts.show', $comment->post) }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                                <div class="p-6">
                                    <div class="text-sm text-gray-500 mb-2">
                                        On: <span class="font-semibold">{{ $comment->post->title }}</span> &bull; {{ $comment->created_at->format('M d, Y H:i') }}
                                    </div>
                                    <p class="text-gray-900">{{ $comment->body }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
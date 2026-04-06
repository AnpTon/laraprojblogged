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

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
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
                        @if($post->user_id === auth()->id())
                            <a href="{{ route('posts.edit', $post) }}" class="px-3 py-1 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 border border-yellow-600">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white font-semibold rounded hover:bg-red-600 border border-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @elseif(auth()->user()->isAdmin())
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white font-semibold rounded hover:bg-red-600 border border-red-600" onclick="return confirm('Are you sure?')">Delete (Admin)</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Comments ({{ $post->comments->count() }})</h2>
                        <button onclick="openCommentModal()" class="px-3 py-1 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">
                            Write Comment
                        </button>
                    </div>
                    
                    @if($post->comments->isEmpty())
                        <p class="text-gray-500">No comments yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="border-b pb-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="text-sm text-gray-500">
                                            <strong>{{ $comment->user->name }}</strong> &bull; {{ $comment->created_at->format('M d, Y H:i') }}
                                        </div>
                                        @if($comment->user_id === auth()->id())
                                            <div class="flex gap-2">
                                                <button onclick="openEditCommentModal({{ $comment->id }}, '{{ addslashes($comment->body) }}')" class="text-yellow-600 hover:text-yellow-900 text-sm">Edit</button>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Delete this comment?')">Delete</button>
                                                </form>
                                            </div>
                                        @elseif(auth()->user()->isAdmin())
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Delete this comment?')">Delete (Admin)</button>
                                            </form>
                                        @endif
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

    <div id="comment-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Write Comment</h3>
            <form id="comment-form" action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="mb-4">
                    <textarea name="body" id="comment-body" rows="4" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Write your comment..." required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCommentModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">Post Comment</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-comment-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Edit Comment</h3>
            <form id="edit-comment-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <textarea name="body" id="edit-comment-body" rows="4" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditCommentModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCommentModal() {
            document.getElementById('comment-modal').classList.remove('hidden');
            document.getElementById('comment-body').focus();
        }

        function closeCommentModal() {
            document.getElementById('comment-modal').classList.add('hidden');
            document.getElementById('comment-body').value = '';
        }

        function openEditCommentModal(commentId, body) {
            document.getElementById('edit-comment-modal').classList.remove('hidden');
            document.getElementById('edit-comment-body').value = body.replace(/\\'/g, "'");
            document.getElementById('edit-comment-form').action = '/comments/' + commentId;
        }

        function closeEditCommentModal() {
            document.getElementById('edit-comment-modal').classList.add('hidden');
        }

        document.getElementById('comment-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommentModal();
            }
        });

        document.getElementById('edit-comment-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditCommentModal();
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Back to Posts
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category_id" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                            <textarea name="body" id="body" rows="8" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tag-input" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <div class="relative">
                                <input type="text" id="tag-input" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Type a tag and press Enter">
                                <div id="tag-suggestions" class="hidden absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                </div>
                            </div>
                            <div id="selected-tags" class="flex flex-wrap gap-2 mt-2">
                            </div>
                            <input type="hidden" name="tags" id="tags-hidden" value="">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">
                            Create Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const selectedTags = [];
        const tagInput = document.getElementById('tag-input');
        const tagSuggestions = document.getElementById('tag-suggestions');
        const selectedTagsContainer = document.getElementById('selected-tags');
        const tagsHidden = document.getElementById('tags-hidden');

        function renderSelectedTags() {
            selectedTagsContainer.innerHTML = selectedTags.map(tag => `
                <span class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-sm">
                    #${tag}
                    <button type="button" class="ml-1 text-indigo-600 hover:text-indigo-900" onclick="removeTag('${tag}')">&times;</button>
                </span>
            `).join('');
            tagsHidden.value = JSON.stringify(selectedTags);
        }

        function removeTag(tag) {
            const index = selectedTags.indexOf(tag);
            if (index > -1) {
                selectedTags.splice(index, 1);
                renderSelectedTags();
            }
        }

        tagInput.addEventListener('keydown', async function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = tagInput.value.trim().toLowerCase();
                if (value && !selectedTags.includes(value)) {
                    selectedTags.push(value);
                    renderSelectedTags();
                }
                tagInput.value = '';
                tagSuggestions.classList.add('hidden');
            }
        });

        tagInput.addEventListener('input', async function() {
            const query = tagInput.value.trim().toLowerCase();
            if (query.length < 1) {
                tagSuggestions.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/tags/search?q=${encodeURIComponent(query)}`);
                const tags = await response.json();

                if (tags.length === 0) {
                    tagSuggestions.classList.add('hidden');
                    return;
                }

                const filteredTags = tags.filter(t => !selectedTags.includes(t.name));
                if (filteredTags.length === 0) {
                    tagSuggestions.classList.add('hidden');
                    return;
                }

                tagSuggestions.innerHTML = filteredTags.map(tag => `
                    <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectTag('${tag.name}')">
                        #${tag.name}
                    </div>
                `).join('');
                tagSuggestions.classList.remove('hidden');
            } catch (err) {
                console.error(err);
            }
        });

        function selectTag(tag) {
            if (!selectedTags.includes(tag)) {
                selectedTags.push(tag);
                renderSelectedTags();
            }
            tagInput.value = '';
            tagSuggestions.classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!tagInput.contains(e.target) && !tagSuggestions.contains(e.target)) {
                tagSuggestions.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
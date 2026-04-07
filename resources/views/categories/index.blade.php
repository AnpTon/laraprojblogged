<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(Auth::user()->isAdmin())
            <div class="mb-6">
                <button onclick="openCreateModal()" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">
                    Create New Category
                </button>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($categories->isEmpty())
                        <p class="text-gray-500">No categories yet.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    @if(Auth::user()->isAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 text-gray-900">{{ $category->name }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $category->description ?? '-' }}</td>
                                        @if(Auth::user()->isAdmin())
                                        <td class="px-6 py-4">
                                            @if($category->id !== 10)
                                            <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</button>
                                            <button onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->posts_count }})" class="text-red-600 hover:text-red-900">Delete</button>
                                            @else
                                            <span class="text-gray-400 text-sm">Protected</span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="create-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Create Category</h3>
            <form id="create-form" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="create-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="create-name" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="create-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="create-description" rows="4" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">Create</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Edit Category</h3>
            <form id="edit-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="edit-name" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit-description" rows="4" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded hover:bg-gray-900 shadow-sm">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
            document.getElementById('create-name').focus();
        }

        function closeCreateModal() {
            document.getElementById('create-modal').classList.add('hidden');
            document.getElementById('create-name').value = '';
            document.getElementById('create-description').value = '';
        }

        function openEditModal(id, name, description) {
            document.getElementById('edit-modal').classList.remove('hidden');
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-description').value = description.replace(/\\'/g, "'");
            document.getElementById('edit-form').action = '/categories/' + id;
            document.getElementById('edit-name').focus();
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        document.getElementById('create-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });

        document.getElementById('edit-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        function openDeleteModal(id, name, postsCount) {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-category-name').textContent = name;
            const deleteForm = document.getElementById('delete-form');
            deleteForm.action = '/categories/' + id;
            
            if (postsCount > 0) {
                document.getElementById('delete-warning').classList.remove('hidden');
                document.getElementById('delete-warning').textContent = 'Warning: ' + postsCount + ' post(s) are using this category. They will be moved to "Uncategorized".';
            } else {
                document.getElementById('delete-warning').classList.add('hidden');
            }
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>

    <div id="delete-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">Delete Category</h3>
            <p class="mb-4">Are you sure you want to delete "<span id="delete-category-name"></span>"?</p>
            <p id="delete-warning" class="mb-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded hidden"></p>
            <form id="delete-form" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 border border-red-600">Delete</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
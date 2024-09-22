<div class="container mx-auto mt-5">
    <div class="card bg-white shadow rounded-lg">
        <div class="card-header flex justify-between items-center p-4 bg-gray-100 border-b">
            <h3 class="text-lg font-semibold">Brand Management</h3>
            <!-- Button to open modal -->
            <button wire:click="openModal" class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                New Brand
            </button>
        </div>

        <div class="card-body p-4">
            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" class="form-control w-full p-2 border rounded-md" placeholder="Search..." wire:model.live="searchTerm">
            </div>

            <!-- Rows per Page -->
            <div class="mb-3 flex items-center space-x-2">
                <label>Show</label>
                <select wire:model.live="perPage" class="form-control w-auto p-2 border rounded-md">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
                <label>entries</label>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $brand->id }}</td>
                                <td class="px-4 py-2">{{ $brand->name }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <button wire:click="edit({{ $brand->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">Edit</button>
                                    <button wire:click.prevent="delete({{ $brand->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center px-4 py-2">No brands found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Light Theme Pagination -->
            <div class="mt-4 flex justify-center">
                {{ $brands->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($isModalOpen)
        <div id="brand-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-lg p-6">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 border-b">
                        <h5 class="text-xl font-semibold">{{ $brandId ? 'Edit Brand' : 'Create Brand' }}</h5>
                        <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 1l6 6m0 0l6 6M7 7L1 13M7 7l6-6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="p-4 space-y-4">
                        <div class="form-group">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                            <input type="text" wire:model="name" id="name" class="form-control block w-full p-2 border border-gray-300 rounded-lg">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center p-4 border-t">
                        <button wire:click="createOrUpdateBrand()" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">
                            {{ $brandId ? 'Update' : 'Create' }}
                        </button>
                        <button wire:click="closeModal" class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            @this.on('swal', (event) => {
                const data = event;
                swal.fire({
                    icon: data[0]['icon'],
                    title: data[0]['title'],
                    text: data[0]['text'],
                });
            });

            @this.on('delete-prompt', (event) => {
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to delete this record, this action is irreversible',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('goOn-Delete');
                        @this.on('deleted', () => {
                            swal.fire({
                                title: 'Deleted',
                                text: 'Your record has been deleted',
                                icon: 'success',
                            });
                        });
                    }
                });
            });
        });
    </script>
</div>

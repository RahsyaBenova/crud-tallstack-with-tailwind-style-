<div class="container mx-auto mt-5">
    <div class="card bg-white shadow rounded-lg">
        <div class="card-header flex justify-between items-center p-4 bg-gray-100 border-b">
            <h3 class="text-lg font-semibold">List Products</h3>
            <a href="{{ route('products.create') }}" class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">New Product</a>
        </div>
        
        <div class="card-body p-4">
            <!-- Notifications -->
            @if (session('success'))
                <div class="alert alert-success p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded-md">
                    {{ session('success') }}
                </div>
                <script>
                    Swal.fire({
                        title: "Success!",
                        text: "{{ session('success') }}",
                        icon: "success"
                    });
                </script>
            @endif

            @if (session('error'))
                <div class="alert alert-danger p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" class="form-control w-full p-2 border rounded-md" placeholder="Search Product Name..." wire:model.live="searchTerm">
            </div>

            <!-- Rows per Page -->
            <div class="mb-3 flex items-center space-x-2">
                <label>Show</label>
                <select wire:model.live="perPage" class="form-control w-auto p-2 border rounded-md">
                    <option value="5">5</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                </select>
                <label>entries</label>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">SKU</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Brand</th>
                            <th class="px-4 py-2">Categories</th>
                            <th class="px-4 py-2">Stock</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Image</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $product->id }}</td>
                                <td class="px-4 py-2">{{ $product->sku }}</td>
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2">{{ $product->brand->name ?? '' }}</td>
                                <td class="px-4 py-2">{{ implode(', ', $product->categories->pluck('name')->toArray()) }}</td>
                                <td class="px-4 py-2">{{ $product->stock }}</td>
                                <td class="px-4 py-2">{{ $product->price }}</td>
                                <td class="px-4 py-2">
                                    <img src="{{ Storage::url($product->image) }}" class="w-32 h-32 object-cover rounded-md" alt="Product Image">
                                </td>
                                <td class="px-4 py-2 prose">{!! $product->description !!}</td>

                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">Edit</a>
                                    <button wire:click="deleteProduct({{ $product->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center px-4 py-2">No Record Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="mt-4 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>

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

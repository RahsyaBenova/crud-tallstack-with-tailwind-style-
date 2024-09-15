<div>
    <div class="card mt-5">
        <div class="card-header">
            <h3>List Products</h3>
        </div>
        <div class="card-body">
            <!-- Notifications -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <script>Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success"
                  });</script>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
             <!-- Search Bar -->
             <div class="mb-3">
                <input type="text" class="form-control" placeholder="Search Product Name..." wire:model.live="searchTerm">
            </div>

            <!-- Rows per Page -->
            <div class="mb-3 d-flex align-items-center">
                <label class="mr-2">Show</label>
                <select wire:model.live="perPage" class="form-control d-inline-block" style="width: auto;">
                    <option value="5">5</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                </select>
                <label class="ml-2">entries</label>
            </div>
            <!-- New Product Button -->
            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">New Product</a>

            <!-- Data Table -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Categories</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->brand->name ?? '' }}</td>
                            <td>{{ implode(', ', $product->categories->pluck('name')->toArray()) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->price }}</td>
                            <td><img src="{{ Storage::url($product->image) }}" class="img-thumbnail" style="width:200px"></td>
                            <td>{!! $product->description !!}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" wire:click="deleteProduct({{ $product->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No Record Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:navigated',()=>{
    
            @this.on('swal',(event)=>{
                const data=event
                swal.fire({
                    icon:data[0]['icon'],
                    title:data[0]['title'],
                    text:data[0]['text'],
                })
            })
    
            @this.on('delete-prompt',(event)=>{
                swal.fire({
                    title:'Are you sure?',
                    text:'You are about to delete this record, this action is irreversible',
                    icon:'warning',
                    showCancelButton:true,
                    confirmButtonColor:'#3085d6',
                    showCancelButtonColor:'#d33',
                    confirmButtonText:'Yes, Delete it!',
                }).then((result)=>{
                    if(result.isConfirmed){
                        @this.dispatch('goOn-Delete')
    
                        @this.on('deleted',(event)=>{
                           swal.fire({
                            title:'Deleted',
                            text:'Your record has been deleted',
                            icon:'success',
                           })
                        })
                    }
                })
            })
    
    
        })
    </script>
</div>

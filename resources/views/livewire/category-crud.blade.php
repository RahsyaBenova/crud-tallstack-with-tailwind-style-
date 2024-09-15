<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Category Management</h3>
            <button wire:click="openModal" class="btn btn-primary">New Category</button>
        </div>
        <div class="card-body">
            <!-- Flash message -->
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            
            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Search..." wire:model.live="searchTerm">
            </div>

            <!-- Rows per Page -->
            <div class="mb-3 d-flex align-items-center">
                <label class="mr-2">Show</label>
                <select wire:model.live="perPage" class="form-control d-inline-block" style="width: auto;">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
                <label class="ml-2">entries</label>
            </div>

            <!-- Category List -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <button wire:click="edit({{ $category->id }})" class="btn btn-info">Edit</button>
                                <button wire:click="delete({{ $category->id }})" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

             <!-- Pagination Links -->
             <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($isModalOpen)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $categoryId ? 'Edit Category' : 'Create Category' }}</h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" wire:model="name" class="form-control" id="name" placeholder="Enter category name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Close</button>
                        <button type="button" wire:click="createOrUpdateCategory()" class="btn btn-primary">{{ $categoryId ? 'Update' : 'Create' }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

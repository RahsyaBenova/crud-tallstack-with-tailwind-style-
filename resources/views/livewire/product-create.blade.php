<div class="container mx-auto mt-5">
    <form wire:submit.prevent="createProduct" enctype="multipart/form-data">
        <div class="card bg-white shadow rounded-lg">
            <div class="card-header p-4 bg-gray-100 border-b">
                <h3 class="text-lg font-semibold">New Product</h3>
            </div>
            <div class="card-body p-4">

                <!-- Error Notifications -->
                @if ($errors->any())
                    <div class="alert alert-danger p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded-md">
                        <h4>Whoops!</h4>
                        There were some problems with your input.
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- SKU Input -->
                <div class="mb-4">
                    <label>SKU</label>
                    <input type="text" wire:model="sku" class="form-control w-full p-2 border rounded-md" />
                </div>

                <!-- Name Input -->
                <div class="mb-4">
                    <label>Name</label>
                    <input type="text" wire:model="name" class="form-control w-full p-2 border rounded-md" />
                </div>

                <!-- Brand Dropdown -->
                <div class="mb-4">
                    <label>Brand</label>
                    <select wire:model="brand_id" class="form-control w-full p-2 border rounded-md">
                        <option value="">-- select --</option>
                        @foreach ($brands as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Multi-select -->
                <div class="mb-4">
                    <label>Category</label>
                    <select wire:model="category_ids" class="form-control w-full p-2 border rounded-md" multiple>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Input -->
                <div class="mb-4">
                    <label>Price</label>
                    <input type="number" wire:model="price" class="form-control w-full p-2 border rounded-md" />
                </div>

                <!-- Stock Input -->
                <div class="mb-4">
                    <label>Stock</label>
                    <input type="number" wire:model="stock" class="form-control w-full p-2 border rounded-md" />
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label>Image</label>
                    <input type="file" wire:model="image" class="form-control w-full p-2 border rounded-md" />
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-48 h-48 object-cover rounded-md" />
                    @endif
                </div>

                <!-- Description (CKEditor Integration) -->
                <div class="mb-4" wire:ignore x-data x-init="
                    ClassicEditor.create($refs.editor)
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('description', editor.getData());
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
                ">
                    <label>Description</label>
                    <textarea x-ref="editor" wire:model.defer="description" class="form-control w-full p-2 border rounded-md" rows="4"></textarea>
                    @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

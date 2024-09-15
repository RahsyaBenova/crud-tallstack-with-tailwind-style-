<div>
    <form wire:submit.prevent="createProduct" enctype="multipart/form-data">
        <div class="card mt-5">
            <div class="card-header">
                <h3>New Product</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-title"><h4>Whoops!</h4></div>
                    There were some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" wire:model="sku" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" wire:model="name" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Brand</label>
                    <select wire:model="brand_id" class="form-control">
                        <option value="">-- select --</option>
                        @foreach ($brands as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select wire:model="category_ids" class="form-control" multiple>
                        @foreach ($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" wire:model="price" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" wire:model="stock" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" wire:model="image" class="form-control" />
                    @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="width:200px" />
                    @endif
                </div>
                <div class="form-group" wire:ignore x-data x-init="
    ClassicEditor
    .create($refs.editor)
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
    <textarea x-ref="editor" wire:model.defer="description" class="form-control" rows="4">{{ $description }}</textarea>
    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
</div>


                <div class="form-group">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

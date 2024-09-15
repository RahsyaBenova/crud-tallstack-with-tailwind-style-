<div>
    <form wire:submit.prevent="updateTransaksi">
        <div class="card mt-5">
            <div class="card-header">
                <h3>Edit Transaksi</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" wire:model="tanggal" class="form-control" />
                </div>

                <div class="form-group">
                    <label>Products</label>
                    <div>
                        @foreach ($products as $product)
                        <button type="button" 
                                wire:click="addProductToCart({{ $product->id }})" 
                                class="btn btn-secondary"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $product->name }} - {{ $product->price }} 
                            @if($product->stock <= 0)
                                (Stok Habis)
                            @else
                                (Stok: {{ $product->stock }})
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                <h4>Cart</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $index => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                <input type="number" 
                                       wire:model="cart.{{ $index }}.jumlah" 
                                       wire:change="updateCart({{ $index }}, 'jumlah', $event.target.value)" 
                                       min="1" 
                                       max="{{ \App\Models\Product::find($item['product_id'])->stock }}" 
                                       class="form-control" />
                            </td>
                            <td>{{ $item['price'] * $item['jumlah'] }}</td>
                            <td>
                                <button type="button" wire:click="removeProductFromCart({{ $index }})" class="btn btn-danger">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group">
                    <label>Total Harga: {{ $total_harga }}</label>
                </div>

                <button type="submit" class="btn btn-primary"
                    {{ count($cart) == 0 || $total_harga == 0 ? 'disabled' : '' }}>
                    Save
                </button>
            </div>
        </div>
    </form>
</div>

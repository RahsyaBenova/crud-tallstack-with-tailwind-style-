<div class="container mx-auto mt-5 p-4">
    <form wire:submit.prevent="createTransaksi">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-gray-100 px-6 py-4 rounded-t-lg">
                <h3 class="text-xl font-semibold">Create Transaksi</h3>
            </div>
            <div class="p-6">
                @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
                @endif

                <div class="mb-4">
                    <label class="block mb-2">Tanggal</label>
                    <input type="date" wire:model="tanggal" class="w-full p-2 border border-gray-300 rounded" />
                </div>

               

                <div class="mb-4">
                    <label class="block mb-2">Products</label>
                    <div class="mb-4">
                        <input type="text" 
                               wire:model.live="search" 
                               placeholder="Cari produk..." 
                               class="w-full p-2 border border-gray-300 rounded mb-4" />
                    </div>
                    <div class="flex flex-wrap">
                        @foreach ($products as $product)
                        <button type="button" 
                                wire:click="addProductToCart({{ $product->id }})" 
                                class="mb-2 mr-2 p-2 rounded 
                                       {{ $product->stock <= 0 ? 'bg-red-500 text-white cursor-not-allowed' : 'bg-gray-200' }}"
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

                <h4 class="text-lg font-semibold mb-4">Cart</h4>
                <table class="min-w-full table-auto mb-4">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Harga</th>
                            <th class="px-4 py-2">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $index => $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item['name'] }}</td>
                            <td class="border px-4 py-2">
                                <input type="number" 
                                       wire:model="cart.{{ $index }}.jumlah" 
                                       wire:change="updateCart({{ $index }}, 'jumlah', $event.target.value)" 
                                       min="1" 
                                       max="{{ \App\Models\Product::find($item['product_id'])->stock }}" 
                                       class="p-2 border border-gray-300 rounded w-16" />
                            </td>
                            <td class="border px-4 py-2">{{ $item['price'] * $item['jumlah'] }}</td>
                            <td class="border px-4 py-2">
                                <button type="button" wire:click="removeProductFromCart({{ $index }})" class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mb-4">
                    <label>Total Harga: {{ $total_harga }}</label>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Create Transaksi</button>
            </div>
        </div>
    </form>
</div>

<div>
    <div class="mt-5">
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">List Transaksi</h3>
            
            <!-- Success Message with SweetAlert -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
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
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" class="form-control w-full border-gray-300 rounded-md" placeholder="Search Transaksi..." wire:model.live="searchTerm">
            </div>

            <!-- Rows per Page -->
            <div class="mb-3 flex items-center space-x-2">
                <label class="mr-2">Show</label>
                <select wire:model.live="perPage" class="form-control border-gray-300 rounded-md" style="width: auto;">
                    <option value="5">5</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                </select>
                <label class="ml-2">entries</label>
            </div>

            <a href="{{ route('transaksi.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-3 inline-block">New Transaksi</a>

            <table class="min-w-full bg-white">
                <thead>
                    <tr class="w-full text-left bg-gray-100">
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Tanggal</th>
                        <th class="py-2 px-4">Total Harga</th>
                        <th class="py-2 px-4">Detail</th>
                        <th class="py-2 px-4">Status</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $transaksi)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $transaksi->id }}</td>
                        <td class="py-2 px-4">{{ $transaksi->tanggal }}</td>
                        <td class="py-2 px-4">{{ $transaksi->total_harga }}</td>
                        <td class="py-2 px-4">
                            <ul class="list-disc pl-5">
                                @foreach($transaksi->detailTransaksis as $detail)
                                <li>{{ $detail->product->name }} ({{ $detail->jumlah }}) - {{ $detail->harga }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="py-2 px-4">{{ $transaksi->status }}</td>
                        <td class="py-2 px-4">
                            @if($transaksi->status !== 'finished')
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600">Edit</a>
                                <button class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600" wire:click.prevent="deleteTransaksi({{ $transaksi->id }})">Delete</button>
                                <button class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600" wire:click.prevent="confirmFinishTransaksi({{ $transaksi->id }})">Finish</button>
                            @else
                                <!-- Button Show Details -->
                                <button class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600" wire:click.prevent="showDetails({{ $transaksi->id }})">Show Details</button>
                                <button class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600" wire:click.prevent="deleteTransaksiFinished({{ $transaksi->id }})">Delete</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No Record Found!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!--Pagination -->
            <div class="mt-4">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
    @if($showDetailsModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold mb-4">Detail Transaksi</h2>
            
            @if($selectedTransaksi)
                <p><strong>ID Transaksi:</strong> {{ $selectedTransaksi->id }}</p>
                <p><strong>Tanggal:</strong> {{ $selectedTransaksi->tanggal }}</p>
                
                <h3 class="font-semibold mt-4">Detail Barang:</h3>
                <ul class="list-disc pl-5 mb-2">
                    @foreach($selectedTransaksi->detailTransaksis as $detail)
                    <li>{{ $detail->product->name }} ({{ $detail->jumlah }}) - {{ number_format($detail->harga, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
                <p><strong>Total Harga:</strong> {{ number_format($selectedTransaksi->total_harga, 0, ',', '.') }}</p>
                <p><strong>Uang Dibayarkan:</strong> {{ number_format($selectedTransaksi->uang_dibayar, 0, ',', '.') }} </p>
                <p><strong>Kembalian:</strong> {{ number_format($selectedTransaksi->kembalian, 0, ',', '.') }}</p>

            @endif

            <div class="flex justify-end space-x-2 mt-4">
                <button class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600" wire:click="closeDetailsModal">Close</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Pembayaran -->
    @if($showPaymentModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold mb-4">Input Pembayaran</h2>
            <div class="mb-4">
                <p class="text-gray-700">Total yang harus dibayarkan: <strong>{{ number_format($totalHarusDibayar, 0, ',', '.') }}</strong></p>
            </div>
            <div class="mb-4">
                <label for="jumlahBayar" class="block text-gray-700">Jumlah Pembayaran</label>
                <input type="number" id="jumlahBayar" wire:model="jumlahBayar" class="w-full border-gray-300 rounded-md" placeholder="Masukkan jumlah pembayaran">
                @if($jumlahBayar)
                <p class="mt-2 text-gray-700">Kembalian: <strong>{{ number_format($kembalian, 0, ',', '.') }}</strong></p>
                @endif
            </div>
            <div class="flex justify-end space-x-2">
                <button class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600" wire:click="closeModal">Cancel</button>
                <button class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600" wire:click="processFinishTransaksi">Submit</button>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            @this.on('swal', (event) => {
                const data = event;
                Swal.fire({
                    icon: data[0]['icon'],
                    title: data[0]['title'],
                    text: data[0]['text'],
                });
            });

            @this.on('delete-prompt', (event) => {
                Swal.fire({
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

                        @this.on('deleted', (event) => {
                            Swal.fire({
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

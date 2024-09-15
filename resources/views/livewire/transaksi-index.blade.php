<div>
    <div class="card mt-5">
        <div class="card-header">
            <h3>List Transaksi</h3>
        </div>
        <div class="card-body">
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
                <input type="text" class="form-control" placeholder="Search Transaksi..." wire:model.live="searchTerm">
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
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">New Transaksi</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $transaksi)
                    <tr>
                        <td>{{ $transaksi->id }}</td>
                        <td>{{ $transaksi->tanggal }}</td>
                        <td>{{ $transaksi->total_harga }}</td>
                        <td>
                            <ul>
                                @foreach($transaksi->detailTransaksis as $detail)
                                <li>{{ $detail->product->name }} ({{ $detail->jumlah }}) - {{ $detail->harga }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $transaksi->status }}</td>
                        <td>
                            @if($transaksi->status !== 'finished')
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-secondary">Edit</a>
                                <button class="btn btn-danger" wire:click.prevent="deleteTransaksi({{ $transaksi->id }})">Delete</button>
                                <button class="btn btn-success" wire:click.prevent="finishTransaksi({{ $transaksi->id }})">Finish</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No Record Found!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!--Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $transaksis->links() }}
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

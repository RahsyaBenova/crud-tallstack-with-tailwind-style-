<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">MyStore</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a wire:navigate class="{{request()->is('products')?'nav-link active':'nav-link'}}"  href="/products" wire:navigate>Products</a>
          </li>
          <li class="nav-item">
            <a wire:navigate class="{{request()->is('categories')?'nav-link active':'nav-link'}}" href="/categories" wire:navigate>Categories</a>
          </li>

          <li class="nav-item">
            <a wire:navigate class="{{request()->is('brand')?'nav-link active':'nav-link'}}" href="/brands" wire:navigate>Brand</a>
          </li>
          <li class="nav-item">
            <a wire:navigate class="{{request()->is('transaksi')?'nav-link active':'nav-link'}}" href="/transaksi" wire:navigate>Transaction</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-category">Stok</li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('stok.gudang.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Gudang</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('stok.retail.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Retail</span>
        </a>
      </li>
      <li class="nav-item nav-category">Log</li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('log.gudang.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Stok Gudang</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('log.barang.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Konsinyasi</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('log.keuangan.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Transaksi</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('log.toko.index') }}">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Toko</span>
        </a>
      </li>
      <li class="nav-item d-flex justify-content-center my-5">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger d-flex align-items-center gap-1">
            <i class="mdi mdi-logout"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </nav>

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
    </ul>
  </nav>

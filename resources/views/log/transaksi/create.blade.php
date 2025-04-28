@extends('index')

@section('main-panel')
<div class="row">
  <form class="d-flex flex-column gap-4" action="{{ route('log.keuangan.store') }}" method="POST">
    @csrf
    @include('log.transaksi.partials.prev-items-card')
    @include('log.transaksi.partials.add-items-card')
  </form>
</div>

@endsection

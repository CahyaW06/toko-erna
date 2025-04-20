@extends('index')

@section('main-panel')
<div class="row">
  <form class="d-flex flex-column gap-4" action="{{ route('log.barang.store') }}" method="POST">
    @csrf
    @include('log.retail.partials.prev-items-card')
    @include('log.retail.partials.add-items-card')
  </form>
</div>

@endsection

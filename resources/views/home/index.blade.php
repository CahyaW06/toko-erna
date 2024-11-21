@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-sm-12">
      <div class="home-tab">
        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
            </li>
          </ul>
        </div>
        <div class="tab-content tab-content-basic">
          <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            @include('partial.stat')
            <div class="row d-flex gap-1 flex-md-nowrap flex-wrap">
              <div class="col-lg-8 d-flex flex-column">
                @include('partial.log-pendapatan')
                {{-- <div class="row mt-2">
                  @include('partial.stok-bar')
                </div> --}}
              </div>
              <div class="col-lg-4 d-flex flex-column">
                @include('partial.event')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

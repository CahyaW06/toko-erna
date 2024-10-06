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
          <div>
            <div class="btn-wrapper">
              <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
              <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
              <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
            </div>
          </div>
        </div>
        <div class="tab-content tab-content-basic">
          <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            @include('partial.stat')
            <div class="row">
              <div class="col-lg-8 d-flex flex-column">
                <div class="row">
                  @include('partial.log-pendapatan')
                </div>
                <div class="row mt-2">
                  @include('partial.stok-bar')
                </div>
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
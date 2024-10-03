<div class="card card-rounded h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="card-title card-title-dash">Aktivitas Terknini</h4>
        {{-- <p class="mb-0">20 finished, 5 remaining</p> --}}
      </div>
      <div class="mt-4">
        <ul class="bullet-line-list">
          @for ($i=0; $i < 10; $i++)
          <li>
            <div class="d-flex justify-content-between">
              <div><span class="text-light-green">Ben Tossell</span> assign you a task</div>
              <p>Just now</p>
            </div>
          </li>
          @endfor
        </ul>
      </div>
      <div class="list align-items-center pt-3">
        <div class="wrapper w-100">
          <p class="mb-0">
            <a href="#" class="fw-bold text-primary">Show all <i class="mdi mdi-arrow-right ms-2"></i></a>
          </p>
        </div>
      </div>
    </div>
  </div>

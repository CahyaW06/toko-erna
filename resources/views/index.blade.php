<!DOCTYPE html>
<html lang="en">

<head>
  @include('base.head')
  @yield('head')
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @auth
    @include('base.navbar')
    <div class="container-fluid page-body-wrapper">
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      @include('partial.sidebar')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('main-panel')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('base.footer')
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    @endauth

    @guest
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <h3 class="welcome-text"><span class="text-black fw-bold">Mom & Kiddos</span></h3>
                </div>
                <p>Silahkan login untuk masuk</p>
                <form class="pt-3" action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Username" value="{{ old('name') }}">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    @endguest
    <!-- partial -->
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  @include('base.script')
  @stack('scripts')
</body>

</html>


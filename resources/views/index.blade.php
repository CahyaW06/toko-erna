<!DOCTYPE html>
<html lang="en">

<head>
  @include('base.head')
  @yield('head')
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('base.navbar')
    <!-- partial -->
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
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  @include('base.script')
  @yield('script')
</body>

</html>


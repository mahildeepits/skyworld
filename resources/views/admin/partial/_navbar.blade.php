<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="height: 70px;">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="height: 70px;">
      <a class="navbar-brand brand-logo" href="{{route('admin.dashboard')}}">
        <img src="{{ asset('images/54.png') }}" alt="logo" class="logo-dark" style="width:100px; height:auto;" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="{{route('admin.dashboard')}}"><img src="{{ asset('images/54-mini.png') }}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
      <h5 class="mb-0 font-weight-medium d-none d-lg-flex">Welcome Admin!</h5>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
          <a class="nav-item dropdown d-none d-xl-inline-flex user-dropdown" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
            <img class="img-xs rounded-circle ml-2" src="{{ asset('stellar_assets/images/faces/face8.jpg') }}" alt="Profile image">
            <span class="font-weight-normal"> Admin </span></a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
            <div class="dropdown-header text-center">
              <img class="img-md rounded-circle" src="{{ asset('stellar_assets/images/faces/face8.jpg') }}" alt="Profile image">
              <p class="mb-1 mt-3">Administrator</p>
              <p class="font-weight-light text-muted mb-0">admin@gmail.com</p>
            </div>
            <a class="dropdown-item" href="{{ route('change.password') }}"><i class="dropdown-item-icon icon-energy text-primary"></i> Change Password</a>
            <a class="dropdown-item" href="{{ route('admin.logout') }}"><i class="dropdown-item-icon icon-power text-primary"></i>Sign Out</a>
          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
      </button>
    </div>
  </nav>

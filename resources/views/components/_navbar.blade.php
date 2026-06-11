<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex d-lg-none flex-row">

  <!-- {{-- Brand wrapper: ONLY visible on mobile (<992px) --}} -->
  <div class="text-center navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-between px-3">
    <a class="navbar-brand" href="{{ route('member.dashboard') }}">
      <img src="{{ asset('images/54.png') }}" alt="logo" style="height:36px; width:auto;"/>
    </a>
    <div class="d-flex justify-content-between gap-3 align-items-center">
      <a href="{{ route('account.profile') }}">
        <img src="{{ authUser()->profile_image_url }}" class="db-welcome-avatar rounded-circle" style="width: 40px!important; height: 40px!important; border: 2px solid rgba(0, 226, 251, 0.5); box-shadow: 0 0 16px rgba(0, 226, 251, 0.3); object-fit: cover;" alt="{{ authUser()->name }}">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="offcanvas" style="border:none; background:transparent;">
        <span class="icon-menu" style="color:rgba(255,255,255,0.85); font-size:1.4rem;"></span>
      </button>
    </div>
  </div>

  {{-- Menu wrapper: always visible --}}
  
</nav>

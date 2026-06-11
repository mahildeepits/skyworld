<nav class="sidebar sidebar-offcanvas" id="sidebar">

  {{-- ── Logo at top (desktop sidebar) ── --}}
  <div class="sidebar-logo-area d-none d-lg-block">
    <a href="{{ route('member.dashboard') }}" class="sidebar-brand-link">
      <img src="{{ asset('images/54.png') }}" alt="Logo" class="sidebar-logo-full">
    </a>
  </div>

  {{-- ── User Profile Card ── --}}
  <!-- <div class="sidebar-profile-card">
    <a href="{{ route('account.profile') }}" class="sidebar-profile-avatar-wrap">
      <img src="{{ auth('member')->user()->profile_image_url }}" alt="profile" class="rounded-circle">
      <span class="sidebar-online-dot"></span>
    </a>
    <div class="sidebar-profile-info">
      <p class="sidebar-profile-name">{{ auth('member')->user()->name }}</p>
      <span class="sidebar-profile-role">Member</span>
    </div>
  </div> -->

  <ul class="nav">
    {{-- Mini logo for collapsed / mobile sidebar brand --}}
    <li class="nav-item navbar-brand-mini-wrapper">
      <a class="nav-link navbar-brand brand-logo-mini" href="{{ route('member.dashboard') }}">
        <img src="{{ asset('images/54-mini.png') }}" alt="logo" />
      </a>
    </li>

    <li class="nav-item nav-category">
      <span class="nav-link">Main Menu</span>
    </li>

    <li class="nav-item {{ Route::is('member.dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('member.dashboard') }}">
        <i class="icon-home menu-icon"></i>
        <span class="menu-title">Member Home</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('register', ['sponsor' => auth('member')->user()->member_id]) }}" target="_blank">
        <i class="icon-user-follow menu-icon" style="color: #00e2fb;"></i>
        <span class="menu-title">Join New User</span>
      </a>
    </li>
   

    <!-- <li class="nav-item {{ Route::is(['edit.wallet.address','member.wallet','wallet.deposit','wallet.withdrawl','wallet.transfer']) ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#wallet"
         aria-expanded="{{ Route::is(['edit.wallet.address','member.wallet','wallet.deposit','wallet.withdrawl','wallet.transfer']) ? 'true' : 'false' }}"
         aria-controls="wallet">
        <i class="icon-credit-card menu-icon"></i>
        <span class="menu-title">Wallet</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ Route::is(['edit.wallet.address','member.wallet','wallet.deposit','wallet.withdrawl','wallet.transfer']) ? 'show' : '' }}" id="wallet">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link {{ Route::is('edit.wallet.address') ? 'active' : '' }}" href="{{ route('edit.wallet.address') }}">Addresses</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('member.wallet') ? 'active' : '' }}" href="{{ route('member.wallet') }}">Transactions</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('wallet.deposit') ? 'active' : '' }}" href="{{ route('wallet.deposit') }}">Deposit</a></li>
          @if(!in_array(authUser()->member_id, ['Company','company']))
          <li class="nav-item"><a class="nav-link {{ Route::is('wallet.withdrawl') ? 'active' : '' }}" href="{{ route('wallet.withdrawl') }}">Withdrawal</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('wallet.transfer') ? 'active' : '' }}" href="{{ route('wallet.transfer') }}">Transfer</a></li>
          @endif
        </ul>
      </div>
    </li> -->

    <li class="nav-item {{ Route::is(['account.profile','account.change-password','account.kyc-details','edit-bank-details','wallet.pin']) ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#my-information"
         aria-expanded="{{ Route::is(['account.profile','account.change-password','account.kyc-details','edit-bank-details','wallet.pin']) ? 'true' : 'false' }}"
         aria-controls="my-information">
        <i class="icon-user menu-icon"></i>
        <span class="menu-title">My Information</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ Route::is(['account.profile','account.change-password','account.kyc-details','edit-bank-details']) ? 'show' : '' }}" id="my-information">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link {{ Route::is('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}">My Profile</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('account.change-password') ? 'active' : '' }}" href="{{ route('account.change-password') }}">Change Password</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('account.kyc-details') ? 'active' : '' }}" href="{{ route('account.kyc-details') }}">KYC Details</a></li>
          <li class="nav-item"><a class="nav-link {{ Route::is('edit-bank-details') ? 'active' : '' }}" href="{{ route('edit-bank-details') }}">Bank Details</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item {{ request()->routeIs(['member.tree','member.tree.view','my-directs','my-downline']) ? 'active' : '' }}">
      <a class="nav-link" data-bs-toggle="collapse" href="#network"
         aria-expanded="{{ request()->routeIs(['member.tree','member.tree.view','my-directs','my-downline']) ? 'true' : 'false' }}"
         aria-controls="network">
        <i class="icon-people menu-icon"></i>
        <span class="menu-title">Network</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->routeIs(['member.tree','member.tree.view','my-directs','my-downline']) ? 'show' : '' }}" id="network">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs(['member.tree','member.tree.view']) ? 'active' : '' }}" href="{{ route('member.tree', 1) }}">View Tree</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('my-directs') ? 'active' : '' }}" href="{{ route('my-directs') }}">My Directs</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('my-downline') ? 'active' : '' }}" href="{{ route('my-downline') }}">My Downline</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item {{ Route::is('id-card') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('id-card') }}">
        <i class="icon-badge menu-icon"></i>
        <span class="menu-title">ID Card</span>
      </a>
    </li>
     <li class="nav-item  {{ Route::is('member.wallet') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('member.wallet') }}">
        <i class="icon-credit-card menu-icon"></i>
        <span class="menu-title">Transactions</span>
      </a>
    </li>
    <li class="nav-item {{ Route::is('wallet.withdrawl') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('wallet.withdrawl') }}">
        <i class="icon-graph menu-icon"></i>
        <span class="menu-title">Withdrawal</span>
      </a>
    </li>

    <!-- <li class="nav-item {{ Route::is('member.locking') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('member.locking') }}">
        <i class="icon-lock menu-icon"></i>
        <span class="menu-title">Locking</span>
      </a>
    </li> -->

    <!-- <li class="nav-item {{ Route::is('contact.us') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('contact.us') }}">
        <i class="icon-phone menu-icon"></i>
        <span class="menu-title">Contact Us</span>
      </a>
    </li>

    <li class="nav-item {{ Route::is('member.rewards') && !request('type') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('member.rewards') }}">
        <i class="icon-trophy menu-icon"></i>
        <span class="menu-title">My Rewards</span>
      </a>
    </li>

    <li class="nav-item {{ Route::is('member.rewards') && request('type')=='points' ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('member.rewards', ['type'=>'points']) }}">
        <i class="icon-star menu-icon"></i>
        <span class="menu-title">My Rewards Points</span>
      </a>
    </li>

    <li class="nav-item {{ Route::is('newsevents.list') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('newsevents.list') }}">
        <i class="icon-paper-plane menu-icon"></i>
        <span class="menu-title">News & Events</span>
      </a>
    </li> -->

    <li class="nav-item {{ Route::is('announcements.list') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('announcements.list') }}">
        <i class="icon-volume-2 menu-icon"></i>
        <span class="menu-title">Announcements</span>
      </a>
    </li>

    <!-- <li class="nav-item {{ Route::is('about-us') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('about-us') }}">
        <i class="icon-info menu-icon"></i>
        <span class="menu-title">About US</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ asset('pdfs/Bittico.pdf') }}" target="_blank">
        <i class="icon-docs menu-icon"></i>
        <span class="menu-title">Plan PDF</span>
      </a>
    </li> -->

    <li class="nav-item sidebar-logout-nav-item">
      <a class="nav-link" href="{{ route('logout') }}">
        <i class="icon-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>
  </ul>
</nav>

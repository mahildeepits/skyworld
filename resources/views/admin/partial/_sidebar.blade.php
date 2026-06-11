<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="profile-image">
            <img class="img-xs rounded-circle" src="{{ asset('stellar_assets/images/faces/face8.jpg') }}" alt="profile image">
            <div class="dot-indicator bg-success"></div>
          </div>
          <div class="text-wrapper">
            <p class="profile-name">Admin</p>
            <p class="designation">Administrator</p>
          </div>
        </a>
      </li>
      <li class="nav-item nav-category">
        <span class="nav-link">Dashboard</span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
          <span class="menu-title">Dashboard</span>
          <i class="icon-screen-desktop menu-icon"></i>
        </a>
      </li>
      <li class="nav-item nav-category"><span class="nav-link">Management</span></li>
      
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.registration.requests') }}">
          <span class="menu-title">Registration Requests</span>
          <i class="icon-user-follow menu-icon"></i>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users') }}">
          <span class="menu-title">Users</span>
          <i class="icon-people menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.edit.user') }}">
          <span class="menu-title">User Profiles</span>
          <i class="icon-user-following menu-icon"></i>
        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('contacts.list') }}">
          <span class="menu-title">Contacts List</span>
          <i class="icon-envelope-letter menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('news.events') }}">
          <span class="menu-title">News & Events</span>
          <i class="icon-calendar menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('tasks.index') }}">
          <span class="menu-title">Task Manager</span>
          <i class="icon-list menu-icon"></i>
        </a>
      </li> -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('agentcategories.index') }}">
          <span class="menu-title">Levels</span>
          <i class="icon-grid menu-icon"></i>
        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('rewards.index') }}">
          <span class="menu-title">Rewards</span>
          <i class="icon-trophy menu-icon"></i>
        </a>
      </li> -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.withdrawal.requests') }}">
          <span class="menu-title">Withdrawals</span>
          <i class="icon-wallet menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.company.revenue.report') }}">
          <span class="menu-title">Company Revenue</span>
          <i class="icon-chart menu-icon"></i>
        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.charges') }}">
          <span class="menu-title">Admin Settings</span>
          <i class="icon-settings menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('website.settings') }}">
          <span class="menu-title">Website Settings</span>
          <i class="icon-globe menu-icon"></i>
        </a>
      </li> -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('announcements.index') }}">
          <span class="menu-title">Announcements</span>
          <i class="icon-volume-2 menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('change.password') }}">
          <span class="menu-title">Password</span>
          <i class="icon-key menu-icon"></i>
        </a>
      </li>

    </ul>
  </nav>

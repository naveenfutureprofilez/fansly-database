<nav class="navbar navbar-inverse sidebar" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header" style="background-color: #fff;">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('super.dashboard') }}">
          <img src="{{ asset('img/logo/logo-white.png') }}" alt="WYI" style="max-width: 160px;">
        </a>
      </div>
      <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li {{ Request::routeIs('super.dashboard') ? 'class=active' : '' }}>
            <a href="{{ route('super.dashboard') }}"><i class="fa fa-link pull-right hidden-xs showopacity"></i> <span>Dashboard</span></a>
          </li>
          <li class="nav-sub {{ Request::routeIs('super.post.*') ? 'active' : '' }}">
            <a href="javascript:;" class="nav-sub-link">Posts</a>
            <ul class="nav-submenu sidebar-dropdown-menu">
              <li>
                <a href="{{ route('super.post.list') }}" class="sub-first-child {{ Request::routeIs('super.post.list') ? 'active' : '' }}"><span>Recent Posts</span></a>
              </li>
              <li>
                <a href="{{ route('super.post.report') }}" class="sub-else-child {{ Request::routeIs('super.post.report') ? 'active' : '' }}">
                  <span>Reported Posts</span>
                </a>
              </li>
              <li>
                <a href="{{ route('super.post.archive') }}"  class="sub-else-child {{ Request::routeIs('super.post.archive') ? 'active' : '' }}">
                  <span>Archived Posts</span>
                </a>
              </li>
              <li>
                <a href="{{ route('super.post.block') }}" class="sub-else-child {{ Request::routeIs('super.post.block') ? 'active' : '' }}">
                  <span>Blocked Posts</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-sub {{ Request::routeIs('super.users.*') ? 'active' : '' }}">
            <a href="javascript:;" class="nav-sub-link">
              <span>Users</span>
            </a>
            <ul class="nav-submenu sidebar-dropdown-menu">
              <li>
                <a href="{{ route('super.users.creator.pro') }}" class="sub-first-child {{ Request::routeIs('super.users.creator.pro') ? 'active' : '' }}"><span>Creator Pro</span></a>
              </li>
              <li>
                <a href="{{ route('super.users.creator') }}" class="sub-else-child {{ Request::routeIs('super.users.creator') ? 'active' : '' }}">
                  <span>Creators</span>
                </a>
              </li>
              <li>
                <a href="{{ route('super.users.fan') }}"  class="sub-else-child {{ Request::routeIs('super.users.fan') ? 'active' : '' }}">
                  <span>Users</span>
                </a>
              </li>
            </ul>
          </li>
          {{-- <li @if(isset($active) AND ($active == 'moderation')) class="active" @endif>
            <a href="/admin/moderation/Image"><i class="fa pull-right hidden-xs showopacity fa-list"></i> <span>Moderation</span></a>
          </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'subscriptions')) class="active" @endif>
            <a href="/admin/subscriptions"><i class="fa pull-right hidden-xs showopacity  fa-address-card-o
          "></i> <span>Subscriptions</span></a>
          </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'tips')) class="active" @endif>
            <a href="/admin/tips"><i class="fa pull-right hidden-xs showopacity  fa-envelope-open
          "></i> <span>Tips</span></a>
          </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'unlocks')) class="active" @endif>
            <a href="/admin/unlocks"><i class="fa pull-right hidden-xs showopacity  fa-lock
          "></i> <span>Unlocks</span></a>
          </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'tx')) class="active" @endif>
            <a href="/admin/tx"><i class="fa pull-right hidden-xs showopacity  fa-credit-card-alt
          "></i> <span>Transactions</span></a>
          </li> --}}
          <li {{ Request::routeIs('super.requests') ? 'class=active' : '' }}>
            <a href="{{ route('super.requests') }}"><span>Verification Requests</span> <i class="fa hidden-xs showopacity fa-globe"></i> </a>
            
          </li>
          
          {{-- <li @if(isset($active) AND ($active == 'payment-requests')) class="active" @endif>
            <a href="/admin/payment-requests"><i class="fa pull-right hidden-xs showopacity fa-money"></i> <span>Withdraw Requests</span></a>
          </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'categories')) class="active" @endif>
            <a href="/admin/categories"><i class="fa pull-right hidden-xs showopacity fa-bars"></i> <span>Categories</span></a>
          </li> --}}
            {{-- <li @if(isset($active) AND ($active == 'pages')) class="active" @endif>
              <a href="/admin/cms"><i class="fa pull-right hidden-xs showopacity fa-sticky-note-o"></i> <span>Pages</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'config')) class="active" @endif>
              <a href="/admin/configuration"><i class="fa pull-right hidden-xs showopacity fa-cog"></i> <span>Configuration</span></a>
            </li> --}}
            {{-- <li @if(isset($active) AND ($active == 'cssjs')) class="active" @endif>
              <a href="/admin/cssjs"><i class="fa pull-right hidden-xs showopacity fa-css3"></i> <span>Extra CSS/JS</span></a>
            </li> --}}
            {{-- <li @if(isset($active) AND ($active == 'payments')) class="active" @endif>
              <a href="/admin/payments-settings"><i class="fa pull-right hidden-xs showopacity fa-bank"></i> <span>Payments Settings</span></a>
            </li>
            <li @if(isset($active) AND ($active == 'mail')) class="active" @endif>
              <a href="/admin/mailconfiguration"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Mail Server</span></a>
            </li> --}}
            {{-- <li @if(isset($active) AND ($active == 'popup')) class="active" @endif>
              <a href="/admin/entry-popup"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Entry Popup</span></a>
            </li> --}}
            {{-- <li @if(isset($active) AND ($active == 'cloud')) class="active" @endif>
              <a href="/admin/cloud"><i class="fa pull-right hidden-xs showopacity fa-cloud"></i> <span>Cloud Storage</span></a>
            </li> --}}
          {{-- <li @if(isset($active) AND ($active == 'admin-login')) class="active" @endif>
              <a href="/admin/config-logins"><i class="fa pull-right hidden-xs showopacity fa-cog"></i> <span>Admin Passwords</span></a>
          </li> --}}
        </ul>
        <div class="sidebar-logout">
          <a href="{{ route('super.logout') }}"></i> <span>Log Out</span> <i class="fa hidden-xs showopacity fa-power-off"></i></a>
        </div>
      </div>
    </div>
</nav>
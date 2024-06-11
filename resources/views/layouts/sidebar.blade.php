<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark pt-2">
            <span class="logo-sm pt-1">
                <img src="{{ URL::asset('assets/images/42-gham-logo-001-70x70.png') }}" alt="" height="22">
                 
            </span>
            <span class="logo-lg pt-1">
                <img src="{{ URL::asset('assets/images/42-gham-logo-001-70x70.png') }}" alt="" height="50">
                 
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light pt-2">
            <span class="logo-sm pt-1">
                <img src="{{ URL::asset('assets/images/42-gham-logo-001-70x70.png') }}" alt="" height="22">
                 
            </span>
            <span class="logo-lg pt-1">
                <img src="{{ URL::asset('assets/images/42-gham-logo-001-70x70.png') }}" alt="" height="50">
                 
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                {{-- <li class="menu-title"><span>Menu</span></li> --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="bx bxs-dashboard"></i> <span>Dashbaord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('hof.*') ? 'active' : '' }}" href="{{ route('hof.index') }}"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="bx bx-user-circle"></i> <span>HOFs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-account-multiple-outline"></i> <span>Members</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Route::is('app.setting.*') ? 'active' : '' }}" href="{{ route('app.setting.index') }}" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span>App Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
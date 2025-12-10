   <!-- Top Bar Start -->

   <div class="topbar">

<!-- LOGO -->
<div class="topbar-left">
    <a href="/" class="logo">
        <span>
                <h1 style="color: white; ">UNIKAA</h1>
            </span>
        <i>
            <h1>U</h1>
            </i>
    </a>
</div>

<nav class="navbar-custom">
    <ul class="navbar-right d-flex list-inline float-right mb-0">
        <li class="dropdown notification-list d-none d-md-block">
            <form role="search" class="app-search">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" placeholder="Search..">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </li>

        <!-- language-->
        <li class="dropdown notification-list d-none d-md-block">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="assets/images/flags/indian.png" class="mr-2" height="12" alt=""/> India <span class="mdi mdi-chevron-down"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right language-switch">
                <a class="dropdown-item" href="#"><img src="assets/images/flags/us_flag.jpg" alt="" height="16" /><span> English </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/germany_flag.jpg" alt="" height="16" /><span> German </span></a>
                {{-- <a class="dropdown-item" href="#"><img src="assets/images/flags/italy_flag.jpg" alt="" height="16" /><span> Italian </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/french_flag.jpg" alt="" height="16" /><span> French </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/spain_flag.jpg" alt="" height="16" /><span> Spanish </span></a>
                <a class="dropdown-item" href="#"><img src="assets/images/flags/russia_flag.jpg" alt="" height="16" /><span> Russian </span></a> --}}
            </div>
        </li>

        <!-- full screen -->
        <li class="dropdown notification-list d-none d-md-block">
            <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                <i class="mdi mdi-fullscreen noti-icon"></i>
            </a>
        </li>

        <!-- notification -->
    <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="mdi mdi-bell-outline noti-icon"></i>
                @if(isset($bookings) && count($bookings) > 0)
                    <span class="badge badge-pill badge-danger noti-icon-badge">{{ count($bookings) }}</span>
                @endif
            </a>

       <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
            <div class="p-3">
                <h6 class="m-0 text-primary">Today & Tomorrow’s Bookings</h6>
            </div>

            <div class="dropdown-divider"></div>

            @forelse($bookings as $booking)
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-primary">
                        <i class="mdi mdi-account"></i>
                    </div>
                    <p class="notify-details mb-0">
                        <strong>{{ $booking['customerName'] ?? 'Unknown' }}</strong>
                        <small class="text-muted d-block">
                            📞 {{ $booking['phone'] ?? 'N/A' }} | {{ $booking['gender'] ?? 'N/A' }}
                        </small>
                        <small class="d-block">
                            💇 {{ $booking['serviceName'] ?? 'Service' }}
                        </small>
                        <small class="text-muted d-block">
                            📍 {{ $booking['location'] ?? 'N/A' }} </br>
                            🏢 {{ $booking['branchLocation'] ?? 'N/A' }}
                        </small>
                        <small class="text-muted">
                            📅 {{ \Carbon\Carbon::parse($booking['date'])->format('Y M d') }}
                            - ⏰ {{ $booking['time'] ?? 'N/A' }}
                        </small>
                    </p>
                </a>
            @empty
                <div class="dropdown-item text-center text-muted">
                    No bookings for today or tomorrow.
                </div>
            @endforelse

            <div class="dropdown-divider"></div>

            {{-- <a href="{{ url('admin/bookings') }}" class="dropdown-item text-center text-primary">
                View all <i class="fi-arrow-right"></i>
            </a> --}}
        </div>
    </li>

        <li class="dropdown notification-list">
            <div class="dropdown notification-list nav-pro-img">
                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/Unikaa-Logo-14.png" alt="user" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5"></i> Profile</a>
                        <a href="#addnew" class="dropdown-item {{ request()->is("changePassword") || request()->is("changePassword/*") ? "mm active" : "" }}">
                            <i class="mdi mdi-account-circle m-r-5"></i>
                            <span>Change Password</span>
                        </a>
                    {{-- <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings m-r-5"></i> Settings</a> --}}
                    <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5"></i> Lock screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="mdi mdi-power text-danger"></i> {{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                </div>
            </div>
        </li>
    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
        {{-- <li class="d-none d-sm-block">
            <div class="dropdown pt-3 d-inline-block">
                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Create
                    </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>
        </li> --}}
    </ul>

</nav>

</div>
<!-- Top Bar End -->

<script>
    function handleClick(element, name) {
        alert('You clicked on: ' + name);
        element.remove();
    }
</script>

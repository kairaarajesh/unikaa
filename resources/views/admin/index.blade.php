@extends('layouts.master')

@section('css')
<!--Chartist Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
@endsection

@section('breadcrumb')
@php
    $user = auth()->user();
    $userPermissions = checkUserPermissions($user);
    $permissions = $userPermissions['permissions'];
    $hasFullAccess = $userPermissions['hasFullAccess'];
@endphp

<div class="col-sm-6 text-left">
    <h4 class="page-title">Dashboard</h4>
    <ol class="breadcrumb">
        @if($user)
        <li class="breadcrumb-item active">
            Welcome to Unikaa CRM, <span class="font-weight-bold text-dark mt-0 header-title">{{ $user->name }}</span>
            @if(!$hasFullAccess)
                <span class="badge badge-info ml-2">Limited Access</span>
            @endif
        </li>
         @else
            <li class="breadcrumb-item active">Welcome to Unikaa CRM</li>
        @endif
    </ol>
</div>
@endsection

@section('content')
            <div class="row">
                {{-- Customer Card - Only show if user has customers permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'customers'))
                <div class="col-xl-3 col-md-6">
                  <a href="{{ url('customer') }}" style="text-decoration: none;">
                    <div class="card mini-stat bg-success text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-layout-grid2" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Customer</h5>
                                <h4 class="font-500">{{$data[8]}} <i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[8]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                  </a>
                </div>
                @endif

                {{-- Service Card - Only show if user has services permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'services'))
                 <div class="col-xl-3 col-md-6">
                    <a href="{{ url('service') }}" style="text-decoration: none;">
                    <div class="card mini-stat bg-muted text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-layout-grid2" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Service</h5>
                                <h4 class="font-500">{{$data[13]}} <i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[13]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                     </a>
                </div>
                @endif

                {{-- Sales Card - Only show if user has billing permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'billing'))
                 <div class="col-xl-3 col-md-6">
                  <div class="card mini-stat bg-info text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-layout-grid2" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Sales</h5>
                                <h4 class="font-500">{{$data[2]}} <i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[2]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Employees Card - Only show if user has employees permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'employees'))
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-dark text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-layout-grid2" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Employees</h5>
                                <h4 class="font-500">{{$data[10]}} <i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[10]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Booking Card - Only show if user has bookings permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'bookings'))
                 <div class="col-xl-3 col-md-6">
                  <div class="card mini-stat bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-star" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Booking</h5>
                                <h4 class="font-500">{{$data[9]}}<i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[5]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Product Card - Only show if user has settings permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'settings'))
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-success text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <span class="ti-id-badge" style="font-size: 20px"></span>
                                </div>
                                <h5 class="font-14 text-uppercase mt-0 text-white-50">Product</h5>
                                <h4 class="font-500">{{$data[0]}} </h4>
                                <span class="ti-user" style="font-size: 71px"></span>
                            </div>
                            <div class="container">
                                <div class="p-0">
                                    <div class="float-right custom-dropdown">
                                        <a href="#" class="text-white-50" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-arrow-right h5"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-scrollable" aria-labelledby="dropdownMenuButton">
                                            @foreach ($managementName as $product => $quantity)
                                                <a class="dropdown-item" href="#">
                                                    <h6 class="font-10" style="color: {{ $quantity < 5 ? 'red' : 'inherit' }}">
                                                        {{ $product }} = {{ $quantity }}
                                                    </h6>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="text-white-50 mb-0">More info</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Brand Card - Only show if user has settings permission --}}
                @if($hasFullAccess || hasPermission($permissions, 'settings'))
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-muted text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="ti-layout-grid2" style="font-size: 20px"></i>
                                </div>
                                <h5 class="font-16 text-uppercase mt-0 text-white-50">Brand</h5>
                                <h4 class="font-500">{{$data[1]}} <i class=" text-success ml-2"></i></h4>
                                <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{$data[1]}}/{{count($data)}}</span>
                            </div>
                            <div class="pt-2">
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- end row -->

            <div class="row">
                @if($hasFullAccess || hasPermission($permissions, 'billing') || hasPermission($permissions, 'reports'))
                <div class="col-xl-9">
                    <div class="btn-group mb-3">
                        <a href="{{ url('admin?filter=today') }}"
                        class="btn btn-sm {{ $filter=='today'?'btn-primary':'btn-light' }}">Today</a>

                        <a href="{{ url('admin?filter=week') }}"
                        class="btn btn-sm {{ $filter=='week'?'btn-primary':'btn-light' }}">This Week</a>

                        <a href="{{ url('admin?filter=month') }}"
                        class="btn btn-sm {{ $filter=='month'?'btn-primary':'btn-light' }}">This Month</a>

                        <a href="{{ url('admin?filter=year') }}"
                        class="btn btn-sm {{ $filter=='year'?'btn-primary':'btn-light' }}">This Year</a>

                       <a href="javascript:void(0)" id="resetFilter" class="btn btn-sm btn-danger">Reset</a>

                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $invoiceCount }}</h4>
                            <p class="text-warning">Total Invoices ({{ strtoupper($filter) }})</p>

                            <h5 class="text-success">₹ {{ number_format($totalAmount, 2) }}</h5>
                            <p>Total Amount</p>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                @endif

                {{-- Academy Section - Only show if user has academy permissions --}}
                @if($hasFullAccess || hasPermission($permissions, 'student') || hasPermission($permissions, 'course') || hasPermission($permissions, 'trainer'))
                <div class="col-xl-3">
                    <div class="card bg-muted">
                        <div class="card-body">
                            <div>
                                <h4 class="mt-0 header-title mb-4">Academy</h4>
                            </div>
                            @if($hasFullAccess || hasPermission($permissions, 'student'))
                            <div class="wid-peity mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-white">Student</p>
                                            <h5 class="mb-4">{{$data[11]}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($hasFullAccess || hasPermission($permissions, 'course'))
                            <div class="wid-peity mb-4">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div>
                                            <p class="text-white">Management</p>
                                            <h5 class="mb-4">{{$data[12]}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($hasFullAccess || hasPermission($permissions, 'subadmin'))
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-white">Users</p>
                                            <h5 class="mb-4">{{$data[6]}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- end row -->

            {{-- Permission Info Section for Limited Users --}}
            {{-- @if(!$hasFullAccess)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-0 header-title mb-3">Your Access Permissions</h4>
                            <div class="row">
                                @if(hasPermission($permissions, 'employees'))
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="ti-user text-success" style="font-size: 40px;"></i>
                                        <h6 class="mt-2">Employees</h6>
                                        <small class="text-muted">Full Access</small>
                                    </div>
                                </div>
                                @endif
                                @if(hasPermission($permissions, 'customers'))
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="ti-layout-grid2 text-info" style="font-size: 40px;"></i>
                                        <h6 class="mt-2">Customers</h6>
                                        <small class="text-muted">Full Access</small>
                                    </div>
                                </div>
                                @endif
                                @if(hasPermission($permissions, 'bookings'))
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="ti-book text-primary" style="font-size: 40px;"></i>
                                        <h6 class="mt-2">Bookings</h6>
                                        <small class="text-muted">Full Access</small>
                                    </div>
                                </div>
                                @endif
                                @if(hasPermission($permissions, 'billing'))
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="ti-files text-warning" style="font-size: 40px;"></i>
                                        <h6 class="mt-2">Billing</h6>
                                        <small class="text-muted">Full Access</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif --}}

        <script>
            document.getElementById("resetFilter").addEventListener("click", function () {
                // redirect to admin page without any filter
                window.location.href = "{{ url('admin') }}";
            });
        </script>
@endsection




@section('script')
<!--Chartist Chart-->
<script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- peity JS -->
<script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/dashboard.js') }}"></script>
@endsection

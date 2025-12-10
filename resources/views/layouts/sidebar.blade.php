      <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        @php
                            $user = auth()->user();
                            $userPermissions = checkUserPermissions($user);
                            $permissions = $userPermissions['permissions'];
                            $hasFullAccess = $userPermissions['hasFullAccess'];
                        @endphp
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Main</li>
                            <li class="">
                                <a href="{{route('admin')}}" class="waves-effect {{ request()->is("admin") || request()->is("admin/*") ? "mm active" : "" }}">
                                    <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right"></span> <span> Dashboard </span>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Employees <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="submenu">
                                    <li>
                                        <a href="/employees" class="waves-effect {{ request()->is("employees") || request()->is("/employees/*") ? "mm active" : "" }}"><i class="dripicons-view-apps"></i><span>Employees List</span></a>
                                    </li>

                                </ul>
                            </li> --}}
                            <li class="menu-title">Salon</li>
                            {{-- @if($hasFullAccess || hasPermission($permissions, 'settings')) --}}
                                @php
                                    $canManageSubadmin = $hasFullAccess || hasPermission($permissions, 'subadmin');
                                @endphp
                                @if($canManageSubadmin)
                                    <li>
                                        <a href="/user" class="waves-effect {{ request()->is("user") || request()->is("/user/*") ? "mm active" : "" }}"><i class="ti-id-badge"></i><span>Admin</span></a>
                                    </li>
                                @endif
                            {{-- @endif --}}

                               @php
                                    // Employee List Permission
                                    $canAccessEmployeeList = $hasFullAccess
                                        || hasPermission($permissions, 'employee_list', 'read')
                                        || hasPermission($permissions, 'employee_list', 'write');

                                    // Attendance Permission
                                    $canAccessAttendance = $hasFullAccess
                                        || hasPermission($permissions, 'attendance', 'read')
                                        || hasPermission($permissions, 'attendance', 'write');

                                    // Show Employee Menu if any sub-permission is allowed
                                    $canViewEmployeeMenu = $canAccessEmployeeList || $canAccessAttendance;

                                    // Check Activity for Sidebar Expansion
                                    $employeeMenuOpen = request()->is('employees')
                                        || request()->is('employees/*')
                                        || request()->is('attendance')
                                        || request()->is('attendance/*');
                                @endphp

                             @if($canViewEmployeeMenu)
                                <li class="{{ $employeeMenuOpen ? 'mm-active' : '' }}">
                                    <a href="javascript:void(0);"
                                    class="waves-effect {{ $employeeMenuOpen ? 'active' : '' }}"
                                    aria-expanded="{{ $employeeMenuOpen ? 'true' : 'false' }}">

                                        <i class="ti-user"></i>
                                        <span>
                                            Employees
                                            <span class="float-right menu-arrow">
                                                <i class="mdi mdi-chevron-right"></i>
                                            </span>
                                        </span>
                                    </a>

                                    <ul class="submenu" style="{{ $employeeMenuOpen ? 'display:block;' : '' }}">

                                        @if($canAccessEmployeeList)
                                            <li>
                                                <a href="/employees"
                                                class="waves-effect {{ request()->is('employees') || request()->is('employees/*') ? 'mm active' : '' }}">
                                                    <i class="dripicons-view-apps"></i>
                                                    <span>Employees List</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if($canAccessAttendance)
                                            <li>
                                                <a href="/attendance"
                                                class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                                                    <i class="ti-calendar"></i>
                                                    <span>Attendance</span>
                                                </a>
                                            </li>
                                        @endif
                                        </ul>
                                    </li>
                                @endif

                                @if($hasFullAccess || hasPermission($permissions, 'customers'))
                                    <li>
                                        <a href="/customer" class="waves-effect {{ request()->is("customer") || request()->is("customer/*") ? "mm active" : "" }}">
                                            <i class="ti-stats-up"></i>
                                            <span>Customer</span>
                                        </a>
                                    </li>
                                @endif

                                 {{-- @if($hasFullAccess || hasPermission($permissions, 'customers')) --}}
                                    <li>
                                        <a href="/staffservice" class="waves-effect {{ request()->is("staffservice") || request()->is("staffservice/*") ? "mm active" : "" }}">
                                            <i class="ti-stats-up"></i>
                                            <span>StaffService</span>
                                        </a>
                                    </li>
                                {{-- @endif --}}

                             {{-- @if($hasFullAccess || hasPermission($permissions, 'billing'))
                                <li>
                                    <a href="/bill" class="waves-effect {{ request()->is("bill") || request()->is("bill/*") ? "mm active" : "" }}">
                                        <i class="ti-stats-up"></i>
                                        <span>Billing</span>
                                    </a>
                                </li>
                            @endif --}}

                            @if($hasFullAccess || hasPermission($permissions, 'bill_table', 'read'))
                                <li>
                                    <a href="/bill" class="waves-effect {{ request()->is("bill") || request()->is("bill/*") ? "mm active" : "" }}">
                                        <i class="ti-files"></i>
                                        <span>Bill Table</span>
                                    </a>
                                </li>
                            @endif

                             @if($hasFullAccess || hasPermission($permissions, 'services'))
                                <li>
                                    <a href="/service" class="waves-effect {{ request()->is("service") || request()->is("service/*") ? "mm active" : "" }}">
                                        <i class="ti-stats-up"></i>
                                        <span>Service List</span>
                                    </a>
                                </li>
                            @endif

                             @if($hasFullAccess || hasPermission($permissions, 'branch'))
                                <li>
                                    <a href="/branch" class="waves-effect {{ request()->is("branch") || request()->is("branch/*") ? "mm active" : "" }}">
                                        <i class="ti-stats-up"></i>
                                        <span>Branch List</span>
                                    </a>
                                </li>
                            @endif

                                {{-- <li>
                                    <a href="/appointment" class="waves-effect {{ request()->is("appointment") || request()->is("appointment/*") ? "mm active" : "" }}">
                                        <i class="ti-stats-up"></i>
                                        <span>A Management</span>
                                    </a>
                                </li> --}}
                            {{-- @php $user = auth()->user(); @endphp
                            @if($user && ($user->hasRole('admin') || ($user->hasRole('1')))) --}}

                            {{-- <li class="">
                                <a href="/customer_management" class="waves-effect {{ request()->is("Customer management") || request()->is("/customer management/*") ? "mm active" : "" }}"><i class="ti-user"></i><span>Customer Management</span></a>
                            </li> --}}

                            @if($hasFullAccess || hasPermission($permissions, 'product_list'))
                            <li class="">
                                <a href="/management" class="waves-effect {{ request()->is("management") || request()->is("/management/*") ? "mm active" : "" }}"><i class="fa fa-users" aria-hidden="true"></i><span>Product List</span></a>
                            </li>
                            @endif

                            @if($hasFullAccess || hasPermission($permissions, 'brand'))
                            <li>
                                <a href="/category" class="waves-effect {{ request()->is("category") || request()->is("/category/*") ? "mm active" : "" }}"><i class="ti-layout-grid2"></i><span>Product Brand</span></a>
                            </li>
                            @endif

                            {{-- @php $user = auth()->user(); @endphp
                            @if($user && ($user->hasRole('admin') || ($user->hasRole('1'))))
                                <li>
                                    <a href="/purchase" class="waves-effect {{ request()->is("purchase") || request()->is("purchase/*") ? "mm active" : "" }}">
                                        <i class="ti-shopping-cart"></i>
                                        <span>Purchase</span>
                                    </a>
                                </li>
                                @endif --}}

                                {{-- @php $user = auth()->user(); @endphp
                            @if($user && ($user->hasRole('admin') || ($user->hasRole('1'))))
                                <li>
                                    <a href="/employees" class="waves-effect {{ request()->is("employees") || request()->is("employees/*") ? "mm active" : "" }}">
                                        <i class="ti-stats-up"></i>
                                        <span>Employee</span>
                                    </a>
                                </li>
                                @endif --}}

                                  {{-- @php $user = auth()->user(); @endphp
                            @if($user && ($user->hasRole('admin') || ($user->hasRole('1')))) --}}

                                {{-- @endif --}}

                                {{-- @php $user = auth()->user(); @endphp
                                @if($user && in_array($user->role, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) --}}
                                @if($hasFullAccess || hasPermission($permissions, 'billing'))
                                    <li>
                                        <a href="/billing" class="waves-effect {{ request()->is("billing") || request()->is("billing/*") ? "mm active" : "" }}">
                                            <i class="ti-files"></i>
                                            <span>Sales Report</span>
                                        </a>
                                    </li>
                                @endif
                                {{-- @endif --}}

                                {{-- @php $user = auth()->user(); @endphp
                                @if($user && in_array($user->role, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) --}}
                                    {{-- <li>
                                        <a href="/employees" class="waves-effect {{ request()->is("employees") || request()->is("employees/*") ? "mm active" : "" }}">
                                            <i class="ti-stats-up"></i>
                                            <span>Employee</span>
                                        </a>
                                    </li> --}}
                                    {{-- @endif --}}

                                {{-- @php $user = auth()->user(); @endphp
                                @if($user && $user->hasAnyRole(['admin', 'manager', 'sales']))
                                    <li>
                                        <a href="/purchase" class="waves-effect {{ request()->is("purchase") || request()->is("purchase/*") ? "mm active" : "" }}">
                                            <i class="ti-files"></i>
                                            <span>Sales Bill</span>
                                        </a>
                                    </li>
                                @endif --}}

                            {{-- @php $user = auth()->user(); @endphp
                                @if($user && ($user->hasRole('admin') || ($user->hasRole('1')))) --}}
                                @if($hasFullAccess || hasPermission($permissions, 'bookings'))
                                    <li>
                                        <a href="/booking" class="waves-effect {{ request()->is("booking") || request()->is("/booking/*") ? "mm active" : "" }}"><i class="ti-book"></i><span>Booking</span></a>
                                    </li>
                                @endif
                            {{-- @endif --}}

                            {{-- @php $user = auth()->user(); @endphp
                            @if($user && ($user->hasRole('admin') || ($user->hasRole('1')))) --}}

                            {{-- @endif --}}
                            {{-- <li class="menu-title">Saloon</li> --}}
                        @if($hasFullAccess || hasPermission($permissions, 'student') || hasPermission($permissions, 'course') || hasPermission($permissions, 'trainer'))
                        <li>
                            <a href="javascript:void(0);" class="waves-effect text-success">
                                <i class="ti-graduation-cap text-success"></i>
                                <span>
                                    Academy
                                    <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right text-success"></i></span>
                                </span>
                            </a>
                            <ul class="submenu">
                                @if($hasFullAccess || hasPermission($permissions, 'student'))
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Students <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                    <ul class="submenu">
                                        @if($hasFullAccess || hasPermission($permissions, 'student', 'read'))
                                        <li>
                                            <a href="/student" class="waves-effect {{ request()->is("student") || request()->is("/student/*") ? "mm active" : "" }}"><span>Student Management</span></a>
                                        </li>
                                        <li>
                                            <a href="/student_attendance" class="waves-effect {{ request()->is("student_attendance") || request()->is("/student_attendance/*") ? "mm active" : "" }}"><span>Student Attendance</span></a>
                                        </li>
                                        <li class="">
                                            <a href="/student_sheet_report" class="waves-effect {{ request()->is("student_sheet_report") || request()->is("student_sheet_report/*") ? "mm active" : "" }}">
                                                <span> Student Sheet Report </span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($hasFullAccess || hasPermission($permissions, 'course', 'read'))
                                <li>
                                    <a href="/course" class="waves-effect {{ request()->is("course") || request()->is("/course/*") ? "mm active" : "" }}"><i class="ti-id-badge"></i><span>Course</span></a>
                                </li>
                                @endif

                                @if($hasFullAccess || hasPermission($permissions, 'trainer'))
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Trainer <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                    <ul class="submenu">
                                        @if($hasFullAccess || hasPermission($permissions, 'trainer', 'read'))
                                        <li>
                                            <a href="/staff_management" class="waves-effect {{ request()->is("staff_management") || request()->is("/staff_management/*") ? "mm active" : "" }}"><span>Management</span></a>
                                        </li>
                                        <li>
                                            <a href="/staff_attendance" class="waves-effect {{ request()->is("staff_attendance") || request()->is("/staff_attendance/*") ? "mm active" : "" }}"><span>Attendance</span></a>
                                        </li>
                                        <li class="">
                                            <a href="/staff_management_sheet_report" class="waves-effect {{ request()->is("staff_management_sheet_report") || request()->is("staff_management_sheet_report/*") ? "mm active" : "" }}">
                                                <span>Sheet Report </span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        </ul>
                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>
                </div>
                <!-- Sidebar -left -->
            </div>
            <!-- Left Sidebar End -->

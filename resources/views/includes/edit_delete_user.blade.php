<!-- Edit -->
<div class="modal fade" id="edit{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h4 class="modal-title"><b>Edit Subadmin</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                  <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                            <div class="form-group">
                                <label for="name">Branch Name</label>
                                <select class="select select2s-hidden-accessible form-control" id="branch_id{{ $user->id }}" name="branch_id">
                                    <option disabled {{ empty($user->branch_id) ? 'selected' : '' }}>Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}" data-address="{{ $branch->address }}" {{ (isset($user->branch_id) && $user->branch_id == $branch->id) ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                           <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" class="form-control" placeholder="Auto-filled Address" id="branch-info-place{{ $user->id }}" name="place" value="{{ $user->place }}" readonly />
                            </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" placeholder="Enter Email" id="email" name="email"
                                value="{{ $user->email }}" required />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Enter new password to change" id="password" name="password" />
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            @php
                                $userPermissions = json_decode($user->permissions, true) ?? [];
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="employees{{ $user->id }}" name="permissions[]" value="employees"
                                            {{ isset($userPermissions['employees']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="employees{{ $user->id }}">Manage Employees</label>
                                    </div>
                                    <!-- Employee detailed permissions -->
                                    <div id="employee-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['employees']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employees_read{{ $user->id }}" name="permissions_detail[employees][read]" value="1"
                                                {{ isset($userPermissions['employees_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="employees_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employees_write{{ $user->id }}" name="permissions_detail[employees][write]" value="1"
                                                {{ isset($userPermissions['employees_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="employees_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customers{{ $user->id }}" name="permissions[]" value="customers"
                                            {{ isset($userPermissions['customers']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customers{{ $user->id }}">Manage Customers</label>
                                    </div>
                                    <!-- Customer detailed permissions -->
                                    <div id="customer-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['customers']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customers_read{{ $user->id }}" name="permissions_detail[customers][read]" value="1"
                                                {{ isset($userPermissions['customers_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customers_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customers_write{{ $user->id }}" name="permissions_detail[customers][write]" value="1"
                                                {{ isset($userPermissions['customers_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customers_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="bookings{{ $user->id }}" name="permissions[]" value="bookings"
                                            {{ isset($userPermissions['bookings']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="bookings{{ $user->id }}">Manage Bookings</label>
                                    </div>
                                    <!-- Booking detailed permissions -->
                                    <div id="booking-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['bookings']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bookings_read{{ $user->id }}" name="permissions_detail[bookings][read]" value="1"
                                                {{ isset($userPermissions['bookings_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="bookings_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bookings_write{{ $user->id }}" name="permissions_detail[bookings][write]" value="1"
                                                {{ isset($userPermissions['bookings_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="bookings_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <!-- Services -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="services{{ $user->id }}" name="permissions[]" value="services"
                                            {{ isset($userPermissions['services']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="services{{ $user->id }}">Manage Services</label>
                                    </div>
                                    <!-- Services detailed permissions -->
                                    <div id="services-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['services']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="services_read{{ $user->id }}" name="permissions_detail[services][read]" value="1"
                                                {{ isset($userPermissions['services_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="services_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="services_write{{ $user->id }}" name="permissions_detail[services][write]" value="1"
                                                {{ isset($userPermissions['services_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="services_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="subadmin{{ $user->id }}" name="permissions[]" value="subadmin"
                                            {{ isset($userPermissions['subadmin']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="subadmin{{ $user->id }}">Subadmin Permission</label>
                                    </div>
                                    <!-- Subadmin detailed permissions -->
                                    <div id="subadmin-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['subadmin']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subadmin_read{{ $user->id }}" name="permissions_detail[subadmin][read]" value="1"
                                                {{ isset($userPermissions['subadmin_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="subadmin_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subadmin_write{{ $user->id }}" name="permissions_detail[subadmin][write]" value="1"
                                                {{ isset($userPermissions['subadmin_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="subadmin_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="billing{{ $user->id }}" name="permissions[]" value="billing"
                                            {{ isset($userPermissions['billing']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="billing{{ $user->id }}">Manage Billing</label>
                                    </div>
                                    <!-- Billing detailed permissions -->
                                    <div id="billing-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['billing']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="billing_read{{ $user->id }}" name="permissions_detail[billing][read]" value="1"
                                                {{ isset($userPermissions['billing_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="billing_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="billing_write{{ $user->id }}" name="permissions_detail[billing][write]" value="1"
                                                {{ isset($userPermissions['billing_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="billing_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="billing_add{{ $user->id }}" name="permissions_detail[billing][add]" value="1"
                                                {{ isset($userPermissions['billing_detail']['add']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="billing_add{{ $user->id }}">Add Access</label>
                                        </div>
                                    </div>

                                    <!-- Bill Table -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="bill_table{{ $user->id }}" name="permissions[]" value="bill_table"
                                            {{ isset($userPermissions['bill_table']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="bill_table{{ $user->id }}">Bill Table</label>
                                    </div>
                                    <!-- Bill Table detailed permissions -->
                                    <div id="bill_table-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['bill_table']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bill_table_read{{ $user->id }}" name="permissions_detail[bill_table][read]" value="1"
                                                {{ isset($userPermissions['bill_table_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="bill_table_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bill_table_write{{ $user->id }}" name="permissions_detail[bill_table][write]" value="1"
                                                {{ isset($userPermissions['bill_table_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="bill_table_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="reports{{ $user->id }}" name="permissions[]" value="reports"
                                            {{ isset($userPermissions['reports']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="reports{{ $user->id }}">View Reports</label>
                                    </div>
                                    <!-- Reports detailed permissions -->
                                    <div id="reports-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['reports']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="reports_read{{ $user->id }}" name="permissions_detail[reports][read]" value="1"
                                                {{ isset($userPermissions['reports_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="reports_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="reports_write{{ $user->id }}" name="permissions_detail[reports][write]" value="1"
                                                {{ isset($userPermissions['reports_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="reports_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="settings{{ $user->id }}" name="permissions[]" value="settings"
                                            {{ isset($userPermissions['settings']) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="settings{{ $user->id }}">Manage Settings</label>
                                    </div>
                                    <!-- Settings detailed permissions -->
                                    <div id="settings-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['settings']) ? 'block' : 'none' }};">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="settings_read{{ $user->id }}" name="permissions_detail[settings][read]" value="1"
                                                {{ isset($userPermissions['settings_detail']['read']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="settings_read{{ $user->id }}">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="settings_write{{ $user->id }}" name="permissions_detail[settings][write]" value="1"
                                                {{ isset($userPermissions['settings_detail']['write']) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="settings_write{{ $user->id }}">Writing Access</label>
                                        </div>
                                    </div>

                                    <!-- Academy Sub-permissions -->
                                    <div class="mt-3">
                                        <h6 class="text-muted">Academy Permissions:</h6>

                                        <!-- Student Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="student{{ $user->id }}" name="permissions[]" value="student"
                                                    {{ isset($userPermissions['student']) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="student{{ $user->id }}">Student Management</label>
                                            </div>
                                            <div id="student-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['student']) ? 'block' : 'none' }};">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="student_read{{ $user->id }}" name="permissions_detail[student][read]" value="1"
                                                        {{ isset($userPermissions['student_detail']['read']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="student_read{{ $user->id }}">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="student_write{{ $user->id }}" name="permissions_detail[student][write]" value="1"
                                                        {{ isset($userPermissions['student_detail']['write']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="student_write{{ $user->id }}">Writing Access</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Course Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="course{{ $user->id }}" name="permissions[]" value="course"
                                                    {{ isset($userPermissions['course']) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="course{{ $user->id }}">Course Management</label>
                                            </div>
                                            <div id="course-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['course']) ? 'block' : 'none' }};">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="course_read{{ $user->id }}" name="permissions_detail[course][read]" value="1"
                                                        {{ isset($userPermissions['course_detail']['read']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="course_read{{ $user->id }}">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="course_write{{ $user->id }}" name="permissions_detail[course][write]" value="1"
                                                        {{ isset($userPermissions['course_detail']['write']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="course_write{{ $user->id }}">Writing Access</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Trainer Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="trainer{{ $user->id }}" name="permissions[]" value="trainer"
                                                    {{ isset($userPermissions['trainer']) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="trainer{{ $user->id }}">Trainer Management</label>
                                            </div>
                                            <div id="trainer-details{{ $user->id }}" class="ml-4 mt-2" style="display: {{ isset($userPermissions['trainer']) ? 'block' : 'none' }};">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="trainer_read{{ $user->id }}" name="permissions_detail[trainer][read]" value="1"
                                                        {{ isset($userPermissions['trainer_detail']['read']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="trainer_read{{ $user->id }}">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="trainer_write{{ $user->id }}" name="permissions_detail[trainer][write]" value="1"
                                                        {{ isset($userPermissions['trainer_detail']['write']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="trainer_write{{ $user->id }}">Writing Access</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Update
                                </button>
                                <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Subadmin</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('user.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="text-center">
                        <h6>Are you sure you want to delete this subadmin?</h6>
                        <h2 class="bold">{{ $user->name }}</h2>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-danger btn-flat">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle permission checkboxes
    function setupPermissionCheckbox(mainId, detailsId) {
        const mainCheckbox = document.getElementById(mainId);
        const detailsDiv = document.getElementById(detailsId);

        if (mainCheckbox && detailsDiv) {
            mainCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    detailsDiv.style.display = 'block';
                } else {
                    detailsDiv.style.display = 'none';
                    // Uncheck the detailed permissions when main checkbox is unchecked
                    const detailCheckboxes = detailsDiv.querySelectorAll('input[type="checkbox"]');
                    detailCheckboxes.forEach(function(cb){ cb.checked = false; });
                }
            });
        }
    }

    // Setup all permission checkboxes for this user
    setupPermissionCheckbox('employees{{ $user->id }}', 'employee-details{{ $user->id }}');
    setupPermissionCheckbox('customers{{ $user->id }}', 'customer-details{{ $user->id }}');
    setupPermissionCheckbox('bookings{{ $user->id }}', 'booking-details{{ $user->id }}');
    setupPermissionCheckbox('services{{ $user->id }}', 'services-details{{ $user->id }}');
    setupPermissionCheckbox('subadmin{{ $user->id }}', 'subadmin-details{{ $user->id }}');
    setupPermissionCheckbox('billing{{ $user->id }}', 'billing-details{{ $user->id }}');
    setupPermissionCheckbox('bill_table{{ $user->id }}', 'bill_table-details{{ $user->id }}');
    setupPermissionCheckbox('reports{{ $user->id }}', 'reports-details{{ $user->id }}');
    setupPermissionCheckbox('settings{{ $user->id }}', 'settings-details{{ $user->id }}');

    // Setup Academy sub-permissions for this user
    setupPermissionCheckbox('student{{ $user->id }}', 'student-details{{ $user->id }}');
    setupPermissionCheckbox('course{{ $user->id }}', 'course-details{{ $user->id }}');
    setupPermissionCheckbox('trainer{{ $user->id }}', 'trainer-details{{ $user->id }}');
});
</script>
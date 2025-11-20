@php
    $authUser = auth()->user();
    $permissionMeta = checkUserPermissions($authUser);
    $authPermissions = $permissionMeta['permissions'];
    $authHasFullAccess = $permissionMeta['hasFullAccess'];
    $canCreateSubadmin = $authHasFullAccess || hasPermission($authPermissions, 'subadmin', 'write');
@endphp

@if($canCreateSubadmin)
<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Create Subadmin</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('user.store') }}">
                        @csrf
                        {{-- <div class="form-group">
                            <label for="name">Branch name</label>
                            <input type="text" class="form-control" placeholder="Enter branch Name" id="name" name="name"
                                required />
                        </div> --}}

                        <div class="form-group">
                                <label for="name">Branch Name</label>
                                <select class="select select2s-hidden-accessible form-control" id="branch_id" name="branch_id">
                                    <option selected disabled>Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}"
                                            data-name="{{ $branch->name }}"
                                            data-address="{{ $branch->address }}"
                                            data-place="{{ $branch->place }}"
                                            data-email="{{ $branch->email }}">
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                            <div class="form-group">
                                <label for="name">Address</label>
                                <input type="text" class="form-control" placeholder="Auto-filled Address" id="branch-info-place" name="place" readonly />
                            </div>

                        <div class="form-group">
                            <label for="name">User ID</label>
                            <input type="text" class="form-control" placeholder="Enter User ID" id="email" name="email"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" placeholder="Enter Password" id="password" name="password"
                                required />
                        </div>
                        {{-- <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="subadmin">Subadmin</option>
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="employees" name="permissions[]" value="employees">
                                        <label class="custom-control-label" for="employees">Manage Employees</label>
                                    </div>
                                    <!-- Employee detailed permissions -->
                                    <div id="employee-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employees_read" name="permissions_detail[employees][read]" value="1">
                                            <label class="custom-control-label" for="employees_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employees_write" name="permissions_detail[employees][write]" value="1">
                                            <label class="custom-control-label" for="employees_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="employee_list" name="permissions[]" value="employee_list">
                                        <label class="custom-control-label" for="employee_list">Employee List</label>
                                    </div>
                                    <div id="employee_list-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employee_list_read" name="permissions_detail[employee_list][read]" value="1">
                                            <label class="custom-control-label" for="employee_list_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employee_list_write" name="permissions_detail[employee_list][write]" value="1">
                                            <label class="custom-control-label" for="employee_list_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="attendance" name="permissions[]" value="attendance">
                                        <label class="custom-control-label" for="attendance">Attendance</label>
                                    </div>
                                    <div id="attendance-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="attendance_read" name="permissions_detail[attendance][read]" value="1">
                                            <label class="custom-control-label" for="attendance_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="attendance_write" name="permissions_detail[attendance][write]" value="1">
                                            <label class="custom-control-label" for="attendance_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customers" name="permissions[]" value="customers">
                                        <label class="custom-control-label" for="customers">Manage Customers</label>
                                    </div>
                                    <!-- Customer detailed permissions -->
                                    <div id="customer-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customers_read" name="permissions_detail[customers][read]" value="1">
                                            <label class="custom-control-label" for="customers_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customers_write" name="permissions_detail[customers][write]" value="1">
                                            <label class="custom-control-label" for="customers_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="services" name="permissions[]" value="services">
                                        <label class="custom-control-label" for="services">Manage Services</label>
                                    </div>
                                    <!-- Services detailed permissions -->
                                    <div id="services-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="services_read" name="permissions_detail[services][read]" value="1">
                                            <label class="custom-control-label" for="services_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="services_write" name="permissions_detail[services][write]" value="1">
                                            <label class="custom-control-label" for="services_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="bookings" name="permissions[]" value="bookings">
                                        <label class="custom-control-label" for="bookings">Manage Bookings</label>
                                    </div>
                                    <!-- Booking detailed permissions -->
                                    <div id="booking-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bookings_read" name="permissions_detail[bookings][read]" value="1">
                                            <label class="custom-control-label" for="bookings_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bookings_write" name="permissions_detail[bookings][write]" value="1">
                                            <label class="custom-control-label" for="bookings_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="subadmin" name="permissions[]" value="subadmin">
                                        <label class="custom-control-label" for="subadmin">Subadmin Permission</label>
                                    </div>
                                    <!-- Subadmin detailed permissions -->
                                    <div id="subadmin-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subadmin_read" name="permissions_detail[subadmin][read]" value="1">
                                            <label class="custom-control-label" for="subadmin_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="subadmin_write" name="permissions_detail[subadmin][write]" value="1">
                                            <label class="custom-control-label" for="subadmin_write">Writing Access</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="billing" name="permissions[]" value="billing">
                                        <label class="custom-control-label" for="billing">Manage Billing</label>
                                    </div>
                                    <!-- Billing detailed permissions -->
                                    <div id="billing-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="billing_read" name="permissions_detail[billing][read]" value="1">
                                            <label class="custom-control-label" for="billing_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="billing_write" name="permissions_detail[billing][write]" value="1">
                                            <label class="custom-control-label" for="billing_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="bill_table" name="permissions[]" value="bill_table">
                                        <label class="custom-control-label" for="bill_table">Bill Table</label>
                                    </div>
                                    <!-- Bill Table detailed permissions -->
                                    <div id="bill_table-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bill_table_read" name="permissions_detail[bill_table][read]" value="1">
                                            <label class="custom-control-label" for="bill_table_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="bill_table_write" name="permissions_detail[bill_table][write]" value="1">
                                            <label class="custom-control-label" for="bill_table_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="reports" name="permissions[]" value="reports">
                                        <label class="custom-control-label" for="reports">View Reports</label>
                                    </div>
                                    <!-- Reports detailed permissions -->
                                    <div id="reports-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="reports_read" name="permissions_detail[reports][read]" value="1">
                                            <label class="custom-control-label" for="reports_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="reports_write" name="permissions_detail[reports][write]" value="1">
                                            <label class="custom-control-label" for="reports_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="settings" name="permissions[]" value="settings">
                                        <label class="custom-control-label" for="settings">Product</label>
                                    </div>
                                    <!-- Settings detailed permissions -->
                                    <div id="settings-details" class="ml-4 mt-2" style="display: none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="settings_read" name="permissions_detail[settings][read]" value="1">
                                            <label class="custom-control-label" for="settings_read">Reading Access</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="settings_write" name="permissions_detail[settings][write]" value="1">
                                            <label class="custom-control-label" for="settings_write">Writing Access</label>
                                        </div>
                                    </div>

                                    <!-- Academy Sub-permissions -->
                                    <div class="mt-3">
                                        <h6 class="text-muted">Academy Permissions:</h6>

                                        <!-- Student Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="student" name="permissions[]" value="student">
                                                <label class="custom-control-label" for="student">Student Management</label>
                                            </div>
                                            <div id="student-details" class="ml-4 mt-2" style="display: none;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="student_read" name="permissions_detail[student][read]" value="1">
                                                    <label class="custom-control-label" for="student_read">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="student_write" name="permissions_detail[student][write]" value="1">
                                                    <label class="custom-control-label" for="student_write">Writing Access</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Course Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="course" name="permissions[]" value="course">
                                                <label class="custom-control-label" for="course">Course Management</label>
                                            </div>
                                            <div id="course-details" class="ml-4 mt-2" style="display: none;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="course_read" name="permissions_detail[course][read]" value="1">
                                                    <label class="custom-control-label" for="course_read">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="course_write" name="permissions_detail[course][write]" value="1">
                                                    <label class="custom-control-label" for="course_write">Writing Access</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Trainer Permissions -->
                                        <div class="ml-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="trainer" name="permissions[]" value="trainer">
                                                <label class="custom-control-label" for="trainer">Trainer Management</label>
                                            </div>
                                            <div id="trainer-details" class="ml-4 mt-2" style="display: none;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="trainer_read" name="permissions_detail[trainer][read]" value="1">
                                                    <label class="custom-control-label" for="trainer_read">Reading Access</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="trainer_write" name="permissions_detail[trainer][write]" value="1">
                                                    <label class="custom-control-label" for="trainer_write">Writing Access</label>
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
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle permission checkboxes
    function setupPermissionCheckbox(mainId, detailsId) {
        const mainCheckbox = document.getElementById(mainId);
        const detailsDiv = document.getElementById(detailsId);

        if (mainCheckbox && detailsDiv) {
            const toggleDetails = () => {
                if (mainCheckbox.checked) {
                    detailsDiv.style.display = 'block';
                } else {
                    detailsDiv.style.display = 'none';
                    // Uncheck all detailed permissions when the main checkbox is unchecked
                    detailsDiv.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            };

            mainCheckbox.addEventListener('change', toggleDetails);
            toggleDetails();
        }
    }

    // Setup all permission checkboxes
    setupPermissionCheckbox('employees', 'employee-details');
    setupPermissionCheckbox('customers', 'customer-details');
    setupPermissionCheckbox('services', 'services-details');
    setupPermissionCheckbox('bookings', 'booking-details');
    setupPermissionCheckbox('subadmin', 'subadmin-details');
    setupPermissionCheckbox('employee_list', 'employee_list-details');
    setupPermissionCheckbox('attendance', 'attendance-details');
    setupPermissionCheckbox('billing', 'billing-details');
    setupPermissionCheckbox('bill_table', 'bill_table-details');
    setupPermissionCheckbox('reports', 'reports-details');
    setupPermissionCheckbox('settings', 'settings-details');

    // Setup Academy sub-permissions
    setupPermissionCheckbox('student', 'student-details');
    setupPermissionCheckbox('course', 'course-details');
    setupPermissionCheckbox('trainer', 'trainer-details');
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var branchSelect = document.getElementById('branch_id');
        var placeInput = document.getElementById('branch-info-place');
        var emailInput = document.getElementById('email');
        var passwordInput = document.getElementById('password');

        function updateBranchFields() {
            if (!branchSelect) return;

            var selected = branchSelect.options[branchSelect.selectedIndex];

            if (selected && selected.value && selected.value !== 'Select Branch') {
                // Get branch data from data attributes
                var branchName = selected.getAttribute('data-name') || '';
                var branchAddress = selected.getAttribute('data-address') || '';
                var branchPlace = selected.getAttribute('data-place') || '';
                var branchEmail = selected.getAttribute('data-email') || '';

                // Auto-fill address field (prefer address, fallback to place, then name)
                if (placeInput) {
                    placeInput.value = branchAddress || branchPlace || branchName;
                }

                // Auto-generate email based on branch (optional - can be customized)
                if (emailInput && !emailInput.value) {
                    // Generate email from branch name (lowercase, replace spaces with dots)
                    var emailSuggestion = branchName.toLowerCase().replace(/\s+/g, '.') + '@unika.com';
                    emailInput.value = emailSuggestion;
                }

                // Auto-generate password based on branch (optional - can be customized)
                if (passwordInput && !passwordInput.value) {
                    // Generate password from branch name (first 3 letters + 123456)
                    var passwordSuggestion = branchName.substring(0, 3).toLowerCase() + '123456';
                    passwordInput.value = passwordSuggestion;
                }
            } else {
                // Clear fields if no branch selected
                if (placeInput) placeInput.value = '';
            }
        }

        if (branchSelect) {
            branchSelect.addEventListener('change', updateBranchFields);

            // Also trigger on modal open if branch is already selected
            $(document).on('shown.bs.modal', '#addnew', function() {
                updateBranchFields();
            });
        }
    });
</script>

@endif

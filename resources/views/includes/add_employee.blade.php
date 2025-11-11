<!-- Add Employee Modal -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h4 class="modal-title"><b>Add Employee</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Employee Name -->
                        <div class="form-group">
                            <label for="employee_name">Employee Name</label>
                            <input type="text" class="form-control" placeholder="Employee Name" id="employee_name" name="employee_name" required>
                        </div>

                        <!-- Employee Phone Number -->
                        <div class="form-group">
                            <label for="employee_number">Employee Phone Number</label>
                            <input type="number" class="form-control" placeholder="Number" id="employee_number" name="employee_number" required>
                        </div>

                        <!-- Employee Email -->
                        <div class="form-group">
                            <label for="employee_email" class="control-label">Employee Email</label>
                            <input type="email" class="form-control" id="employee_email" name="employee_email" required>
                        </div>

                        @php($authUser = auth()->user())
                        @if($authUser && method_exists($authUser, 'roles') && $authUser->roles()->where('slug','subadmin')->exists() && isset($authUser->branch_id))
                            <input type="hidden" name="branch_id" value="{{ $authUser->branch_id }}">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Branch</label>
                                <input type="text" class="form-control" value="{{ optional($Branch->first())->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="placeSelect" class="col-sm-5 control-label">Place</label>
                                <input type="text" class="form-control" id="branch-info-place" name="place" value="{{ optional($Branch->first())->place ?? optional($Branch->first())->address }}" readonly>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="branch_id" class="col-sm-6 control-label">Branch</label>
                                    <select class="select select2s-hidden-accessible form-control" id="branch_id" name="branch_id">
                                        <option selected disabled>Select Branch</option>
                                        @foreach($Branch as $branch)
                                            <option value="{{ $branch->id }}" data-place="{{ $branch->place }}" data-address="{{ $branch->address }}">
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="placeSelect" class="col-sm-5 control-label">Place</label>
                                <input type="text" class="form-control" id="branch-info-place" name="place" readonly>
                            </div>
                        @endif

                        <!-- Join Date -->
                        <div class="form-group">
                            <label for="joining_date">Join Date</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date" autofocus required>
                        </div>

                        <!-- Aadhar Card Number -->
                        <div class="form-group">
                            <label for="aadhar_card">Aadhar Card Number</label>
                            <input type="text" class="form-control" placeholder="Aadhar Card" id="aadhar_card" name="aadhar_card" required>
                        </div>

                        <!-- Emergency Contact Section -->
                        <label for="emergency_name"><u>Emergency Contact</u></label>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Full name" id="emergency_name" name="emergency_name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Emergency Number" id="emergency_number" name="emergency_number" required>
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">--Select--</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Age -->
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="text" class="form-control" placeholder="Age" id="age" name="age" required>
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" autofocus required>
                        </div>

                        <!-- Qualification -->
                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <input type="text" class="form-control" placeholder="Qualification" id="qualification" name="qualification">
                        </div>

                        <!-- Certificate Upload -->
                        <div class="form-group">
                            <label for="certificate">Certificate</label>
                            <input type="file" class="form-control" id="certificate" name="certificate" accept="image/*,application/pdf" onchange="previewCertificate()">
                            <!-- Preview container -->
                            <div id="certificate-preview" style="margin-top: 10px;"></div>
                        </div>

                        <!-- Previous Employment Details (Collapsible) -->
                        <label type="checkbox" for="privacy-toggle" style="cursor:pointer;" onclick="togglePrivacyDetails()">
                            <u>Previous Employment Details</u>
                        </label>
                        <div id="privacy-section" style="display:none;">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Company Name" id="company" name="company">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Location" id="team" name="team">
                            </div>
                            <div class="form-group">
                                <select class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience">
                                    <option value="">--Select Year OF Experience --</option>
                                    <option value="0 to 1" {{ old('experience') == '0 to 1' ? 'selected' : '' }}>0 to 1</option>
                                    <option value="1 to 2" {{ old('experience') == '1 to 2' ? 'selected' : '' }}>1 to 2</option>
                                    <option value="2 to 3" {{ old('experience') == '2 to 3' ? 'selected' : '' }}>2 to 3</option>
                                    <option value="3 to 4" {{ old('experience') == '3 to 4' ? 'selected' : '' }}>3 to 4</option>
                                    <option value="4 to 5" {{ old('experience') == '4 to 5' ? 'selected' : '' }}>4 to 5</option>
                                    <option value="5 to 6" {{ old('experience') == '5 to 6' ? 'selected' : '' }}>5 to 6</option>
                                    <option value="6 to 7" {{ old('experience') == '6 to 7' ? 'selected' : '' }}>6 to 7</option>
                                    <option value="7 to 8" {{ old('experience') == '7 to 8' ? 'selected' : '' }}>7 to 8</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Role" id="role" name="role">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Previous salary" id="old_salary" name="old_salary">
                            </div>
                        </div>
                        <br>

                        <!-- Designation Section -->
                        <label for="position"><u>Designation</u></label>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Position" id="position" name="position" required>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" placeholder="Salary" id="salary" name="salary" required>
                        </div>

                        <!-- Employee Address -->
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea type="text" class="form-control" placeholder="Address" id="address" name="address"></textarea>
                        </div>

                        <!-- Form Actions -->
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

<!-- JavaScript Functions -->
<script>
    // Toggle privacy details section
    function togglePrivacyDetails() {
        var section = document.getElementById('privacy-section');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    }

    // Preview certificate file
    function previewCertificate() {
        const fileInput = document.getElementById('certificate');
        const preview = document.getElementById('certificate-preview');
        preview.innerHTML = '';

        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();

        if (file.type.startsWith('image/')) {
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.marginTop = '5px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            const pdfNotice = document.createElement('p');
            pdfNotice.textContent = 'PDF uploaded: ' + file.name;
            preview.appendChild(pdfNotice);
        } else {
            preview.textContent = 'Unsupported file type.';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var branchSelect = document.getElementById('branch_id');
        var placeInput = document.getElementById('branch-info-place');

        if (!branchSelect || !placeInput) { return; }

        function updatePlaceFromBranch() {
            var selected = branchSelect.options[branchSelect.selectedIndex];
            if (!selected) { placeInput.value = ''; return; }
            var place = selected.getAttribute('data-address') || selected.getAttribute('data-place') || '';
            placeInput.value = place;
        }

        branchSelect.addEventListener('change', updatePlaceFromBranch);

        // Initialize on load if a branch is preselected
        updatePlaceFromBranch();
    });
</script>

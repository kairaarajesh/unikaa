<!-- Edit Employee Modal -->
<div class="modal fade" id="edit{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Edit Employee</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-left">
                <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Employee Name</label>
                        <input type="text" name="employee_name" class="form-control @error('employee_name') is-invalid @enderror" value="{{ old('employee_name', $employee->employee_name) }}">
                        @error('employee_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                     <div class="form-group">
                        <label>Employee Phone Number</label>
                        <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror" value="{{ old('employee_number', $employee->employee_number) }}">
                        @error('employee_number')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Employee Email</label>
                        <input type="email" name="employee_email" class="form-control @error('employee_email') is-invalid @enderror" value="{{ old('employee_email', $employee->employee_email) }}">
                        @error('employee_email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" value="{{ $employee->employee_id }}">
                    </div>

                     <div class="form-group">
                            <label for="name">Branch</label>
                                    <select class="select select2s-hidden-accessible form-control @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}" data-address="{{ $branch->address }}" data-place="{{ $branch->place }}"
                                            {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                        </div>
                         <div class="form-group">
                                <label for="name">Place</label>
                                <input type="text" class="form-control" placeholder="Auto-filled Address" id="branch-info-place" name="place" value="{{ old('place', $employee->place) }}" readonly />
                            </div>
                    <div class="form-group">
                        <label>Join Date</label>
                        <input type="datetime-local" id="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('Y-m-d\\TH:i') : '') }}" autofocus>
                        @error('joining_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Aadhar Card</label>
                        <input type="number" name="aadhar_card" class="form-control @error('aadhar_card') is-invalid @enderror" value="{{ old('aadhar_card', $employee->aadhar_card) }}">
                        @error('aadhar_card')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <label for="name"><u>Emergency Contact</u></label>
                   <div class="form-group">
                        <label>Emergency Name</label>
                        <input type="text" name="emergency_name" class="form-control @error('emergency_name') is-invalid @enderror" value="{{ old('emergency_name', $employee->emergency_name) }}">
                        @error('emergency_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Emergency Number</label>
                        <input type="number" name="emergency_number" class="form-control @error('emergency_number') is-invalid @enderror" value="{{ old('emergency_number', $employee->emergency_number) }}">
                        @error('emergency_number')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                   <div class="form-group">
                        <label>Gender</label>
                           <select class="select select2s-hidden-accessible form-control @error('gender') is-invalid @enderror" name="gender" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" value="{{ $employee->age }}">
                    </div>
                     <div class="form-group">
                        <label>DOB</label>
                        <input type="datetime-local" name="dob" class="form-control" value="{{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('Y-m-d\\TH:i') : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control" value="{{ $employee->qualification }}">
                    </div>
                    <div class="form-group">
                        <label>Certificate</label>
                        @if(!empty($employee->certificate))
                            <div class="mb-2">
                                <img src="{{ $employee->certificate }}" alt="Certificate" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        @endif
                        <input type="file" name="certificate" class="form-control @error('certificate') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">Upload new certificate image to replace existing one</small>
                        @error('certificate')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                        <div class="form-group">
                             <label for="old-company-toggle" style="cursor:pointer; color: #007bff; text-decoration: underline;" onclick="togglePrivacyDetails()">
                                 Old Company Details <i class="fa fa-plus-circle" aria-hidden="true"></i>
                             </label>
                             <div id="privacy-sections" style="display:none; border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-top: 10px; background-color: #f8f9fa;">
                                 <div class="form-group">
                                     <label for="company">Company Name</label>
                                     <input type="text" class="form-control old-company-input" id="company" name="company" placeholder="Enter company name" value="{{ $employee->company }}" onclick="openOldCompanySection()"/>
                                 </div>
                                 <div class="form-group">
                                     <label for="team">Location</label>
                                     <input type="text" class="form-control old-company-input" id="team" name="team" placeholder="Enter location" value="{{ $employee->team }}" onclick="openOldCompanySection()"/>
                                 </div>
                                 <div class="form-group">
                                     <label for="experience">Experience</label>
                                     <select class="form-control old-company-input @error('experience') is-invalid @enderror" id="experience" name="experience" onclick="openOldCompanySection()">
                                         <option value="">-- Select Year OF Experience --</option>
                                         @php
                                             $experienceOptions = [
                                                 '0 to 1', '1 to 2', '2 to 3', '3 to 4',
                                                 '4 to 5', '5 to 6', '6 to 7', '7 to 8'
                                             ];
                                             $selectedExperience = old('experience', $employee->experience ?? '');
                                         @endphp
                                         @foreach ($experienceOptions as $option)
                                             <option value="{{ $option }}" {{ $selectedExperience == $option ? 'selected' : '' }}>
                                                 {{ $option }}
                                             </option>
                                         @endforeach
                                     </select>
                                     @error('experience')
                                         <span class="invalid-feedback d-block">{{ $message }}</span>
                                     @enderror
                                 </div>
                                 <div class="form-group">
                                     <label for="role">Role</label>
                                     <input type="text" class="form-control old-company-input" id="role" name="role" placeholder="Enter previous role" value="{{ $employee->role }}" onclick="openOldCompanySection()"/>
                                 </div>
                                 <div class="form-group">
                                     <label for="old_salary">Previous Salary</label>
                                     <input type="number" class="form-control old-company-input" id="old_salary" name="old_salary" placeholder="Enter previous salary" value="{{ $employee->old_salary }}" onclick="openOldCompanySection()"/>
                                 </div>
                             </div>
                         </div>
                     <br>
                 <label for="name"><u>Designation</u></label>
                  <label for="experience">Position</label>
                    <div class="form-group">
                        <input type="text" name="position" class="form-control" value="{{ $employee->position }}">
                    </div>
                    <label for="experience">Salary</label>
                     <div class="form-group">
                        <input type="number" name="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary', $employee->salary) }}">
                        @error('salary')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label>Employee Status</label>
                           <select class="select select2s-hidden-accessible form-control" name="employee_status" style="width: 100%;" @error('employee_status') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Active" {{ old('employee_status',$employee->employee_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="InActive" {{ old('employee_status',$employee->employee_status) == 'InActive' ? 'selected' : '' }}>InActive</option>
                             </select>
                    </div> --}}

                    {{-- <div class="form-group">
                        <label>Team</label>
                        <input type="text" name="team" class="form-control" value="{{ $employee->team }}">
                    </div> --}}


                    {{-- <label for="name"><u>Address</u></label>
                    <div class="form-group">
                        <label>Street</label>
                        <input type="text" name="street" class="form-control" value="{{ $employee->street }}">
                    </div>
                     <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ $employee->city }}">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="{{ $employee->state }}">
                    </div>
                    <div class="form-group">
                        <label>Pin Code</label>
                        <input type="number" name="pin_code" class="form-control" value="{{ $employee->pin_code }}">
                    </div> --}}

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Enter complete address">{{ $employee->address }}</textarea>
                    </div>
               </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                        <i class="fa fa-close"></i> Close
                    </button>
                    <button type="submit" class="btn btn-success btn-flat" name="edit">
                        <i class="fa fa-check-square-o"></i> Update
                    </button>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete Employee</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('employees.destroy', $employee) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_id">{{$employee->employee_id}}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view{{ $employee->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>View Employee</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-left">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <tbody>
                            <tr><th>Emp Id</th><td>{{ $employee->employee_id }}</td></tr>
                            <tr><th>Emp Name</th><td>{{ $employee->employee_name }}</td></tr>
                            <tr><th>Emp Number</th><td>{{ $employee->employee_number }}</td></tr>
                            <tr><th>Emp Email</th><td>{{ $employee->employee_email }}</td></tr>
                            <tr><th>Branch</th><td>{{ $employee->branch?->name ?? 'N/A' }}</td></tr>
                            <tr><th>Place</th><td>{{ $employee->place }}</td></tr>
                            <tr><th>Join Date</th><td>{{ $employee->joining_date }}</td></tr>
                            <tr><th>Qualification</th><td>{{ $employee->qualification }}</td></tr>
                            <tr>
                                <th>Certificate</th>
                                <td>
                                    @if(!empty($employee->certificate))
                                        <a href="{{ $employee->certificate }}" target="_blank">
                                            <img src="{{ $employee->certificate }}" alt="Certificate" style="max-width: 120px; max-height: 120px;">
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><th>Position</th><td>{{ $employee->position }}</td></tr>
                            <tr><th>Salary</th><td>{{ $employee->salary }}</td></tr>
                            <tr><th>Gender</th><td>{{ $employee->gender }}</td></tr>
                            <tr><th>DOB</th><td>{{ $employee->dob }}</td></tr>
                            <tr><th>Age</th><td>{{ $employee->age }}</td></tr>
                            <tr><th>Address</th><td>{{ $employee->address }}</td></tr>
                            {{-- <tr><th>City</th><td>{{ $employee->city }}</td></tr>
                            <tr><th>State</th><td>{{ $employee->state }}</td></tr>
                            <tr><th>Pin code</th><td>{{ $employee->pin_code }}</td></tr> --}}
                            <tr><th>Aadhar card</th><td>{{ $employee->aadhar_card }}</td></tr>
                            <tr><th>Emergency Name</th><td>{{ $employee->emergency_name }}</td></tr>
                            <tr><th>Emergency Number</th><td>{{ $employee->emergency_number }}</td></tr>
                            <tr><th>Company</th><td>{{ $employee->company }}</td></tr>
                            <tr><th>Experience</th><td>{{ $employee->experience }}</td></tr>
                            <tr><th>Role</th><td>{{ $employee->role }}</td></tr>
                            <tr><th>Privacy salary</th><td>{{ $employee->old_salary }}</td></tr>
                            <tr><th>Location</th><td>{{ $employee->team }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach($employees as $employee)
            const amountInput{{ $employee->id }} = document.getElementById('amount_{{ $employee->id }}');
            const discountInput{{ $employee->id }} = document.getElementById('discount_{{ $employee->id }}');
            const totalAmountInput{{ $employee->id }} = document.getElementById('total_amount_{{ $employee->id }}');
            // console.log('tesgggt', amountInput{{ $employee->id }});
            function calculateTotal{{ $employee->id }}() {
                const amount = parseFloat(amountInput{{ $employee->id }}.value) || 0;
                const discountPercent = parseFloat(discountInput{{ $employee->id }}.value) || 0;
            //    console.log('test',amount);
            //    console.log('test1',discountPercent);
                const discountAmount = (amount * discountPercent) / 100;
                const total = amount - discountAmount;
                // console.log('discountAmount',discountAmount);
                // console.log('total',total);
                totalAmountInput{{ $employee->id }}.value = total >= 0 ? total.toFixed(2) : '0.00';
            }

            amountInput{{ $employee->id }}.addEventListener('input', calculateTotal{{ $employee->id }});
            discountInput{{ $employee->id }}.addEventListener('input', calculateTotal{{ $employee->id }});

            calculateTotal{{ $employee->id }}();
        @endforeach
    });
</script>
@endsection



<script>
    function togglePrivacyDetails() {
        const section = document.getElementById("privacy-sections");
        const toggleLabel = document.querySelector('label[for="old-company-toggle"]');
        const icon = toggleLabel.querySelector('i');

        if (section.style.display === "none" || section.style.display === "") {
            section.style.display = "block";
            icon.className = "fa fa-minus-circle";
            toggleLabel.style.color = "#dc3545";
        } else {
            section.style.display = "none";
            icon.className = "fa fa-plus-circle";
            toggleLabel.style.color = "#007bff";
        }
    }

    function openOldCompanySection() {
        const section = document.getElementById("privacy-sections");
        const toggleLabel = document.querySelector('label[for="old-company-toggle"]');
        const icon = toggleLabel.querySelector('i');

        // Open the section if it's closed
        if (section.style.display === "none" || section.style.display === "") {
            section.style.display = "block";
            icon.className = "fa fa-minus-circle";
            toggleLabel.style.color = "#dc3545";
        }
    }

    window.addEventListener('DOMContentLoaded', function () {
        const company = "{{ old('company', $employee->company ?? '') }}";
        const toggleLabel = document.querySelector('label[for="old-company-toggle"]');
        const icon = toggleLabel.querySelector('i');

        if (company) {
            document.getElementById("privacy-sections").style.display = "block";
            icon.className = "fa fa-minus-circle";
            toggleLabel.style.color = "#dc3545";
        }

        // Add click event listeners to all old company input fields
        const oldCompanyInputs = document.querySelectorAll('.old-company-input');
        oldCompanyInputs.forEach(function(input) {
            input.addEventListener('click', openOldCompanySection);
        });

        // Handle branch selection and auto-fill place
        const branchSelect = document.getElementById('branch_id');
        const placeInput = document.getElementById('branch-info-place');

        if (branchSelect && placeInput) {
            // Set initial place value if branch is already selected
            const selectedOption = branchSelect.options[branchSelect.selectedIndex];
            if (selectedOption && selectedOption.value !== '') {
                var address = selectedOption.getAttribute('data-address') || '';
                var place = selectedOption.getAttribute('data-place') || '';

                // Format the address properly: Place, Address
                var formattedAddress = '';
                if (place && address) {
                    formattedAddress = place + ', ' + address;
                } else if (place) {
                    formattedAddress = place;
                } else if (address) {
                    formattedAddress = address;
                }

                placeInput.value = formattedAddress;
            }

            // Handle branch change
            branchSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value !== '') {
                    var address = selectedOption.getAttribute('data-address') || '';
                    var place = selectedOption.getAttribute('data-place') || '';

                    // Format the address properly: Place, Address
                    var formattedAddress = '';
                    if (place && address) {
                        formattedAddress = place + ', ' + address;
                    } else if (place) {
                        formattedAddress = place;
                    } else if (address) {
                        formattedAddress = address;
                    }

                    placeInput.value = formattedAddress;
                } else {
                    placeInput.value = '';
                }
            });
        }
    });
</script>


<script>
function myFunction() {
  let x = document.getElementById("date").autofocus;
  document.getElementById("demo").innerHTML = x;
}
</script>
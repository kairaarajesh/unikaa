<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Student</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('student.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Student Name</label>
                            <input type="text" class="form-control" placeholder="Student Name" id="student_name" name="student_name"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Student ID</label>
                            <input type="text" class="form-control" placeholder="Student ID" id="student_id" name="student_id"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="text" class="form-control" placeholder="Email" id="email" name="email"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Number</label>
                            <input type="text" class="form-control" placeholder="Number" id="number" name="number"
                                required />
                        </div>
                         <div class="form-group">
                            <label for="name">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option> --Select-- </option>
                                         <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                        </div>
                        <div class="form-group">
                            <label for="name">DOB</label>
                            <input type="date" class="form-control" placeholder="Enter DOB" id="dob" name="dob"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Joining Date</label>
                            <input type="date" class="form-control timepicker" id="joining_date" name="joining_date"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Aadhar Card Number</label>
                            <input type="text" class="form-control" placeholder="Aadhar Card" id="aadhar_card" name="aadhar_card"
                                required />
                        </div>
                         <div class="form-group">
                            <label for="name">Fees Status</label>
                                    <select class="form-control @error('fees_status') is-invalid @enderror" id="fees_status" name="fees_status">
                                        <option> --Select-- </option>
                                         <option value="Paid" {{ old('fees_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Unpaid" {{ old('fees_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Payment History</label>
                            <input type="text" class="form-control" placeholder="Payment History" id="Aadhar" name="payment_history"
                                required />
                        </div>
                         <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Staff Management</label>
                            <select class="select select2s-hidden-accessible form-control" id="staff_management_id" name="staff_management_id">
                                <option selected disabled>Select Staff Management</option>
                                @foreach($staff_managements as $staff_management)
                                    <option value="{{ $staff_management->id }}" data-amount="{{ $staff_management->trainer }}">
                                        {{ $staff_management->trainer }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                     <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Course Name</label>
                            <select class="select select2s-hidden-accessible form-control" id="course_id" name="course_id">
                                <option selected disabled>Select Course Name</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-amount="{{ $course->name }}">
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                   <div class="form-group">
                        <label for="batch_timing" class="col-sm-3 control-label">Course Time</label>
                        <select class="select select2s-hidden-accessible form-control" id="batch_timing" name="batch_timing">
                            <option selected disabled>Select Course Time</option>
                            @foreach($courses as $course)
                                @php
                                    $formattedStart = \Carbon\Carbon::createFromFormat('H:i:s', $course->start_time)->format('h:i A');
                                    $formattedEnd = \Carbon\Carbon::createFromFormat('H:i:s', $course->end_time)->format('h:i A');
                                @endphp
                                <option value="{{ $course->id }}" data-start="{{ $formattedStart }}" data-end="{{ $formattedEnd }}">
                                    {{ $formattedStart }} - {{ $formattedEnd }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                         <label for="name">Address*</label>
                         <div class="form-group">
                            <input type="text" class="form-control" placeholder="Street" id="street" name="street"
                                required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="City" id="city" name="city"
                                required />
                        </div>
                         <div class="form-group">
                            <input type="text" class="form-control" placeholder="State" id="state" name="state"
                                required />
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" placeholder="Pin code" id="pin_code" name="pin_code"
                                required />
                        </div>
                        <label for="name">Emergency Contact</label>
                         <div class="form-group">
                            <input type="text" class="form-control" placeholder="Full name" id="emergency_name" name="emergency_name"
                                required />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Emergency Number" id="emergency_number" name="emergency_number"
                                required />
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
</div>

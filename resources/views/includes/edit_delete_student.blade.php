<!-- Edit -->
<div class="modal fade" id="edit{{ $student->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit Student</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('student.update', $student) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-sm-6 control-label">Student Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="student_name" name="student_name" value="{{ $student->student_name }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Student ID</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="student_id" name="student_id" value="{{ $student->student_id }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" value="{{ $student->email }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Number</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="number" name="number" value="{{ $student->number }}" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Gender</label>
                           <select class="select select2s-hidden-accessible form-control" name="gender" style="width: 100%;" @error('gender') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('Male',$student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('Female',$student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                    </div>
                      <div class="form-group">
                            <label>DOB</label>
                            <input type="datetime-local" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', \Carbon\Carbon::parse($student->dob)->format('Y-m-d\TH:i')) }}">
                            @error('dob')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="datetime-local" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', \Carbon\Carbon::parse($student->joining_date)->format('Y-m-d\TH:i')) }}">
                            @error('joining_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                                <label>Aadhar Card</label>
                                <input type="number" name="aadhar_card" class="form-control" value="{{ $student->aadhar_card }}">
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-6 control-label">Staff management</label>
                            <select class="select2 form-control" id="staff_management_id" name="staff_management_id">
                                <option selected disabled>Select category name</option>
                                @foreach($staff_managements as $staff_management)
                                    <option value="{{ $staff_management->id }}"{{ $student->staff_management_id == $staff_management->id ? 'selected' : '' }}>
                                        {{$staff_management->trainer}}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-6 control-label">Course</label>
                            <select class="select2 form-control" id="course_id" name="course_id">
                                <option selected disabled>Select category name</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}"{{ $student->course_id == $course->id ? 'selected' : '' }}>
                                        {{$course->name}}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
    <label for="batch_timing" class="col-sm-6 control-label">Course Time</label>
    <select class="form-control @error('batch_timing') is-invalid @enderror" id="batch_timing" name="batch_timing">
        <option selected disabled>Select Course Time</option>
        @foreach($courses as $course)
            @php
                $formattedStart = $course->start_time 
                    ? \Carbon\Carbon::createFromFormat('H:i:s', $course->start_time)->format('h:i A') 
                    : 'N/A';
                $formattedEnd = $course->end_time 
                    ? \Carbon\Carbon::createFromFormat('H:i:s', $course->end_time)->format('h:i A') 
                    : 'N/A';
            @endphp
            <option 
                value="{{ $course->id }}" 
                data-start="{{ $formattedStart }}" 
                data-end="{{ $formattedEnd }}"
                {{ old('batch_timing', $student->batch_timing ?? '') == $course->id ? 'selected' : '' }}
            >
                {{ $formattedStart }} - {{ $formattedEnd }}
            </option>
        @endforeach
    </select>
    @error('batch_timing')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
                    <div class="form-group">
                        <label>Fees Status</label>
                           <select class="select select2s-hidden-accessible form-control" name="fees_status" style="width: 100%;" @error('fees_status') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Paid" {{ old('Paid',$student->fees_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="Unpaid" {{ old('Unpaid',$student->fees_status) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                    </div>
                   <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Payment History</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="payment_history" name="payment_history" value="{{ $student->payment_history }}" required>
                        </div>
                    </div>
                    
                    <label for="name"><u>Address</u></label>
                    <div class="form-group">
                        <label>Street</label>
                        <input type="text" name="street" class="form-control" value="{{ $student->street }}">
                    </div>
                     <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ $student->city }}">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="{{ $student->state }}">
                    </div>
                    <div class="form-group">
                        <label>Pin Code</label>
                        <input type="number" name="pin_code" class="form-control" value="{{ $student->pin_code }}">
                    </div>
                 <label for="name"><u>Emergency Contact</u></label>
                   <div class="form-group">
                        <label>Emergency Name</label>
                        <input type="text" name="emergency_name" class="form-control" value="{{ $student->emergency_name }}">
                    </div>
                    <div class="form-group">
                        <label>Emergency Number</label>
                        <input type="number" name="emergency_number" class="form-control" value="{{ $student->emergency_number }}">
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
 </div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $student->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete Student</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('student.destroy', $student) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$student->student_name}}</h2>
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

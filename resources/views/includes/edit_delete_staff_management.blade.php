<!-- Edit -->
<div class="modal fade" id="edit{{ $staff_management->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit </span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('staff_management.update', $staff_management) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Trainer</label>
                        <input type="text" class="form-control"  name="trainer" value="{{ $staff_management->trainer }}"
                            >
                    </div>
                     <div class="form-group">
                        <label>Trainer Email</label>
                        <input type="email" name="trainer_email" class="form-control" value="{{ $staff_management->trainer_email }}">
                    </div>
                    <div class="form-group">
                        <label>Trainer Phone Number</label>
                        <input type="text" name="trainer_number" class="form-control" value="{{ $staff_management->trainer_number }}">
                    </div>
                    <div class="form-group">
                        <label>Branch</label>
                         <select class="select select2s-hidden-accessible form-control" name="branch" style="width: 100%;" @error('branch') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Chennai" {{ old('Chennai',$staff_management->branch) == 'Chennai' ? 'selected' : '' }}>Chennai</option>
                                                <option value="Madurai" {{ old('Madurai',$staff_management->branch) == 'Madurai' ? 'selected' : '' }}>Madurai</option>
                                                <option value="Salem" {{ old('Salem',$staff_management->branch) == 'Salem' ? 'selected' : '' }}>Salem</option>
                                                <option value="Trichy" {{ old('Trichy',$staff_management->branch) == 'Trichy' ? 'selected' : '' }}>Trichy</option>
                                                <option value="Madurai" {{ old('Coimbatore',$staff_management->branch) == 'Coimbatore' ? 'selected' : '' }}>Coimbatore</option>
                                                <option value="velour" {{ old('velour',$staff_management->branch) == 'velour' ? 'selected' : '' }}>velour</option>
                                            </select>
                        {{-- <input type="text" name="place" class="form-control" value="{{ $employee->place }}"> --}}
                    </div>
                     <div class="form-group">
                        <label>Join Date</label>
                        <input type="datetime-local" name="joining_date" class="form-control" value="{{ $staff_management->joining_date }}">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                           <select class="select select2s-hidden-accessible form-control" name="gender" style="width: 100%;" @error('gender') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('Male',$staff_management->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('Female',$staff_management->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <label>DOB</label>
                        <input type="datetime-local" name="dob" class="form-control" value="{{ $staff_management->dob }}">
                    </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Subject</label>
                        <input type="text" class="form-control"  name="subject" value="{{ $staff_management->subject }}"
                            >
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Salary</label>
                        <input type="text" class="form-control" name="salary" value="{{ $staff_management->salary }}"
                            >
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Commission</label>
                        <input type="text" class="form-control" name="commission" value="{{ $staff_management->commission }}"
                            >
                    </div>
                     <div class="form-group">
                        <label>Aadhar Card</label>
                        <input type="number" name="aadhar_card" class="form-control" value="{{ $staff_management->aadhar_card }}">
                    </div>
                    <label for="name"><u>Address</u></label>
                    <div class="form-group">
                        <label>Street</label>
                        <input type="text" name="street" class="form-control" value="{{ $staff_management->street }}">
                    </div>
                     <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ $staff_management->city }}">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="{{ $staff_management->state }}">
                    </div>
                    <div class="form-group">
                        <label>Pin Code</label>
                        <input type="number" name="pin_code" class="form-control" value="{{ $staff_management->pin_code }}">
                    </div>
                    <label for="name"><u>Emergency Contact</u></label>
                   <div class="form-group">
                        <label>Emergency Name</label>
                        <input type="text" name="emergency_name" class="form-control" value="{{ $staff_management->emergency_name }}">
                    </div>
                    <div class="form-group">
                        <label>Emergency Number</label>
                        <input type="number" name="emergency_number" class="form-control" value="{{ $staff_management->emergency_number }}">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
                    Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $staff_management->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items: center">
              <h4 class="modal-title"><span class="employee_id">Delete Employee</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('staff_management.destroy', $staff_management) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$staff_management->trainer}}</h2>
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
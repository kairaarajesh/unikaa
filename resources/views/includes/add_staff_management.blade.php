<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Staff Management</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('staff_management.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Trainer</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="trainer" name="trainer">
                            </div>
                    </div>
                     <div class="form-group">
                            <label for="email" class="control-label">Trainer Email</label>
                            <input type="email" class="form-control" id="email" name="trainer_email">
                        </div>
                        <div class="form-group">
                            <label for="number">Trainer Phone Number</label>
                            <input type="number" class="form-control" placeholder="Number" id="number" name="trainer_number"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Branch</label>
                                    <select class="form-control @error('branch') is-invalid @enderror" id="branch" name="branch">
                                        <option> --Select-- </option>
                                        <option value="Chennai" {{ old('branch') == 'Chennai' ? 'selected' : '' }}>Chennai</option>
                                        <option value="Madurai" {{ old('branch') == 'Madurai' ? 'selected' : '' }}>Madurai</option>
                                         <option value="Salem" {{ old('branch') == 'Salem' ? 'selected' : '' }}>Salem</option>
                                        <option value="Madurai" {{ old('branch') == 'Madurai' ? 'selected' : '' }}>Madurai</option>
                                         <option value="Trichy" {{ old('branch') == 'Trichy' ? 'selected' : '' }}>Trichy</option>
                                         <option value="Coimbatore" {{ old('branch') == 'Coimbatore' ? 'selected' : '' }}>Coimbatore</option>
                                         <option value="velour" {{ old('branch') == 'velour' ? 'selected' : '' }}>velour</option>
                                    </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Join Date</label>
                            <input type="datetime-local" class="form-control" id="joining_date" name="joining_date"
                                required />
                        </div>
                         <div class="form-group">
                            <label for="name">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option> --Select-- </option>
                                         <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>female</option>
                                    </select>
                        </div>
                        <div class="form-group">
                            <label for="date">date fo birth</label>
                            <input type="datetime-local" class="form-control" id="dbo" name="dob"
                                required />
                        </div>
                         <div class="form-group">
                        <label for="product_code" class="col-sm-5 control-label">Subject</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control " id="subject" name="subject">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="Quantity" class="col-sm-5 control-label">Salary</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="salary" name="salary">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">Commission</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="commission" name="commission">
                            </div>
                    </div>
                         <div class="form-group">
                            <label for="name">Aadhar Card Number</label>
                            <input type="text" class="form-control" placeholder="Aadhar Card" id="aadhar_card" name="aadhar_card"
                                required />
                        </div>
                        <label for="name">Address*</label>
                         <div class="form-group">
                            <input type="text" class="form-control" placeholder="Street" id="street" name="street"
                                required />
                        </div>
                        <div class="form-group">
                            {{-- <label for="position">City</label> --}}
                            <input type="text" class="form-control" placeholder="City" id="city" name="city"
                                required />
                        </div>
                         <div class="form-group">
                            {{-- <label for="position">State</label> --}}
                            <input type="text" class="form-control" placeholder="State" id="state" name="state"
                                required />
                        </div>
                        <div class="form-group">
                            {{-- <label for="position">Pin code</label> --}}
                            <input type="number" class="form-control" placeholder="Pin code" id="pin_code" name="pin_code"
                                required />
                        </div>
                        <label for="name">Emergency Contact</label>
                         <div class="form-group">
                            {{-- <label for="name">Full Name</label> --}}
                            <input type="text" class="form-control" placeholder="Full name" id="emergency_name" name="emergency_name"
                                required />
                        </div>
                        <div class="form-group">
                            {{-- <label for="position">Emergency Number</label> --}}
                            <input type="text" class="form-control" placeholder="Emergency Number" id="emergency_number" name="emergency_number"
                                required />
                        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
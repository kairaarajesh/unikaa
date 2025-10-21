<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Appointment</b></h4>
            <div class="modal-body">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('appointment.store') }}">
                        @csrf
                        <div class="form-group">
                           <label for="category" class="col-sm-3 control-label">Assign staff</label>
                            <select class="select select2s-hidden-accessible form-control" id="emp_id" name="emp_id">
                                <option selected disabled>Select Assign staff</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->employee_name	 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Services</label>
                            <input type="text" class="form-control" placeholder="Enter Services" id="name" name="service"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="name">Date time</label>
                            <input type="datetime-local" class="form-control" id="date" name="date"
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

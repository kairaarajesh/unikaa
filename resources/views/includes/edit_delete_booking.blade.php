<div class="modal fade" id="edit{{ $booking->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit booking</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('booking.update', $booking) }}">
                    @csrf
                    @method('PUT')
                     {{-- <div class="form-group">
                        <label for="emp_id" class="col-sm-3 control-label">Employee</label>
                         <div class="col-sm-12">
                            <select class="select2 form-control" id="emp_id" name="emp_id">
                                <option selected disabled>Select</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"{{ $booking->emp_id == $employee->id ? 'selected' : '' }}>
                                        {{$employee->employee_name}}
                                    </option>
                                @endforeach
                            </select>
                         </div>
                    </div> --}}
                     {{-- <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $booking->name }}">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" value="{{ $booking->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="col-sm-3 control-label">Phone</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $booking->phone }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="location" class="col-sm-3 control-label">Location</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="location" name="location" value="{{ $booking->location }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="service" class="col-sm-3 control-label">Service</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="service" name="service" value="{{ $booking->service }}">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="date" class="col-sm-3 control-label">Date</label>
                        <div class="col-sm-12">
                            <input type="date" class="form-control" id="date" name="date" value="{{ $booking->date }}">
                        </div>
                    </div> --}}
                     <div class="form-group">
                        <label for="time" class="col-sm-3 control-label">Time</label>
                        <div class="col-sm-12">
                            <input type="time" class="form-control" id="time" name="time" value="{{ $booking->time }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="status" name="status">
                                <option value="Service Completed" {{ $booking->status == 'Service Completed' ? 'selected' : '' }}>Service Completed</option>
                                <option value="Reschedule" {{ $booking->status == 'Reschedule' ? 'selected' : '' }}>Reschedule</option>
                                <option value="Appointment Canceled" {{ $booking->status == 'Appointment Canceled' ? 'selected' : '' }}>Appointment Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="artist" class="col-sm-3 control-label">Employee</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="artist" name="artist">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $booking->artist == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="gender" class="col-sm-3 control-label">Gender</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="gender" name="gender">
                                <option value="Male" {{ $booking->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $booking->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $booking->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div> --}}
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


<!-- Delete -->
<div class="modal fade" id="delete{{ $booking->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete booking</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('booking.destroy', $booking) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$booking->name}}</h2>
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
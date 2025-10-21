<!-- Edit -->
<div class="modal fade" id="edit{{ $appointment->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit appointment</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('appointment.update', $appointment) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Category</label>
                         <div class="col-sm-12">
                            <select class="select2 form-control" id="emp_id" name="emp_id">
                                <option selected disabled>Select</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"{{ $appointment->emp_id == $employee->id ? 'selected' : '' }}>
                                        {{$employee->employee_name}}
                                    </option>
                                @endforeach
                            </select>
                         </div>
                    </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Services</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="service" name="service" value="{{ $appointment->service }}">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Date</label>
                        <div class="col-sm-12">
                            <input type="datetime-local" class="form-control" id="date" name="date" value="{{ $appointment->date }}">
                        </div>
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


<!-- Delete -->
<div class="modal fade" id="delete{{ $appointment->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete appointment</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('appointment.destroy', $appointment->emp_id) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$appointment->emp_id}}</h2>
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

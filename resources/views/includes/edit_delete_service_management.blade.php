<!-- Edit -->
<div class="modal fade" id="edit{{ $serviceManagement->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit Service</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('service.update', $serviceManagement->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="service_name" class="col-sm-5 control-label">Service Name</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" value="{{ $serviceManagement->service_name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" value="{{ $serviceManagement->amount }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                           <select class="select select2s-hidden-accessible form-control" name="gender" style="width: 100%;" @error('gender') is-invalid @enderror>
                                                <option value="">Select</option>
                                                <option value="Male" {{ old('gender', $serviceManagement->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $serviceManagement->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                    </div>
                    {{-- <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $serviceManagement->quantity }}"
                            required>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="tax" class="col-sm-3 control-label">Tax</label>
                        <input type="text" class="form-control" id="tax" name="tax" value="{{ $serviceManagement->tax }}"
                            required>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="total_amount" class="col-sm-3 control-label">Total Amount</label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{ $serviceManagement->total_amount }}"
                            readonly>
                        <small class="form-text text-muted">This field is calculated automatically based on amount, quantity, and tax.</small>
                    </div> --}}
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
<div class="modal fade" id="delete{{ $serviceManagement->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete Service</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('service.destroy', $serviceManagement->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$serviceManagement->service_name}}</h2>
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
<!-- Edit -->
<div class="modal fade" id="edit{{ $branch->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Update Branch</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('branch.update', $branch->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="product_name" class="col-sm-5 control-label">Branch Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $branch->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="product_code" class="col-sm-5 control-label">Place</label>
                        <input type="text" class="form-control" name="place" value="{{ $branch->place }}" >
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <textarea type="text" class="form-control" name="address" value="{{ $branch->address }}" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">Number</label>
                        <input type="text" class="form-control" name="number" value="{{ $branch->number }}">
                    </div>
                    <div class="form-group">
                        <label for="branch" class="col-sm-3 control-label">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $branch->email }}" >
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-3 control-label">GST</label>
                        <input type="text" class="form-control" name="gst_no" value="{{ $branch->gst_no }}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <button type="submit" class="btn btn-success btn-flat">
                    <i class="fa fa-check-square-o"></i> Update
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $branch->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('branch.destroy', $branch->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{ $branch->name }}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <button type="submit" class="btn btn-danger btn-flat">
                    <i class="fa fa-trash"></i> Delete
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit{{ $management->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="employee_id">Edit Management</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('management.update', $management->product_name) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product Name</label>
                        <input type="text" class="form-control"  name="product_name" value="{{ $management->product_name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product Code</label>
                        <input type="text" class="form-control"  name="product_code" value="{{ $management->product_code }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Count</label>
                        <input type="text" class="form-control" name="Quantity" value="{{ $management->Quantity }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Price</label>
                        <input type="text" class="form-control"  name="price" value="{{ $management->price }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Branch</label>
                        <input type="text" class="form-control" id="branch" name="branch" value="{{ $management->branch }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Date</label>
                        <input type="datetime-local" class="form-control"  name="date" value="{{ $management->date }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Category</label>
                            <select class="select2 form-control" id="category_id" name="category_id">
                                <option selected disabled>Select category name</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"{{ $management->category_id == $category->id ? 'selected' : '' }}>
                                        {{$category->name}}
                                    </option>
                                @endforeach
                            </select>
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
<div class="modal fade" id="delete{{ $management->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="employee_id">Delete Employee</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('management.destroy', $management->product_name) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{$management->product_name}}</h2>
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

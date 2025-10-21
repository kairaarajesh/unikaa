<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Service</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('service.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="service_name" class="col-sm-5 control-label">Service Name</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Enter service name" required>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Amount</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
                            </div>
                    </div>
                        <div class="form-group">
                            <label for="name">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option> --Select-- </option>
                                         <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                        </div>
                    {{-- <div class="form-group">
                        <label for="quantity" class="col-sm-5 control-label">Quantity</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
                            </div>
                    </div> --}}
                    <!--<div class="form-group">-->
                    <!--    <label for="tax" class="col-sm-3 control-label">Tax (%)</label>-->
                    <!--        <div class="bootstrap">-->
                    <!--            <input type="text" class="form-control" id="tax" name="tax" placeholder="Enter tax percentage">-->
                    <!--        </div>-->
                    <!--</div>-->
                    {{-- <div class="form-group">
                        <label for="total_amount" class="col-sm-3 control-label">Total Amount</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                                <small class="form-text text-muted">This field is calculated automatically based on amount, quantity, and tax.</small>
                            </div>
                    </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
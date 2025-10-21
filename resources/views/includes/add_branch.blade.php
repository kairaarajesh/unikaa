<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Branch</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('branch.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="service_name" class="col-sm-5 control-label">Branch Name</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                            </div>
                    </div>
                    <!--<div class="form-group">-->
                    <!--    <label for="amount" class="col-sm-5 control-label">Place</label>-->
                    <!--        <div class="bootstrap">-->
                    <!--            <input type="text" class="form-control" id="place" name="place" placeholder="Enter place" required>-->
                    <!--        </div>-->
                    <!--</div>-->
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Address</label>
                            <div class="bootstrap">
                                <textarea type="text" class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">number</label>
                            <div class="bootstrap">
                                <input type="number" class="form-control" id="number" name="number" placeholder="Enter Number" required >
                            </div>
                    </div>
                     <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Email</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required >
                            </div>
                   </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">GST</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter gst" required >
                            </div>
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
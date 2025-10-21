<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Product</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('management.store') }}">
                    @csrf
                      <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Brand</label>
                            <select class="select select2s-hidden-accessible form-control" id="category_id" name="category_id">
                                <option selected disabled>Select Brand</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-amount="{{ $category->name }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product name</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="product_name" name="product_name">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="product_code" class="col-sm-5 control-label">Product code</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control " id="product_code" name="product_code">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="Quantity" class="col-sm-5 control-label">Count</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="Quantity" name="Quantity">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">Price</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="price" name="price">
                            </div>
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
                        <label for="date" class="col-sm-3 control-label">Date</label>
                            <div class="bootstrap">
                                <input type="date" class="form-control" id="date" name="date" autofocus>
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

<script>
function myFunction() {
  let x = document.getElementById("date").autofocus;
  document.getElementById("demo").innerHTML = x;
}
</script>
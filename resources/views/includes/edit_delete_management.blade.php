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
                    @php($authUser = auth()->user())
                    @php($isSubadmin = $authUser && method_exists($authUser, 'roles') && $authUser->roles()->where('slug','subadmin')->exists() && isset($authUser->branch_id))
                    @if($canViewBranchDetails ?? false)
                        @if($isSubadmin && isset($userBranch))
                            <input type="hidden" name="branch_id" value="{{ $authUser->branch_id }}">
                            <div class="form-group">
                                <label for="branch_id_{{ $management->id }}" class="col-sm-6 control-label">Branch</label>
                                <select class="form-control" id="branch_id_{{ $management->id }}" name="branch_id" style="pointer-events: none; background-color: #f8f9fa;" readonly>
                                    <option value="{{ $userBranch->id }}" selected>
                                        {{ $userBranch->name ?? 'N/A' }}
                                    </option>
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label for="place_{{ $management->id }}" class="col-sm-5 control-label">Place</label>
                                <textarea class="form-control" id="place_{{ $management->id }}" name="place" rows="2" readonly style="background-color: #f8f9fa; resize: none;">{{ $userBranch->address ?? $userBranch->place ?? 'N/A' }}</textarea>
                            </div> --}}
                        @else
                            <div class="form-group">
                                <label for="branch_id_{{ $management->id }}" class="col-sm-6 control-label">Branch</label>
                                <select class="form-control" id="branch_id_{{ $management->id }}" name="branch_id" required>
                                    <option value="" disabled>Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}"
                                                data-place="{{ $branch->place }}"
                                                data-address="{{ $branch->address }}"
                                                {{ $management->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="place_{{ $management->id }}" class="col-sm-5 control-label">Place</label>
                                <input type="text" class="form-control" id="place_{{ $management->id }}" name="place" value="{{ $management->place }}" readonly placeholder="Auto-filled from branch">
                            </div>
                        @endif
                    @else
                        <input type="hidden" name="branch_id" value="{{ $management->branch_id ?? '' }}">
                        <div class="form-group">
                            <label for="place_{{ $management->id }}" class="col-sm-5 control-label">Place</label>
                            <input type="text" class="form-control" id="place_{{ $management->id }}" name="place" value="{{ $management->place }}" placeholder="Enter place">
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Date</label>
                        <input type="date" class="form-control"  name="date" value="{{ $management->date }}"
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var branchSelect = document.getElementById('branch_id_{{ $management->id }}');
        var placeInput = document.getElementById('place_{{ $management->id }}');

        if (branchSelect && placeInput) {
            function updatePlaceFromBranch() {
                var selected = branchSelect.options[branchSelect.selectedIndex];
                if (!selected || !selected.value || selected.value === 'Select Branch') {
                    placeInput.value = '';
                    return;
                }
                // Get place from data attributes, prefer address over place
                var place = selected.getAttribute('data-address') || selected.getAttribute('data-place') || '';
                placeInput.value = place;
            }

            // Add event listener for branch selection change
            branchSelect.addEventListener('change', updatePlaceFromBranch);

            // Handle Select2 if it's being used
            if (typeof jQuery !== 'undefined' && jQuery(branchSelect).hasClass('select2-hidden-accessible')) {
                jQuery(branchSelect).on('change', function() {
                    updatePlaceFromBranch();
                });
            }

            // Initialize on load if a branch is preselected
            updatePlaceFromBranch();
        }
    });
</script>

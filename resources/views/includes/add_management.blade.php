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

                       @php($authUser = auth()->user())

                    @if($authUser && method_exists($authUser, 'roles')
                        && $authUser->roles()->where('slug', 'subadmin')->exists()
                        && isset($authUser->branch_id))

                        @php($userBranch = $Branch->where('id', $authUser->branch_id)->first())

                        <div class="form-group">
                            <label class="col-sm-6 control-label">Branch</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $userBranch->name ?? 'N/A' }}"
                                name="branch_name"
                                readonly
                            >
                        </div>

        <div class="form-group">
            <label for="placeSelect" class="col-sm-5 control-label">Place</label>
            <input
                type="text"
                class="form-control"
                id="branch-info-place"
                name="place"
                value="{{ $userBranch->place ?? $userBranch->address ?? 'N/A' }}"
                readonly
            >
        </div>
                        @else

                            {{-- Users with branch permission: show branch dropdown and auto place in list format --}}
                            <div class="form-group">
                                <label for="branch_id" class="col-sm-6 control-label">Branch</label>
                                <select class="select select2s-hidden-accessible form-control" id="branch_id" name="branch_id">
                                    <option selected disabled>Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}" data-place="{{ $branch->place }}" data-address="{{ $branch->address }}">
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="placeSelect" class="col-sm-5 control-label">Place</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="branch-info-place"
                                    name="place"
                                    readonly
                                >
                            </div>
                      @endif

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var branchSelect = document.getElementById('branch_id');
        var placeInput = document.getElementById('branch-info-place');

        // Only attach event handler if branch select exists (not for subadmin users with fixed branch)
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

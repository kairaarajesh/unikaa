<!-- Add -->
<div class="modal fade" id="addnewcombo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Service Combo</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('serviceCombo.store') }}">
                    @csrf
                     <!-- Service Selection with Checkboxes -->
                    <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Select Services</strong></label>
                        <div class="col-sm-12">
                            <div class="row">
                                @foreach($ServiceManagements as $serviceManagement)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="service_combo[]"
                                                       value="{{ $serviceManagement->id }}"
                                                       class="service-checkbox"
                                                       data-service-name="{{ $serviceManagement->service_name }}"
                                                       data-amount="{{ $serviceManagement->amount }}">
                                                {{ $serviceManagement->service_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Amount</label>
                        <div class="bootstrap">
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Auto calculated from selected services">
                        </div>
                    </div>
                     <div class="form-group">
                            <label for="name">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option> --Select-- </option>
                                         <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>female</option>
                                    </select>
                        </div>
                    <div class="form-group">
                            <label for="Quantity" class="col-sm-5 control-label">Offer Price</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="Quantity" name="quantity">
                            </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">Tax</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="tax" name="tax">
                            </div>
                    </div> --}}
                    <div class="form-group">
                            <label for="branch" class="col-sm-3 control-label">Total Amount</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
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
document.addEventListener('DOMContentLoaded', function () {
    function computeTotals() {
        var servicesTotal = 0;
        var checkboxes = document.querySelectorAll('#addnewcombo .service-checkbox:checked');
        checkboxes.forEach(function (cb) {
            var amt = parseFloat(cb.getAttribute('data-amount')) || 0;
            servicesTotal += amt;
        });

        var amountInput = document.querySelector('#addnewcombo #amount');
        if (amountInput) {
            amountInput.value = servicesTotal.toFixed(2);
        }

        var offerInput = document.querySelector('#addnewcombo #Quantity');
        var offerPrice = 0;
        if (offerInput) {
            offerPrice = parseFloat(offerInput.value) || 0;
        }

        var totalAmountInput = document.querySelector('#addnewcombo #total_amount');
        if (totalAmountInput) {
            var totalAmount = servicesTotal - offerPrice;
            totalAmountInput.value = totalAmount.toFixed(2);
        }
    }

    var allCheckboxes = document.querySelectorAll('#addnewcombo .service-checkbox');
    allCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', computeTotals);
    });

    var offerInputEl = document.querySelector('#addnewcombo #Quantity');
    if (offerInputEl) {
        offerInputEl.addEventListener('input', computeTotals);
        offerInputEl.addEventListener('change', computeTotals);
    }

    // Initialize totals when modal opens (in case of pre-checked values)
    var modalEl = document.getElementById('addnewcombo');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', computeTotals);
    }

    // Also compute once on page load in case modal is already visible
    computeTotals();
});
</script>
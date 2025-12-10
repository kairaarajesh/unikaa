<!-- Edit Modal -->
<div class="modal fade" id="editcombo{{ $ServiceCombo->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Show Service Combo</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('serviceCombo.update', $ServiceCombo->id) }}" id="editForm{{ $ServiceCombo->id }}">
                    @csrf
                    @method('PUT')

                    <!-- Services Selection -->
                    <div class="form-group">
                        <label class="control-label"><strong>Select Services <span class="text-danger">*</span></strong></label>
                        <div class="row" id="servicesContainer{{ $ServiceCombo->id }}">
                            @php
                                $selectedServices = is_string($ServiceCombo->service_combo)
                                    ? json_decode($ServiceCombo->service_combo, true)
                                    : (is_array($ServiceCombo->service_combo) ? $ServiceCombo->service_combo : []);
                                $selectedServices = is_array($selectedServices) ? $selectedServices : [];
                            @endphp

                            @foreach($services as $service)
                                <div class="col-sm-6 col-md-4 mb-2">
                                    <div class="form-check">
                                        @php
                                            if (is_object($service)) {
                                                $serviceId = $service->id;
                                                $serviceName = $service->service_name;
                                                $serviceAmount = $service->amount;
                                            } else {
                                                $svcModel = \App\Models\ServiceManagement::find($service);
                                                $serviceId = $svcModel ? $svcModel->id : (is_scalar($service) ? $service : null);
                                                $serviceName = $svcModel ? $svcModel->service_name : ('Service #' . $serviceId);
                                                $serviceAmount = $svcModel ? $svcModel->amount : 0;
                                            }
                                        @endphp
                                        <input type="checkbox"
                                            name="service_combo[]"
                                            value="{{ $serviceId }}"
                                            class="form-check-input service-checkbox-edit"
                                            id="service_edit_{{ $ServiceCombo->id }}_{{ $serviceId }}"
                                            data-amount="{{ number_format($serviceAmount, 2, '.', '') }}"
                                            data-modal-id="{{ $ServiceCombo->id }}"
                                            {{ in_array($serviceId, $selectedServices, true) || in_array((string)$serviceId, $selectedServices, true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_edit_{{ $ServiceCombo->id }}_{{ $serviceId }}">
                                            {{ $serviceName }} (₹{{ number_format($serviceAmount, 2) }})
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select at least one service</small>
                    </div>

                    <hr>

                    <!-- Amount (Auto-calculated) -->
                    <div class="form-group row">
                        <label for="amount{{ $ServiceCombo->id }}" class="col-sm-3 col-form-label">
                            <strong>Total Services Amount</strong>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number"
                                    step="0.01"
                                    class="form-control"
                                    id="amount{{ $ServiceCombo->id }}"
                                    name="amount"
                                    value="{{ number_format($ServiceCombo->amount, 2, '.', '') }}"
                                    readonly
                                    style="background-color: #f8f9fa;">
                            </div>
                            <small class="text-muted">Auto-calculated from selected services</small>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="form-group row">
                        <label for="gender{{ $ServiceCombo->id }}" class="col-sm-3 col-form-label">
                            <strong>Gender <span class="text-danger">*</span></strong>
                        </label>
                        <div class="col-sm-9">
                            <select class="form-control @error('gender') is-invalid @enderror"
                                id="gender{{ $ServiceCombo->id }}"
                                name="gender"
                                required>
                                <option value="">-- Select Gender --</option>
                                @php $currentGender = old('gender', $ServiceCombo->gender ?? ''); @endphp
                                <option value="Male" {{ $currentGender === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $currentGender === 'Female' || $currentGender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="Unisex" {{ $currentGender === 'Unisex' ? 'selected' : '' }}>Unisex</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Offer Price -->
                    <div class="form-group row">
                        <label for="quantity{{ $ServiceCombo->id }}" class="col-sm-3 col-form-label">
                            <strong>Discount/Offer Price <span class="text-danger">*</span></strong>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    id="quantity{{ $ServiceCombo->id }}"
                                    name="quantity"
                                    value="{{ number_format($ServiceCombo->quantity, 2, '.', '') }}"
                                    required>
                            </div>
                            <small class="text-muted">Discount amount to be applied</small>
                        </div>
                    </div>

                    <!-- Total Amount (Auto-calculated) -->
                    <div class="form-group row">
                        <label for="total_amount{{ $ServiceCombo->id }}" class="col-sm-3 col-form-label">
                            <strong>Final Amount</strong>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-success text-white">₹</span>
                                </div>
                                <input type="number"
                                    step="0.01"
                                    class="form-control font-weight-bold"
                                    id="total_amount{{ $ServiceCombo->id }}"
                                    name="total_amount"
                                    value="{{ number_format($ServiceCombo->total_amount, 2, '.', '') }}"
                                    readonly
                                    style="background-color: #d4edda; font-size: 1.1rem;">
                            </div>
                            <small class="text-muted">Total Services Amount - Discount/Offer Price</small>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    {{-- <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Calculation Summary</h6>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><small>Services Total:</small> <span class="float-right font-weight-bold" id="summary_services{{ $ServiceCombo->id }}">₹0.00</span></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><small>Discount:</small> <span class="float-right text-danger font-weight-bold" id="summary_discount{{ $ServiceCombo->id }}">-₹0.00</span></p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <p class="mb-0"><strong>Final Amount:</strong> <span class="float-right text-success font-weight-bold" id="summary_total{{ $ServiceCombo->id }}">₹0.00</span></p>
                        </div>
                    </div> --}}

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                {{-- <button type="submit" form="editForm{{ $ServiceCombo->id }}" class="btn btn-success btn-flat">
                    <i class="fa fa-check-square-o"></i> Update
                </button> --}}
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var modalId = '{{ $ServiceCombo->id }}';
    var modal = document.getElementById('editcombo' + modalId);
    if (!modal) return;

    function formatCurrency(value) {
        return '₹' + parseFloat(value).toFixed(2);
    }

    function computeTotalsEdit() {
        var servicesTotal = 0;
        var selectedCount = 0;
        var selected = modal.querySelectorAll('.service-checkbox-edit:checked');

        selected.forEach(function (cb) {
            var amt = parseFloat(cb.getAttribute('data-amount')) || 0;
            servicesTotal += amt;
            selectedCount++;
        });

        // Update Amount field
        var amountInput = modal.querySelector('#amount' + modalId);
        if (amountInput) {
            amountInput.value = servicesTotal.toFixed(2);
        }

        // Get offer/discount price
        var offerEl = modal.querySelector('#quantity' + modalId);
        var offerPrice = offerEl ? (parseFloat(offerEl.value) || 0) : 0;

        // Validate offer price doesn't exceed services total
        if (offerPrice > servicesTotal) {
            offerEl.setCustomValidity('Discount cannot exceed total services amount');
            offerPrice = servicesTotal;
            offerEl.value = offerPrice.toFixed(2);
        } else {
            offerEl.setCustomValidity('');
        }

        // Calculate final amount
        var finalAmount = servicesTotal - offerPrice;
        if (finalAmount < 0) finalAmount = 0;

        // Update Total Amount field
        var totalEl = modal.querySelector('#total_amount' + modalId);
        if (totalEl) {
            totalEl.value = finalAmount.toFixed(2);
        }

        // Update summary card
        var summaryServices = modal.querySelector('#summary_services' + modalId);
        var summaryDiscount = modal.querySelector('#summary_discount' + modalId);
        var summaryTotal = modal.querySelector('#summary_total' + modalId);

        if (summaryServices) summaryServices.textContent = formatCurrency(servicesTotal);
        if (summaryDiscount) summaryDiscount.textContent = '-' + formatCurrency(offerPrice);
        if (summaryTotal) summaryTotal.textContent = formatCurrency(finalAmount);

        // Validate at least one service is selected
        var submitBtn = modal.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = selectedCount === 0;
            if (selectedCount === 0) {
                submitBtn.title = 'Please select at least one service';
            } else {
                submitBtn.title = '';
            }
        }
    }

    // Compute on modal show
    $(modal).on('shown.bs.modal', function() {
        computeTotalsEdit();
    });

    // Compute on checkbox change
    modal.querySelectorAll('.service-checkbox-edit').forEach(function (cb) {
        cb.addEventListener('change', computeTotalsEdit);
    });

    // Compute on offer price change
    var offerInput = modal.querySelector('#quantity' + modalId);
    if (offerInput) {
        offerInput.addEventListener('input', computeTotalsEdit);
        offerInput.addEventListener('change', computeTotalsEdit);
    }

    // Initial computation
    computeTotalsEdit();
})();
</script>

<style>
    #editcombo{{ $ServiceCombo->id }} .form-check-input {
        margin-top: 0.25rem;
    }

    #editcombo{{ $ServiceCombo->id }} .form-check-label {
        margin-left: 0.25rem;
        cursor: pointer;
    }

    #editcombo{{ $ServiceCombo->id }} .input-group-text {
        min-width: 40px;
    }
</style>

<!-- Delete -->
<div class="modal fade" id="deletecombo{{ $ServiceCombo->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items: center">
              <h4 class="modal-title"><span class="employee_id">Delete Service Combo</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('serviceCombo.destroy', $ServiceCombo->id) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete this service combo?</h6>
                        <div class="mt-3">
                            @php
                                $services = is_string($ServiceCombo->service_combo) ? json_decode($ServiceCombo->service_combo, true) : [];
                            @endphp
                            @if(is_array($services) && count($services) > 0)
                                @foreach($services as $serviceId)
                                    @php
                                        $service = \App\Models\ServiceManagement::find($serviceId);
                                    @endphp
                                    @if($service)
                                        <span class="badge badge-info mr-1 mb-1">{{ $service->service_name }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="badge badge-secondary">{{ $ServiceCombo->service_combo ?? 'N/A' }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <strong>Amount:</strong> {{ $ServiceCombo->amount }} |
                            <strong>Offer Price:</strong> {{ $ServiceCombo->quantity }} |
                            <strong>Total Amount:</strong> {{ $ServiceCombo->total_amount }}
                        </div>
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

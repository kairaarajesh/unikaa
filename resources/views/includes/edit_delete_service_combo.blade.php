<!-- Edit -->
<div class="modal fade" id="editcombo{{ $ServiceCombo->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b><span class="employee_id">Edit Service Combo</span></b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('serviceCombo.update', $ServiceCombo->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="control-label"><strong>Select Services</strong></label>
                        <div class="row">
                            @php
                                $selectedServices = is_string($ServiceCombo->service_combo) ? json_decode($ServiceCombo->service_combo, true) : (is_array($ServiceCombo->service_combo) ? $ServiceCombo->service_combo : []);
                            @endphp
                            @foreach($services as $service)
                                <div class="col-sm-6 col-md-4">
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
                                            class="form-check-input service-checkbox"
                                            id="service_{{ $ServiceCombo->id }}_{{ $serviceId }}"
                                            data-amount="{{ $serviceAmount }}"
                                            {{ in_array($serviceId, $selectedServices, true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_{{ $ServiceCombo->id }}_{{ $serviceId }}">
                                            {{ $serviceName }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $ServiceCombo->amount }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="col-sm-5 control-label">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value=""> --Select-- </option>
                            @php $currentGender = old('gender', $ServiceCombo->gender ?? ''); @endphp
                            <option value="Male" {{ $currentGender === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $currentGender === 'female' ? 'selected' : '' }}>female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label">Offer Price</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ $ServiceCombo->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label for="total_amount" class="col-sm-3 control-label">Total Amount</label>
                        <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" value="{{ $ServiceCombo->total_amount }}" readonly>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('editcombo{{ $ServiceCombo->id }}');
    if (!modal) return;

    function computeTotalsEdit() {
        var servicesTotal = 0;
        var selected = modal.querySelectorAll('.service-checkbox:checked');
        selected.forEach(function (cb) {
            var amt = parseFloat(cb.getAttribute('data-amount')) || 0;
            servicesTotal += amt;
        });

        var amountInput = modal.querySelector('#amount');
        if (amountInput) {
            amountInput.value = servicesTotal.toFixed(2);
        }

        var offerEl = modal.querySelector('#quantity');
        var offer = offerEl ? (parseFloat(offerEl.value) || 0) : 0;

        var totalEl = modal.querySelector('#total_amount');
        if (totalEl) {
            totalEl.value = (servicesTotal - offer).toFixed(2);
        }
    }

    modal.addEventListener('shown.bs.modal', computeTotalsEdit);
    modal.querySelectorAll('.service-checkbox').forEach(function (cb) {
        cb.addEventListener('change', computeTotalsEdit);
    });
    var offerInput = modal.querySelector('#quantity');
    if (offerInput) {
        offerInput.addEventListener('input', computeTotalsEdit);
        offerInput.addEventListener('change', computeTotalsEdit);
    }

    // Also compute immediately in case modal is already visible
    computeTotalsEdit();
});
</script>
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
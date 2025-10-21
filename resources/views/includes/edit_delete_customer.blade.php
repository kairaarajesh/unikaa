<!-- Edit Employee Modal -->
<!-- Edit Customer Modal -->
<div class="modal fade" id="edit{{ $customer->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Edit Customer</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-left">
                <div class="card-body text-left">
                    <form method="POST" action="{{ route('customer.update', $customer) }}" id="editCustomerForm_{{ $customer->id }}">
                        @csrf
                        @method('PUT')
                        @php($authUser = auth()->user())
                        @if($authUser && method_exists($authUser, 'roles') && $authUser->roles()->where('slug','subadmin')->exists() && isset($authUser->branch_id))
                            <input type="hidden" name="branch_id" value="{{ $authUser->branch_id }}">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Branch</label>
                                <input type="text" class="form-control" value="{{ optional($Branch->first())->name }}" readonly>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="branch_id_{{ $customer->id }}" class="col-sm-6 control-label">Branch</label>
                                <select class="form-control" id="branch_id_{{ $customer->id }}" name="branch_id" required>
                                    <option value="" disabled>Select Branch</option>
                                    @foreach($Branch as $branch)
                                        <option value="{{ $branch->id }}"
                                                data-place="{{ $branch->place }}"
                                                data-address="{{ $branch->address }}"
                                                {{ $customer->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="place_{{ $customer->id }}" class="col-sm-5 control-label">Place</label>
                            @if($authUser && method_exists($authUser, 'roles') && $authUser->roles()->where('slug','subadmin')->exists() && isset($authUser->branch_id))
                                <input type="text" class="form-control" id="place_{{ $customer->id }}" name="place" value="{{ optional($Branch->first())->place ?? optional($Branch->first())->address }}" readonly>
                            @else
                                <input type="text" class="form-control" id="place_{{ $customer->id }}" name="place" value="{{ $customer->place }}" placeholder="Enter place or select branch to auto-fill">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="employeeSelect_{{ $customer->id }}" class="col-sm-5 control-label">Staff Name</label>
                            <select class="select2 form-control" id="employeeSelect_{{ $customer->id }}" name="employee_id" required>
                                <option disabled>----  ----</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (string)$customer->employee_id === (string)$employee->id ? 'selected' : '' }}> {{ $employee->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employeeDetailsSelect_{{ $customer->id }}" class="col-sm-5 control-label">Staff ID</label>
                            <select class="select2 form-control" id="employeeDetailsSelect_{{ $customer->id }}" name="employee_details" required>
                                <option disabled>---- ---</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (string)$customer->employee_details === (string)$employee->id ? 'selected' : '' }}> {{ $employee->employee_id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name_{{ $customer->id }}">Customer Name</label>
                            <input type="text" class="form-control" placeholder="Enter Name" id="name_{{ $customer->id }}" name="name" value="{{ $customer->name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email_{{ $customer->id }}" class="col-sm-3 control-label">Customer Email</label>
                            <input type="email" class="form-control" id="email_{{ $customer->id }}" name="email" value="{{ $customer->email }}">
                        </div>
                        <div class="form-group">
                            <label for="number_{{ $customer->id }}">Customer Number</label>
                            <input type="number" class="form-control" placeholder="Enter Number" id="number_{{ $customer->id }}" name="number" value="{{ $customer->number }}" min="1" required />
                        </div>
                        <div class="form-group">
                            <label for="date_{{ $customer->id }}" class="col-sm-3 control-label">Date</label>
                            <div class="bootstrap">
                                <input type="datetime-local" class="form-control" id="date_{{ $customer->id }}" name="date" value="{{ $customer->date }}">
                            </div>
                        </div>

                        <!-- Service Items from service_management -->
                        {{-- <div class="form-group">
                            <label>Service Items</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-big" id="service-items-table-edit-{{ $customer->id }}">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Amount</th>
                                            <th>Quantity</th>
                                            <th>Tax (%)</th>
                                            <th>Total Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="service-items-body-edit-{{ $customer->id }}"></tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg" id="add-item-edit-{{ $customer->id }}">Add New Item</button>
                            <div id="category-hidden-container-edit-{{ $customer->id }}"></div>
                            <input type="hidden" name="amount" id="aggregate-amount-edit-{{ $customer->id }}" value="{{ $customer->amount }}">
                            <input type="hidden" name="tax" id="aggregate-tax-edit-{{ $customer->id }}" value="{{ $customer->tax  }}">
                            <input type="hidden" name="total_amount" id="aggregate-total-amount-edit-{{ $customer->id }}" value="{{ $customer->total_amount }}">
                            <!-- Persist categories as JSON string to align with update validator -->
                            <input type="hidden" name="category" id="category-json-edit-{{ $customer->id }}" value="{{ is_array(json_decode($customer->category, true)) ? $customer->category : json_encode(array_filter([$customer->category])) }}">
                        </div> --}}

                        {{-- <div class="form-group">
                            <label>Discount (%)</label>
                            <input type="number" step="0.01" id="discount_{{ $customer->id }}" name="discount" class="form-control" value="{{ $customer->discount }}">
                        </div>

                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" step="0.01" id="total_amount_{{ $customer->id }}" name="total_amount_display" class="form-control" value="{{ $customer->total_amount }}" readonly>
                        </div> --}}

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-success waves-effect waves-light">
                                    Update
                                </button>
                                <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete{{ $customer->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
              <h4 class="modal-title "><span class="customer_id">Delete Customer</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('customer.destroy', $customer) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_customer_name">{{$customer->name}}</h2>
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

<div class="modal fade" id="view{{ $customer->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>View Customer</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-left">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <tbody>
                            <!--<tr><th>Branch Name</th><td>{{ $customer->branch?->name ?? 'N/A' }}</td></tr>-->
                            <tr><th>CUST ID</th><td>{{ $customer->customer_id }}</td></tr>
                            <tr><th>CUST Name</th><td>{{ $customer->name }}</td></tr>
                            <tr><th>CUST Email</th><td>{{ $customer->email }}</td></tr>
                            <tr><th>CUST Number</th><td>{{ $customer->number }}</td></tr>
                            <tr><th>Gender</th><td>{{ $customer->gender }}</td></tr>
                            <tr><th>Place</th><td>{{ $customer->place }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-fill place when branch is selected
        @if(isset($customer))
            (function() {
                const cid = '{{ $customer->id }}';
                const branchSelect = document.getElementById('branch_id_' + cid);
                const placeInput = document.getElementById('place_' + cid);

                if (branchSelect && placeInput) {
                    function updatePlaceFromBranch() {
                        const selectedOption = branchSelect.options[branchSelect.selectedIndex];
                        if (!selectedOption || !selectedOption.value) {
                            return;
                        }

                        // Get place from data attributes, prefer place over address
                        const place = selectedOption.getAttribute('data-place') || selectedOption.getAttribute('data-address') || '';
                        if (place) {
                            placeInput.value = place;
                        }
                    }

                    // Add event listener for branch selection change
                    branchSelect.addEventListener('change', updatePlaceFromBranch);

                    // Initialize on page load if a branch is already selected
                    if (branchSelect.value) {
                        updatePlaceFromBranch();
                    }
                }
            })();
        @endif

        @if(isset($customers))
        @foreach($customers as $customer)
            (function() {
                const cid = '{{ $customer->id }}';
                const itemsBody = document.getElementById('service-items-body-edit-' + cid);
                const addItemBtn = document.getElementById('add-item-edit-' + cid);
                const hiddenCategoryJson = document.getElementById('category-json-edit-' + cid);
                const aggAmount = document.getElementById('aggregate-amount-edit-' + cid);
                const aggTax = document.getElementById('aggregate-tax-edit-' + cid);
                const aggTotal = document.getElementById('aggregate-total-amount-edit-' + cid);
                const discountInput = document.getElementById('discount_' + cid);
                const totalDisplay = document.getElementById('total_amount_' + cid);
                const amountDisplay = document.getElementById('amount_' + cid);

                function serviceOptionsHtml(selectedName) {
                    let html = '<option value="">-- Select Service --</option>';
                    @foreach($services as $srv)
                        const isSelected = selectedName === "{{ $srv->service_name }}" ? 'selected' : '';
                        html += '<option ' + isSelected + ' value="{{ $srv->service_name }}" data-amount="{{ $srv->amount }}" data-tax="{{ $srv->tax ?? 18 }}">{{ $srv->service_name }}</option>';
                    @endforeach
                    return html;
                }

                function addRow(preselectName) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <select class="form-control item-service">${serviceOptionsHtml(preselectName || '')}</select>
                        </td>
                        <td><input type="number" step="0.01" min="0" class="form-control item-amount" value=""></td>
                        <td><input type="number" min="1" class="form-control item-qty" value="1"></td>
                        <td><input type="number" min="0" max="100" class="form-control item-tax" value="0"></td>
                        <td><input type="text" class="form-control item-line-total" value="0.00" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                    `;
                    itemsBody.appendChild(row);
                    bindRowEvents(row);
                    // If preselect, trigger change to fill defaults
                    if (preselectName) {
                        const sel = row.querySelector('.item-service');
                        sel.dispatchEvent(new Event('change'));
                    }
                    recalcAndAggregate();
                }

                function bindRowEvents(row) {
                    const serviceSelect = row.querySelector('.item-service');
                    const amountInput = row.querySelector('.item-amount');
                    const qtyInput = row.querySelector('.item-qty');
                    const taxPctInput = row.querySelector('.item-tax');
                    const lineTotalInput = row.querySelector('.item-line-total');
                    const removeBtn = row.querySelector('.remove-item');

                    serviceSelect.addEventListener('change', function() {
                        const opt = this.options[this.selectedIndex];
                        const amt = opt.getAttribute('data-amount') || '';
                        const tax = opt.getAttribute('data-tax') || '0';
                        if (amt) amountInput.value = amt;
                        taxPctInput.value = tax;
                        updateHiddenCategories();
                        recalcRow(amountInput, qtyInput, taxPctInput, lineTotalInput);
                    });
                    amountInput.addEventListener('input', function() {
                        recalcRow(amountInput, qtyInput, taxPctInput, lineTotalInput);
                    });
                    qtyInput.addEventListener('input', function() {
                        recalcRow(amountInput, qtyInput, taxPctInput, lineTotalInput);
                    });
                    taxPctInput.addEventListener('input', function() {
                        recalcRow(amountInput, qtyInput, taxPctInput, lineTotalInput);
                    });
                    removeBtn.addEventListener('click', function() {
                        row.remove();
                        updateHiddenCategories();
                        recalcAndAggregate();
                    });
                }

                function recalcRow(amountInput, qtyInput, taxPctInput, lineTotalInput) {
                    const rate = parseFloat(amountInput.value) || 0;
                    const qty = parseFloat(qtyInput.value) || 0;
                    const base = rate * qty;
                    const taxPct = parseFloat(taxPctInput.value) || 0;
                    const taxAmt = base * (taxPct / 100);
                    const line = base + taxAmt;
                    lineTotalInput.value = line.toFixed(2);
                    recalcAndAggregate();
                }

                function updateHiddenCategories() {
                    const rows = itemsBody.querySelectorAll('tr');
                    const names = [];
                    rows.forEach(function(r){
                        const sel = r.querySelector('.item-service');
                        if (sel && sel.value) names.push(sel.value);
                    });
                    hiddenCategoryJson.value = JSON.stringify(names);
                }

                function recalcAndAggregate() {
                    let sumBase = 0, sumTax = 0, sumLine = 0;
                    const rows = itemsBody.querySelectorAll('tr');
                    rows.forEach(function(r) {
                        const rate = parseFloat(r.querySelector('.item-amount').value) || 0;
                        const qty = parseFloat(r.querySelector('.item-qty').value) || 0;
                        const base = rate * qty;
                        const taxPct = parseFloat(r.querySelector('.item-tax').value) || 0;
                        const taxAmt = base * (taxPct / 100);
                        const line = base + taxAmt;
                        sumBase += base; sumTax += taxAmt; sumLine += line;
                    });
                    aggAmount.value = sumBase.toFixed(2);
                    aggTax.value = sumTax.toFixed(2);
                    const discountPct = parseFloat(discountInput.value) || 0;
                    const discountAmt = sumBase * (discountPct / 100);
                    const finalTotal = sumBase + sumTax - discountAmt;
                    aggTotal.value = finalTotal.toFixed(2);
                    totalDisplay.value = finalTotal.toFixed(2);
                }

                if (discountInput) {
                    discountInput.addEventListener('input', recalcAndAggregate);
                }

                // Pre-populate rows from saved categories
                let initialCats = [];
                try { initialCats = JSON.parse(hiddenCategoryJson.value || '[]') || []; } catch(e) { initialCats = []; }
                if (Array.isArray(initialCats) && initialCats.length) {
                    initialCats.forEach(function(name){ addRow(name); });
                } else {
                    addRow();
                }

                addItemBtn.addEventListener('click', function(){ addRow(); });
            })();
        @endforeach
        @endif
    });
</script>
@endsection

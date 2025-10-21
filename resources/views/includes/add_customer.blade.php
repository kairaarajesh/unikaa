<!-- Add -->
<div class="modal fade" id="addnew">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
			</div>
			<h4 class="modal-title"><b>Customer</b></h4>
			<div class="modal-body">
				<div class="card-body text-left">
					<form method="POST" action="{{ route('customer.store') }}" id="addCustomerForm">
						@csrf
						{{-- <div class="form-group">
							<label for="name">Branch</label>
							<input type="text" class="form-control" placeholder="Enter Branch" id="Branch" name="branch"
								required />
						</div> --}}
                        {{-- <div class="form-group">
                            <label for="name">Place</label>
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
                        </div> --}}

                        @php($authUser = auth()->user())
                        @if($authUser && method_exists($authUser, 'roles') && $authUser->roles()->where('slug','subadmin')->exists() && isset($authUser->branch_id))
                            <input type="hidden" name="branch_id" value="{{ $authUser->branch_id }}">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Branch</label>
                                <input type="text" class="form-control" value="{{ optional($Branch->first())->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="placeSelect" class="col-sm-5 control-label">Place</label>
                                <input type="text" class="form-control" id="branch-info-place" name="place" value="{{ optional($Branch->first())->place ?? optional($Branch->first())->address }}" readonly>
                            </div>
                        @else
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
                                <input type="text" class="form-control" id="branch-info-place" name="place" readonly>
                            </div>
                        @endif

						<div class="form-group">
                                <label for="employeeSelect" class="col-sm-5 control-label">Staff Name</label>
                                <select class="select2 form-control" id="employeeSelect" name="employee_id">
                                    <option selected disabled>---- ----</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" data-employee-id="{{ $employee->employee_id }}">
                                            {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                            <div class="form-group">
                                <label for="employeeDetailsSelect" class="col-sm-5 control-label">Staff ID</label>
                                <input type="text" class="form-control" id="employeeDetails" name="employee_details" readonly>
                            </div>
					<div class="form-group">
						<label for="number">Customer Number</label>
						<input type="text" class="form-control" placeholder="Enter or select number" id="numberInput" name="number" list="existingNumbers" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit phone number"/>
						<datalist id="existingNumbers">
							@foreach($customers as $cust)
								<option value="{{ $cust->number }}"
									data-name="{{ $cust->name }}"
									data-email="{{ $cust->email }}"
									data-gender="{{ $cust->gender }}"
									data-membership-card="{{ $cust->membership_card }}">
									{{ $cust->name }} - {{ $cust->email }} ({{ $cust->gender }})
								</option>
							@endforeach
						</datalist>
						<small class="form-text text-muted">Enter a 10-digit phone number to auto-fill customer details. If customer exists, their info will be updated; if new, a new customer will be created.</small>
						<div id="customer-status" class="mt-2" style="display: none;">
							<span id="customer-status-text" class="badge" style="font-size: 12px; padding: 6px 12px;"></span>
						</div>
						<div id="customer-info" class="mt-2" style="display: none;">
							<div class="alert alert-info" style="padding: 8px 12px; margin-bottom: 0; font-size: 13px;">
								<strong>Note:</strong> <span id="customer-info-text"></span>
							</div>
						</div>
					</div>
						<div class="form-group">
							<label for="name">Customer Name</label>
							<input type="text" class="form-control" placeholder="Enter Name" id="name" name="name"
								 />
						</div>
					<!-- Offer input (visible only when customer has membership_card) -->
					<div class="form-group" id="offer-group" style="display:none;">
						<label for="offer_input">Offer</label>
						<input type="text" class="form-control" id="offer_input" name="offer" placeholder="Enter offer">
					</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Customer Email</label>
							<input type="email" placeholder="Enter Email" class="form-control" id="email" name="email">
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
							<label for="date" class="col-sm-3 control-label">Date</label>
								<div class="bootstrap">
									<input type="date" class="form-control" id="date" name="date" autofocus>
								</div>
						</div> --}}
                        <div class="form-group">
                            <label for="name">Payment Method</label>
                                    <select class="form-control @error('payment') is-invalid @enderror" id="payment" name="payment">
                                        <option> --Select-- </option>
                                        <option value="Cash" {{ old('Cash') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Debit card / Credit card" {{ old('Debit card / Credit card') == 'Debit card / Credit card' ? 'selected' : '' }}>Debit card / Credit card</option>
                                        <option value="Paytm" {{ old('Paytm') == 'Paytm' ? 'selected' : '' }}>Paytm</option>
                                        <option value="Gpay" {{ old('Gpay') == 'Gpay' ? 'selected' : '' }}>Gpay</option>
                                    </select>
                        </div>
                                    <input type="hidden" name="service_items" id="service-items-hidden" >
                                    <input type="hidden" name="purchase_items" id="purchase-items-hidden">
                        <div class="modal-body">
                            {{-- <form id="invoiceCreateForm" method="POST" action="{{ route('customer.store') }}">
                                 @csrf --}}
                                 <input type="hidden" name="customer_id" id="invoice-customer-id" value="">
                    			<label><strong>Service Items *</strong></label>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover table-big" id="service-items-table">
									<thead class="thead-light">
										<tr>
											<th>Service Name *</th>
											<th>Amount *</th>
                                            <th>Discount (%)</th>
                                            {{-- <th>Tax (%)</th>
									        <th>Tax Amount</th> --}}
									        <th>Total Amount</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="service-items-body"></tbody>
								</table>
							</div>
						<button type="button" class="btn btn-primary btn-lg" id="add-item">Add New Item</button>
						<div id="category-hidden-container"></div>
						<div class="row" style="margin-top:12px;">
							{{-- <div class="col-md-3">
								<label for="subtotal"><strong>Subtotal</strong></label>
								<input type="number" class="form-control" id="subtotal" name="subtotal" value="0.00" readonly>
							</div> --}}
                            <div class="col-md-3">
                                <label for="tax"><strong>Tax (%)</strong></label>
                                <input type="number" class="form-control" id="item_tax" name="item_tax" min="0" max="100" step="0.01" value="0">
                            </div>
                            <div class="col-md-3">
                                <label for="service_tax_amount"><strong>Tax Amount</strong></label>
                                <input type="number" class="form-control" id="service_tax_amount" name="service_tax_amount" value="0.00" readonly>
                            </div>
							<div class="col-md-3">
								<label for="service_total_calculation"><strong>Total Calculation</strong></label>
								<input type="number" class="form-control" id="service_total_calculation" name="service_total_calculation" value="0.00" readonly>
							</div>
						</div>
						<!-- Aggregated totals for backend validation -->
						<input type="hidden" name="amount" id="aggregate-amount" value="0">
                        <input type="hidden" name="tax" id="aggregate-tax" value="0">
						<input type="hidden" name="total_amount" id="aggregate-total-amount" value="0">
						<input type="hidden" name="total_calculation" id="total-calculation-hidden" value="0">

						<div class="form-group">
								<label><strong>Sales Items *</strong> </label>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover table-big" id="purchase-items-table">
										<thead class="thead-light">
											<tr>
												<th>Product Name *</th>
												<th>Product Code</th>
												<th>Amount *</th>
												<th>Discount (%)</th>
												<th>Tax (%)</th>
												<th>Total Amount</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody id="purchase-items-body"></tbody>
									</table>
								</div>
								<button type="button" class="btn btn-primary btn-lg" id="add-purchase-item">Add New Item</button>
								<input type="hidden" name="purchase_total_amount" id="purchase-total-amount-hidden" value="0">
                        </div>
						<div id="service-inputs"></div>
                         </div>
                    {{-- </form> --}}
						<div class="form-group">
							<div>
								<button type="submit" class="btn btn-primary waves-effect waves-light">
									Submit
								</button>
								<button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
									Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="addmember">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
			</div>
			<h4 class="modal-title"><b>Member Card </b></h4>
			<div class="modal-body">
				<div class="card-body text-left">
					<form method="POST" action="{{ route('customer.update-membership-card') }}" id="addCustomerForm">
						@csrf
					<div class="form-group">
						<label for="number">Customer Number</label>
						<input type="text" class="form-control" placeholder="Enter or select number" id="numberInput" name="number" list="existingNumber" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit phone number"/>
						<datalist id="existingNumber">
							@foreach($customers as $cust)
								<option value="{{ $cust->number }}"
									data-name="{{ $cust->name }}"
									data-email="{{ $cust->email }}"
									data-gender="{{ $cust->gender }}"
									data-place="{{ $cust->place ?? '' }}">
									{{ $cust->name }} - {{ $cust->email }} ({{ $cust->gender }})
								</option>
							@endforeach
						</datalist>
						<small class="form-text text-muted">Enter a 10-digit phone number to auto-fill customer details. If customer exists, their info will be updated; if new, a new customer will be created.</small>
						<div id="customer-status" class="mt-2" style="display: none;">
							<span id="customer-status-text" class="badge" style="font-size: 12px; padding: 6px 12px;"></span>
						</div>
						<div id="customer-info" class="mt-2" style="display: none;">
							<div class="alert alert-info" style="padding: 8px 12px; margin-bottom: 0; font-size: 13px;">
								<strong>Note:</strong> <span id="customer-info-text"></span>
							</div>
						</div>
					</div>
						<div class="form-group">
							<label for="name">Customer Name</label>
							<input type="text" class="form-control" placeholder="Enter Name" id="name" name="name"
								 />
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Customer Email</label>
							<input type="email" placeholder="Enter Email" class="form-control" id="email" name="email">
						</div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option> --Select-- </option>
                                         <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                        </div>
                        <div class="form-group">
							<label for="place">place</label>
							<input type="text" class="form-control" placeholder="" id="place" name="place"
								 />
						</div>
                        <div class="form-group">
							<label for="membership_card">MEM Card</label>
							<input type="text" class="form-control" placeholder="" id="membership_card" name="membership_card"
								 />
						</div>
						<div class="form-group">
							<div>
								<button type="submit" class="btn btn-primary waves-effect waves-light">
									Submit
								</button>
								<button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
									Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
    /* Big format for Service Items */
    #service-items-table.table-big th,
    #service-items-table.table-big td,
    #purchase-items-table.table-big th,
    #purchase-items-table.table-big td { font-size: 16px; }
    #service-items-table.table-big .form-control,
    #purchase-items-table.table-big .form-control { height: 44px; font-size: 16px; }
    #add-item.btn-lg, #add-purchase-item.btn-lg { padding: 10px 18px; font-size: 16px; }
    .modal-dialog.modal-lg { max-width: 1000px; }
    @media (max-width: 576px) {
        #service-items-table.table-big th,
        #service-items-table.table-big td,
        #purchase-items-table.table-big th,
        #purchase-items-table.table-big td { font-size: 14px; }
        #service-items-table.table-big .form-control,
        #purchase-items-table.table-big .form-control { height: 40px; font-size: 14px; }
    }
    /* Open table format: remove borders, add spacing */
    #service-items-table.table-big,
    #purchase-items-table.table-big {
        border-collapse: separate !important;
        border-spacing: 0 12px !important;
        background: transparent;
    }
    #service-items-table.table-big th,
    #purchase-items-table.table-big th {
        border: none !important;
        background: #f8f9fa;
    }
    #service-items-table.table-big td,
    #purchase-items-table.table-big td {
        border: none !important;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    #service-items-table.table-big tr,
    #purchase-items-table.table-big tr {
        border-bottom: 1px solid #e9ecef !important;
    }
    /* Alignment and widths */
    #service-items-table th, #service-items-table td,
    #purchase-items-table th, #purchase-items-table td {
        vertical-align: middle;
    }
    #service-items-table th, #purchase-items-table th { text-align: left; }
    /* Right align numeric inputs */
    #service-items-table input[type="number"],
    #service-items-table input.item-line-total,
    #purchase-items-table input[type="number"],
    #purchase-items-table input.purchase-line-total { text-align: right; }
    /* Service table column widths */
    #service-items-table thead th:nth-child(1),
    #service-items-table tbody td:nth-child(1) { width: 26%; }
    #service-items-table thead th:nth-child(2),
    #service-items-table tbody td:nth-child(2) { width: 14%; }
    #service-items-table thead th:nth-child(3),
    #service-items-table tbody td:nth-child(3) { width: 12%; }
    #service-items-table thead th:nth-child(4),
    #service-items-table tbody td:nth-child(4) { width: 12%; }
    #service-items-table thead th:nth-child(5),
    #service-items-table tbody td:nth-child(5) { width: 14%; }
    #service-items-table thead th:nth-child(6),
    #service-items-table tbody td:nth-child(6) { width: 14%; }
    #service-items-table thead th:nth-child(7),
    #service-items-table tbody td:nth-child(7) { width: 8%; }
    /* Purchase table column widths */
    #purchase-items-table thead th:nth-child(1),
    #purchase-items-table tbody td:nth-child(1) { width: 24%; }
    #purchase-items-table thead th:nth-child(2),
    #purchase-items-table tbody td:nth-child(2) { width: 16%; }
    #purchase-items-table thead th:nth-child(3),
    #purchase-items-table tbody td:nth-child(3) { width: 12%; }
    #purchase-items-table thead th:nth-child(4),
    #purchase-items-table tbody td:nth-child(4) { width: 12%; }
    #purchase-items-table thead th:nth-child(5),
    #purchase-items-table tbody td:nth-child(5) { width: 12%; }
    #purchase-items-table thead th:nth-child(6),
    #purchase-items-table tbody td:nth-child(6) { width: 16%; }
    #purchase-items-table thead th:nth-child(7),
    #purchase-items-table tbody td:nth-child(7) { width: 8%; }

    /* Service input styling */
    .service-input-container {
        position: relative;
    }
    .service-input-container .item-service-input {
        padding-right: 35px; /* Make room for dropdown button */
    }
    .service-input-container .service-dropdown-toggle {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        border: none;
        background: transparent;
        padding: 2px 8px;
        color: #6c757d;
        cursor: pointer;
    }
    .service-input-container .service-dropdown-toggle:hover {
        color: #495057;
    }
    .service-input-container .service-dropdown-toggle:focus {
        outline: none;
        box-shadow: none;
    }
</style>
<!-- Dynamic service items JS -->
<script>
	(function() {
		// Wait for jQuery to be available
		function waitForJQuery(callback) {
			if (typeof jQuery !== 'undefined') {
				callback();
			} else {
				setTimeout(function() { waitForJQuery(callback); }, 100);
			}
		}

		waitForJQuery(function() {
			// Scope lookups to the modal to avoid nulls before it mounts
			function getServiceBody() { return document.querySelector('#addnew #service-items-body'); }
			const hiddenCategoryContainer = document.getElementById('category-hidden-container');
			const form = document.getElementById('addCustomerForm');

			function addRow() {
				const row = document.createElement('tr');
				row.innerHTML = `
					<td>
						<div class="service-input-container" style="position: relative;">
							<input type="text" class="form-control item-service-input" name="category[]" placeholder="Type or select service..." autocomplete="off" list="service-suggestions-${Date.now()}">
							<datalist id="service-suggestions-${Date.now()}">
								@foreach($services as $srv)
								<option value="{{ $srv->service_name }}" data-amount="{{ $srv->amount }}" data-tax="{{ $srv->tax ?? 18 }}">{{ $srv->service_name }}</option>
								@endforeach
							</datalist>
							<button type="button" class="btn btn-sm btn-outline-secondary service-dropdown-toggle" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); z-index: 10; border: none; background: transparent; padding: 2px 8px;">
								<i class="fa fa-chevron-down" style="font-size: 12px;"></i>
							</button>
						</div>
					</td>
					<td><input type="number" name="item_amount[]" step="0.01" min="0" inputmode="decimal" placeholder="0.00" class="form-control item-amount" value=""></td>
					<td><input type="number" min="0" max="100" name="item_discount[]" inputmode="decimal" placeholder="0" class="form-control item-discount" value="0"></td>
					<td><input type="text" name="item_total_amount[]" class="form-control item-line-total" placeholder="0.00" value="0.00" readonly></td>
					<td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
				`;

				const bodyEl = getServiceBody();
				if (!bodyEl) { return; }
				bodyEl.appendChild(row);

				bindRowEvents(row);
				recalculateFromItems();

				// Focus the service input of the newly added row
				const serviceInputNew = row.querySelector('.item-service-input');
				if (serviceInputNew) { serviceInputNew.focus(); }
			}

			function bindRowEvents(row) {
				const serviceInput = row.querySelector('.item-service-input');
				const amountSelect = row.querySelector('.item-amount');
				const discountInputRow = row.querySelector('.item-discount');
				const lineTotalInput = row.querySelector('.item-line-total');
				const removeBtn = row.querySelector('.remove-item');
				const dropdownToggle = row.querySelector('.service-dropdown-toggle');

				// Handle service input changes (both typing and selection from datalist)
				serviceInput.addEventListener('input', function() {
					const inputValue = this.value;
					const datalist = this.getAttribute('list');
					const datalistElement = document.getElementById(datalist);

					// Find matching option in datalist
					if (datalistElement) {
						const options = datalistElement.querySelectorAll('option');
						for (let option of options) {
							if (option.value === inputValue) {
								const amt = option.getAttribute('data-amount') || '';
								const tax = option.getAttribute('data-tax') || '18';
								if (amt) {
									amountSelect.value = amt;
								}
								// Update any tax field if needed
								const taxInput = document.getElementById('item_tax');
								if (taxInput && tax !== '18') {
									taxInput.value = tax;
								}
								break;
							}
						}
					}

					updateHiddenCategories();
					recalculateRow(amountSelect, lineTotalInput);
				});

				// Handle service input selection from datalist
				serviceInput.addEventListener('change', function() {
					const inputValue = this.value;
					const datalist = this.getAttribute('list');
					const datalistElement = document.getElementById(datalist);

					if (datalistElement) {
						const options = datalistElement.querySelectorAll('option');
						for (let option of options) {
							if (option.value === inputValue) {
								const amt = option.getAttribute('data-amount') || '';
								if (amt) {
									amountSelect.value = amt;
									// Move focus to amount after selecting a service
									amountSelect.focus();
									amountSelect.select();
								}
								break;
							}
						}
					}
					updateHiddenCategories();
					recalculateRow(amountSelect, lineTotalInput);
				});

				// Handle dropdown toggle button click
				dropdownToggle.addEventListener('click', function() {
					serviceInput.focus();
					serviceInput.click(); // This will trigger the datalist dropdown
				});

				// Handle keyboard navigation for service input
				serviceInput.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') {
						e.preventDefault();
						amountSelect.focus();
						amountSelect.select();
					} else if (e.key === 'ArrowDown') {
						// Focus on amount field when arrow down is pressed
						e.preventDefault();
						amountSelect.focus();
						amountSelect.select();
					}
				});

				amountSelect.addEventListener('change', function() {
					recalculateRow(amountSelect, lineTotalInput);
				});

				// Recalculate on typing as well for instant feedback
				amountSelect.addEventListener('input', function() {
					recalculateRow(amountSelect, lineTotalInput);
				});

				// On focus, select contents for quick overwrite
				amountSelect.addEventListener('focus', function() { amountSelect.select(); });
				discountInputRow.addEventListener('focus', function() { discountInputRow.select(); });

				discountInputRow.addEventListener('input', function() {
					recalculateRow(amountSelect, lineTotalInput);
				});
				removeBtn.addEventListener('click', function() {
					row.remove();
					updateHiddenCategories();
					recalculateFromItems();
				});

				amountSelect.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') { e.preventDefault(); discountInputRow.focus(); discountInputRow.select(); }
				});
				discountInputRow.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') {
						e.preventDefault();
						// Trigger add action safely without relying on undefined variables
						$('#addnew #add-item').trigger('click');
						const lastRow = document.getElementById('service-items-body')?.querySelector('tr:last-child');
						if (lastRow) {
							const svc = lastRow.querySelector('.item-service');
							if (svc) { svc.focus(); }
						}
					}
				});
			}

			function recalculateRow(amountSelect, lineTotalInput) {
				const rate = parseFloat(amountSelect.value) || 0;
				const row = amountSelect.closest('tr');
				const discountEl = row ? row.querySelector('.item-discount') : null;
				const discountPct = parseFloat(discountEl && discountEl.value) || 0;
				const net = rate - (rate * (discountPct / 100));

				// For service items, we only calculate the net amount (after discount)
				// Tax will be calculated overall, not per item
				if (lineTotalInput) lineTotalInput.value = net.toFixed(2);
				recalculateFromItems();
			}

			function updateHiddenCategories() {
				// category[] is submitted directly via the select name
				hiddenCategoryContainer.innerHTML = '';
			}

			function recalculateFromItems() {
				let sumBase = 0;
				const bodyEl = document.getElementById('service-items-body');
				if (!bodyEl) { return; }
				const rows = bodyEl.querySelectorAll('tr');
				rows.forEach(function(r) {
					const rate = parseFloat(r.querySelector('.item-amount').value) || 0;
					const discountPct = parseFloat(r.querySelector('.item-discount').value) || 0;
					const net = rate - (rate * (discountPct / 100));
					sumBase += net;
				});

				// Get overall tax percentage from the form
				const overallTaxPct = parseFloat(document.getElementById('item_tax')?.value) || 0;
				const taxAmount = sumBase * (overallTaxPct / 100);
				const totalAfterTax = sumBase + taxAmount;

				// populate hidden aggregate fields for backend validation
				const aggAmount = document.getElementById('aggregate-amount');
				const aggTax = document.getElementById('aggregate-tax');
				const aggTotal = document.getElementById('aggregate-total-amount');
				if (aggAmount) aggAmount.value = sumBase.toFixed(2);
				if (aggTax) aggTax.value = taxAmount.toFixed(2);
				if (aggTotal) aggTotal.value = totalAfterTax.toFixed(2);

				// update displayed fields and hidden total_calculation
				const displaySubtotal = document.getElementById('subtotal');
				if (displaySubtotal) displaySubtotal.value = sumBase.toFixed(2);
				const displayTaxAmount = document.getElementById('service_tax_amount');
				if (displayTaxAmount) displayTaxAmount.value = taxAmount.toFixed(2);
				const displayAfterTax = document.getElementById('service_total_calculation');
				if (displayAfterTax) displayAfterTax.value = totalAfterTax.toFixed(2);
				const totalCalcHidden = document.getElementById('total-calculation-hidden');
				if (totalCalcHidden) totalCalcHidden.value = totalAfterTax.toFixed(2);
			}

			// Delegate click to ensure element exists when clicked
			$(document).on('click', '#addnew #add-item', function(){ addRow(); });
			// Recalculate when overall service tax changes
			const serviceTaxInput = document.getElementById('item_tax');
			if (serviceTaxInput) {
				serviceTaxInput.addEventListener('input', recalculateFromItems);
				serviceTaxInput.addEventListener('change', recalculateFromItems);
			}

			// Initialize when modal opens - use jQuery safely
			$(document).on('shown.bs.modal', '#addnew', function () {
				const bodyEl = getServiceBody();
				if (bodyEl) { bodyEl.innerHTML = ''; addRow(); }
			});

			// Clean up when modal is closed
			$(document).on('hidden.bs.modal', '#addnew', function () {
				// Clear any service input values if needed
				const serviceInputs = document.querySelectorAll('#addnew .item-service-input');
				serviceInputs.forEach(input => {
					input.value = '';
				});
			});

			// Basic client-side validation including dynamic rows
            form.addEventListener('submit', function(e) {
				console.log('Form submission started');

				// HTML5 validation first
				if (!form.checkValidity()) {
					e.preventDefault();
					form.reportValidity();
					return false;
				}

                // Collect service items data
                const serviceItems = [];
                const serviceBodyEl = document.getElementById('service-items-body');
                const serviceRows = serviceBodyEl ? serviceBodyEl.querySelectorAll('tr') : [];
                console.log('Service rows found:', serviceRows.length);

                // Read overall tax percent once for item tax calculations
                const serviceTaxPercentEl = document.getElementById('item_tax');
                const serviceTaxPercent = parseFloat(serviceTaxPercentEl && serviceTaxPercentEl.value) || 0;

				if (serviceRows.length === 0) {
					e.preventDefault();
					alert('Please add at least one service item.');
					return false;
				}

                for (const row of serviceRows) {
                    const serviceInput = row.querySelector('.item-service-input');
                    const amountInput = row.querySelector('.item-amount');
                    const discountInput = row.querySelector('.item-discount');
                    const totalInput = row.querySelector('.item-line-total');

                    const nameVal = (serviceInput && serviceInput.value || '').trim();
                    const amount = parseFloat(amountInput && amountInput.value) || 0;
                    const discount = parseFloat(discountInput && discountInput.value) || 0;
                    const net = amount - (amount * (discount / 100));
                    const perItemTaxAmount = net * (serviceTaxPercent / 100);
                    const totalAmount = (parseFloat(totalInput && totalInput.value) || (net + perItemTaxAmount));

                    // Skip placeholder/empty rows
                    if (!nameVal || (amount <= 0 && totalAmount <= 0)) {
                        continue;
                    }

                    serviceItems.push({
                        service_name: nameVal,
                        amount: amount,
                        discount: discount,
                        tax: serviceTaxPercent,
                        tax_amount: perItemTaxAmount,
                        total_amount: totalAmount
                    });
                }

				// Append a summary object matching the displayed totals
                const subtotalComputed = serviceItems.reduce(function(sum, item){
					const amt = item && typeof item.amount === 'number' ? item.amount : 0;
					const disc = item && typeof item.discount === 'number' ? item.discount : 0;
					const net = amt - (amt * (disc / 100));
					return sum + net;
				}, 0);
				const taxAmountComputed = subtotalComputed * (serviceTaxPercent / 100);
				const totalAfterTaxComputed = subtotalComputed + taxAmountComputed;
				const purchaseBody = document.getElementById('purchase-items-body'); // Get purchaseBody here
				const purchaseItems = [];
                const purchaseRows = purchaseBody ? purchaseBody.querySelectorAll('tr') : [];
				console.log('Purchase rows found:', purchaseRows.length);

				// if (purchaseRows.length === 0) {
				// 	e.preventDefault();
				// 	alert('Please add at least one purchase item.');
				// 	return false;
				// }

                for (const row of purchaseRows) {
                    const productSelect = row.querySelector('.purchase-product');
                    const codeInput = row.querySelector('.purchase-code');
                    const amountInput = row.querySelector('.purchase-amount');
                    const discountInput = row.querySelector('.purchase-discount');
                    const taxInput = row.querySelector('.purchase-tax');
                    const totalInput = row.querySelector('.purchase-line-total');

                    const prodId = (productSelect && productSelect.value || '').trim();
                    const prodName = productSelect && productSelect.options[productSelect.selectedIndex] ? (productSelect.options[productSelect.selectedIndex].text || '').trim() : '';
                    const amt = parseFloat(amountInput && amountInput.value) || 0;
                    const disc = parseFloat(discountInput && discountInput.value) || 0;
                    const tax = parseFloat(taxInput && taxInput.value) || 0;
                    const lineTotal = parseFloat(totalInput && totalInput.value) || 0;

                    // Skip placeholder/empty rows
                    if (!prodId || prodName === '-- Select Product --' || (amt <= 0 && lineTotal <= 0)) {
                        continue;
                    }

                    purchaseItems.push({
                        product_id: prodId,
                        product_name: prodName,
                        product_code: codeInput && codeInput.value || '',
                        amount: amt,
                        discount: disc,
                        tax: tax,
                        total_amount: lineTotal
                    });
                }

				console.log('Service items:', serviceItems);
				console.log('Purchase items:', purchaseItems);

				// Set the hidden fields with JSON data
                document.getElementById('service-items-hidden').value = JSON.stringify(serviceItems.length ? serviceItems : []);
                document.getElementById('purchase-items-hidden').value = JSON.stringify(purchaseItems.length ? purchaseItems : []);

				console.log('Hidden fields set:', {
					service_items: document.getElementById('service-items-hidden').value,
					purchase_items: document.getElementById('purchase-items-hidden').value
				});

				// Update aggregate totals
				const aggAmount = document.getElementById('aggregate-amount');
				const aggTax = document.getElementById('aggregate-tax');
				const aggTotal = document.getElementById('aggregate-total-amount');
				const purchaseTotal = document.getElementById('purchase-total-amount-hidden');

                const computedSubtotal = serviceItems.reduce((sum, item) => {
					const net = item.amount - (item.amount * (item.discount || 0) / 100);
					return sum + net;
				}, 0);
				const overallTaxPct = parseFloat(document.getElementById('item_tax')?.value) || 0;
				const computedTax = computedSubtotal * (overallTaxPct / 100);
				const computedTotal = computedSubtotal + computedTax;
				if (aggAmount) aggAmount.value = computedSubtotal.toFixed(2);
				if (aggTax) aggTax.value = computedTax.toFixed(2);
				if (aggTotal) aggTotal.value = computedTotal.toFixed(2);
				const totalCalcHiddenSubmit = document.getElementById('total-calculation-hidden');
				if (totalCalcHiddenSubmit) totalCalcHiddenSubmit.value = computedTotal.toFixed(2);
                if (purchaseTotal) purchaseTotal.value = purchaseItems.reduce((sum, item) => sum + (parseFloat(item.total_amount) || 0), 0).toFixed(2);

				console.log('Form validation passed, proceeding with submission');
			});
		});
	})();
</script>
<script>
    (function() {
        function waitForDom(cb){
            if(document.readyState==='complete' || document.readyState==='interactive'){
                return cb();
            }
            document.addEventListener('DOMContentLoaded', cb);
        }

        waitForDom(function(){
            var numberInput = document.getElementById('numberInput');
            if (!numberInput) return;

            var originalValues = {
                name: '',
                email: '',
                gender: ''
            };

            function applyCustomerDetails(customer, isExisting){
                var nameEl = document.getElementById('name');
                var emailEl = document.getElementById('email');
                var genderEl = document.getElementById('gender');
                var statusDiv = document.getElementById('customer-status');
                var statusText = document.getElementById('customer-status-text');
                var infoDiv = document.getElementById('customer-info');
                var infoText = document.getElementById('customer-info-text');
                var offerGroup = document.getElementById('offer-group');

                if (customer) {
                    // Store current values as original if they exist
                    if (nameEl && nameEl.value) originalValues.name = nameEl.value;
                    if (emailEl && emailEl.value) originalValues.email = emailEl.value;
                    if (genderEl && genderEl.value) originalValues.gender = genderEl.value;

                    // Apply customer details
                    if (nameEl) nameEl.value = customer.name || '';
                    if (emailEl) emailEl.value = customer.email || '';

                    if (genderEl && customer.gender) {
                        var gender = customer.gender.trim();
                        var matched = false;

                        // Try exact match first
                        for (var i = 0; i < genderEl.options.length; i++) {
                            if (genderEl.options[i].value === gender) {
                                genderEl.selectedIndex = i;
                                matched = true;
                                break;
                            }
                        }

                        // Try case-insensitive match if exact match failed
                        if (!matched) {
                            for (var j = 0; j < genderEl.options.length; j++) {
                                if ((genderEl.options[j].value || '').toLowerCase() === gender.toLowerCase()) {
                                    genderEl.selectedIndex = j;
                                    break;
                                }
                            }
                        }
                    }

                    // Show customer status
                    if (statusDiv && statusText) {
                        if (isExisting) {
                            statusText.textContent = '✓ Existing Customer - Information will be updated and new invoice created';
                            statusText.className = 'badge badge-warning';
                            statusText.style.backgroundColor = '#ffc107';
                            statusText.style.color = '#000';
                        } else {
                            statusText.textContent = '✓ New Customer - New record will be created with invoice';
                            statusText.className = 'badge badge-success';
                            statusText.style.backgroundColor = '#28a745';
                            statusText.style.color = '#fff';
                        }
                        statusDiv.style.display = 'block';
                    }

                    // Show additional info for existing customers
                    if (isExisting && infoDiv && infoText) {
                        infoText.textContent = 'This customer already exists in the system. Their information will be updated with the new details you provide and a new invoice will be created.';
                        infoDiv.style.display = 'block';
                    } else if (!isExisting && infoDiv) {
                        infoDiv.style.display = 'none';
                    }

                    // Toggle Offer input based on membership_card presence
                    if (offerGroup) {
                        var hasCard = !!(customer.membership_card && String(customer.membership_card).trim());
                        offerGroup.style.display = hasCard ? 'block' : 'none';
                    }
                } else {
                    // Clear fields if no customer found
                    if (nameEl) nameEl.value = '';
                    if (emailEl) emailEl.value = '';
                    if (genderEl) genderEl.selectedIndex = 0; // Reset to default option

                    // Hide status and info
                    if (statusDiv) statusDiv.style.display = 'none';
                    if (infoDiv) infoDiv.style.display = 'none';

                    // Hide offer group
                    if (offerGroup) offerGroup.style.display = 'none';
                }
            }

            function findCustomerByNumber(number) {
                var listId = numberInput.getAttribute('list');
                if (!listId) return null;

                var list = document.getElementById(listId);
                if (!list) return null;

                for (var k = 0; k < list.options.length; k++) {
                    var opt = list.options[k];
                    if ((opt.value || '').trim() === number.trim()) {
                        return {
                            name: opt.getAttribute('data-name') || '',
                            email: opt.getAttribute('data-email') || '',
                            gender: opt.getAttribute('data-gender') || '',
                            membership_card: opt.getAttribute('data-membership-card') || ''
                        };
                    }
                }
                return null;
            }

            function findCustomerByNumberAjax(number) {
                return new Promise(function(resolve, reject) {
                    // Check if jQuery is available for AJAX
                    if (typeof jQuery === 'undefined') {
                        resolve(null);
                        return;
                    }

                    jQuery.ajax({
                        url: '{{ route("customer.get-by-number") }}',
                        method: 'POST',
                        data: {
                            number: number,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success && response.customer) {
                                resolve({
                                    name: response.customer.name || '',
                                    email: response.customer.email || '',
                                    gender: response.customer.gender || '',
                                    membership_card: response.customer.membership_card || ''
                                });
                            } else {
                                resolve(null);
                            }
                        },
                        error: function() {
                            resolve(null);
                        }
                    });
                });
            }

            function onNumberChanged(){
                var val = (numberInput.value || '').trim();

                if (!val) {
                    applyCustomerDetails(null, false);
                    return;
                }

                // Only auto-fill if the number is exactly 10 digits (valid phone number)
                if (val.length === 10 && /^\d+$/.test(val)) {
                    // First try to find in datalist
                    var customerData = findCustomerByNumber(val);

                    if (customerData && (customerData.name || customerData.email)) {
                        // Found in datalist, apply immediately
                        applyCustomerDetails(customerData, true);
                    } else {
                        // Not found in datalist, try AJAX lookup
                        findCustomerByNumberAjax(val).then(function(ajaxData) {
                            if (ajaxData && (ajaxData.name || ajaxData.email)) {
                                applyCustomerDetails(ajaxData, true);
                            } else {
                                applyCustomerDetails(null, false);
                            }
                        });
                    }
                } else {
                    // Clear fields if number is not valid
                    applyCustomerDetails(null, false);
                }
            }

            // Add event listeners
            numberInput.addEventListener('change', onNumberChanged);
            numberInput.addEventListener('blur', onNumberChanged);
            numberInput.addEventListener('input', function(){
                // Debounce the input to avoid too many calls
                clearTimeout(numberInput._timeout);
                numberInput._timeout = setTimeout(onNumberChanged, 300);
            });

            // Also trigger on paste
            numberInput.addEventListener('paste', function(){
                setTimeout(onNumberChanged, 100);
            });
        });
    })();
</script>
<script>
    // Wait for jQuery to be available
    function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() { waitForJQuery(callback); }, 100);
        }
    }

    waitForJQuery(function() {
        function getPurchaseBody() {
            return document.getElementById('purchase-items-body');
        }
        // Use delegated click for add purchase row to avoid null before modal opens
        const hiddenPurchaseTotal = document.getElementById('purchase-total-amount-hidden');

        function addPurchaseRow() {
            const purchaseBody = getPurchaseBody();
            if (!purchaseBody) { return; }
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select class="form-control purchase-product" name="purchase_product[]">
                        <option value="">-- Select Product --</option>
                        @isset($managements)
                            @foreach($managements as $mg)
                                <option value="{{ $mg->id }}" data-code="{{ $mg->product_code }}" data-price="{{ $mg->price }}" data-tax="0">{{ $mg->product_name }} ({{ $mg->product_code }}) - ₹{{ number_format($mg->price, 2) }}</option>
                            @endforeach
                        @endisset
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control purchase-code" name="purchase_code[]" placeholder="Product Code" value="">
                </td>
                <td>
                    <input type="number" name="purchase_amount[]" step="0.01" min="0" inputmode="decimal" placeholder="0.00" class="form-control purchase-amount" value="">
                </td>
                <td>
                    <input type="number" min="0" max="100" name="purchase_discount[]" inputmode="decimal" placeholder="0" class="form-control purchase-discount" value="0">
                </td>
                <td>
                    <input type="number" min="0" max="100" name="purchase_tax[]" inputmode="decimal" placeholder="0" class="form-control purchase-tax" value="0">
                </td>
                <td>
                    <input type="text" name="purchase_line_total[]" class="form-control purchase-line-total" placeholder="0.00" value="0.00" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-purchase-item">Remove</button>
                </td>
            `;

            purchaseBody && purchaseBody.appendChild(row);
            bindPurchaseRowEvents(row);
            recalcPurchaseTotals();

            // Focus amount field immediately for quick entry
            const amountInputNew = row.querySelector('.purchase-amount');
            if (amountInputNew) { amountInputNew.focus(); amountInputNew.select(); }
        }

        function bindPurchaseRowEvents(row) {
            const productSelect = row.querySelector('.purchase-product');
            const codeInput = row.querySelector('.purchase-code');
            const amountInput = row.querySelector('.purchase-amount');
            const discountInput = row.querySelector('.purchase-discount');
            const taxInput = row.querySelector('.purchase-tax');
            const lineTotalInput = row.querySelector('.purchase-line-total');
            const removeBtn = row.querySelector('.remove-purchase-item');

            productSelect && productSelect.addEventListener('change', function() {
                const price = this.options[this.selectedIndex]?.getAttribute('data-price') || '';
                const code = this.options[this.selectedIndex]?.getAttribute('data-code') || '';
                const tax = this.options[this.selectedIndex]?.getAttribute('data-tax') || '0';
                if (codeInput) codeInput.value = code;
                if (amountInput && price !== '') amountInput.value = price;
                if (taxInput) taxInput.value = tax;
                recalcPurchaseRow(amountInput, taxInput, lineTotalInput);
                amountInput && amountInput.focus();
                amountInput && amountInput.select();
            });

            amountInput && amountInput.addEventListener('input', function() {
                recalcPurchaseRow(amountInput, taxInput, lineTotalInput);
            });

            discountInput && discountInput.addEventListener('input', function() {
                recalcPurchaseRow(amountInput, taxInput, lineTotalInput);
            });

            taxInput && taxInput.addEventListener('input', function() {
                recalcPurchaseRow(amountInput, taxInput, lineTotalInput);
            });

            removeBtn && removeBtn.addEventListener('click', function() {
                row.remove();
                recalcPurchaseTotals();
            });

            amountInput && amountInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); discountInput && discountInput.focus(); discountInput && discountInput.select(); }
            });
            discountInput && discountInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); taxInput && taxInput.focus(); taxInput && taxInput.select(); }
            });
				taxInput && taxInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
						// Trigger add action safely without relying on undefined variables
						$('#addnew #add-purchase-item').trigger('click');
						const lastRow = document.getElementById('purchase-items-body')?.querySelector('tr:last-child');
                    if (lastRow) {
                        const amt = lastRow.querySelector('.purchase-amount');
                        amt && amt.focus();
                    }
                }
            });

            // Select content on focus for quick overwrite
            amountInput && amountInput.addEventListener('focus', function() { amountInput.select(); });
            discountInput && discountInput.addEventListener('focus', function() { discountInput.select(); });
            taxInput && taxInput.addEventListener('focus', function() { taxInput.select(); });
        }

        function recalcPurchaseRow(amountInput, taxInput, lineTotalInput) {
            const amt = parseFloat(amountInput?.value) || 0;
            const discountEl = taxInput?.closest('tr') ? taxInput.closest('tr').querySelector('.purchase-discount') : null;
            const discountPct = parseFloat(discountEl && discountEl.value) || 0;
            const net = amt - (amt * (discountPct / 100));
            const taxPct = parseFloat(taxInput?.value) || 0;
            const taxAmt = net * (taxPct / 100);
            const line = net + taxAmt;
            if (lineTotalInput) lineTotalInput.value = line.toFixed(2);
            recalcPurchaseTotals();
        }

        function recalcPurchaseTotals() {
            const purchaseBody = getPurchaseBody();
            if (!purchaseBody) { return; }
            let sumAmount = 0;
            let sumTax = 0;
            let sumTotal = 0;
            const rows = purchaseBody.querySelectorAll('tr');
            rows.forEach(function(r) {
                const amt = parseFloat(r.querySelector('.purchase-amount')?.value) || 0;
                const discountPct = parseFloat(r.querySelector('.purchase-discount')?.value) || 0;
                const net = amt - (amt * (discountPct / 100));
                const taxPct = parseFloat(r.querySelector('.purchase-tax')?.value) || 0;
                const taxAmt = net * (taxPct / 100);
                const line = parseFloat(r.querySelector('.purchase-line-total')?.value) || (net + taxAmt);
                sumAmount += net;
                sumTax += taxAmt;
                sumTotal += line;
            });
            if (hiddenPurchaseTotal) hiddenPurchaseTotal.value = sumTotal.toFixed(2);
        }

        $(document).on('click', '#addnew #add-purchase-item', function(){ addPurchaseRow(); });

        // Initialize when modal opens - use jQuery safely
        $(document).on('shown.bs.modal', '#addnew', function () {
            const purchaseBody = getPurchaseBody();
            if (purchaseBody) { purchaseBody.innerHTML = ''; }
            addPurchaseRow();
        });
    });
</script>
{{-- <script>
    // Wait for jQuery to be available
    function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() { waitForJQuery(callback); }, 100);
        }
    }

    waitForJQuery(function() {
        var branchSelect = document.getElementById('branch');
        var placeInput = document.getElementById('branch-info-place');
        var fallbackPlaceByBranch = {
            'rajesh': 'bra'
        };

        function updatePlace() {
            if (!branchSelect) return;
            var opt = branchSelect.options[branchSelect.selectedIndex];
            var place = '';
            if (opt) {
                // Prefer address; fallback to place
                place = opt.getAttribute('data-address') || opt.getAttribute('data-place') || '';
                if (!place) {
                    var nameLower = (opt.textContent || '').trim().toLowerCase();
                    if (nameLower && Object.prototype.hasOwnProperty.call(fallbackPlaceByBranch, nameLower)) {
                        place = fallbackPlaceByBranch[nameLower];
                    }
                }
            }
            if (placeInput) placeInput.value = place;
        }

        branchSelect && branchSelect.addEventListener('change', updatePlace);

        // Initialize on modal open and clear when closed
        $(document).on('shown.bs.modal', '#addnew', function () {
            updatePlace();
        });
        $(document).on('hidden.bs.modal', '#addnew', function () {
            if (placeInput) { placeInput.value = ''; }
            if (branchSelect) { branchSelect.selectedIndex = 0; }
        });
    });
</script> --}}
<script>
    // Wait for jQuery to be available
    function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() { waitForJQuery(callback); }, 100);
        }
    }

    waitForJQuery(function() {
        document.getElementById('employeeSelect').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var employeeID = selectedOption.getAttribute('data-employee-id');
            document.getElementById('employeeDetails').value = employeeID;
        });

        var targetBodyId = null;
        var targetHiddenTotalId = null;

        function recalcModalTotal() {
            var amt = parseFloat(document.getElementById('modal-product-amount').value) || 0;
            var taxPct = parseFloat(document.getElementById('modal-product-tax').value) || 0;
            var total = amt + (amt * (taxPct / 100));
            document.getElementById('modal-product-total').value = total.toFixed(2);
        }

        // Hijack Add New Item button to open modal instead
        var addBtn = document.getElementById('add-purchase-item');
        // Only hijack the click to open modal if explicitly enabled with data-mode="modal"
        if (addBtn && addBtn.dataset.mode === 'modal') {
            addBtn.addEventListener('click', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
                if (ev.stopImmediatePropagation) ev.stopImmediatePropagation();
                targetBodyId = 'purchase-items-body';
                targetHiddenTotalId = 'purchase-total-amount-hidden';
                var sel = document.getElementById('modal-product-select');
                var code = document.getElementById('modal-product-code');
                var amt = document.getElementById('modal-product-amount');
                var tax = document.getElementById('modal-product-tax');
                var tot = document.getElementById('modal-product-total');
                if (sel) sel.value = '';
                if (code) code.value = '';
                if (amt) amt.value = '';
                if (tax) tax.value = '0';
                if (tot) tot.value = '0.00';
                $('#productItemModal').modal('show');
                setTimeout(function(){ sel && sel.focus(); }, 250);
            }, true);
        }

        document.getElementById('modal-product-select') && document.getElementById('modal-product-select').addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            var code = opt.getAttribute('data-code') || '';
            var price = opt.getAttribute('data-price') || '';
            var tax = opt.getAttribute('data-tax') || '0';
            document.getElementById('modal-product-code').value = code;
            document.getElementById('modal-product-amount').value = price;
            document.getElementById('modal-product-tax').value = tax;
            recalcModalTotal();
            var amt = document.getElementById('modal-product-amount');
            amt && amt.focus();
            amt && amt.select();
        });

        document.getElementById('modal-product-amount') && document.getElementById('modal-product-amount').addEventListener('input', recalcModalTotal);
        document.getElementById('modal-product-tax') && document.getElementById('modal-product-tax').addEventListener('input', recalcModalTotal);

        function recalcTargetTotals() {
            if (!targetBodyId) return;
            var body = document.getElementById(targetBodyId);
            var rows = body ? body.querySelectorAll('tr') : [];
            var sumAmount = 0, sumTax = 0, sumTotal = 0;
            rows.forEach(function(r){
                var amt = parseFloat(r.querySelector('.purchase-amount') && r.querySelector('.purchase-amount').value) || 0;
                var taxPct = parseFloat(r.querySelector('.purchase-tax') && r.querySelector('.purchase-tax').value) || 0;
                var line = parseFloat(r.querySelector('.purchase-line-total') && r.querySelector('.purchase-line-total').value);
                if (isNaN(line)) { line = amt + (amt * (taxPct/100)); }
                sumAmount += amt;
                sumTax += amt * (taxPct/100);
                sumTotal += line;
            });
            if (targetHiddenTotalId) {
                var hidden = document.getElementById(targetHiddenTotalId);
                if (hidden) hidden.value = sumTotal.toFixed(2);
            }
        }

        document.getElementById('modal-save-product') && document.getElementById('modal-save-product').addEventListener('click', function() {
            if (!targetBodyId) return;
            var body = document.getElementById(targetBodyId);
            if (!body) return;

            var prodSel = document.getElementById('modal-product-select');
            var prodId = prodSel.value;
            var prodName = prodSel.options[prodSel.selectedIndex] ? prodSel.options[prodSel.selectedIndex].text : '';
            if (!prodId) { alert('Please select a product'); return; }

            var code = document.getElementById('modal-product-code').value || '';
            var amt = parseFloat(document.getElementById('modal-product-amount').value) || 0;
            var taxPct = parseFloat(document.getElementById('modal-product-tax').value) || 0;
            var total = amt + (amt * (taxPct / 100));

            var row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select class="form-control purchase-product" name="purchase_product[]">
                        <option value="${prodId}" selected>${prodName.replace(/</g,'&lt;')}</option>
                    </select>
                </td>
                <td><input type="text" class="form-control purchase-code" name="purchase_code[]" value="${code.replace(/"/g,'&quot;')}"></td>
                <td><input type="number" name="purchase_amount[]" step="0.01" min="0" inputmode="decimal" class="form-control purchase-amount" value="${amt.toFixed(2)}" ></td>
                <td><input type="number" min="0" max="100" name="purchase_tax[]" inputmode="decimal" class="form-control purchase-tax" value="${taxPct}"></td>
                <td><input type="text" name="purchase_line_total[]" class="form-control purchase-line-total" value="${total.toFixed(2)}" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-purchase-item">Remove</button></td>`;

            body.appendChild(row);

            var removeBtn = row.querySelector('.remove-purchase-item');
            if (removeBtn) {
                removeBtn.addEventListener('click', function(){
                    row.remove();
                    recalcTargetTotals();
                });
            }

            recalcTargetTotals();
            $('#productItemModal').modal('hide');
        });
    });
</script>

{{-- <script>
function myFunction() {
  let x = document.getElementById("date").autofocus;
  document.getElementById("demo").innerHTML = x;
}
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var branchSelect = document.getElementById('branch_id');
        var placeInput = document.getElementById('branch-info-place');

        if (!branchSelect || !placeInput) { return; }

        function updatePlaceFromBranch() {
            var selected = branchSelect.options[branchSelect.selectedIndex];
            if (!selected) { placeInput.value = ''; return; }
            var place = selected.getAttribute('data-address') || selected.getAttribute('data-place') || '';
            placeInput.value = place;
        }

        branchSelect.addEventListener('change', updatePlaceFromBranch);

        // Initialize on load if a branch is preselected
        updatePlaceFromBranch();
    });
</script>

<!-- Auto-fill functionality for addmember modal -->
<script>
    (function() {
        function waitForDom(cb){
            if(document.readyState==='complete' || document.readyState==='interactive'){
                return cb();
            }
            document.addEventListener('DOMContentLoaded', cb);
        }

        waitForDom(function(){
            // Target the addmember modal specifically
            var numberInput = document.querySelector('#addmember #numberInput');
            if (!numberInput) return;

            var originalValues = {
                name: '',
                email: '',
                gender: '',
                place: ''
            };

            function applyCustomerDetails(customer, isExisting){
                var nameEl = document.querySelector('#addmember #name');
                var emailEl = document.querySelector('#addmember #email');
                var genderEl = document.querySelector('#addmember #gender');
                var placeEl = document.querySelector('#addmember #place');
                var statusDiv = document.querySelector('#addmember #customer-status');
                var statusText = document.querySelector('#addmember #customer-status-text');
                var infoDiv = document.querySelector('#addmember #customer-info');
                var infoText = document.querySelector('#addmember #customer-info-text');

                if (customer) {
                    // Store current values as original if they exist
                    if (nameEl && nameEl.value) originalValues.name = nameEl.value;
                    if (emailEl && emailEl.value) originalValues.email = emailEl.value;
                    if (genderEl && genderEl.value) originalValues.gender = genderEl.value;
                    if (placeEl && placeEl.value) originalValues.place = placeEl.value;

                    // Apply customer details
                    if (nameEl) nameEl.value = customer.name || '';
                    if (emailEl) emailEl.value = customer.email || '';
                    if (placeEl) placeEl.value = customer.place || '';

                    if (genderEl && customer.gender) {
                        var gender = customer.gender.trim();
                        var matched = false;

                        // Try exact match first
                        for (var i = 0; i < genderEl.options.length; i++) {
                            if (genderEl.options[i].value === gender) {
                                genderEl.selectedIndex = i;
                                matched = true;
                                break;
                            }
                        }

                        // Try case-insensitive match if exact match failed
                        if (!matched) {
                            for (var j = 0; j < genderEl.options.length; j++) {
                                if ((genderEl.options[j].value || '').toLowerCase() === gender.toLowerCase()) {
                                    genderEl.selectedIndex = j;
                                    break;
                                }
                            }
                        }
                    }

                    // Show customer status
                    if (statusDiv && statusText) {
                        if (isExisting) {
                            statusText.textContent = '✓ Existing Customer - Information will be updated and new invoice created';
                            statusText.className = 'badge badge-warning';
                            statusText.style.backgroundColor = '#ffc107';
                            statusText.style.color = '#000';
                        } else {
                            statusText.textContent = '✓ New Customer - New record will be created with invoice';
                            statusText.className = 'badge badge-success';
                            statusText.style.backgroundColor = '#28a745';
                            statusText.style.color = '#fff';
                        }
                        statusDiv.style.display = 'block';
                    }

                    // Show additional info for existing customers
                    if (isExisting && infoDiv && infoText) {
                        infoText.textContent = 'This customer already exists in the system. Their information will be updated with the new details you provide and a new invoice will be created.';
                        infoDiv.style.display = 'block';
                    } else if (!isExisting && infoDiv) {
                        infoDiv.style.display = 'none';
                    }
                } else {
                    // Clear fields if no customer found
                    if (nameEl) nameEl.value = '';
                    if (emailEl) emailEl.value = '';
                    if (placeEl) placeEl.value = '';
                    if (genderEl) genderEl.selectedIndex = 0; // Reset to default option

                    // Hide status and info
                    if (statusDiv) statusDiv.style.display = 'none';
                    if (infoDiv) infoDiv.style.display = 'none';
                }
            }

            function findCustomerByNumber(number) {
                var listId = numberInput.getAttribute('list');
                if (!listId) return null;

                var list = document.getElementById(listId);
                if (!list) return null;

                for (var k = 0; k < list.options.length; k++) {
                    var opt = list.options[k];
                    if ((opt.value || '').trim() === number.trim()) {
                        return {
                            name: opt.getAttribute('data-name') || '',
                            email: opt.getAttribute('data-email') || '',
                            gender: opt.getAttribute('data-gender') || '',
                            place: opt.getAttribute('data-place') || ''
                        };
                    }
                }
                return null;
            }

            function findCustomerByNumberAjax(number) {
                return new Promise(function(resolve, reject) {
                    // Check if jQuery is available for AJAX
                    if (typeof jQuery === 'undefined') {
                        resolve(null);
                        return;
                    }

                    jQuery.ajax({
                        url: '{{ route("customer.get-by-number") }}',
                        method: 'POST',
                        data: {
                            number: number,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success && response.customer) {
                                resolve({
                                    name: response.customer.name || '',
                                    email: response.customer.email || '',
                                    gender: response.customer.gender || '',
                                    place: response.customer.place || ''
                                });
                            } else {
                                resolve(null);
                            }
                        },
                        error: function() {
                            resolve(null);
                        }
                    });
                });
            }

            function onNumberChanged(){
                var val = (numberInput.value || '').trim();

                if (!val) {
                    applyCustomerDetails(null, false);
                    return;
                }

                // Only auto-fill if the number is exactly 10 digits (valid phone number)
                if (val.length === 10 && /^\d+$/.test(val)) {
                    // First try to find in datalist
                    var customerData = findCustomerByNumber(val);

                    if (customerData && (customerData.name || customerData.email)) {
                        // Found in datalist, apply immediately
                        applyCustomerDetails(customerData, true);
                    } else {
                        // Not found in datalist, try AJAX lookup
                        findCustomerByNumberAjax(val).then(function(ajaxData) {
                            if (ajaxData && (ajaxData.name || ajaxData.email)) {
                                applyCustomerDetails(ajaxData, true);
                            } else {
                                applyCustomerDetails(null, false);
                            }
                        });
                    }
                } else {
                    // Clear fields if number is not valid
                    applyCustomerDetails(null, false);
                }
            }

            // Add event listeners
            numberInput.addEventListener('change', onNumberChanged);
            numberInput.addEventListener('blur', onNumberChanged);
            numberInput.addEventListener('input', function(){
                // Debounce the input to avoid too many calls
                clearTimeout(numberInput._timeout);
                numberInput._timeout = setTimeout(onNumberChanged, 300);
            });

            // Also trigger on paste
            numberInput.addEventListener('paste', function(){
                setTimeout(onNumberChanged, 100);
            });
        });
    })();
</script>


<!-- add customer -->
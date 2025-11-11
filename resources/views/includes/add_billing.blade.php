<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b>Add Billing</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('billing.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Customer name</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="customer_name" name="customer_name">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="product_code" class="col-sm-5 control-label">Customer phone number</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control " id="customer_number" name="customer_number">
                            </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="branch" class="col-sm-5 control-label">Category</label>
                            <div class="bootstrap" id="branch">
                                <label><input type="checkbox" id="service" name="branch" value="service"> Service</label>
                                <label><input type="checkbox" id="product" name="branch" value="product"> Product</label>
                            </div>
                    </div>
                    <div id="service-inputs"></div>
                    <div id="product-inputs"></div> --}}
                    {{-- <div class="form-group">
                        <label for="Quantity" class="col-sm-5 control-label">Quantity</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="Quantity" name="Quantity">
                            </div>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">price</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="price" name="price">
                            </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="productSelect" class="col-sm-5 control-label">Product Name</label>
                        <select class="select2 form-control" id="productSelect" name="management_id">
                            <option selected disabled>Select product</option>
                            @foreach($managements as $management)
                                <option value="{{ $management->id }}" data-amount="{{ $management->product_code }}"> {{ $management->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-3 control-label">Product Code</label>
                        <div class="bootstrap">
                            <input type="text" class="form-control" id="productAmount" name="product_code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Quantity" class="col-sm-5 control-label">count</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="Quantity" name="Quantity">
                            </div>
                    </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Amount</label>
                    <input type="number" class="form-control" name="price" id="amount">
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Discount</label>
                    <input type="number" class="form-control" name="discount" id="discount">
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Total Amount</label>
                    <input type="text" class="form-control" name="total_amount" id="total_amount" readonly>
                </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Tax (%)</label>
                        <input type="number" class="form-control" name="tax" id="tax" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Total Calculation</label>
                        <input type="text" class="form-control" name="total_calculation" id="total_calculation" readonly />
                    </div>
                    {{-- <div class="form-group">
                        <label for="date" class="col-sm-3 control-label">Payment</label>
                            <div class="bootstrap">
                                <input type="text" class="form-control" id="payment" name="payment">
                            </div>
                    </div> --}}
                       <div class="form-group">
                            <label for="name">Payment Method</label>
                                    <select class="form-control @error('payment') is-invalid @enderror" id="payment" name="payment" required>
                                        <option> --Select-- </option>
                                        <option value="Cash" {{ old('Cash') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Debit card / Credit card" {{ old('Debit card / Credit card') == 'Debit card / Credit card' ? 'selected' : '' }}>Debit card / Credit card</option>
                                        <option value="Paytm" {{ old('Paytm') == 'Paytm' ? 'selected' : '' }}>Paytm</option>
                                        <option value="Gpay" {{ old('Gpay') == 'Gpay' ? 'selected' : '' }}>Gpay</option>
                                    </select>
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
    document.addEventListener("DOMContentLoaded", function () {
        const productSelect = document.getElementById("productSelect");
        const productAmount = document.getElementById("productAmount");
        const salesTableBody = document.querySelector("#salesTable tbody");

        productSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption && selectedOption.value) {
                const productName = selectedOption.text.trim();
                const productCode = selectedOption.getAttribute("data-amount");
                productAmount.value = productCode;
                const newRow = `
                    <tr>
                        <td>${productName}</td>
                        <td>${productCode}</td>
                    </tr>
                `;
                salesTableBody.innerHTML += newRow;
            }
        });
    });

    function toggleInputs(checkboxId, containerId) {
        const checkbox = document.getElementById(checkboxId);
        const container = document.getElementById(containerId);

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                container.innerHTML = `
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Quantity (${checkboxId})</label>
                        <div class="bootstrap">
                            <input type="number" class="form-control" name="Quantity" id="quantity_${checkboxId}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Price (${checkboxId})</label>
                        <div class="bootstrap">
                            <input type="number" class="form-control" name="price" id="price_${checkboxId}" oninput="calculateTotal(1)"('${checkboxId}')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Discount (${checkboxId})</label>
                        <div class="bootstrap">
                            <input type="number" class="form-control" name="discount" id="discount_${checkboxId}" oninput="calculateTotal(1)"('${checkboxId}')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Total Amount (${checkboxId})</label>
                        <div class="bootstrap">
                            <input type="text" class="form-control" name="total_amount" id="total_amount_${checkboxId}" readonly>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = '';
            }
        });
    }

    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount');
    const taxInput = document.getElementById('tax');
    const totalAmountInput = document.getElementById('total_amount');
    const totalCalculationInput = document.getElementById('total_calculation');

    function calculateTotal() {
        let amount = parseFloat(amountInput.value) || 0;
        let discount = parseFloat(discountInput.value) || 0;
        let tax = parseFloat(taxInput.value) || 0;

        // If discount is a percentage (0-100)
        if (discount > 100) {
            discount = 100;
            discountInput.value = 100;
        }

        let discountAmount = (amount * discount / 100);
        let total = amount - discountAmount;
        totalAmountInput.value = total >= 0 ? total.toFixed(2) : 0;

        // Calculate total calculation with tax
        let taxAmount = total * (tax / 100);
        let totalCalculation = total + taxAmount;
        totalCalculationInput.value = totalCalculation >= 0 ? totalCalculation.toFixed(2) : 0;
    }

    amountInput.addEventListener('input', calculateTotal);
    discountInput.addEventListener('input', calculateTotal);
    taxInput.addEventListener('input', calculateTotal);

    toggleInputs('service', 'service-inputs');
    toggleInputs('product', 'product-inputs');
</script>

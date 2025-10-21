<!-- Edit -->
<div class="modal fade" id="edit{{ $purchase->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <h4 class="modal-title"><b><span class="">Edit Management</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('billing.update', $purchase) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product Name</label>
                        <input type="text" class="form-control"  name="customer_name" value="{{ $purchase->customer_name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product Code</label>
                        <input type="text" class="form-control"  name="customer_number" value="{{ $purchase->customer_number }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Quantity</label>
                        <input type="text" class="form-control"  id="quantity_{{ $purchase->id }}" name="Quantity" value="{{ $purchase->Quantity }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Price</label>
                        <input type="text" class="form-control" id="price_{{ $purchase->id }}" name="price" value="{{ $purchase->price }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Total Amount</label>
                        <input type="text" class="form-control" id="totalAmount_{{ $purchase->id }}"  name="total_amount" value="{{ $purchase->total_amount }}"
                            required>
                    </div>
                    <div class="product-container form-group">
                        <label for="category" class="col-sm-5 control-label">Product name</label>
                            <select class="select2 form-control" id="productSelect_{{ $purchase->id }}" name="management_id">
                                <option selected disabled>Select category name</option>
                                @foreach($managements as $management)
                                    <option value="{{ $management->id }}"{{ $purchase->management_id == $management->id ? 'selected' : '' }} data-amount="{{ $management->product_code }}">
                                        {{$management->product_name}}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Product Code</label>
                        <input type="text" class="code form-control" id="productAmounts_{{ $purchase->id }}" name="product_code" value="{{ $purchase->product_code }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Payment</label>
                        <input type="text" class="form-control"  name="payment" value="{{ $purchase->payment }}"
                            required>
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
<div class="modal fade" id="delete{{ $purchase->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items: center">
                <h4 class="modal-title"><span class="employee_id">Delete Billing</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('billing.destroy', $purchase->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <h6>Are you sure you want to delete:</h6>
                    <h2 class="bold del_employee_name">{{ $purchase->customer_name }}</h2>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                        <i class="fa fa-close"></i> Close
                    </button>
                    <button type="submit" class="btn btn-danger btn-flat">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productSelect = document.getElementById("productSelect_{{ $purchase->id }}");
        const productAmount = document.getElementById("productAmounts_{{ $purchase->id }}");
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
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.getElementById('quantity_{{ $purchase->id }}');
        const priceInput = document.getElementById('price_{{ $purchase->id }}');
        const totalAmountInput = document.getElementById('totalAmount_{{ $purchase->id }}');

        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            totalAmountInput.value = (quantity * price).toFixed(2);
        }

        quantityInput.addEventListener('input', calculateTotal);
        priceInput.addEventListener('input', calculateTotal);
    });
</script>

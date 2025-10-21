@extends('layouts.master')

@section('button')
@php
    $user = auth()->user();
    $userPermissions = checkUserPermissions($user);
    $permissions = $userPermissions['permissions'];
    $hasFullAccess = $userPermissions['hasFullAccess'];
@endphp
{{-- <a href="#addnewinvoice" data-toggle="modal" class="btn btn-success btn-sm btn-flat">
    <i class="mdi mdi-plus mr-2"></i>Invoice
</a> --}}
@if($hasFullAccess || hasPermission($permissions, 'customers', 'write'))
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
    <i class="mdi mdi-plus mr-2"></i>Add Bill
</a>
@endif
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-9">
            <form method="get" action="{{ route('bill.index') }}" class="row g-2">
                <div class="col-auto">
                    <label class="form-label mb-0">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $filters['date'] ?? now()->toDateString() }}">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">Number</label>
                    <input type="text" name="number" class="form-control" placeholder="Customer Number" value="{{ $filters['number'] ?? '' }}">
                </div>
                <div class="col-auto align-self-end">
                    <button class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bills</div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Customer Name</th>
                                <th>Customer ID</th>
                                <th>Phone Number</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td>{{ $bill->id }}</td>
                                <td>{{ $bill->customer->name ?? 'N/A' }}</td>
                                <td>{{ $bill->customer->customer_id ?? 'N/A' }}</td>
                                <td>{{ $bill->customer->number ?? 'N/A' }}</td>
                                <td>{{ optional($bill->date)->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format((float) $bill->amount, 2) }}</td>
                                <td>{{ number_format((float) $bill->tax, 2) }}</td>
                                <td>{{ number_format((float) $bill->total_amount, 2) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-file-pdf-o"></i> Invoice
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('invoice.download', $bill->id) }}">
                                                <i class="fa fa-download"></i> Download PDF
                                            </a>
                                            <a class="dropdown-item" href="{{ route('invoice.view', $bill->id) }}" target="_blank">
                                                <i class="fa fa-eye"></i> View in Browser
                                            </a>
                                            <a class="dropdown-item" href="{{ route('customer.email-invoice', $bill->customer_id) }}" onclick="return confirm('Send invoice to {{ $bill->customer->email ?? "this customer" }}?')">
                                                <i class="fa fa-envelope"></i> Email Invoice
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#customInvoice{{ $bill->id }}">
                                                <i class="fa fa-cog"></i> Custom Invoice
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                     <button type="button" class="btn btn-success btn-sm edit btn-flat" onclick="printInvoice({{ $bill->id }})">
                                        <i class='fa fa-print'></i> Print
                                    </button>
                            </tr>
                            @php
                                $itemsRaw = $bill->service_items ?? [];
                                if (is_string($itemsRaw)) {
                                    $decoded = json_decode($itemsRaw, true);
                                    $items = is_array($decoded) ? $decoded : [];
                                } else {
                                    $items = is_array($itemsRaw) ? $itemsRaw : [];
                                }
                                $overallTaxPercent = (float) ($bill->service_tax ?? 0);
                            @endphp
                            @if(!empty($items))
                            {{-- <tr>
                                <td colspan="7">
                                    <div class="fw-bold mb-1">Service Items</div>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Amount</th>
                                                    <th class="text-end">Discount %</th>
                                                    <th class="text-end">Tax ({{ number_format($overallTaxPercent, 2) }}%)</th>
                                                    <th class="text-end">Line Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                    @php
                                                        $qty = isset($item['qty']) ? (float)$item['qty'] : 1;
                                                        $amount = (float) ($item['amount'] ?? ($item['price'] ?? 0));
                                                        $amount = $amount * ($qty ?: 1);
                                                        $discountPct = (float) ($item['discount'] ?? 0);
                                                        $net = $amount - ($amount * ($discountPct / 100));
                                                        $taxAmt = $net * ($overallTaxPercent / 100);
                                                        $lineTotal = $net + $taxAmt;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item['service_name'] ?? ($item['name'] ?? ($item['particulars'] ?? '-')) }}</td>
                                                        <td class="text-end">{{ rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') }}</td>
                                                        <td class="text-end">{{ number_format($amount, 2) }}</td>
                                                        <td class="text-end">{{ number_format($discountPct, 2) }}</td>
                                                        <td class="text-end">{{ number_format($taxAmt, 2) }}</td>
                                                        <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr> --}}
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No bills found for the selected date.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="card mt-3">
                <div class="card-header">Customers</div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Customer ID</th>
                                <th>Phone</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->customer_id }}</td>
                                <td>{{ $customer->number }}</td>
                                <td>{{ optional($customer->date)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No customers found for the selected filter.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Bill Details</div>
                <div class="card-body">
                    @if($selectedCustomer)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-2 rounded-circle bg-secondary" style="width:40px;height:40px"></div>
                            <div>
                                <div class="fw-bold">{{ $selectedCustomer->name }}</div>
                                <div class="text-muted">{{ $selectedCustomer->number }}</div>
                            </div>
                        </div>
                        <div class="mb-2"><span class="text-muted">No of visit:</span> <strong>{{ $visitsCount }}</strong></div>
                        <div class="mb-2"><span class="text-muted">Total no bill:</span> <strong>{{ $visitsCount }}</strong></div>
                        <div class="mb-2"><span class="text-muted">Avg bill total:</span> <strong>{{ $visitsCount ? number_format($totalBillsAmount / $visitsCount, 2) : '0.00' }}</strong></div>
                        <div class="mb-2"><span class="text-muted">Last visit:</span> <strong>{{ optional($selectedCustomer->date)->format('d/m/Y') }}</strong></div>
                    @else
                        <div class="text-muted">Filter by Customer ID to see customer statistics.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($customers as $customer)
{{-- @include('includes.edit_delete_customer') --}}
@endforeach

@include('includes.add_customer')

<script>
function printInvoice(invoiceId) {
    // Open the invoice in a new window for printing (by invoice id)
    const printWindow = window.open('{{ route('invoice.view', ':id') }}'.replace(':id', invoiceId), '_blank', 'width=800,height=600');

    // Wait for the window to load, then trigger print dialog
    printWindow.onload = function() {
        // Small delay to ensure content is fully loaded
        setTimeout(function() {
            printWindow.print();
        }, 1000);
    };

    // Focus on the print window
    printWindow.focus();
}
</script>

@endsection
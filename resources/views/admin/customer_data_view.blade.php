@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Customer Data View - {{ $customer->name }}</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Customer ID</th>
                                    <td>{{ $customer->customer_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $customer->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $customer->number }}</td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td>{{ $customer->branch }}</td>
                                </tr>
                                <tr>
                                    <th>Place</th>
                                    <td>{{ $customer->place ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $customer->date ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Totals</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Service Amount</th>
                                    <td>₹{{ number_format($customer->amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Service Tax</th>
                                    <td>₹{{ number_format($customer->tax ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Service Total</th>
                                    <td>₹{{ number_format($customer->total_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Total</th>
                                    <td>₹{{ number_format($customer->purchase_total_amount ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Service Items</h5>
                            @if($serviceItems && count($serviceItems) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Amount</th>
                                            <th>Tax %</th>
                                            <th>Tax Amount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($serviceItems as $item)
                                            <tr>
                                                <td>{{ $item['service_name'] ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($item['amount'] ?? 0, 2) }}</td>
                                                <td>{{ $item['tax_percentage'] ?? 0 }}%</td>
                                                <td>₹{{ number_format($item['tax_amount'] ?? 0, 2) }}</td>
                                                <td>₹{{ number_format($item['total_amount'] ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">No service items found.</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h5>Purchase Items</h5>
                            @if($purchaseItems && count($purchaseItems) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Code</th>
                                            <th>Amount</th>
                                            <th>Tax %</th>
                                            <th>Tax Amount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchaseItems as $item)
                                            <tr>
                                                <td>{{ $item['product_name'] ?? 'N/A' }}</td>
                                                <td>{{ $item['product_code'] ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($item['amount'] ?? 0, 2) }}</td>
                                                <td>{{ $item['tax_percentage'] ?? 0 }}%</td>
                                                <td>₹{{ number_format($item['tax_amount'] ?? 0, 2) }}</td>
                                                <td>₹{{ number_format($item['total_amount'] ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">No purchase items found.</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Raw JSON Data</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Service Items JSON:</h6>
                                    <pre style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">{{ $customer->service_items ?? 'No data' }}</pre>
                                </div>
                                <div class="col-md-6">
                                    <h6>Purchase Items JSON:</h6>
                                    <pre style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">{{ $customer->purchase_items ?? 'No data' }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



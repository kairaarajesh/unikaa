@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">

    <style>
        .mini-stat {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .mini-stat:hover {
            transform: translateY(-5px);
        }

        .mini-stat-img {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
        }

        .export-card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .export-card .card-header {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Billing</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Billing</a></li>
        </ol>
    </div>
@endsection
{{-- @section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Billing</a>
@endsection --}}

@section('content')
@include('includes.flash')

<!--Show Validation Errors here-->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!--End showing Validation Errors here-->

<!-- Summary Dashboard -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-start mini-stat-img me-4">
                        <i class="fa fa-money text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="font-size-16 text-uppercase mt-0 text-white">Total Sales</h5>
                    <h4 class="fw-medium font-size-24 text-white">₹{{ number_format($totalSales, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-success">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-start mini-stat-img me-4">
                        <i class="fa fa-calendar-day text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="font-size-16 text-uppercase mt-0 text-white">Today's Sales</h5>
                    <h4 class="fw-medium font-size-24 text-white">₹{{ number_format($todaySales, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-info">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-start mini-stat-img me-4">
                        <i class="fa fa-calendar-alt text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="font-size-16 text-uppercase mt-0 text-white">This Month</h5>
                    <h4 class="fw-medium font-size-24 text-white">₹{{ number_format($monthlySales, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-warning">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-start mini-stat-img me-4">
                        <i class="fa fa-calendar text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="font-size-16 text-uppercase mt-0 text-white">This Year</h5>
                    <h4 class="fw-medium font-size-24 text-white">₹{{ number_format($yearlySales, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th data-priority="1">ID</th>
                                        <th data-priority="2">Customer Name</th>
                                        <th data-priority="3">Customer Number</th>
                                        <th data-priority="4">Count</th>
                                        <th data-priority="5">Price</th>
                                        <th data-priority="5">Discount</th>
                                        <th data-priority="5">Total Amount</th>
                                        <th data-priority="5">Tax</th>
                                        <th data-priority="5">Total Calculation</th>
                                        <th data-priority="7">Product Name</th>
                                        <th data-priority="8">Product Code</th>
                                        <th data-priority="9">Payment</th>
                                        @php
                                            $user = auth()->user();
                                        @endphp
                                        @if($user && $user->role != 1  && $user->role != 2 && $user->role != 3 && $user->role != 4 && $user->role != 5 && $user->role != 6 && $user->role != 7 && $user->role != 8 && $user->role != 9 && $user->role != 10)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $purchase->customer_name }} </td>
                                            <td> {{ $purchase->customer_number }} </td>
                                            <td> {{ $purchase->Quantity }} </td>
                                            <td> {{ $purchase->price }} </td>
                                            <td> {{ $purchase->discount }} </td>
                                            <td> {{ $purchase->total_amount }} </td>
                                            <td> {{ $purchase->tax }} </td>
                                            <td> {{ $purchase->total_calculation }} </td>
                                            <td>{{($purchase->management)->product_name ?? 'N/A' }}</td>
                                            <td> {{ $purchase->product_code }} </td>
                                            <td> {{ $purchase->payment }} </td>
                                            @php
                                            $user = auth()->user();
                                        @endphp

                                        @if($user && $user->role != 1  && $user->role != 2 && $user->role != 3 && $user->role != 4 && $user->role != 5 && $user->role != 6 && $user->role != 7 && $user->role != 8 && $user->role != 9 && $user->role != 10)
                                            <td>
                                                 <a href="{{ route('billing.invoice', $purchase->id) }}" class="btn btn-info btn-sm" title="Download Invoice">
                                                    <i class="fa fa-file-pdf-o"></i> Invoice
                                                </a>
                                                <a href="#edit{{ $purchase->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                                    <i class='fa fa-edit'></i> Edit
                                                </a>
                                                <a href="#delete{{ $purchase->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
                                                    <i class='fa fa-trash'></i> Delete
                                                </a>

                                            </td>
                                        @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@foreach ($purchases as $purchase)
@include('includes.edit_delete_billing')
@endforeach
@include('includes.add_billing')

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card export-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa fa-file-excel-o mr-2"></i>Export Reports
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('billing.export') }}" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="type" class="mr-2 font-weight-bold">Report Type:</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="daily">Daily Report</option>
                            <option value="monthly">Monthly Report</option>
                            <option value="yearly">Yearly Report</option>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label for="date" class="mr-2 font-weight-bold">Date:</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-download mr-1"></i> Export Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });

            // Handle report type change
            $('#type').change(function() {
                var type = $(this).val();
                var dateInput = $('#date');

                if (type === 'daily') {
                    dateInput.attr('type', 'date');
                    dateInput.attr('placeholder', 'Select Date');
                } else if (type === 'monthly') {
                    dateInput.attr('type', 'month');
                    dateInput.attr('placeholder', 'Select Month');
                } else if (type === 'yearly') {
                    dateInput.attr('type', 'number');
                    dateInput.attr('min', '2020');
                    dateInput.attr('max', '2030');
                    dateInput.attr('placeholder', 'Enter Year (e.g., 2024)');
                }
            });

            // Trigger change event on page load
            $('#type').trigger('change');
        });
    </script>
@endsection

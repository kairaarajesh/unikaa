@extends('layouts.master')

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Customer</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Customer</a></li>
    </ol>
</div>
@endsection

@section('button')
{{-- <a href="#addnewinvoice" data-toggle="modal" class="btn btn-success btn-sm btn-flat">
    <i class="mdi mdi-plus mr-2"></i>Invoice
</a> --}}
<a href="#addmember" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
    <i class="mdi mdi-plus mr-2"></i>Add MB Card
</a>
@endsection

@section('content')
@include('includes.flash')

<style>
    /* Make the Place column scroll horizontally if content is long */
    td.scroll-x {
        max-width: 180px;
        white-space: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
    }
</style>

<!-- Filters -->
<form method="GET" action="{{ url()->current() }}" id="customerFilters">
    <div class="row mb-3 filter-section">
        <div class="col-md-4">
            <label for="numberFilter" class="form-label fw-bold">Filter by customer number:</label>
            <input type="text" class="form-control" id="numberFilter" name="number" list="numbersList" placeholder="All Number" value="{{ request('number') }}">
            <datalist id="numbersList">
                @foreach ($customers as $customer)
                    @if(!empty($customer->number))
                        <option value="{{ $customer->number }}"></option>
                    @endif
                @endforeach
            </datalist>
        </div>
        <div class="col-md-4">
            <label for="cardFilter" class="form-label fw-bold">Filter by Membership Card:</label>
            <input type="text" class="form-control" id="cardFilter" name="membership_card" list="cardsList" placeholder="All Member Card" value="{{ request('membership_card') }}">
            <datalist id="cardsList">
                @foreach ($customers as $customer)
                    @if(!empty($customer->membership_card))
                        <option value="{{ $customer->membership_card }}"></option>
                    @endif
                @endforeach
            </datalist>
        </div>
        <div class="col-md-4">
            <label for="periodFilter" class="form-label fw-bold">Filter by Period:</label>
            <select class="form-control" id="periodFilter" name="period">
                <option value="all" {{ ($period ?? request('period')) === 'all' ? 'selected' : '' }}>All</option>
                <option value="today" {{ ($period ?? request('period')) === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ ($period ?? request('period')) === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ ($period ?? request('period','month')) === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ ($period ?? request('period')) === 'year' ? 'selected' : '' }}>This Year</option>
                <option value="custom" {{ ($period ?? request('period')) === 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end mt-2 mt-md-0">
            <button type="submit" class="btn btn-primary" id="applyFilter">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
            <a href="{{ url()->current() }}" class="btn btn-secondary ms-2" id="resetFilter">
                <i class="fas fa-undo"></i> Reset
            </a>
            <div class="spinner-border spinner-border-sm text-primary ms-2" id="filterSpinner" style="display: none;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    {{-- Custom Date Range (Hidden by default) --}}
    <div class="row mb-3" id="customDateRange" style="display: none;">
        <div class="col-md-6">
            <label for="startDate" class="form-label fw-bold">Start Date:</label>
            <input type="date" class="form-control" id="startDate" name="start_date" value="{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : '' }}">
        </div>
        <div class="col-md-6">
            <label for="endDate" class="form-label fw-bold">End Date:</label>
            <input type="date" class="form-control" id="endDate" name="end_date" value="{{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : '' }}">
        </div>
    </div>
</form>
<!-- End Filters -->

<!-- Show Validation Errors -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<!-- End Validation Errors -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($period != 'all')
                    <div class="alert alert-info mb-3">
                        <i class="fa fa-info-circle"></i>
                        Showing customers for:
                        <strong>
                            @php
                                $label = '';
                                switch($period){
                                    case 'today':
                                        $label = 'Today (' . \Carbon\Carbon::parse($startDate ?? now())->format('d M Y') . ')';
                                        break;
                                    case 'week':
                                        $label = 'This Week (' . (isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '') . ' - ' . (isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '') . ')';
                                        break;
                                    case 'month':
                                        $label = 'This Month (' . (isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('01 M Y') : '') . ' - ' . (isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('t M Y') : '') . ')';
                                        break;
                                    case 'year':
                                        $label = 'This Year (' . (isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('01 Jan Y') : '') . ' - ' . (isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('31 Dec Y') : '') . ')';
                                        break;
                                    case 'custom':
                                        $label = 'Custom Range';
                                        break;
                                }
                            @endphp
                            {{ $label }}
                            @if(isset($startDate) && isset($endDate) && $period === 'custom')
                                ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})
                            @endif
                        </strong>
                    </div>
                @endif
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th data-priority="1">ID</th>
                                    <th data-priority="2">Place</th>
                                    <th data-priority="2">CUST ID</th>
                                    <th data-priority="2">Customer Number</th>
                                    {{-- <th data-priority="2">MEM Card</th> --}}
                                    <th data-priority="2">CUST Name</th>
                                    <th data-priority="2">CUST Email</th>
                                    <th data-priority="2">Gender</th>
                                    <th data-priority="2">Date</th>
                                    {{-- <th data-priority="2">Branch</th> --}}
                                    @php $user = auth()->user(); @endphp
                                    @if($user && !in_array($user->role, range(1, 10)))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $customer->branch?->name ?? 'N/A' }} </td>
                                    <td>{{ $customer->customer_id }}</td>
                                    <td>{{ $customer->number }}</td>
                                    {{-- <td>{{ $customer->membership_card ?? 'N/A' }}</td> --}}
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->gender }}</td>
                                    <td>{{ explode(' ', $customer->date)[0] }}</td>
                                    {{-- <td class="scroll-x">{{ $customer->place }}</td> --}}
                                    @if($user && !in_array($user->role, range(1, 10)))
                                    <td>
                                        <a href="#edit{{ $customer->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        {{-- <a href="#view{{$customer->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-view'></i> View</a> --}}
                                        <a href="{{ route('customer.bill-details', $customer->id) }}" class="btn btn-info btn-sm btn-flat" target="_blank">
                                            <i class="fa fa-file-text"></i>View
                                        </a>
                                        <a href="#delete{{ $customer->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->
                </div><!-- table-rep-plugin -->
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-12 -->
</div><!-- row -->

@foreach($customers as $customer)
@include('includes.edit_delete_customer')

<!-- Custom Invoice Modal -->
{{-- <div class="modal fade" id="customInvoice{{ $customer->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Custom Invoice Options - {{ $customer->name }}</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.custom-invoice', $customer->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="include_logo" value="1" checked> Include Company Logo
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="include_payment_info" value="1" checked> Include Payment Information
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="custom_message">Custom Message (Optional)</label>
                        <textarea class="form-control" name="custom_message" rows="3" placeholder="Enter a custom message for the invoice...">{{ $customer->category ?: 'Thank you for your business!' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Action:</label>
                        <div class="btn-group" role="group">
                            <button type="submit" name="action" value="download" class="btn btn-primary">
                                <i class="fa fa-download"></i> Download PDF
                            </button>
                            <button type="submit" name="action" value="view" class="btn btn-info">
                                <i class="fa fa-eye"></i> View in Browser
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
            </div>
        </div>
    </div>
</div> --}}
@endforeach

@include('includes.add_customer')
@endsection

@section('script')
<script>
    $(function() {
        $('.table-responsive').responsiveTable({
            addDisplayAllBtn: 'btn btn-secondary'
        });

        // Auto-submit form when period filter changes
        $('#periodFilter').on('change', function() {
            $(this).closest('form')[0].requestSubmit();
        });

        // Add loading indicator on submit and normalize dates to mm/dd/yyyy for backend
        function formatToMMDDYYYY(isoDate) {
            if (!isoDate) return '';
            var d = new Date(isoDate);
            if (isNaN(d.getTime())) return isoDate; // fallback
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            var dd = String(d.getDate()).padStart(2, '0');
            var yyyy = d.getFullYear();
            return mm + '/' + dd + '/' + yyyy;
        }

        $('#customerFilters').on('submit', function() {
            var period = $('#periodFilter').val();
            if (period === 'custom') {
                var s = $('#startDate').val();
                var e = $('#endDate').val();
                if (s) { $('#startDate').val(formatToMMDDYYYY(s)); }
                if (e) { $('#endDate').val(formatToMMDDYYYY(e)); }
            } else {
                // ensure dates are not sent when not custom
                $('#startDate').prop('disabled', true);
                $('#endDate').prop('disabled', true);
            }
            $('#applyFilter').html('<i class="fa fa-spinner fa-spin"></i> Filtering...').prop('disabled', true);
            $('#filterSpinner').show();
        });

        // Toggle custom date range visibility
        function toggleCustomRange() {
            var period = $('#periodFilter').val();
            if (period === 'custom') {
                $('#customDateRange').show();
            } else {
                $('#customDateRange').hide();
                // clear dates when not custom to avoid accidental filtering
                $('#startDate').val('');
                $('#endDate').val('');
            }
        }
        toggleCustomRange();
        $('#periodFilter').on('change', toggleCustomRange);
    });
</script>
@endsection

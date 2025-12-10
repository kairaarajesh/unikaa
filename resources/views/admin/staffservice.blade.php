@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Staff Service</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Staff Service</a></li>
    </ol>
</div>
@endsection

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
                      <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="GET" class="form-inline mb-3">
                                            <label for="range" class="mr-2">Filter:</label>
                                            <select name="range" id="range" class="form-control mr-2">
                                                <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                                                <option value="today" {{ ($filter ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                                                <option value="week" {{ ($filter ?? '') === 'week' ? 'selected' : '' }}>This Week</option>
                                                <option value="month" {{ ($filter ?? '') === 'month' ? 'selected' : '' }}>This Month</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                        </form>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">                                                    <thead>
                                                    <tr>
                                                        <th data-priority="1"> ID</th>
                                                        <th data-priority="2">Date</th>
                                                        <th data-priority="2">Employee ID </th>
                                                        <th data-priority="2">Employee Name</th>
                                                        <th data-priority="2">Customer Name</th>
                                                        <th data-priority="2">Service Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $Invoice as $invoice)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ optional($invoice->date)->format('Y-m-d') ?? 'N/A' }}</td>
                                                            <td>{{ $invoice->employee_details ?? 'N/A' }}</td>
                                                            <td>{{ $invoice->employee->employee_name ?? 'N/A' }}</td>
                                                            <td>{{ $invoice->customer->name ?? $invoice->customer->customer_name ?? $invoice->customer_id ?? 'N/A' }}</td>
                                                            <td>
    @forelse($invoice->service_items ?? [] as $item)
        <div style="margin-bottom: 6px;">
            <strong>Service:</strong> {{ $item['service_name'] ?? 'N/A' }} <br>
            <strong>Total Amount:</strong> {{ $item['total_amount'] ?? '0.00' }}
        </div>
        @if(!$loop->last) <hr> @endif
    @empty
        N/A
    @endforelse
</td>

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
                                </div>
                            </div>
                        </div>

@endsection


@section('script')
<!-- Responsive-table-->

@endsection

@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Booking</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Booking</a></li>
        </ol>
    </div>
@endsection
@section('button')
    {{-- <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Management</a> --}}
    <a href="{{ route('booking.calendar') }}" class="btn btn-success btn-sm btn-flat">
        <i class="fa fa-calendar-alt mr-2"></i>Calendar View
    </a>
@endsection

@section('content')
@include('includes.flash')

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
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th data-priority="2">ID</th>
                                        <th data-priority="3">Name</th>
                                        <th data-priority="4">Email</th>
                                        <th data-priority="5">Phone</th>
                                         <th data-priority="7">Gender</th>
                                        <th data-priority="5">Service Name</th>
                                        <th data-priority="5">Service Price</th>
                                        <th data-priority="5">serviceCategory</th>
                                        <th data-priority="6">Branch</th>
                                        <th data-priority="6">Place</th>
                                        <th data-priority="9">date</th>
                                        <th data-priority="9">time</th>
                                        <th>Status</th>
                                        <th data-priority="9">Stylist</th>
                                    </tr>
                                </thead>
                             <tbody>
                                    @forelse ($bookings as $index => $booking)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $booking['customerName'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['email'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['phone'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['gender'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['serviceName'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['servicePrice'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['serviceCategory'] ?? 'N/A' }}</td>
                                            <td>{{ $booking['location'] ?? 'N/A' }}</td>
                                             <td>{{ $booking['branchLocation'] ?? 'N/A' }}</td>
                                            <td>
                                                @if(!empty($booking['date']))
                                                    {{ \Carbon\Carbon::parse($booking['date'])->format('Y M d') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $booking['time'] ?? 'N/A' }}</td>
                                            <td>
                                                {{ $booking['status'] ?? 'Not Selected' }}
                                                @if(isset($booking['status']) && $booking['status'] === 'Service Completed' && !empty($booking['stylist']))
                                                    <br>
                                                    {{-- <small>Artist: {{ $booking['stylist'] }}</small> --}}
                                                @endif
                                            </td>
                                            <td>{{ $booking['stylist'] ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted">
                                                No bookings available or failed to connect to API.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@foreach ($bookings as $booking)
{{-- @include('includes.edit_delete_booking') --}}
@endforeach
{{-- @include('includes.edit_delete_booking') --}}
@endsection

@section('script')
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@endsection

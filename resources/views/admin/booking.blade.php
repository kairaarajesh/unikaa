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
                                        <th data-priority="6">Location</th>
                                        <th data-priority="7">Gender</th>
                                        <th data-priority="8">Service</th>
                                        <th data-priority="9">date</th>
                                        <th data-priority="9">time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $booking->name }} </td>
                                            <td> {{ $booking->email }} </td>
                                            <td> {{ $booking->phone }} </td>
                                            <td> {{ $booking->location }} </td>
                                            <td> {{ $booking->gender }} </td>
                                            <td> {{ $booking->service }} </td>
                                            <td> {{ $booking->date }} </td>
                                            <td> {{ $booking->time }} </td>
                                            <td>
                                                @if(empty($booking->status))
                                                    Not Selected
                                                @else
                                                    {{ $booking->status }}
                                                    @if($booking->status === 'Service Completed' && $booking->artist)
                                                        <br>
                                                        @php
                                                            // If artist is stored as ID, fetch the name
                                                            $artist = null;
                                                            if (is_numeric($booking->artist)) {
                                                                $artist = \App\Models\Employees::find($booking->artist);
                                                            }
                                                        @endphp
                                                        @if($artist)
                                                            <small>Artist: {{ $artist->employee_name }} (ID: {{ $artist->employee_id }})</small>
                                                        @else
                                                            <small>Artist: {{ $booking->artist }}</small>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            @php
                                            $user = auth()->user();
                                        @endphp

                                        @if($user && $user->role != 1  && $user->role != 2 && $user->role != 3 && $user->role != 4 && $user->role != 5 && $user->role != 6 && $user->role != 7 && $user->role != 8 && $user->role != 9 && $user->role != 10)

                                            <td>
                                                {{-- <a href="{{ url('admin/booking_show/' . $booking->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-print me-1"></i> Print
                                                </a> --}}
                                                <a href="#edit{{$booking->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                                <a href="#delete{{$booking->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
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

@foreach ($bookings as $booking)
@include('includes.edit_delete_booking')
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
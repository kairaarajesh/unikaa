@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Student</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Student</a></li>
    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Student</a>
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
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">                                                    <thead>
                                                    <tr>
                                                        <th data-priority="1"> ID</th>
                                                        <th data-priority="2">Student Name</th>
                                                        <th data-priority="2">Student ID</th>
                                                        <th data-priority="2">Email</th>
                                                        <th data-priority="2">Number</th>
                                                        <th data-priority="2">Gender</th>
                                                        <th data-priority="2">DOB</th>
                                                        <th data-priority="2">Joining Date</th>
                                                        <th data-priority="2">Street</th>
                                                        <th data-priority="2">City</th>
                                                        <th data-priority="2">State</th>
                                                        <th data-priority="2">Pin Code</th>
                                                        <th data-priority="2">Emergency Name</th>
                                                        <th data-priority="2">Emergency Number</th>
                                                        <th data-priority="2">Aadhar Card</th>
                                                        <th data-priority="2">Fees Status</th>
                                                        <th data-priority="2">Payment History</th>
                                                        <th data-priority="2">Course</th>
                                                       <th data-priority="2">Batch Timing</th>
                                                        <th data-priority="2">Staff Management</th>
                                                        <th data-priority="7">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $Students as $student)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$student->student_name}}</td>
                                                            <td>{{$student->student_id}}</td>
                                                            <td>{{$student->email}}</td>
                                                            <td>{{$student->number}}</td>
                                                            <td>{{$student->gender}}</td>
                                                            <td>{{$student->dob}}</td>
                                                            <td>{{$student->joining_date}}</td>
                                                            <td>{{$student->street}}</td>
                                                            <td>{{$student->city}}</td>
                                                            <td>{{$student->state}}</td>
                                                            <td>{{$student->pin_code}}</td>
                                                            <td>{{$student->emergency_name}}</td>
                                                            <td>{{$student->emergency_number}}</td>
                                                            <td>{{$student->aadhar_card}}</td>
                                                            <td>{{$student->fees_status}}</td>
                                                            <td>{{$student->payment_history}}</td>
                                                            {{-- <td>{{$student->course_id}}</td> --}}
                                                            <td> {{$student->courses?->name ?? 'N/A' }} </td>
                                                          <td>
                                                                {{ $student->courses?->start_time
                                                                    ? \Carbon\Carbon::createFromFormat('H:i:s', $student->courses->start_time)->format('h:i A')
                                                                    : 'N/A'
                                                                }}
                                                                -
                                                                {{ $student->courses?->end_time
                                                                    ? \Carbon\Carbon::createFromFormat('H:i:s', $student->courses->end_time)->format('h:i A')
                                                                    : 'N/A'
                                                                }}
                                                            </td>
                                                            <td> {{$student->staff_managements?->trainer ?? 'N/A' }} </td>
                                                            {{-- <td>{{$student->staff_management_id}}</td> --}}
                                                            <td>
                                                                <a href="#edit{{$student->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                                                <a href="#delete{{$student->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
                                                            </td>
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

@foreach( $Students as $student)
@include('includes.edit_delete_student')
@endforeach

@include('includes.add_student')

@endsection


@section('script')
<!-- Responsive-table-->

@endsection
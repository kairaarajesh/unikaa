@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Management</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Management</a></li>
        </ol>
    </div>
@endsection
@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Staff Management</a>
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
                                        <th data-priority="3">Trainer</th>
                                        <th data-priority="3">Trainer Email</th>
                                        <th data-priority="3">Trainer Number</th>
                                        <th data-priority="3">Branch</th>
                                        <th data-priority="3">Join Date</th>
                                        <th data-priority="3">Gender</th>
                                        <th data-priority="3">DOB</th>
                                        <th data-priority="3">Subject</th>
                                        <th data-priority="3">Salary</th>
                                        <th data-priority="4">Commission</th>
                                        <th data-priority="5">Street</th>
                                        <th data-priority="6">City</th>
                                        <th data-priority="4">State</th>
                                        <th data-priority="5">Pin Code</th>
                                        <th data-priority="6">Emergency Name</th>
                                       <th data-priority="6">Emergency Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Staff_managements as $staff_management)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $staff_management->trainer }} </td>
                                            <td> {{ $staff_management->trainer_email }} </td>
                                            <td> {{ $staff_management->trainer_number }} </td>
                                            <td> {{ $staff_management->branch }} </td>
                                            <td> {{ $staff_management->joining_date }} </td>
                                            <td> {{ $staff_management->gender }} </td>
                                            <td> {{ $staff_management->dob }} </td>
                                             <td> {{ $staff_management->subject }} </td>
                                            <td> {{ $staff_management->salary }} </td>
                                            <td> {{ $staff_management->commission }} </td>
                                            <td> {{ $staff_management->street }} </td>
                                            <td> {{ $staff_management->city	 }} </td>
                                            <td> {{ $staff_management->state }} </td>
                                            <td> {{ $staff_management->pin_code }} </td>
                                            <td> {{ $staff_management->emergency_name}} </td>
                                            <td> {{ $staff_management->emergency_number}} </td>

                                            <td>
                                                <a href="#edit{{$staff_management->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                                    <i class='fa fa-edit'></i> Edit
                                                </a>
                                                <a href="#delete{{ $staff_management->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
                                                    <i class='fa fa-trash'></i> Delete
                                                </a>
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

@foreach ($Staff_managements as $staff_management)
@include('includes.edit_delete_staff_management')
@endforeach
@include('includes.add_staff_management')
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
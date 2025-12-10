@extends('layouts.master')


@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Employee</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employee</a></li>
    </ol>
</div>
@endsection
@section('button')
@php
    $user = auth()->user();
    $userPermissions = checkUserPermissions($user);
    $permissions = $userPermissions['permissions'];
    $hasFullAccess = $userPermissions['hasFullAccess'];
@endphp
@if($hasFullAccess || hasPermission($permissions, 'employees', 'write'))
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Employee</a>
@endif
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
@php
    $user = auth()->user();
    $userPermissions = checkUserPermissions($user);
    $permissions = $userPermissions['permissions'];
    $hasFullAccess = $userPermissions['hasFullAccess'];
    $canViewSalary = $hasFullAccess || hasPermission($permissions, 'salary');
@endphp
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1">No</th>
                                                        <th data-priority="1">Emp Id</th>
                                                        <th data-priority="2">Emp Name</th>
                                                        <th data-priority="2">Emp Number</th>
                                                        <th data-priority="2">Emp Email</th>
                                                        <th data-priority="2">Branch</th>
                                                        <th data-priority="2">Join Date</th>
                                                        <th data-priority="2">Position</th>
                                                        @if($canViewSalary)
                                                        <th data-priority="2">Salary</th>
                                                        @endif
                                                        <th data-priority="2">Gender</th>
                                                        @if($hasFullAccess || hasPermission($permissions, 'employees'))
                                                        <th>Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $employees as $employee)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$employee->employee_id}}</td>
                                                            <td>{{$employee->employee_name}}</td>
                                                            <td>{{$employee->employee_number}}</td>
                                                            <td>{{$employee->employee_email}}</td>
                                                            {{-- <td>{{$employee->branch}}</td> --}}
                                                          <td>{{ $employee->branch?->name ?? 'N/A' }}</td>
                                                            <td>{{$employee->joining_date}}</td>
                                                           <td>{{$employee->position}}</td>
                                                            @if($canViewSalary)
                                                            <td>{{$employee->salary}}</td>
                                                            @endif
                                                            <td>{{$employee->gender}}</td>
                                                            {{-- <td>{{$employee->employee_status}}</td> --}}

                                                        @if($hasFullAccess || hasPermission($permissions, 'employees'))

                                                            <td>
                                                                {{-- <a href="{{ url('admin/employee_show/' . $employee->id) }}" class="btn btn-sm btn-warning">
                                                                    <i class="fa fa-print me-1"></i> Print
                                                                </a> --}}
                                                                <a href="#view{{$employee->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-view'></i> View</a>
                                                                @if($hasFullAccess || hasPermission($permissions, 'employees', 'write'))
                                                                <a href="#edit{{$employee->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                                                <a href="#delete{{$employee->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
                                                                @endif
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
@foreach( $employees as $employee)
@include('includes.edit_delete_employee')
@endforeach
@include('includes.add_employee')
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

@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Admin</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
    </ol>
</div>
@endsection

@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Subadmin</a>
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
                                                <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                        <tr>
                            {{-- <th data-priority="1">ID</th> --}}
                            <th data-priority="2">Branch Name</th>
                            <th data-priority="2">Branch Place</th>
                            <th data-priority="2">Email</th>
                            <th data-priority="2">Permissions</th>
                            <th data-priority="2">Create Date</th>
                            <th data-priority="7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                           <td class="text-center">
                                {{-- Show user name only if it exists --}}
                                @if(!empty($user->name))
                                    <span class="btn btn-info btn-sm m-1">
                                        {{ $user->name }}
                                    </span>
                                @endif

                                {{-- Show branch name only if it exists --}}
                                @if(!empty($user->branch?->name))
                                    <span class="btn btn-success btn-sm m-1">
                                        {{ $user->branch->name }}
                                    </span>
                                @endif

                            </td>
                           <td>
                                <div class="overflow-auto" style="max-height: 80px; white-space: normal;">
                                    {{ $user->place }}
                                </div>
                            </td>
                            <td>{{$user->email}}</td>
                           <td>
                                <div class="overflow-auto" style="max-height: 100px; white-space: normal;">
                                    @if($user->permissions)
                                        @foreach(json_decode($user->permissions, true) as $permission => $value)
                                            @if($value)
                                                <span class="badge badge-primary d-inline-block mb-1">{{ ucfirst($permission) }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>{{$user->created_at}}</td>
                            <td>
                                @if(empty($user->name))
                                    <a href="#edit{{ $user->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                        <i class='fa fa-edit'></i> Edit
                                    </a>
                                    <a href="#delete{{ $user->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
                                        <i class='fa fa-trash'></i> Delete
                                    </a>
                                @else
                                    <span class="text-muted">Not Available</span>
                                @endif
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

@foreach($users as $user)
    @if(empty($user->name))
        @include('includes.edit_delete_user')
    @endif
@endforeach
@include('includes.add_user')
@endsection

@section('script')
<!-- Responsive-table-->
@endsection

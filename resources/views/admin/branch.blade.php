@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Branch</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Branch</a></li>
        </ol>
    </div>
@endsection
@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Branch</a>
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
                                        <th data-priority="3">Branch Name</th>
                                        <!--<th data-priority="4">Place</th>-->
                                        <th data-priority="7">Address</th>
                                        <th data-priority="5">Number</th>
                                        <th data-priority="6">Email</th>
                                        <th data-priority="8">Gst</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branchs as $branch)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $branch->name }} </td>
                                            <!--<td> {{ $branch->place }} </td>-->
                                            <td> {{ $branch->address }} </td>
                                            <td> {{ $branch->number }} </td>
                                            <td> {{ $branch->email }} </td>
                                            <td> {{ $branch->gst_no }} </td>
                                            <td>
                                                <a href="#edit{{$branch->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                                    <i class='fa fa-edit'></i> Edit
                                                </a>
                                                <a href="#delete{{ $branch->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
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

@foreach ($branchs as $branch)
@include('includes.edit_delete_branch')
@endforeach
@include('includes.add_branch')
@endsection


@section('script')
    {{-- <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });

            // Debug modal functionality
            console.log('Management page loaded');
            console.log('Total products:', {{ $branchs->count() }});

            // Test modal triggers
            $('.edit').on('click', function() {
                var target = $(this).attr('href');
                console.log('Edit button clicked, target:', target);
                console.log('Modal exists:', $(target).length > 0);
            });

            $('.delete').on('click', function() {
                var target = $(this).attr('href');
                console.log('Delete button clicked, target:', target);
                console.log('Modal exists:', $(target).length > 0);
            });
        });
    </script> --}}
@endsection
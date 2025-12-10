@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Product</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Product</a></li>
        </ol>
    </div>
@endsection
@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Product</a>
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
                                        <th data-priority="3">Product Name</th>
                                        <th data-priority="4">Product Code</th>
                                        <th data-priority="7">Branch</th>
                                        <th data-priority="5">Count</th>
                                        <th data-priority="6">price</th>
                                        <th data-priority="8">Brand</th>
                                        <th data-priority="9">date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($managements as $management)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $management->product_name }} </td>
                                            <td> {{ $management->product_code }} </td>
                                            <td> {{ $management->branch?->name ?? 'N/A' }} </td>
                                            <td  style="background-color: {{ $management->Quantity < 5 ? 'red' : 'inherit' }}">
                                              {{ $management->Quantity }}
                                            </td>
                                            <td> {{ $management->price }} </td>
                                            <td> {{ $management->categories?->name ?? 'N/A' }} </td>
                                            <td> {{ $management->date }} </td>
                                            <td>
                                                <a href="#edit{{$management->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                                    <i class='fa fa-edit'></i> Edit
                                                </a>
                                                <a href="#delete{{ $management->id }}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
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

@foreach ($managements as $management)
@include('includes.edit_delete_management')
@endforeach
@include('includes.add_management')
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

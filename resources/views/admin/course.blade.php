@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Brand</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Brand</a></li>
    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Course</a>

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
                                                        <th data-priority="1">ID</th>
                                                        <th data-priority="2">Name</th>
                                                        <th data-priority="2">Batch</th>
                                                          <th data-priority="2">Type</th>
                                                        <th data-priority="2">Course</th>
                                                        <th data-priority="2">Duration</th>
                                                        <th data-priority="2">Fees</th>
                                                        <th data-priority="2">Max Student</th>
                                                        <th data-priority="2">Staff Management</th>
                                                        <th data-priority="2">Start Time</th>
                                                        <th data-priority="2">End time</th>
                                                        <th data-priority="7">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $Courses as $course)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$course->name}}</td>
                                                            <td>{{$course->batch}}</td>
                                                            {{-- <td>{{$course->type}}</td> --}}
                                                            <td>
                                                                @php
                                                                    $courses = json_decode($course->type, true);
                                                                @endphp
                                                                {{ is_array($courses) ? implode(', ', $courses) : $course->type }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $courses = json_decode($course->course, true);
                                                                @endphp
                                                                {{ is_array($courses) ? implode(', ', $courses) : $course->course }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $courses = json_decode($course->duration, true);
                                                                @endphp
                                                                {{ is_array($courses) ? implode(', ', $courses) : $course->duration }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $courses = json_decode($course->fees, true);
                                                                @endphp
                                                                {{ is_array($courses) ? implode(', ', $courses) : $course->fees }}
                                                            </td>
                                                            {{-- <td>{{$course->duration}}</td> --}}
                                                            {{-- <td>{{$course->fees}}</td> --}}
                                                            <td>{{$course->max_student}}</td>
                                                            <td> {{$course->staff_managements?->trainer ?? 'N/A' }} </td>
                                                            <td>{{$course->start_time}}</td>
                                                            {{-- <td>{{$course->end_time}}</td> --}}
                                                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $course->end_time)->format('h:i A') }}</td>

                                                            <td>
                                                                <a href="#edit{{$course->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                                                <a href="#delete{{$course->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
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

@foreach( $Courses as $course)
@include('includes.edit_delete_course')
@endforeach
@include('includes.add_course')
@endsection
@section('script')

@endsection
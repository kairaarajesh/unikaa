@extends('layouts.master')

@section('css')
<style>
    select[multiple] {
        min-height: 120px;
    }

    select[multiple] option {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }

    select[multiple] option:checked {
        background-color: #007bff;
        color: white;
    }

    .form-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .table-container {
        margin-bottom: 30px;
    }

    .table-title {
        background: #007bff;
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .table-title:hover {
        background: #0056b3;
    }

    .table-title.active {
        background: #28a745;
    }

    .table-title i {
        float: right;
        transition: transform 0.3s;
    }

    .table-title.active i {
        transform: rotate(180deg);
    }

    .table-content {
        display: none;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }

    .table-content.show {
        display: block;
    }

    .filter-row {
        margin-bottom: 15px;
    }

    .filter-input {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
        font-size: 14px;
    }

    .filter-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .btn-filter {
        background: #6c757d;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        color: white;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-filter:hover {
        background: #5a6268;
        color: white;
    }

    .btn-reset {
        background: #dc3545;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        color: white;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-reset:hover {
        background: #c82333;
        color: white;
    }

    /* Make long cell content scroll horizontally instead of expanding table */
    .scroll-cell {
        max-width: 320px;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Service Management</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Service</a></li>
    </ol>
</div>
@endsection

@section('button')
<div class="col-sm-17">
    <div class="text-right m-2">
        <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Service</a>
        <a href="#addnewcombo" data-toggle="modal" class="btn btn-success btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Combo</a>
    </div>
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

@php
    // Ensure variables are arrays to prevent count() errors
    $ServiceManagements = $ServiceManagements ?? [];
    $ServiceCombo = $ServiceCombo ?? [];
@endphp

<!-- Filter Section -->
<div class="filter-section">
    <h5 class="mb-3"><i class="mdi mdi-filter"></i> Filter Services</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="filter-row">
                <label for="filter_id">ID:</label>
                <input type="text" id="filter_id" class="form-control filter-input" placeholder="Filter by ID">
            </div>
        </div>
        <div class="col-md-3">
            <div class="filter-row">
                <label for="filter_name">Service Name:</label>
                <input type="text" id="filter_name" class="form-control filter-input" placeholder="Filter by name">
            </div>
        </div>
        <div class="col-md-2">
            <div class="filter-row">
                <label for="filter_amount">Amount:</label>
                <input type="number" id="filter_amount" class="form-control filter-input" placeholder="Min amount">
            </div>
        </div>
        <div class="col-md-2">
            <div class="filter-row">
                <label for="filter_quantity">Quantity:</label>
                <input type="number" id="filter_quantity" class="form-control filter-input" placeholder="Min quantity">
            </div>
        </div>
        <div class="col-md-2">
            <div class="filter-row">
                <label>&nbsp;</label><br>
                <button type="button" id="apply_filter" class="btn btn-filter btn-sm">
                    <i class="mdi mdi-filter"></i> Filter
                </button>
                <button type="button" id="reset_filter" class="btn btn-reset btn-sm">
                    <i class="mdi mdi-refresh"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Service Management Table -->
<div class="table-container">
    <h5 class="table-title" onclick="toggleTable('service-table')">
        <i class="mdi mdi-chevron-down"></i>
        Service Management <span class="badge badge-light">{{ $ServiceManagements ? count($ServiceManagements) : 0 }}</span>
    </h5>
    <div class="table-content show" id="service-table">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th data-priority="1">ID</th>
                    <th data-priority="2">Service Name</th>
                    <th data-priority="2">Amount</th>
                    <th data-priority="2">Gender</th>
                    {{-- <th data-priority="2">Tax</th> --}}
                    <th data-priority="7">Actions</th>
                </tr>
                </thead>
                <tbody>
                    @if($ServiceManagements && count($ServiceManagements) > 0)
                        @foreach( $ServiceManagements as $serviceManagement)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><div class="scroll-cell">{{ $serviceManagement->service_name }}</div></td>
                            <td>{{$serviceManagement->amount}}</td>
                            <td>{{$serviceManagement->gender}}</td>
                            {{-- <td>{{$serviceManagement->tax}}</td> --}}
                            <td>
                                <a href="#edit{{$serviceManagement->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Edit</a>
                                <a href="#delete{{$serviceManagement->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">No services found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Service Combos Table -->
<div class="table-container">
    <h5 class="table-title" onclick="toggleTable('combo-table')">
        <i class="mdi mdi-chevron-down"></i>
        Service Combos <span class="badge badge-light">{{ $ServiceCombo ? count($ServiceCombo) : 0 }}</span>
    </h5>
    <div class="table-content" id="combo-table">
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Combo</th>
                    <th>Amount</th>
                    <th>Offer Price</th>
                    <th>Gender</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @if($ServiceCombo && count($ServiceCombo) > 0)
                        @foreach( $ServiceCombo as $serviceCombo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="scroll-cell">
                                @php
                                    $services = is_string($serviceCombo->service_combo) ? json_decode($serviceCombo->service_combo, true) : [];
                                @endphp

                                @if(is_array($services) && count($services) > 0)
                                    @foreach($services as $serviceId)
                                        @php
                                            $service = \App\Models\ServiceManagement::find($serviceId);
                                        @endphp
                                        @if($service)
                                            <span class="badge badge-info mr-1">{{ $service->service_name }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    {{ $serviceCombo->service_combo ?? 'N/A' }}
                                @endif
                                </div>
                            </td>
                            <td>{{ $serviceCombo->amount }}</td>
                            <td>{{ $serviceCombo->quantity }}</td>
                            <td>{{ $serviceCombo->gender }}</td>
                            <td>{{ $serviceCombo->total_amount }}</td>
                            <td>
                                <a href="#editcombo{{$serviceCombo->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class="fa fa-eye"></i> View Details</a>
                                <a href="#deletecombo{{$serviceCombo->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">No service combos found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($ServiceManagements && count($ServiceManagements) > 0)
    @foreach($ServiceManagements as $serviceManagement)
        @include('includes.edit_delete_service_management')
    @endforeach
@endif

@if($ServiceCombo && count($ServiceCombo) > 0)
    @foreach($ServiceCombo as $serviceCombo)
        @include('includes.edit_delete_service_combo', ['ServiceCombo' => $serviceCombo, 'ServiceManagements' => $ServiceManagements])
    @endforeach
@endif

@include('includes.add_service_management')
@include('includes.add_service_combo')

@endsection

@section('script')
<script>
// Table toggle functionality
function toggleTable(tableId) {
    const tableContent = document.getElementById(tableId);
    const tableTitle = tableContent.previousElementSibling;

    if (tableContent.classList.contains('show')) {
        tableContent.classList.remove('show');
        tableTitle.classList.remove('active');
    } else {
        tableContent.classList.add('show');
        tableTitle.classList.add('active');
    }
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterId = document.getElementById('filter_id');
    const filterName = document.getElementById('filter_name');
    const filterAmount = document.getElementById('filter_amount');
    const filterQuantity = document.getElementById('filter_quantity');
    const applyFilterBtn = document.getElementById('apply_filter');
    const resetFilterBtn = document.getElementById('reset_filter');

        // Apply filter
    applyFilterBtn.addEventListener('click', function() {
        const idValue = filterId.value.toLowerCase();
        const nameValue = filterName.value.toLowerCase();
        const amountValue = filterAmount.value;
        const quantityValue = filterQuantity.value;

        const rows = document.querySelectorAll('#service-table tbody tr');

        if (rows.length === 0) return;

        rows.forEach(row => {

            if (row.cells[0].textContent === 'No services found') return;

            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            const amount = parseFloat(row.cells[2].textContent);
            const quantity = parseFloat(row.cells[3].textContent);

            let showRow = true;

            if (idValue && !id.includes(idValue)) showRow = false;
            if (nameValue && !name.includes(nameValue)) showRow = false;
            if (amountValue && amount < parseFloat(amountValue)) showRow = false;
            if (quantityValue && quantity < parseFloat(quantityValue)) showRow = false;

            row.style.display = showRow ? '' : 'none';
        });

        // Update visible row count
        updateVisibleCount();
    });

        // Reset filter
    resetFilterBtn.addEventListener('click', function() {
        filterId.value = '';
        filterName.value = '';
        filterAmount.value = '';
        filterQuantity.value = '';

        const rows = document.querySelectorAll('#service-table tbody tr');
        rows.forEach(row => {
            // Skip the "No services found" row
            if (row.cells[0].textContent !== 'No services found') {
                row.style.display = '';
            }
        });

        updateVisibleCount();
    });

    // Update visible count
    function updateVisibleCount() {
        const visibleRows = document.querySelectorAll('#service-table tbody tr:not([style*="display: none"])');
        const countBadge = document.querySelector('#service-table .table-title .badge');
        if (countBadge) {
            // Count only actual data rows, not the "No services found" row
            let actualCount = 0;
            visibleRows.forEach(row => {
                if (row.cells[0].textContent !== 'No services found') {
                    actualCount++;
                }
            });
            countBadge.textContent = actualCount;
        }
    }

    // Enter key support for filters
    [filterId, filterName, filterAmount, filterQuantity].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilterBtn.click();
            }
        });
    });
});

// Initialize DataTable
// $(document).ready(function() {
//     $('#datatable-buttons').DataTable({
//         lengthChange: false,
//         buttons: ['copy', 'excel', 'pdf', 'colvis']
//     }).buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
// });
</script>
@endsection

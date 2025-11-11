@extends('layouts.master')

@section('css')
<style>
    .bill-details-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }

    .customer-info-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .customer-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .customer-avatar {
        width: 60px;
        height: 60px;
        background: #4CAF50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
    }

    .customer-details h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .customer-details p {
        margin: 5px 0;
        font-size: 16px;
        opacity: 0.9;
    }

    .member-status {
        background: #dc3545;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 10px;
        display: inline-block;
    }

    .stats-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bill-tabs {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .tab-header {
        display: flex;
        border-bottom: 1px solid #e9ecef;
    }

    .tab-button {
        flex: 1;
        padding: 15px 20px;
        background: #f8f9fa;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background: #007bff;
        color: white;
    }

    .tab-button:hover {
        background: #e9ecef;
    }

    .tab-button.active:hover {
        background: #0056b3;
    }

    .bill-breakdown {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .bill-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 2px dashed #e9ecef;
        font-size: 16px;
    }

    .bill-item:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 18px;
        color: #2c3e50;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .bill-label {
        color: #6c757d;
    }

    .bill-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .close-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #dc3545;
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        transition: all 0.3s ease;
    }

    .close-button:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .history-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .history-link:hover {
        text-decoration: underline;
    }

    .arrow-button {
        background: #007bff;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .arrow-button:hover {
        background: #0056b3;
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Customer View</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer View</a></li>
        <li class="breadcrumb-item active">Customer View</li>
    </ol>
</div>
@endsection

@section('button')
    <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm btn-flat">
        <i class="fa fa-arrow-left mr-2"></i>Back to Customers
    </a>
@endsection

@section('content')
<div class="bill-details-container">
    <!-- Customer Information Card -->
    <div class="customer-info-card">
        <div class="customer-header">
            <div class="customer-avatar">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div class="customer-details">
                <h3>{{ $customer->name }}</h3>
                <p><strong>Phone Number:</strong> {{ $customer->number }}</p>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><stong>Place:</stong>{{ $customer->branch?->name ?? 'N/A' }}</p>
                <span class="member-status">
                    {{ $customer->membership_card ? $customer->membership_card : 'Non Member' }}
                </span>
             </div>
            <div style="margin-left: auto;">
                <button class="arrow-button">>></button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $visitCount ?? 1 }}</div>
                <div class="stat-label">No of visit</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalBills ?? 1 }}</div>
                <div class="stat-label">Total no bill</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">₹{{ number_format($avgBillTotal ?? 0, 2) }}</div>
                <div class="stat-label">Avg bill total</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $lastVisit ?? date('d/m/Y') }}</div>
                <div class="stat-label">Last visit</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">
                    <a href="#" class="history-link">History: Click Here</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bill Details Tabs -->
    <div class="bill-tabs">
        <div class="tab-header">
            <button class="tab-button">Discount Details</button>
            <button class="tab-button active">Bill Details</button>
        </div>

        <div class="bill-breakdown">
            <div class="bill-item">
                <span class="bill-label">Net Sales (before Discount):</span>
                <span class="bill-value">₹{{ number_format($invoice->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Mem - Disc:</span>
                <span class="bill-value">+ ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Discount:</span>
                <span class="bill-value">+ ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Discount Total:</span>
                <span class="bill-value">- ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Net Sales (- Discount):</span>
                <span class="bill-value">₹{{ number_format($invoice->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">GST (Tax):</span>
                <span class="bill-value">+ ₹{{ number_format($invoice->tax ?? 0, 2) }}</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Gross Sales:</span>
                <span class="bill-value">₹{{ number_format($invoice->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Wallet Advance:</span>
                <span class="bill-value">+ ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Advance Amount:</span>
                <span class="bill-value">+ ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Advance Total:</span>
                <span class="bill-value">- ₹0.00</span>
            </div>
            <div class="bill-item">
                <span class="bill-label">Total:</span>
                <span class="bill-value">₹{{ number_format($invoice->total_amount ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Close Button -->
    <button class="close-button" onclick="window.close()">
        Close X
    </button>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Tab switching functionality
        $('.tab-button').click(function() {
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
@endsection

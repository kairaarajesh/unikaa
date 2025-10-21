<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $purchase->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }

        .invoice-header {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            float: left;
            width: 60%;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-details {
            color: #666;
            font-size: 11px;
        }

        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .invoice-date {
            font-size: 11px;
            color: #666;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .customer-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-group {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 12px;
            color: #333;
            margin-top: 2px;
        }

        .items-section {
            margin-bottom: 30px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #2c3e50;
            border: 1px solid #ddd;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .totals-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .total-label {
            display: inline-block;
            width: 120px;
            text-align: right;
            font-weight: bold;
            color: #555;
        }

        .total-value {
            display: inline-block;
            width: 100px;
            text-align: right;
            color: #333;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-top: 2px solid #2c3e50;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .payment-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .payment-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .payment-details {
            font-size: 11px;
            line-height: 1.6;
        }

        @media print {
            body {
                font-size: 10px;
            }
            .invoice-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header clearfix">
            <div class="company-info">
                <div class="company-logo">UNIKAA</div>
                <div class="company-details">
                    <!-- 123 Business Street<br> -->
                    34, T M Nagar 1st Cross St, opp. Saravana Store Road, Mattuthavani, Sambakulam, T M Nagar, Madurai, Kodikulam, Tamil Nadu 625107<br>
                    Phone: 7092770399<br>
                    Email: glow@unikaabeauty.com<br>
                    Website: https://unikaabeauty.com/
                </div>
            </div>
            {{-- <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">Invoice #: {{ $purchase->id }}</div>
                <div class="invoice-date">Date: {{ $purchase->date ? \Carbon\Carbon::parse($customer->date)->format('M d, Y') : \Carbon\Carbon::now()->format('M d, Y') }}</div>
            </div> --}}
        </div>

        <!-- Customer Information -->
        <div class="customer-section">
            <div class="section-title">BILL TO</div>
            <div class="customer-info">
                <div class="info-group">
                    <div class="info-label">Customer Name</div>
                    <div class="info-value">{{ $purchase->customer_name }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Product Name</div>
                    <div class="info-value">{{($purchase->management)->product_name ?? 'N/A' }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Product Code</div>
                    <div class="info-value">{{ $purchase->product_code }}</div>
                </div>
            </div>
        </div>

        <!-- Items/Services Section -->
        <div class="items-section">
            <div class="section-title">SERVICE DETAILS</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Customer Name</th>
                        <th>Quantity</th>
                        <th>price</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $purchase->customer_name }}</td>
                        <td>{{ $purchase->customer_number }}</td>
                        <td>{{ $purchase->Quantity }}</td>
                        <td>{{ $purchase->price }}</td>
                        <td>{{ $purchase->total_amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="total-row">
                <span class="total-label">Subtotal:</span>
                <span class="total-value">₹{{ number_format($purchase->price, 2) }}</span>
            </div>
             <div class="total-row">
                <span class="total-label">Discount ({{ $purchase->discount }}%):</span>
                <span class="total-value">₹{{ number_format(($purchase->price * $purchase->discount / 100), 2) }}</span>
            </div>
             <div class="total-row">
                <span class="total-label">Tax ({{ $purchase->tax }}%):</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label">Total Calculation:</span>
                <span class="total-value">₹{{ number_format($purchase->total_calculation) }}</span>
            </div>
        </div>

        <!-- Payment Information -->
        {{-- <div class="payment-info">
            <div class="payment-title">Payment Information</div>
            <div class="payment-details">
                <strong>Payment Terms:</strong> Due upon receipt<br>
                <strong>Payment Methods:</strong> Cash, Check, Bank Transfer, Credit Card<br>
                <strong>Bank Details:</strong> Account: 1234567890, Bank: Sample Bank, Routing: 123456789<br>
                <strong>Notes:</strong> {{ $customer->category ?: 'Thank you for your business!' }}
            </div>
        </div> --}}

        <!-- Footer -->
        <div class="footer-section">
            <p>Thank you for choosing UNIKAA CRM!</p>
            <p>This is a computer generated invoice. No signature required.</p>
            <p>For any questions, please contact us at unikaabeauty.com or call 7092770399</p>
        </div>
    </div>
</body>
</html>
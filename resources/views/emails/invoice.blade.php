<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $customer->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .invoice-details {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #2c3e50;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            text-align: right;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIKAA CRM</h1>
        <p>Your Invoice is Ready</p>
    </div>

    <div class="content">
        <h2>Dear {{ $customer->name }},</h2>

        <p>Thank you for your business! Your invoice has been generated and is attached to this email.</p>

        <div class="invoice-details">
            <h3>Invoice Summary</h3>
            <p><strong>Invoice #:</strong> {{ $customer->id }}</p>
            <p><strong>Date:</strong> {{ $customer->date ? \Carbon\Carbon::parse($customer->date)->format('M d, Y') : \Carbon\Carbon::now()->format('M d, Y') }}</p>
            <p><strong>Service/Product:</strong> {{ $customer->category ?: 'General Service' }}</p>
            <p><strong>Amount:</strong> ${{ number_format($customer->amount, 2) }}</p>
            <p><strong>Discount:</strong> {{ $customer->discount }}%</p>
            <div class="total">
                <strong>Total Amount: ${{ number_format($customer->total_amount, 2) }}</strong>
            </div>
        </div>

        <p>Please find the complete invoice attached to this email. You can also download it by clicking the button below:</p>

        <p><strong>Payment Information:</strong></p>
        <ul>
            <li>Payment Terms: Due upon receipt</li>
            <li>Payment Methods: Cash, Check, Bank Transfer, Credit Card</li>
            <li>Bank Details: Account: 1234567890, Bank: Sample Bank, Routing: 123456789</li>
        </ul>

        <p>If you have any questions about this invoice, please don't hesitate to contact us:</p>
        <ul>
            <li>Email: info@unikaacrm.com</li>
            <li>Phone: (555) 123-4567</li>
            <li>Website: www.unikaacrm.com</li>
        </ul>

        <p>Thank you for choosing UNIKAA CRM!</p>

        <p>Best regards,<br>
        The UNIKAA CRM Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} UNIKAA CRM. All rights reserved.</p>
    </div>
</body>
</html>

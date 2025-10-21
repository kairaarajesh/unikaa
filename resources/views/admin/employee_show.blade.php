<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .section { margin-bottom: 15px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Invoice</h2>

    <div class="section"><span class="label">Name:</span> {{ $employee->name }}</div>
    <div class="section"><span class="label">Email:</span> {{ $employee->email }}</div>
    <div class="section"><span class="label">Number:</span> {{ $employee->number }}</div>
    <div class="section"><span class="label">Status:</span> {{ $employee->amount }}</div>
    <div class="section"><span class="label">Total Amount:</span> {{ $employee->total_amount }}</div>
    <div class="section"><span class="label">Place:</span> {{ $employee->place }}</div>
    <div class="section"><span class="label">Discount:</span> {{ $employee->discount }}</div>
    <div class="section"><span class="label">Category:</span> {{ $employee->category }}</div>
    <div class="section"><span class="label">Date:</span> {{ $employee->date }}</div>
</body>
</html>

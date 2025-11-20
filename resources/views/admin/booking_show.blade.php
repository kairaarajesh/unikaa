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
@php
    $rawImage = $booking->image ?? $booking->service_image ?? $booking->serviceImage ?? null;
    if ($rawImage) {
        $imageUrl = preg_match('/^https?:\/\//i', $rawImage) ? $rawImage : asset(ltrim($rawImage, '/'));
    } else {
        $imageUrl = asset('assets/images/Unikaa-Logo-14.png');
    }
@endphp
    <div class="section">
        <span class="label">Service Image:</span>
        <br>
        <img src="{{ $imageUrl }}" alt="Service image" style="max-width: 160px; height: auto; border: 1px solid #ccc; border-radius: 6px; margin-top: 6px;">
    </div>
    <div class="section"><span class="label">Name:</span> {{  $booking->name }}</div>
    <div class="section"><span class="label">Email:</span> {{ $booking->email }}</div>
    <div class="section"><span class="label">Phone:</span> {{ $booking->phone }}</div>
    {{-- <div class="section"><span class="label">Price:</span> {{ $booking->price }}</div> --}}
    <div class="section"><span class="label">Location:</span> {{ $booking->location }}</div>
    <div class="section"><span class="label">Gender:</span> {{ $booking->gender }}</div>
    <div class="section"><span class="label">Service:</span> {{ $booking->service }}</div>
    <div class="section"><span class="label">Status:</span> {{ $booking->status }}</div>
<div class="section">
    <span class="label">Artist:</span>
    {{ $booking->employee?->employee_name ?? 'N/A' }}
</div>
    <div class="section"><span class="label">Date:</span> {{ $booking->date }}</div>
    {{-- <div class="section"><span class="label">management Name:</span> {{ ($booking->management)->product_name }}</div> --}}
    <div class="section"><span class="label">Time:</span> {{ $booking->time }}</div>

</body>
</html>

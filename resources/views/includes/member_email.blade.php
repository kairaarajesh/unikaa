{{-- @extends('layouts.master')
@section('content') --}}
{{-- <html>
<head>
    <title>picknow.ecommerce@gmail.com</title>
</head>
    <h1>Give The product details</h1>
    <p><strong>Name:</strong> {{ $contactData['name'] }}</p> --}}
    {{-- <p><strong>Email:</strong> {{ $contactData['email'] }}</p> --}}
    {{-- <p><strong>Message:</strong></p>
    <p>{{ $contactData['message'] }}</p>
    <p><strong>status:</strong> {{ $contactData['status'] }}</p>
    <p><strong>total:</strong> {{ $contactData['total_amount'] }}</p>

</html> --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Membership Card</title>
</head>
<body style="background:#f8f8f8; padding:20px; font-family:Arial, sans-serif">

    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:10px;">

        <div style="text-align:center; margin-bottom:20px;">
            <img src="https://unikaabeauty.com/assets/Unikaa_logo-CKxLd7l2.png" width="120" alt="Unikaa Logo">
        </div>

        <h2 style="text-align:center;">Thank You for Joining!</h2>

        <p style="font-size:16px; text-align:center; margin-bottom:30px;">
            Hi <strong>{{ $name }}</strong>,
        </p>

        <table style="width:100%; border-collapse:collapse; font-size:16px;">
            <tr>
                <td><strong>Membership ID:</strong></td>
                <td style="text-align:right;">{{ $membership_card }}</td>
            </tr>
        </table>

        <hr style="margin:25px 0;">

        <p style="font-size:16px;">Your membership includes:</p>

        <ul style="font-size:16px; line-height:24px;">
            <li>Priority booking</li>
            <li>Exclusive service discounts</li>
            <li>Free birthday service</li>
            <li>Special monthly offers</li>
        </ul>

        <hr style="margin:25px 0;">

        <p style="font-size:16px;">
            Visit our website:
            <a href="https://unikaabeauty.com/" style="color:#d63384; text-decoration:none;">
                Unikaa Beauty
            </a>
        </p>

    </div>

</body>
</html>


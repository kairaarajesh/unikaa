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

  <body class="bg-red-100">
    <div class="container">
        <img src="https://unikaabeauty.com/assets/Unikaa_logo-CKxLd7l2.png" alt="user" class="rounded-circle">
        <div class="space-y-4 mb-6">
        <h1 class="text-4xl fw-800">Thanks ,{{ $contactData['name'] }}</h1>
        {{-- <p>The estimated delivery time for your order is 10:00 PM - 07:00 PM. Track your order on the Hip Corp website.</p> --}}
        {{-- <a class="btn btn-red-500 rounded-full px-6 w-full w-lg-48" href="https://app.bootstrapemail.com/templates">Track Your Order</a> --}}
      </div>
      <div class="card rounded-3xl px-4 py-8 p-lg-10 mb-6">
        <h3 class="text-center">Details</h3>
        <p class="text-center text-muted">KAIRAA</p>
        <table class="p-2 w-full">
          <tbody>
            <tr>
                <td>Service</td>
               <td>
                                                                @php
                                                                    $categories = json_decode($customer->category, true);
                                                                @endphp

                                                                @if(is_array($categories))
                                                                    {{ implode(', ', $categories) }}
                                                                @else
                                                                    {{ $customer->category }}
                                                                @endif
                                                            </td>
           </tr>
            <tr>
                <td>Number</td>
                <td class="text-right"> {{ $contactData['number'] }}</td>
            </tr>
            <tr>
              <td>Amount</td>
              <td class="text-right"> ₹{{ $contactData['amount'] }}</td>
            </tr>
            <tr>
              <td>Discount</td>
              <td class="text-right"> {{ $contactData['discount'] }}</td>
            </tr>
            <tr>
              <td class="fw-700 border-top">Total Amount</td>
              <td class="fw-700 text-right border-top">₹{{ $contactData['total_amount'] }}</td>
            </tr>
             <tr>
              <td>Branch</td>
              <td class="text-right"> {{ $contactData['branch'] }}</td>
            </tr>
             <tr>
              <td>Place</td>
              <td class="text-right"> {{ $contactData['place'] }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td class="text-right"> {{ $contactData['date'] }}</td>
            </tr>
          </tbody>
        </table>
        <hr class="my-6">
        <p>Can you please click Website <a href="https://unikaabeauty.com/">Unikaa Beauty</a>.</p>
      </div>
    </div>
</body>

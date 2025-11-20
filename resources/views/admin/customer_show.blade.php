<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $customer->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color:#222; background:#fff; }
        .receipt { width: 320px; margin: 0 auto; padding: 12px 14px; }
        .center { text-align: center; }
        .muted { color:#666; }
        .sm { font-size: 11px; }
        .xs { font-size: 10px; }
        .lg { font-size: 16px; font-weight: bold; }
        .line { border-top: 1px dashed #999; margin: 6px 0; }
        .pair { display:flex; justify-content: space-between; font-size: 12px; margin: 2px 0; }
        .pair b { font-weight: 600; }
        table { width:100%; border-collapse: collapse; font-size: 12px; }
        th, td { padding: 4px 0; }
        th { text-align:left; border-bottom: 1px dashed #999; }
        td { border-bottom: 1px dashed #eee; }
        /* Fixed column widths for better alignment */
        .col-desc { width: 60%; }
        .col-qty { width: 12%; text-align: right; }
        .col-amt { width: 28%; text-align: right; }
        .right { text-align:right; }
        .totals td { border-bottom:none; padding: 3px 0; }
        .grand { font-weight: bold; font-size: 14px; border-top: 1px dashed #999; padding-top: 4px; }
        .footer { margin-top: 8px; }
        @media print {
            body { width: 58mm; }
            .receipt { width: 100%; padding: 6px; }
        }
    </style>
</head>
<body>
    @php
        // Prefer invoice data when available
        $invoiceData = isset($invoice) ? $invoice : null;

        // Decode structured items (services/products) from invoice first, fallback to customer
        $serviceItems = [];
        $rawServiceItems = $invoiceData->service_items ?? $customer->service_items ?? [];
        if (!empty($rawServiceItems)) {
            if (is_string($rawServiceItems)) {
                $decoded = json_decode($rawServiceItems, true);
                if (is_array($decoded)) { $serviceItems = $decoded; }
            } elseif (is_array($rawServiceItems)) { $serviceItems = $rawServiceItems; }
        }

        $purchaseItems = [];
        $rawPurchaseItems = $invoiceData->purchase_items ?? $customer->purchase_items ?? [];
        if (!empty($rawPurchaseItems)) {
            if (is_string($rawPurchaseItems)) {
                $decodedPurch = json_decode($rawPurchaseItems, true);
                if (is_array($decodedPurch)) { $purchaseItems = $decodedPurch; }
            } elseif (is_array($rawPurchaseItems)) { $purchaseItems = $rawPurchaseItems; }
        }

        // Build numbers like the photo: Base Sale, Discount, GST, Net
        $baseSale = 0; $discountTotal = 0; $subtotal = 0; $taxTotal = 0;
        $rowBuilder = function($amt, $discPct, $taxPct) use (&$baseSale, &$discountTotal, &$subtotal, &$taxTotal) {
            $baseSale += $amt;
            $discountAmount = $amt * ($discPct/100);
            $discountTotal += $discountAmount;
            $net = $amt - $discountAmount;
            $subtotal += $net;
            $taxAmount = $net * ($taxPct/100);
            $taxTotal += $taxAmount;
            return [$net, $taxAmount, $net + $taxAmount];
        };

        foreach ($serviceItems as $it) {
            $amt = (float)($it['amount'] ?? 0);
            $discPct = (float)($it['discount'] ?? 0);
            $taxPct = (float)($it['tax'] ?? ($it['tax_pct'] ?? 0));
            $rowBuilder($amt, $discPct, $taxPct);
        }
        foreach ($purchaseItems as $it) {
            $amt = (float)($it['amount'] ?? 0);
            $qty = (float)($it['quantity'] ?? 1);
            $totalAmt = $amt * $qty;
            $discPct = (float)($it['discount'] ?? 0);
            $taxPct = (float)($it['tax'] ?? ($it['tax_pct'] ?? 0));
            $rowBuilder($totalAmt, $discPct, $taxPct);
        }

        if (empty($serviceItems) && empty($purchaseItems)) {
            $amt = (float)($invoiceData->amount ?? $customer->amount ?? 0);
            $discPct = 0.0;
            $taxPct = (float)($invoiceData->tax ?? $customer->tax ?? 0);
            $baseSale = $amt;
            $discountTotal = 0;
            $net = $amt;
            $subtotal = $net;
            $taxTotal = $net * ($taxPct/100);
        }

        $grandTotal = $subtotal + $taxTotal;
        $roundedTotal = round($grandTotal);
        $roundOff = $roundedTotal - $grandTotal;
    @endphp

    <div class="receipt">
        <div class="center">
            <img src="https://res.cloudinary.com/dspp2vqid/image/upload/w_200,h_64,c_limit,q_auto,f_auto/v1763190132/picknowcrm/ol37ycat5une3kzyttdo.png" alt="Logo" style="height:50px;margin-bottom:10px;">
            <div class="lg">UNIKAA</div>
            <div class="xs muted" style="text-transform: uppercase;">India's No.1 hair and beauty salon</div>
            <div class="xs muted" style="text-transform: uppercase;">BRANCH:  {{ $customer->branch?->name ?? 'N/A' }} | {{ $customer->place }}</div>
            <div class="xs">PHONE: 7092770399</div>
            <div class="xs">GSTIN: 33AAIFU3741Q1ZO</div>
        </div>
        <div class="line"></div>

        <div class="sm">
            <div class="pair"><span>Customer</span><b>:             {{ $customer->name }}</b></div>
            <div class="pair"><span>Mobile</span><b>:                {{ $customer->number }}</b></div>
            <div class="pair"><span>Bill No</span><b>:            #{{ $invoiceData->id ?? $customer->id }}</b></div>
            <div class="pair"><span>Date</span><b>:                     {{ ($invoiceData->date ?? $customer->date) ? \Carbon\Carbon::parse($invoiceData->date ?? $customer->date)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y H:i') }}</b></div>
            @php
                $payment = $invoiceData->payment_method ?? $customer->payment;
                if (is_string($payment) && is_array(json_decode($payment, true))) {
                    $payment = implode(', ', json_decode($payment, true));
                } elseif (is_array($payment)) {
                    $payment = implode(', ', $payment);
                }
            @endphp

            <div class="pair">
                <span>Payment Method</span><b>: {{ $payment }}</b>
            </div>
            {{-- <div class="pair"><span>Payment Method</span><b>:           {{ $invoiceData->payment_method ?? $customer->payment }}</b></div> --}}
        </div>

        <div class="line"></div>

        <table>
                <thead>
                    <tr>
                        <th class="col-desc">Services</th>
                        <th class="right col-qty">Qty</th>
                        <th class="right col-amt">Amount</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($serviceItems))
                        @foreach($serviceItems as $item)
                            @php
                                $amt = (float)($item['amount'] ?? 0);
                                $discPct = (float)($item['discount'] ?? 0);
                                $taxPct = (float)($item['tax'] ?? ($item['tax_pct'] ?? 0));
                            $net = $amt - ($amt * $discPct / 100);
                            $lineTax = $net * ($taxPct/100);
                            $lineTotal = $net + $lineTax;
                            @endphp
                            <tr>
                                <td class="col-desc">{{ $item['service_name'] ?? 'Service' }}</td>
                                <td class="right col-qty">1</td>
                                <td class="right col-amt">{{ number_format($amt, 2) }}</td>
                                {{-- <td class="right">{{ number_format($lineTotal, 2) }}</td> --}}
                            </tr>
                        @endforeach
                @endif
                @if(!empty($purchaseItems))
                    <tr><th colspan="3" style="border-bottom:none;padding-top:8px;">Product</th></tr>
                        @foreach($purchaseItems as $p)
                            @php
                                $amt = (float)($p['amount'] ?? 0);
                                $qty = (float)($p['quantity'] ?? 1);
                                $discPct = (float)($p['discount'] ?? 0);
                                $taxPct = (float)($p['tax'] ?? ($p['tax_pct'] ?? 0));
                            $net = $amt - ($amt * $discPct / 100);
                            $lineTax = $net * ($taxPct/100);
                            $lineTotal = $net + $lineTax;
                            @endphp
                            <tr>
                                <td class="col-desc">{{ $p['product_name'] ?? 'Product' }}</td>
                                <td class="right col-qty">{{ number_format($qty, 0) }}</td>
                                <td class="right col-amt">{{ number_format($amt * $qty, 2) }}</td>
                            </tr>
                        @endforeach
                @endif
                @if(empty($serviceItems) && empty($purchaseItems))
                        <tr>
                            <td class="col-desc">hair cut</td>
                            <td class="right col-qty">1</td>
                            <td class="right col-amt">{{ number_format($customer->amount ?? 200, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

        <div class="line"></div>

        <table class="totals">
            {{-- <tr>
                <td>Base Sale</td>
                <td class="right">{{ number_format($baseSale, 2) }}</td>
            </tr> --}}
            <tr>
                <td>Other Discount</td>
                <td class="right">-{{ number_format($discountTotal, 2) }}</td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <td class="right">{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>GST</td>
                <td class="right">{{ number_format($taxTotal, 2) }}</td>
            </tr>
            <tr class="grand">
                <td>Bill Amount</td>
                <td class="right">{{ number_format($grandTotal, 2) }}</td>
            </tr>
            <tr>
                <td>Round off</td>
                <td class="right">{{ number_format($roundOff, 2) }}</td>
            </tr>
            <tr class="grand">
                <td>Net Total</td>
                <td class="right">{{ number_format($roundedTotal, 2) }}</td>
            </tr>
        </table>

        <div class="footer center xs muted">
            Thank you for choosing UNIKAA<br>
            This is a computer generated invoice. No signature required.<br>
            Visit: unikaabeauty.com
        </div>
    </div>

    <script>
        // Auto-print functionality
        window.onload = function() {
            // Check if this window was opened for printing
            if (window.opener) {
                // Small delay to ensure all content is loaded
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        };

        // Handle print dialog events
        window.onafterprint = function() {
            // Close the window after printing (optional)
            // window.close();
        };
    </script>
</body>
</html>

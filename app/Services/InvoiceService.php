<?php

namespace App\Services;

use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate PDF invoice
     *
     * @param Customer $customer
     * @param array $options
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(Customer $customer, array $options = [])
    {
        $data = [
            'customer' => $customer,
            'includeLogo' => $options['include_logo'] ?? config('invoice.styling.show_logo', true),
            'includePaymentInfo' => $options['include_payment_info'] ?? config('invoice.styling.show_payment_info', true),
            'customMessage' => $options['custom_message'] ?? config('invoice.invoice.default_notes'),
            'company' => config('invoice.company'),
            'payment' => config('invoice.payment'),
            'styling' => config('invoice.styling'),
        ];

        $pdf = Pdf::loadView('admin.customer_show', $data);

        // Set PDF options
        $pdf->setPaper(
            config('invoice.pdf.paper_size', 'A4'),
            config('invoice.pdf.orientation', 'portrait')
        );

        $pdf->setOptions([
            'margin-top' => config('invoice.pdf.margin_top', 10),
            'margin-bottom' => config('invoice.pdf.margin_bottom', 10),
            'margin-left' => config('invoice.pdf.margin_left', 10),
            'margin-right' => config('invoice.pdf.margin_right', 10),
        ]);

        return $pdf;
    }

    /**
     * Generate invoice filename
     *
     * @param Customer $customer
     * @return string
     */
    public function generateFilename(Customer $customer)
    {
        $prefix = config('invoice.invoice.prefix', 'INV');
        $date = Carbon::now()->format('Y-m-d');
        return "{$prefix}_{$customer->id}_{$date}.pdf";
    }

    /**
     * Save invoice to storage
     *
     * @param Customer $customer
     * @param array $options
     * @return string
     */
    public function saveToStorage(Customer $customer, array $options = [])
    {
        $pdf = $this->generatePdf($customer, $options);
        $filename = $this->generateFilename($customer);
        $path = "invoices/{$filename}";

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * Get invoice number
     *
     * @param Customer $customer
     * @return string
     */
    public function getInvoiceNumber(Customer $customer)
    {
        $prefix = config('invoice.invoice.prefix', 'INV');
        $format = config('invoice.invoice.number_format', '0000');
        return $prefix . '-' . str_pad($customer->id, strlen($format), '0', STR_PAD_LEFT);
    }

    /**
     * Calculate invoice totals
     *
     * @param Customer $customer
     * @return array
     */
    public function calculateTotals(Customer $customer)
    {
        $subtotal = (float) $customer->amount;
        $discountPercent = (float) $customer->discount;
        $discountAmount = ($subtotal * $discountPercent) / 100;
        $total = $subtotal - $discountAmount;

        return [
            'subtotal' => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total' => $total,
        ];
    }

    /**
     * Validate invoice data
     *
     * @param array $data
     * @return array
     */
    public function validateInvoiceData(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'total_amount' => 'required|numeric|min:0',
            'place' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
        ];

        return validator($data, $rules)->validate();
    }

    /**
     * Get invoice statistics
     *
     * @return array
     */
    public function getInvoiceStats()
    {
        $customers = Customer::all();

        $totalInvoices = $customers->count();
        $totalAmount = $customers->sum('total_amount');
        $averageAmount = $totalInvoices > 0 ? $totalAmount / $totalInvoices : 0;
        $totalDiscount = $customers->sum(function($customer) {
            return ($customer->amount * $customer->discount) / 100;
        });

        return [
            'total_invoices' => $totalInvoices,
            'total_amount' => $totalAmount,
            'average_amount' => $averageAmount,
            'total_discount' => $totalDiscount,
            'this_month' => $customers->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'this_year' => $customers->where('created_at', '>=', Carbon::now()->startOfYear())->count(),
        ];
    }
}

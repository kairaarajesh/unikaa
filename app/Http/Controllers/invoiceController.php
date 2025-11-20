<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $invoicesQuery = Invoice::with(['customer', 'branch', 'employee']);

        // Subadmin: scope by user's branch_id
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $invoicesQuery->where('branch_id', $user->branch_id);
            }
        }

        $invoices = $invoicesQuery->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.invoices', compact('invoices'));
    }

    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'branch', 'employee'])->findOrFail($id);

        // Subadmin can only view invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($invoice->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('admin.invoice_show', compact('invoice'));
    }

    /**
     * Generate PDF for invoice
     */
    public function generatePdf($id)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $invoice = Invoice::with(['customer', 'branch', 'employee'])->findOrFail($id);

        // Subadmin can only download invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($invoice->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Reuse the unified invoice template
        $pdf = Pdf::loadView('admin.customer_show', [
            'invoice' => $invoice,
            'customer' => $invoice->customer,
        ]);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('UNIKAA INVOICE_' . $invoice->invoice_number . '_' . $todayDate . '.pdf');
    }

    /**
     * Stream PDF for invoice (view in browser)
     */
    public function viewPdf($id)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $invoice = Invoice::with(['customer', 'branch', 'employee'])->findOrFail($id);

        // Subadmin can only view invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($invoice->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Reuse the unified invoice template
        $pdf = Pdf::loadView('admin.customer_show', [
            'invoice' => $invoice,
            'customer' => $invoice->customer,
        ]);

        return $pdf->stream('invoice_' . $invoice->invoice_number . '.pdf');
    }
}

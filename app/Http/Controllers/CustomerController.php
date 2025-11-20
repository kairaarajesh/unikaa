<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRec;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmployeeFormSmsNotification;
use Carbon\Carbon;
use App\Console\Commands\SendReminderEmails;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employees;
use App\Http\Requests\CustomerRec;
use App\Models\ServiceManagement;
use Illuminate\Support\Facades\DB;
use App\Models\Management; // added
use App\Models\Invoice;

class CustomerController extends Controller
{
      public function index(Request $request)
    {
        $user = auth()->user();
        $customersQuery = Customer::query();
        $employeesQuery = Employees::query();

        // Optional filters: number, membership_card, custom date range (mm/dd/yyyy)
        $filterNumber = trim((string)$request->get('number'));
        $filterCard = trim((string)$request->get('membership_card'));
        $rawStart = $request->get('start_date'); // expected mm/dd/yyyy
        $rawEnd = $request->get('end_date');     // expected mm/dd/yyyy

        if ($filterNumber !== '') {
            $customersQuery->where('number', 'like', "%$filterNumber%");
        }

        if ($filterCard !== '') {
            // membership_card column assumed on customers table
            $customersQuery->where('membership_card', 'like', "%$filterCard%");
        }

        // Subadmin: scope by user's branch_id; employees table stores branch as name
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $customersQuery->where('branch_id', $user->branch_id);
                $userBranch = Branch::find($user->branch_id);
                if ($userBranch) {
                    $employeesQuery->where('branch_id', $userBranch->name);
                }
            }
        }

        $period = $request->get('period', 'all');
        $startDate = null;
        $endDate = null;

        // If explicit start/end provided in mm/dd/yyyy, apply as custom filter
        if (!empty($rawStart) || !empty($rawEnd)) {
            try {
                $startDate = $rawStart ? Carbon::createFromFormat('m/d/Y', $rawStart)->format('Y-m-d') : null;
            } catch (\Exception $e) {
                $startDate = null;
            }
            try {
                $endDate = $rawEnd ? Carbon::createFromFormat('m/d/Y', $rawEnd)->format('Y-m-d') : null;
            } catch (\Exception $e) {
                $endDate = null;
            }

            if ($startDate && !$endDate) { $endDate = $startDate; }
            if ($startDate && $endDate) {
                $customersQuery->whereBetween(DB::raw('DATE(`date`)'), [$startDate, $endDate]);
            }

            // Treat as custom selection for UI purposes
            $period = 'custom';
        } else {
            // Fallback to existing period logic if no explicit start/end provided
        if ($period !== 'all') {
            $now = now();

            switch ($period) {
                case 'today':
                    $customersQuery->whereDate('date', $now->toDateString());
                    $startDate = $now->toDateString();
                    $endDate = $now->toDateString();
                    break;
                case 'week': {
                    $start = $now->copy()->startOfWeek()->toDateString();
                    $end = $now->copy()->endOfWeek()->toDateString();
                    $customersQuery->whereBetween(DB::raw('DATE(`date`)'), [$start, $end]);
                    $startDate = $start;
                    $endDate = $end;
                    break;
                }
                case 'month': {
                    $start = $now->copy()->startOfMonth()->toDateString();
                    $end = $now->copy()->endOfMonth()->toDateString();
                    $customersQuery->whereBetween(DB::raw('DATE(`date`)'), [$start, $end]);
                    $startDate = $start;
                    $endDate = $end;
                    break;
                }
                case 'year': {
                    $start = $now->copy()->startOfYear()->toDateString();
                    $end = $now->copy()->endOfYear()->toDateString();
                    $customersQuery->whereBetween(DB::raw('DATE(`date`)'), [$start, $end]);
                    $startDate = $start;
                    $endDate = $end;
                    break;
                }
                case 'custom': {
                    $rawStart = $request->get('start_date');
                    $rawEnd = $request->get('end_date');
                    try {
                        $startDate = $rawStart ? Carbon::parse($rawStart)->format('Y-m-d') : null;
                    } catch (\Exception $e) {
                        $startDate = null;
                    }
                    try {
                        $endDate = $rawEnd ? Carbon::parse($rawEnd)->format('Y-m-d') : null;
                    } catch (\Exception $e) {
                        $endDate = null;
                    }
                    if ($startDate && !$endDate) {
                        $endDate = $startDate;
                    }
                    if ($startDate && $endDate) {
                        $customersQuery->whereBetween(DB::raw('DATE(`date`)'), [$startDate, $endDate]);
                    }
                    break;
                }
            }
        }
        }

        $customers = $customersQuery->with(['branch', 'invoices'])->get();
        $employees = $employeesQuery->get();
        $services = ServiceManagement::all();
        $managements = Management::all();

        // Branch options for the form: subadmin sees only their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists() && isset($user->branch_id)) {
            $Branch = Branch::where('id', $user->branch_id)->get();
        } else {
            $Branch = Branch::all();
        }

        // Keep customers as objects but ensure customer_id is available
        $customers = $customers->map(function($customer) {
            // If customer_id is not set, generate it
            if (empty($customer->customer_id)) {
                $customer->customer_id = 'CUST' . str_pad($customer->id, 4, '0', STR_PAD_LEFT);
            }

            // Get the latest invoice for display purposes
            $latestInvoice = $customer->invoices->sortByDesc('created_at')->first();
            if ($latestInvoice) {
                $customer->latest_invoice = $latestInvoice;
                // For backward compatibility, add invoice data to customer object
                $customer->amount = $latestInvoice->amount;
                $customer->tax = $latestInvoice->tax;
                $customer->total_amount = $latestInvoice->total_amount;
                $customer->service_items = $latestInvoice->service_items;
                $customer->purchase_items = $latestInvoice->purchase_items;
                $customer->purchase_total_amount = $latestInvoice->purchase_total_amount;
                $customer->subtotal = $latestInvoice->subtotal;
                $customer->service_tax_amount = $latestInvoice->service_tax_amount;
                $customer->service_total_calculation = $latestInvoice->service_total_calculation;
            } else {
                // Set default values if no invoice exists
                $customer->amount = 0;
                $customer->tax = 0;
                $customer->total_amount = 0;
                $customer->service_items = [];
                $customer->purchase_items = [];
                $customer->purchase_total_amount = 0;
                $customer->subtotal = 0;
                $customer->service_tax_amount = 0;
                $customer->service_total_calculation = 0;
            }

            return $customer;
        });

        return view('admin.customer', compact('customers', 'employees', 'services', 'managements', 'period','Branch','startDate','endDate'));
    }

    /**
     * Persist service items and totals as an invoice on a customer record.
     */
    public function createInvoice(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        // Subadmin can only create invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $validated = $request->validate([
            'category' => 'required|array|min:1',
            'category.*' => 'required|string',
            'item_amount' => 'required|array|min:1',
            'item_amount.*' => 'required|numeric|min:0',
            'item_tax' => 'required|array|min:1',
            'item_tax.*' => 'nullable|numeric|min:0|max:100',
            'item_total_amount' => 'required|array|min:1',
            'item_total_amount.*' => 'required|numeric|min:0',
            'aggregate_amount' => 'required|numeric|min:0',
            'aggregate_tax' => 'required|numeric|min:0',
            'aggregate_total_amount' => 'required|numeric|min:0',
        ]);

        // Normalize payment if it's provided in the request
        $paymentMethod = $customer->payment;
        if ($request->has('payment') && is_array($request->input('payment'))) {
            $paymentMethod = array_map(function($payment) {
                $normalized = strtolower(trim($payment));
                // Handle special cases - normalize "Debit card / Credit card" to "card"
                if (strpos($normalized, 'debit') !== false || strpos($normalized, 'credit') !== false) {
                    return 'card';
                }
                return $normalized;
            }, array_filter($request->input('payment')));
            $paymentMethod = array_values(array_unique($paymentMethod));
        }

        // Create invoice record instead of storing in customer
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'date' => Carbon::parse($request->input('date', now()))->format('Y-m-d'),
            'amount' => round($validated['aggregate_amount'], 2),
            'tax' => round($validated['aggregate_tax'], 2),
            'total_amount' => round($validated['aggregate_total_amount'], 2),
            'service_items' => $validated['category'],
            'purchase_items' => [],
            'purchase_total_amount' => 0,
            'subtotal' => round($validated['aggregate_amount'], 2),
            'service_tax_amount' => round($validated['aggregate_tax'], 2),
            'service_total_calculation' => round($validated['aggregate_total_amount'], 2),
            'payment_method' => $paymentMethod,
            'branch_id' => $customer->branch_id,
            'employee_id' => $customer->employee_id,
            'employee_details' => $customer->employee_details,
        ]);

        return redirect()->route('customer.index')->with('success', 'Invoice created for customer #' . $customer->id);
    }

   public function store(Request $request)
{
    try {
        $user = auth()->user();

        // Check if user has write permission for customers
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'customers', 'write')) {
            abort(403, 'You do not have permission to create customers.');
        }

        // Log the incoming request data for debugging
        Log::info('Customer store request data:', $request->all());

        // Check if service_items is valid JSON
        $serviceItemsJson = $request->input('service_items');
        if ($serviceItemsJson && !is_array(json_decode($serviceItemsJson, true))) {
            Log::warning('Invalid service_items JSON:', ['service_items' => $serviceItemsJson]);
            return redirect()->route('customer.index')->with('error', 'Invalid service items data format.');
        }

        // Check if purchase_items is valid JSON
        $purchaseItemsJson = $request->input('purchase_items');
        if ($purchaseItemsJson && !is_array(json_decode($purchaseItemsJson, true))) {
            Log::warning('Invalid purchase_items JSON:', ['purchase_items' => $purchaseItemsJson]);
            return redirect()->route('customer.index')->with('error', 'Invalid purchase items data format.');
        }

        // Prepare validation rules based on user role
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'number' => 'required|digits:10',
            'place' => 'nullable|string|max:255',
            'date' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
            'employee_details' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Male,Female',
            'payment' => 'nullable|array',
            'payment.*' => 'nullable|string|max:100',
            'service_items' => 'required',
            'purchase_items' => 'nullable',
        ];

        // Add branch_id validation only for non-subadmin users
        if (!$user || !method_exists($user, 'roles') || !$user->roles()->where('slug', 'subadmin')->exists()) {
            $validationRules['branch_id'] = 'required|exists:branches,id';
        }

        $validatedData = $request->validate($validationRules);

        Log::info('Validation passed, processing data');

        // Normalize payment array to lowercase format like ["cash","paytm"]
        if (isset($validatedData['payment']) && is_array($validatedData['payment'])) {
            $validatedData['payment'] = array_map(function($payment) {
                // Convert to lowercase and normalize format
                $normalized = strtolower(trim($payment));
                // Handle special cases - normalize "Debit card / Credit card" to "card"
                if (strpos($normalized, 'debit') !== false || strpos($normalized, 'credit') !== false) {
                    return 'card';
                }
                return $normalized;
            }, array_filter($validatedData['payment'])); // Remove empty values
            // Remove duplicates and re-index
            $validatedData['payment'] = array_values(array_unique($validatedData['payment']));
        } else {
            $validatedData['payment'] = [];
        }

        // Additional debugging
        Log::info('Service items JSON:', ['service_items' => $request->input('service_items')]);
        Log::info('Purchase items JSON:', ['purchase_items' => $request->input('purchase_items')]);
        Log::info('Payment normalized:', ['payment' => $validatedData['payment']]);

        // Process service items data - handle different formats
        $serviceItemsRaw = $request->input('service_items');
        $purchaseItemsRaw = $request->input('purchase_items');

        // Handle different formats for service items
        if (is_string($serviceItemsRaw)) {
            $serviceItems = json_decode($serviceItemsRaw, true);
        } elseif (is_array($serviceItemsRaw)) {
            $serviceItems = $serviceItemsRaw;
        } else {
            $serviceItems = [];
        }

        // Handle different formats for purchase items
        if (is_string($purchaseItemsRaw)) {
            $purchaseItems = json_decode($purchaseItemsRaw, true);
        } elseif (is_array($purchaseItemsRaw)) {
            $purchaseItems = $purchaseItemsRaw;
        } else {
            $purchaseItems = [];
        }

        // Sanitize: remove placeholder/empty rows
        if (is_array($serviceItems)) {
            $serviceItems = array_values(array_filter($serviceItems, function ($item) {
                if (!is_array($item)) { return false; }
                $name = isset($item['service_name']) ? trim((string)$item['service_name']) : '';
                $amount = isset($item['amount']) ? (float)$item['amount'] : 0.0;
                $totalAmount = isset($item['total_amount']) ? (float)$item['total_amount'] : 0.0;
                if ($name === '' || $name === '-- Select Service --') { return false; }
                if ($amount <= 0 && $totalAmount <= 0) { return false; }
                return true;
            }));
        }

        if (is_array($purchaseItems)) {
            $purchaseItems = array_values(array_filter($purchaseItems, function ($item) {
                if (!is_array($item)) { return false; }
                $prodId = isset($item['product_id']) ? trim((string)$item['product_id']) : '';
                $prodName = isset($item['product_name']) ? trim((string)$item['product_name']) : '';
                $amount = isset($item['amount']) ? (float)$item['amount'] : 0.0;
                $totalAmount = isset($item['total_amount']) ? (float)$item['total_amount'] : 0.0;
                if ($prodId === '' || $prodName === '' || $prodName === '-- Select Product --') { return false; }
                if ($amount <= 0 && $totalAmount <= 0) { return false; }
                return true;
            }));
        }

        Log::info('Decoded service items:', ['service_items' => $serviceItems]);
        Log::info('Decoded purchase items:', ['purchase_items' => $purchaseItems]);

        if (!is_array($serviceItems) || empty($serviceItems)) {
            Log::warning('Service items validation failed', ['service_items' => $request->input('service_items')]);
            return redirect()->route('customer.index')->with('error', 'At least one service item is required.');
        }

        // Filter out summary items from service items
        $serviceItems = array_filter($serviceItems, function($item) {
            return !isset($item['_type']) || $item['_type'] !== '_summary';
        });

        if (empty($serviceItems)) {
            Log::warning('No valid service items after filtering', ['service_items' => $serviceItems]);
            return redirect()->route('customer.index')->with('error', 'At least one valid service item is required.');
        }

        // Purchase items are optional
        if (!is_array($purchaseItems)) {
            $purchaseItems = [];
        }

        // Validate service items structure
        foreach ($serviceItems as $index => $item) {
            if (isset($item['_type']) && $item['_type'] === '_summary') {
                continue;
            }
            if (empty($item['service_name']) || !isset($item['amount']) || !isset($item['tax']) || !isset($item['tax_amount']) || !isset($item['total_amount'])) {
                Log::warning('Invalid service item structure', ['index' => $index, 'item' => $item]);
                return redirect()->route('customer.index')->with('error', 'Invalid service item data format.');
            }
        }

        foreach ($purchaseItems as $index => $item) {
            if (empty($item['product_id']) || empty($item['product_name']) || $item['product_name'] === '-- Select Product --' || !isset($item['amount']) || !isset($item['tax'])) {
                Log::warning('Invalid purchase item structure', ['index' => $index, 'item' => $item]);
                return redirect()->route('customer.index')->with('error', 'Invalid purchase item data format.');
            }
        }

        // Calculate totals
        $serviceTotalNet = 0;
        $serviceTaxTotal = 0;
        foreach ($serviceItems as $item) {
            if (isset($item['_type']) && $item['_type'] === '_summary') {
                continue;
            }
            $amount = $item['amount'] ?? 0;
            $discountPct = $item['discount'] ?? 0;
            $net = $amount - ($amount * ($discountPct / 100));
            $serviceTotalNet += $net;
        }

        $overallServiceTaxPct = (float)$request->input('item_tax', 0);
        $serviceTaxTotal = $serviceTotalNet * ($overallServiceTaxPct / 100);
        $serviceTotalAfterTax = $serviceTotalNet + $serviceTaxTotal;

        // Calculate totals from purchase items
        $purchaseTotalNet = 0;
        $purchaseTaxTotal = 0;
        foreach ($purchaseItems as $item) {
            $amount = $item['amount'] ?? 0;
            $discountPct = $item['discount'] ?? 0;
            $net = $amount - ($amount * ($discountPct / 100));
            $taxPct = $item['tax'] ?? 0;
            $taxAmt = $net * ($taxPct / 100);
            $purchaseTotalNet += $net;
            $purchaseTaxTotal += $taxAmt;
        }

        // Calculate grand totals
        $totalAmount = $serviceTotalNet + $serviceTaxTotal + $purchaseTotalNet + $purchaseTaxTotal;
        $totalTax = $serviceTaxTotal + $purchaseTaxTotal;

        Log::info('Calculated totals:', [
            'service_total_net' => $serviceTotalNet,
            'service_tax_total' => $serviceTaxTotal,
            'purchase_total_net' => $purchaseTotalNet,
            'purchase_tax_total' => $purchaseTaxTotal,
            'total_amount' => $totalAmount,
            'total_tax' => $totalTax
        ]);

        // Handle branch_id for subadmin users
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $validatedData['branch_id'] = $user->branch_id;
            } else {
                return redirect()->route('customer.index')->with('error', 'Branch not assigned to user.');
            }
        }

        // Check if customer with this phone number already exists
        $existingCustomerQuery = Customer::where('number', $validatedData['number']);

        // Subadmin can only access customers from their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            $existingCustomerQuery->where('branch_id', $user->branch_id);
        }

        $existingCustomer = $existingCustomerQuery->first();

        DB::beginTransaction();

        if ($existingCustomer) {
            // Update existing customer with new information
            Log::info('Updating existing customer', [
                'customer_id' => $existingCustomer->id,
                'customer_number' => $existingCustomer->number,
                'old_name' => $existingCustomer->name,
                'new_name' => $validatedData['name']
            ]);

            // Set date
            try {
                $date = Carbon::parse($request->input('date', now()))->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $request->input('date', now()))->format('Y-m-d');
                } catch (\Exception $e2) {
                    $date = now()->format('Y-m-d');
                }
            }

            // Update customer information
            $existingCustomer->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'gender' => $validatedData['gender'],
                'place' => $validatedData['place'],
                'employee_id' => $validatedData['employee_id'],
                'employee_details' => $validatedData['employee_details'],
                'branch_id' => $validatedData['branch_id'],
                'payment' => $validatedData['payment'],
                'date' => $date,
                'service_items' => $serviceItems,
                'purchase_items' => $purchaseItems,
            ]);

            $customer = $existingCustomer;
        } else {
            // Create new customer
            Log::info('Creating new customer');

            // Generate Customer ID
            $lastCustomer = Customer::orderBy('id', 'desc')->first();
            $nextNumber = $lastCustomer ? $lastCustomer->id + 1 : 1;
            $customerId = 'CUST' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Set date
            try {
                $date = Carbon::parse($request->input('date', now()))->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $request->input('date', now()))->format('Y-m-d');
                } catch (\Exception $e2) {
                    $date = now()->format('Y-m-d');
                }
            }

            // Set place from branch if not provided
            if (empty($validatedData['place'])) {
                $resolvedBranchId = $validatedData['branch_id'] ?? null;
                if ($resolvedBranchId) {
                    $resolvedBranch = \App\Models\Branch::find($resolvedBranchId);
                    if ($resolvedBranch) {
                        $validatedData['place'] = $resolvedBranch->address ?? $resolvedBranch->place ?? $resolvedBranch->name;
                    }
                }
            }

            // Create customer with all required fields
            $customer = Customer::create([
                'customer_id' => $customerId,
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'number' => $validatedData['number'],
                'gender' => $validatedData['gender'],
                'place' => $validatedData['place'],
                'date' => $date,
                'employee_id' => $validatedData['employee_id'],
                'employee_details' => $validatedData['employee_details'],
                'branch_id' => $validatedData['branch_id'],
                'payment' => $validatedData['payment'],
                'service_items' => $serviceItems,
                'purchase_items' => $purchaseItems,
            ]);
        }

        // Create separate invoice record with proper date handling
        try {
            $invoiceDate = Carbon::parse($request->input('date', now()))->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                $invoiceDate = Carbon::createFromFormat('d-m-Y', $request->input('date', now()))->format('Y-m-d');
            } catch (\Exception $e2) {
                $invoiceDate = now()->format('Y-m-d');
            }
        }

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'date' => $invoiceDate,
            'amount' => round($serviceTotalNet, 2),
            'tax' => round($totalTax, 2),
            'total_amount' => round($totalAmount, 2),
            'service_items' => $serviceItems,
            'purchase_items' => $purchaseItems,
            'purchase_total_amount' => round($purchaseTotalNet + $purchaseTaxTotal, 2),
            'subtotal' => round($serviceTotalNet, 2),
            'service_tax_amount' => round($serviceTaxTotal, 2),
            'service_total_calculation' => round($serviceTotalAfterTax, 2),
            'payment_method' => $validatedData['payment'], // Already normalized array
            'branch_id' => $validatedData['branch_id'],
            'employee_id' => $validatedData['employee_id'],
            'employee_details' => $validatedData['employee_details'],
        ]);

        DB::commit();

        $action = $existingCustomer ? 'updated' : 'created';
        Log::info('Customer and invoice processed successfully', [
            'customer_id' => $customer->id,
            'invoice_id' => $invoice->id,
            'action' => $action
        ]);

        // Set success message based on action
        $successMessage = $action === 'updated'
            ? 'Customer information updated successfully and new invoice created!'
            : 'New customer created successfully and invoice generated!';

        // Send email if email is provided
        if (!empty($customer->email)) {
            try {
                Mail::to($customer->email)->send(new \App\Mail\EmployeeFormEmail($customer));
            } catch (\Exception $e) {
                Log::warning('Failed to send email to customer: ' . $e->getMessage());
                // Don't fail the entire process if email fails
            }
        }

        // Generate PDF invoice using the invoice data
        try {
            // Increase memory limit for PDF generation
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 300);

            $pdf = Pdf::loadView('admin.customer_show', ['customer' => $customer, 'invoice' => $invoice]);
            $todayDate = Carbon::now()->format('d-m-y');

            // Return PDF download directly with success message
            return $pdf->download('UNIKAA INVOICE_' . $invoice->invoice_number . '_' . $todayDate . '.pdf')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->route('bill.index')->with('success', $successMessage . ' (PDF generation failed)');
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Customer Store Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        return redirect()->route('bill.index')->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
    }
}

 public function edit($id)
    {
        $customer=Customer::find($id);
        if(!$customer){
            request()->session()->flash('error','management not found');
        }
        // Subadmin can only access their branch customer
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer && $customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Get branch data for the form
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists() && isset($user->branch_id)) {
            $Branch = Branch::where('id', $user->branch_id)->get();
        } else {
            $Branch = Branch::all();
        }

        // Get employees data for the form
        $employeesQuery = Employees::query();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $userBranch = Branch::find($user->branch_id);
                if ($userBranch) {
                    $employeesQuery->where('branch_id', $userBranch->name);
                }
            }
        }
        $employees = $employeesQuery->get();

        return view('includes.edit_delete_customer', compact('customer', 'Branch', 'employees'));
    }

    public function update(CustomerRec $request, Customer $customer)
    {
        $user = auth()->user();

        // Check if user has write permission for customers
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'customers', 'write')) {
            abort(403, 'You do not have permission to update customers.');
        }

        $request->validated();
        // Subadmin can only modify within their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->number = $request->number;
        // Enforce branch for subadmin
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            $customer->branch_id = $user->branch_id;
        } else {
            $customer->branch_id = $request->branch_id;
        }
        $customer->place = $request->place;

        // if (is_array($request->category)) {
        //     $customer->category = json_encode($request->category);
        // } else {
        //     $customer->category = $request->category;
        // }

        // Calculate total_calculation if not provided
        // if (empty($request->total_calculation)) {
        //     $discount = (float)($request->discount ?? 0);
        //     $tax = (float)($request->tax ?? 0);
        //     $discountAmount = ($request->amount * $discount) / 100;
        //     $taxAmount = ($request->amount * $tax) / 100;
        //     $customer->total_calculation = $request->amount - $discountAmount + $taxAmount;
        // } else {
        //     $customer->total_calculation = $request->total_calculation;
        // }

        $customer->save();

        flash()->success('Success','management Record has been Updated successfully !');

        return redirect()->route('customer.index')->with('success');
    }

    public function destroy(Customer $customer)
    {
        $user = auth()->user();

        // Check if user has write permission for customers
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'customers', 'write')) {
            abort(403, 'You do not have permission to delete customers.');
        }

        // Subadmin can only delete within their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }
        $customer->delete();
        flash()->success('Success','Customer Record has been Deleted successfully !');
        return redirect()->route('customer.index')->with('success');
    }

    public function generateInvoice($id)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $customer = Customer::with('invoices')->findOrFail($id);
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $invoice = $customer->invoices->sortByDesc('created_at')->first();
        if (!$invoice) {
            abort(404, 'No invoice found for this customer');
        }

        $data = ['customer' => $customer, 'invoice' => $invoice];
        $pdf = Pdf::loadView('admin/customer_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('invoice_' . $invoice->invoice_number . '_' . $todayDate . '.pdf');
    }

    /**
     * Generate and display invoice in browser
     */
    public function viewInvoice($id)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $customer = Customer::with('invoices')->findOrFail($id);
        // Subadmin can only view invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Get the latest invoice
        $invoice = $customer->invoices->sortByDesc('created_at')->first();
        if (!$invoice) {
            abort(404, 'No invoice found for this customer');
        }

        $data = ['customer' => $customer, 'invoice' => $invoice];
        $pdf = Pdf::loadView('admin/customer_show', $data);

        return $pdf->stream('UNIKAA INVOICE_' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Download invoice PDF by invoice id
     */
    public function downloadInvoiceById($invoiceId)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $invoice = Invoice::with('customer')->findOrFail($invoiceId);
        $customer = $invoice->customer;

        // Subadmin can only download invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer && $customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $data = ['customer' => $customer, 'invoice' => $invoice];
        $pdf = Pdf::loadView('admin.customer_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('UNIKAA INVOICE_' . $invoice->invoice_number . '_' . $todayDate . '.pdf');
    }

    /**
     * View invoice PDF by invoice id
     */
    public function viewInvoiceById($invoiceId)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $invoice = Invoice::with('customer')->findOrFail($invoiceId);
        $customer = $invoice->customer;

        // Subadmin can only view invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer && $customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $data = ['customer' => $customer, 'invoice' => $invoice];
        $pdf = Pdf::loadView('admin.customer_show', $data);

        return $pdf->stream('UNIKAA INVOICE_' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Generate invoice and email it to customer
     */
    public function emailInvoice($id)
    {
        $customer = Customer::with('invoices')->findOrFail($id);
        // Subadmin can only email invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Get the latest invoice
        $invoice = $customer->invoices->sortByDesc('created_at')->first();
        if (!$invoice) {
            abort(404, 'No invoice found for this customer');
        }

        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $data = ['customer' => $customer, 'invoice' => $invoice];
        $pdf = Pdf::loadView('admin/customer_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');
        $filename = 'UNIKAA INVOICE_' . $invoice->invoice_number . '_' . $todayDate . '.pdf';

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pdfPath = storage_path('app/temp/' . $filename);
        $pdf->save($pdfPath);

        try {
            Mail::to($customer->email)->send(new \App\Mail\InvoiceEmail($customer, $pdfPath));

            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return redirect()->route('customer.index')->with('success', 'Invoice has been sent to ' . $customer->email);
        } catch (\Exception $e) {
            Log::error('Error sending invoice email: ' . $e->getMessage());
            return redirect()->route('customer.index')->with('error', 'Failed to send invoice email.');
        }
    }

    /**
     * Generate invoice with custom options
     */
    public function generateCustomInvoice(Request $request, $id)
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $customer = Customer::with('invoices')->findOrFail($id);
        // Subadmin can only generate/view custom invoices within their branch
        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Get the latest invoice
        $invoice = $customer->invoices->sortByDesc('created_at')->first();
        if (!$invoice) {
            abort(404, 'No invoice found for this customer');
        }

        $data = [
            'customer' => $customer,
            'invoice' => $invoice,
            'includeLogo' => $request->get('include_logo', true),
            'includePaymentInfo' => $request->get('include_payment_info', true),
            'customMessage' => $request->get('custom_message', ''),
        ];

        $pdf = Pdf::loadView('admin.customer_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        if ($request->get('action') === 'download') {
            return $pdf->download('UNIKAA INVOICE' . $invoice->invoice_number . '_' . $todayDate . '.pdf');
        } else {
            return $pdf->stream('UNIKAA INVOICE_' . $invoice->invoice_number . '.pdf');
        }
    }

    /**
     * Get customer details by phone number for auto-fill functionality
     */
    public function getCustomerByNumber(Request $request)
    {
        $request->validate([
            'number' => 'required|digits:10'
        ]);

        $user = auth()->user();
        $query = Customer::where('number', $request->number);

        // Subadmin can only access customers from their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        $customer = $query->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'number' => $customer->number,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'gender' => $customer->gender,
                    'branch_id' => $customer->branch_id,
                    'place' => $customer->place,
                    'payment' => $customer->payment,
                    'employee_id' => $customer->employee_id,
                    'employee_details' => $customer->employee_details
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found'
        ]);
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        $user = auth()->user();
        $employees = Employees::all();
        $services = ServiceManagement::all();
        $managements = Management::all();

        // Branch options for the form: subadmin sees only their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists() && isset($user->branch_id)) {
            $Branch = Branch::where('id', $user->branch_id)->get();
        } else {
            $Branch = Branch::all();
        }

        return view('admin.customer', compact('employees', 'services', 'managements', 'Branch'));
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        $user = auth()->user();

        // Subadmin can only view customers from their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('admin.customer_show', compact('customer'));
    }

    /**
     * View items data for a customer
     */
    public function viewItemsData($id)
    {
        $customer = Customer::with('invoices')->findOrFail($id);

        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $latestInvoice = $customer->invoices->sortByDesc('created_at')->first();

        return response()->json([
            'success' => true,
            'service_items' => $latestInvoice ? $latestInvoice->service_items : [],
            'purchase_items' => $latestInvoice ? $latestInvoice->purchase_items : []
        ]);
    }

    /**
     * View data for a customer
     */
    public function viewData($id)
    {
        $customer = Customer::with('invoices')->findOrFail($id);

        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $latestInvoice = $customer->invoices->sortByDesc('created_at')->first();

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'invoice' => $latestInvoice
        ]);
    }

    /**
     * Show bill details for a customer
     */
    public function billDetails($id)
    {
        $customer = Customer::with(['invoices', 'branch'])->findOrFail($id);

        $user = auth()->user();
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($customer->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Get the latest invoice
        $invoice = $customer->invoices->sortByDesc('created_at')->first();

        // Calculate statistics
        $visitCount = $customer->invoices->count();
        $totalBills = $customer->invoices->count();
        $avgBillTotal = $customer->invoices->avg('total_amount') ?? 0;
        $lastVisit = $customer->invoices->sortByDesc('created_at')->first()?->created_at?->format('d/m/Y') ?? date('d/m/Y');

        return view('admin.bill_details', compact('customer', 'invoice', 'visitCount', 'totalBills', 'avgBillTotal', 'lastVisit'));
    }

    /**
     * Update membership card for a customer
     */
    public function updateMembershipCard(Request $request)
    {
        $user = auth()->user();

        // Check if user has write permission for customers
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'customers', 'write')) {
            return redirect()->route('customer.index')->with('error', 'You do not have permission to update customers.');
        }

        $request->validate([
            'number' => 'required|digits:10',
            'membership_card' => 'nullable|string|max:255'
        ]);

        // Find customer by phone number
        $customerQuery = Customer::where('number', $request->number);

        // Subadmin can only access customers from their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id)) {
                $customerQuery->where('branch_id', $user->branch_id);
            }
        }

        $customer = $customerQuery->first();

        if (!$customer) {
            return redirect()->route('customer.index')->with('error', 'Customer not found with the provided phone number.');
        }

        $customer->membership_card = $request->membership_card;
        $customer->save();

        return redirect()->route('customer.index')->with('success', 'Membership card updated successfully for ' . $customer->name . ' (' . $customer->number . ').');
    }

    /**
     * Get employees by branch ID for dropdown
     */
    public function getEmployeesByBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        $user = auth()->user();
        $branchId = $request->branch_id;

        // Query employees by branch_id
        $employeesQuery = Employees::where('branch_id', $branchId);

        // Subadmin can only access employees from their branch
        if ($user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists()) {
            if (isset($user->branch_id) && $branchId != $user->branch_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this branch'
                ], 403);
            }
        }

        $employees = $employeesQuery->select('id', 'employee_name', 'employee_id', 'branch_id')
            ->orderBy('employee_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'employees' => $employees
        ]);
    }
}

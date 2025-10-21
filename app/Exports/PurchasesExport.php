<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection,WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Purchase::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->get([
            'customer_name',
            'customer_number',
            'Quantity',
            'price',
            'total_amount',
            'product_code',
            'payment',
            'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Customer Number',
            'Quantity',
            'Price',
            'Total Amount',
            'Product Code',
            'Payment Method',
            'Purchase Date'
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\attendancelogs;


class SalaryExport implements  FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
public function collection()
    {
        return DB::table('employees')
            ->leftJoin('attendances', 'employees.employee_id', '=', 'attendances.emp_id')
            ->select(
                'employees.employee_name as Employee Name',
                'employees.position as Employee Position',
                'employees.employee_id as Employee ID',
                'employees.salary as Salary',
                DB::raw('COUNT(DISTINCT attendances.attendance_date) as `Total Dates`'),
                DB::raw('ROUND((employees.salary / 30) * COUNT(DISTINCT attendances.attendance_date), 2) as `Total Salary`')
            )
            ->groupBy(
                'employees.employee_name',
                'employees.position',
                'employees.employee_id',
                'employees.salary'
            )
            ->get();
    }

     public function headings(): array
    {
      return [
            'Employee Name',
            'Employee Position',
            'Employee ID',
            'Salary',
            'Total Dates',
            'Total Salary',
        ];
    }
}
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainerExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
  public function collection()
    {
        return DB::table('staff_managements')
            ->leftJoin('staff_attendances', 'staff_managements.id', '=', 'staff_attendances.staff_management__id')
            ->select(
                'staff_managements.trainer as trainer',
                'staff_managements.subject as staff_management_subject',
                'staff_managements.salary as salary',
                DB::raw('COUNT(DISTINCT staff_attendances.attendance_date) as `Total Dates`'),
                DB::raw('ROUND((staff_managements.salary / 30) * COUNT(DISTINCT staff_attendances.attendance_date), 2) as `Total Salary`')
            )
            ->groupBy(
                'staff_managements.trainer',
                'staff_managements.subject',
                'staff_managements.salary'
            )
            ->get();
    }

     public function headings(): array
    {
      return [
            'Trainer Name',
            'Trainer Subject',
            'Salary',
            'Total Dates',
            'Total Salary',
        ];
    }
}

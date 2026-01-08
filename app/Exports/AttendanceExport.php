<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $users;

    public function __construct($month, $users)
    {
        $this->month = $month;
        $this->users = $users;
    }

    public function collection()
    {
        $startDate = Carbon::parse($this->month.'-01')->startOfMonth();
        $endDate   = Carbon::parse($this->month.'-01')->endOfMonth();

        $holidays = Holiday::pluck('holiday_date')->toArray();

        $rows = collect();

        foreach ($this->users as $user) {

            $full = $half = $absent = $holiday = $wo = $leave = 0;

            $saved = Attendance::where('user_id',$user->id)
                ->whereBetween('attendance_date',[$startDate,$endDate])
                ->get()
                ->keyBy(fn($a)=>$a->attendance_date->format('Y-m-d'));

            $row = [
                'Employee Name' => $user->name
            ];

            $date = $startDate->copy();

            while ($date <= $endDate) {

                $key = $date->format('Y-m-d');
                $label = '';

                if (in_array($key,$holidays)) {
                    $label = 'H';
                    $holiday++;
                }
                elseif ($date->isSunday()) {
                    $label = 'WO';
                    $wo++;
                }
                elseif (isset($saved[$key])) {
                    $status = $saved[$key]->status;

                    if ($status === 'P_FULL') {
                        $label = 'P';
                        $full++;
                    }
                    elseif ($status === 'P_HALF') {
                        $label = 'HP';
                        $half++;
                    }
                    elseif ($status === 'A') {
                        $label = 'A';
                        $absent++;
                    }
                    else {
                        $label = $status; // leave code
                        $leave++;
                    }
                }
                else {
                    $label = 'A';
                    $absent++;
                }

                // Day column (01,02...)
                $row[$date->format('d')] = $label;

                $date->addDay();
            }

            // Totals
            $row['Full']     = $full;
            $row['Half']     = $half;
            $row['Absent']  = $absent;
            $row['Leave']   = $leave;
            $row['Holiday'] = $holiday;
            $row['WO']      = $wo;
            $row['Total']   = $startDate->daysInMonth;

            $rows->push($row);
        }

        return $rows;
    }

    public function headings(): array
    {
        $startDate = Carbon::parse($this->month.'-01')->startOfMonth();
        $endDate   = Carbon::parse($this->month.'-01')->endOfMonth();

        $headings = ['Employee Name'];

        $date = $startDate->copy();
        while ($date <= $endDate) {
            $headings[] = $date->format('d');
            $date->addDay();
        }

        return array_merge($headings, [
            'Full',
            'Half',
            'Absent',
            'Leave',
            'Holiday',
            'WO',
            'Total'
        ]);
    }
}

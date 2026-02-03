<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $users;
    protected $attendance;

    public function __construct($month, $users, $attendance)
    {
        $this->month      = $month;
        $this->users      = $users;
        $this->attendance = $attendance;
    }

    public function collection()
    {
        $startDate = Carbon::parse($this->month.'-01')->startOfMonth();
        $endDate   = Carbon::parse($this->month.'-01')->endOfMonth();

        $rows = collect();

        foreach ($this->users as $user) {

            $full = $half = $absent = $holiday = $wo = $leave = 0;

            $row = [
                'Employee Name' => $user->name
            ];

            $date = $startDate->copy();

            while ($date <= $endDate) {

                $key = $date->format('Y-m-d');
                $status = $this->attendance[$user->id][$key] ?? 'A';

                switch ($status) {
                    case 'P_FULL':
                        $label = 'P';
                        $full++;
                        break;

                    case 'P_HALF':
                        $label = 'HP';
                        $half++;
                        break;

                    case 'WO':
                        $label = 'WO';
                        $wo++;
                        break;

                    case 'H':
                        $label = 'H';
                        $holiday++;
                        break;

                    case 'A':
                        $label = 'A';
                        $absent++;
                        break;

                    case 'NA':
                        $label = 'NA';
                        break;

                    default:
                        $label = $status; // Leave codes
                        $leave++;
                        break;
                }

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

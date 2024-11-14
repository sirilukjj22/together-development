<?php

namespace App\Exports;

use App\Models\Revenues;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // ใช้ Worksheet แทน Sheet

class HotelManualChargeExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filterBy;
    protected $startDate;
    protected $endDate;

    public function __construct($filterBy, $startDate, $endDate)
    {
        $this->filterBy = $filterBy;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Revenues::query()->leftJoin('revenue_credit', 'revenue.id', '=', 'revenue_credit.revenue_id');

        if ($this->filterBy == "date") {
            $query->whereBetween('revenue.date', [$this->startDate, $this->endDate]);
            $search_date = date('d/m/Y', strtotime($this->startDate))." - ".date('d/m/Y', strtotime($this->endDate));
        }

        if ($this->filterBy == "month") {
            $query->whereBetween('revenue.date', [date($this->startDate.'-01'), date($this->startDate.'-31')]);
            $search_date = date('F Y', strtotime(date($this->startDate.'-01')));
        }

        if ($this->filterBy == "year") {
            $query->whereYear('revenue.date', $this->startDate);
            $search_date = $this->startDate;
        }

        $query->select(
            'revenue.date',
            'revenue.total_credit',
            DB::raw("SUM(revenue_credit.credit_amount) as manual_charge"),
            DB::raw("SUM(revenue_credit.credit_amount) - revenue.total_credit as fee"));

        $data_query = $query->groupBy('revenue.date', 'revenue.total_credit')->orderBy('revenue.date', 'asc')->get();
        
        return $data_query;
    }

    public function map($revenue): array
    {
        static $index = 1;
        return [
            $index++, // ลำดับที่
            Carbon::parse($revenue->date)->format('d/m/Y'),
            number_format($revenue->manual_charge, 2),
            number_format($revenue->fee, 2),
            number_format($revenue->total_credit, 2),
        ];
    }

    public function headings(): array
    {
        return [
            '#', // ลำดับที่
            'Date',
            'Manual Charge',
            'Fee',
            'SMS Revenue'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // ตั้งค่าการจัดตำแหน่งในแถวที่ 1 (header) ให้อยู่ตรงกลาง
            1    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,  // แนวตั้ง
                ],
                'font' => [
                    'bold' => true, // เพิ่มความหนาให้ตัวหนังสือในแถวที่ 1
                ]
            ],
            'A'    => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            // ตั้งค่าคอลัมน์ C, D, E ให้ชิดขวาตั้งแต่แถวที่ 2 เป็นต้นไป
            'C2:C1000' => [  // กำหนดช่วงที่ชัดเจนสำหรับการจัดตำแหน่ง
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ]
            ],
            'D2:D1000' => [  // กำหนดช่วงที่ชัดเจนสำหรับการจัดตำแหน่ง
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ]
            ],
            'E2:E1000' => [  // กำหนดช่วงที่ชัดเจนสำหรับการจัดตำแหน่ง
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ]
            ],
        ];
    }
}

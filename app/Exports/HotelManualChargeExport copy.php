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
    }

    if ($this->filterBy == "month") {
        $query->whereBetween('revenue.date', [date($this->startDate.'-01'), date($this->startDate.'-31')]);
    }

    if ($this->filterBy == "year") {
        $query->whereYear('revenue.date', $this->startDate);
    }

    $query->select(
        'revenue.date',
        'revenue.total_credit',
        DB::raw("SUM(revenue_credit.credit_amount) as manual_charge"),
        DB::raw("SUM(revenue_credit.credit_amount) - revenue.total_credit as fee")
    );

    $data_query = $query->groupBy('revenue.date', 'revenue.total_credit')->orderBy('revenue.date', 'asc')->get();
    
    return $data_query;
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

public function map($revenue): array
{
    // กำหนดลำดับที่เริ่มจากแถวที่ 8
    static $index = 1;  // เริ่มต้นจากแถวที่ 8

    return [
        $index++, // ลำดับที่เริ่มต้นจากแถวที่ 8
        Carbon::parse($revenue->date)->format('d/m/Y'),
        number_format($revenue->manual_charge, 2),
        number_format($revenue->fee, 2),
        number_format($revenue->total_credit, 2),
    ];
}

public function styles(Worksheet $sheet)
{
    // เพิ่มโลโก้ก่อนหัวข้อคอลัมน์ (แถวที่ 1-3)
    $logoPath = public_path('image/Logo-tg2.png'); // Path ไปยังโลโก้ในโฟลเดอร์ public
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo of Together Resort');
    $drawing->setPath($logoPath);
    $drawing->setHeight(80);  // ตั้งขนาดของโลโก้
    $drawing->setCoordinates('A1');  // ตำแหน่งโลโก้ที่ A1
    $drawing->setWorksheet($sheet);

    // รวมเซลล์ในแถวที่ 1-3 เพื่อให้โลโก้แสดงได้
    $sheet->mergeCells('A1:E3');  // รวมเซลล์ตั้งแต่ A1 ถึง E3 เพื่อให้โลโก้แสดงได้

    // ตั้งค่าหัวข้อ (header) ให้อยู่ในแถวที่ 4
    $sheet->getRowDimension(4)->setRowHeight(20); // เพิ่มความสูงให้แถวที่ 4 หากจำเป็น

    // ตั้งค่าการจัดตำแหน่งหัวข้อในแถวที่ 4
    $sheet->getStyle('A4:E4')->applyFromArray([
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'font' => [
            'bold' => true,
        ]
    ]);

    // ปรับขนาดความกว้างของคอลัมน์ให้เหมาะสม
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(20);

    // ตั้งค่าคอลัมน์ C, D, E ให้ชิดขวาตั้งแต่แถวที่ 8 เป็นต้นไป
    $sheet->getStyle('C8:C1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('D8:D1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('E8:E1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    return [];
}


}


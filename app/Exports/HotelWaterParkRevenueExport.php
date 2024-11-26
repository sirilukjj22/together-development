<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class HotelWaterParkRevenueExport implements FromView, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filter_by;
    protected $data_query;
    protected $search_date;
    protected $startDate;
    protected $status;

    public function __construct($filter_by, $data_query, $search_date, $startDate, $status)
    {
        $this->filter_by = $filter_by;
        $this->data_query = $data_query;
        $this->search_date = $search_date;
        $this->startDate = $startDate;
        $this->status = $status;
    }

    public function view(): View
    {
        return view('pdf.hotel_water_park_revenue.excel_1A', [
            'filter_by' => $this->filter_by,
            'data_query' => $this->data_query,
            'search_date' => $this->search_date,
            'startDate' => $this->startDate,
            'status' => $this->status
        ]);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Styles for the sheet
        return [];
    }
}

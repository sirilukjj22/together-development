<?php

namespace App\Exports;

use App\Models\Revenues;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class HotelManualChargeExport implements FromView, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filter_by;
    protected $data_query;
    protected $search_date;
    protected $statusHide;
    protected $statusNotComplete;

    public function __construct($filter_by, $data_query, $search_date, $statusHide, $statusNotComplete)
    {
        $this->filter_by = $filter_by;
        $this->data_query = $data_query;
        $this->search_date = $search_date;
        $this->statusHide = $statusHide;
        $this->statusNotComplete = $statusNotComplete;
    }

    public function view(): View
    {

        return view('pdf.hotel_manual_charge.export_excel', [
            'data_query' => $this->data_query,
            'search_date' => $this->search_date,
            'filterBy' => $this->filter_by,
            'statusHide' => $this->statusHide,
            'statusNotComplete' => $this->statusNotComplete,
        ]);
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Manual Charge',
            'Fee',
            'SMS Revenue'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styles for the sheet
        return [];
    }
}




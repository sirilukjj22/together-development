<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class DebtorAgodaOutstandingExport implements FromView, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filter_by;
    protected $data_query;
    protected $search_date;
    protected $total_agoda_amount;

    public function __construct($filter_by, $data_query, $total_agoda_amount, $search_date)
    {
        $this->filter_by = $filter_by;
        $this->data_query = $data_query;
        $this->search_date = $search_date;
        $this->total_agoda_amount = $total_agoda_amount;
    }

    public function view(): View
    {

        return view('pdf.report_agoda.agoda_outstanding.export_excel', [
            'data_query' => $this->data_query,
            'search_date' => $this->search_date,
            'filterBy' => $this->filter_by,
            'total_agoda_amount' => $this->total_agoda_amount,
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

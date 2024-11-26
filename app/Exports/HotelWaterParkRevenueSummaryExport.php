<?php

namespace App\Exports;

use App\Models\Revenues;
use App\Models\TB_outstanding_balance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class HotelWaterParkRevenueSummaryExport implements FromView, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filter_by;
    protected $search_date;
    protected $status;

    public function __construct($filter_by, $search_date, $status)
    {
        $this->filter_by = $filter_by;
        $this->search_date = $search_date;
        $this->status = $status;
    }

    public function view(): View
    {

        if ($this->filter_by == "date") {
            $exp_date = array_map('trim', explode('-', $this->search_date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Date
            $FromDate = $FormatDate->format('Y-m-d');
            $ToDate = $FormatDate2->format('Y-m-d');

            // เช็ค Month, Year ถ้าเป็นเดือนเดียวกันให้สร้าง Format
            if ($FormatDate->format('m') == $FormatDate2->format('m')) {
                $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
                $ToMonth = $FormatDate2->format('Y-m-d');

                $FromYear = $FormatDate->format('Y-01-01');
                $ToYear = $FormatDate2->format('Y-m-d');
            } else {
                $FromMonth = null;
                $ToMonth = null;

                $FromYear = null;
                $ToYear = null;
            }

        } elseif ($this->filter_by == "month") {
            $FormatDate = Carbon::createFromFormat('Y-m', $this->search_date);
            $FormatDate2 = Carbon::createFromFormat('Y-m', $this->search_date);

            // Date
            $FromDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToDate = $FormatDate->endOfMonth()->format('Y-m-d');

            // Month
            $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToMonth = $FormatDate->endOfMonth()->format('Y-m-d');

            // Year
            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate->endOfMonth()->format('Y-m-d');

        } elseif ($this->filter_by == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $this->search_date);
            $FormatDate2 = Carbon::createFromFormat('Y', $this->search_date);

            // Date
            $FromDate = $FormatDate->format('Y-01-01');
            $ToDate = $FormatDate->format('Y-12-31');

            // Month
            $FromMonth = $FormatDate->format('Y-01-01');
            $ToMonth = $FormatDate->format('Y-12-31');

            // Year
            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate->format('Y-12-31');
        }

        // Outstanding Balance From Last Year
        $lastYear = date('Y', strtotime('-1 year'));
        $agoda_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('agoda_balance');
        $elexa_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('elexa_balance');

        ## ข้อมูลในตาราง

        ### Credit Card Hotel ###
        // Date
        $credit_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Month
        $credit_revenue_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Year
        $credit_revenue_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        ### Front Desk ###
        // Date
        $total_front_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Month
        $total_front_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Year
        $total_front_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Charge
        $front_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 6);

        ### Guest Deposit ###
        // Date
        $total_guest_deposit = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Month
        $total_guest_deposit_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Year
        $total_guest_deposit_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Charge
        $guest_deposit_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 1);
 
        ### All Outlet ###
        // Date
        $total_fb_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Month
        $total_fb_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Year
        $total_fb_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Charge
        $fb_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 2);

        ## Other Revenue ###
        // Date
        $total_other_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select('other_revenue')->sum('other_revenue');

        // Month
        $total_other_month = Revenues::whereBetween('date', [$FromMonth, $ToDate])->select('other_revenue')->sum('other_revenue');

        // Year
        $total_other_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select('other_revenue')->sum('other_revenue');

        ### Agoda ###
        // Date
        $total_agoda_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->sum('total_credit_agoda');

        // Month
        $total_agoda_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->sum('total_credit_agoda');

        // Year
        $total_agoda_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->sum('total_credit_agoda');

        // Charge
        $agoda_charge = $this->getManualAgodaCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 5);

        ### Water Park ###
        // Date
        $total_wp_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Month
        $total_wp_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Year
        $total_wp_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Charge
        $wp_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 3);

        ### Elexa EGAT ###
        // Date
        $total_ev_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select('total_elexa')->sum('total_elexa');

        // Month
        $total_ev_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select('total_elexa')->sum('total_elexa');

        // Year
        $total_ev_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select('total_elexa')->sum('total_elexa');

        // Charge
        $ev_charge = $this->getManualEvCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 8);
        
        return view('pdf.hotel_water_park_revenue.excel_1B', [
            'credit_revenue' => $credit_revenue, 'credit_revenue_month' => $credit_revenue_month, 'credit_revenue_year' => $credit_revenue_year,
    
            'total_front_revenue' => $total_front_revenue, 'total_front_month' => $total_front_month, 'total_front_year' => $total_front_year, 'front_charge' => $front_charge,

            'total_guest_deposit' => $total_guest_deposit, 'total_guest_deposit_month' => $total_guest_deposit_month, 'total_guest_deposit_year' => $total_guest_deposit_year, 'guest_deposit_charge' => $guest_deposit_charge,

            'total_fb_revenue' => $total_fb_revenue, 'total_fb_month' => $total_fb_month, 'total_fb_year' => $total_fb_year, 'fb_charge' => $fb_charge,

            'total_agoda_revenue' => $total_agoda_revenue, 'total_agoda_month' => $total_agoda_month, 'total_agoda_year' => $total_agoda_year, 'agoda_charge' => $agoda_charge,

            'total_wp_revenue' => $total_wp_revenue, 'total_wp_month' => $total_wp_month, 'total_wp_year' => $total_wp_year, 'wp_charge' => $wp_charge,

            'total_ev_revenue' => $total_ev_revenue, 'total_ev_month' => $total_ev_month, 'total_ev_year' => $total_ev_year, 'ev_charge' => $ev_charge,

            'total_other_revenue' => $total_other_revenue, 'total_other_month' => $total_other_month, 'total_other_year' => $total_other_year,

            'agoda_outstanding_last_year' => $agoda_outstanding_last_year, 'elexa_outstanding_last_year' => $elexa_outstanding_last_year,

            'filter_by' => $this->filter_by,
            'search_date' => $this->search_date,
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

    public function getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {
        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->credit_amount : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0,
            'fee_date' => isset($sum_revenue) && $sum_revenue->total > 0 ? $sum_revenue->total_credit : 0,
            'fee_month' => isset($sum_revenue_month) && $sum_revenue_month->total > 0 ? $sum_revenue_month->total_credit : 0,
            'fee_year' => isset($sum_revenue_year) && $sum_revenue_year->total > 0 ? $sum_revenue_year->total_credit : 0,
            'total' => (isset($sum_revenue) ? $sum_revenue->credit_amount : 0) - (isset($sum_revenue) ? $sum_revenue->total_credit : 0),
            'total_month' => (isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0) - (isset($sum_revenue_month) ? $sum_revenue_month->total_credit : 0),
            'total_year' => (isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0) - (isset($sum_revenue_year) ? $sum_revenue_year->total_credit : 0)
        ];

        return $data;
    }

    public function getManualAgodaCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {
        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();
        
        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->agoda_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->total_credit_agoda : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->total_credit_agoda : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->total_credit_agoda : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->agoda_outstanding : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_outstanding : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_outstanding : 0,
        ];

        return $data;
    }

    public function getManualEvCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {

        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $data[] = [ 
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->ev_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->ev_fee : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_fee : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_fee : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->ev_revenue : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_revenue : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_revenue : 0,
        ];

        return $data;
    }
}

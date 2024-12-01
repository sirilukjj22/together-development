<?php

namespace App\Http\Controllers;

use App\Exports\DebtorAgodaRevenueExport;
use App\Models\SMS_alerts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportAgodaRevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter_by = "month";
        $search_date = date('F Y');

        $data_query = SMS_alerts::whereBetween('date', [date('Y-m-d 21:00:00', strtotime('last day of previous month')), date('Y-m-t 20:59:59')])
            ->where('status', 5)->whereNull('date_into')
            ->orderBy('date', 'asc')->paginate(10);

        $total_sms_amount = SMS_alerts::whereBetween('date', [date('Y-m-d 21:00:00', strtotime('last day of previous month')), date('Y-m-t 20:59:59')])
            ->where('status', 5)->whereNull('date_into')
            ->orderBy('date', 'asc')->sum('amount');

        return view('report.agoda.index', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date'));
    }

    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $statusAll = $request->statusAll ?? 0;
        $statusHide = $request->statusHide ?? 0;
        $statusNotComplete = $request->statusNotComplete ?? 0;
        $endDate = '';

        $query = SMS_alerts::query();

        if ($filter_by == "date") {
            $exp = explode('-', $request->startDate);
            $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d 21:00:00');
            $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d 20:59:59');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime($adate)));
            $smsEndDate = $adate2;

            $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $query->whereBetween('date', [$smsFromDate, $smsEndDate])->where('status', 5)->whereNull('date_into');
            $query->orWhereBetween(DB::raw('DATE(date_into)'), [$startDate, $endDate])->where('status', 5);
            $search_date = date('d/m/Y', strtotime($startDate))." - ".date('d/m/Y', strtotime($endDate));
        }

        if ($filter_by == "month") {
            $startDate = $request->month ?? 0;
            $query->whereBetween('date', [date('Y-m-d 21:00:00', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t 20:59:59', strtotime("$startDate-01"))])->where('status', 5)->whereNull('date_into');
            $query->orWhereBetween(DB::raw('DATE(date_into)'), [date($startDate.'-01'), date('Y-m-t', strtotime("$startDate-01"))])->where('status', 5);
            $search_date = date('F Y', strtotime(date($startDate.'-01')));
        }

        if ($filter_by == "year") {
            $startDate = $request->startDate ?? 0;
            $query->whereYear('date', $startDate)->where('status', 5)->whereNull('date_into');
            $query->orWhereYear(DB::raw('DATE(date_into)'), $startDate)->where('status', 5);
            $search_date = $startDate;
        }

        $total_sms_amount = $query->sum('amount');

        if ($request->method_name == "search") {
            $data_query = $query->orderBy('date', 'asc')->paginate(10);
        } else {
            $data_query = $query->orderBy('date', 'asc')->get();
        }

        if ($request->method_name == "search") {
            return view('report.agoda.index', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date', 'startDate'));

        } elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.report_agoda.1A', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new DebtorAgodaRevenueExport($filter_by, $data_query, $total_sms_amount, $search_date), 'agoda_revenue.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function paginate_table(Request $request)
    {
        $perPage = (int)$request->perPage;

        $query_sms = SMS_alerts::query();

        if ($request->filter_by == "date") {
            $exp = explode('-', $request->date);
            $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d 21:00:00');
            $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d 20:59:59');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime($adate)));
            $smsEndDate = $adate2;

            $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $query_sms->whereBetween('date', [$smsFromDate, $smsEndDate])->where('status', 5)->whereNull('date_into');
            $query_sms->orWhereBetween(DB::raw('DATE(date_into)'), [$startDate, $endDate])->where('status', 5);
        }

        if ($request->filter_by == "month") {
            $startDate = $request->date ?? 0;
            $query_sms->whereBetween('date', [date('Y-m-d 21:00:00', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t 20:59:59', strtotime("$startDate-01"))])->where('status', 5)->whereNull('date_into');
            $query_sms->orWhereBetween(DB::raw('DATE(date_into)'), [date($startDate.'-01'), date('Y-m-t', strtotime("$startDate-01"))])->where('status', 5);
        }

        if ($request->filter_by == "year") {
            $startDate = $request->date ?? 0;
            $query_sms->whereYear('date', $startDate)->where('status', 5)->whereNull('date_into');
            $query_sms->orWhereYear(DB::raw('DATE(date_into)'), $startDate)->where('status', 5);
        }

        if ($perPage == 10) {
            $data_query = $query_sms->limit($request->page.'0')->get();
        } else {
            $data_query = $query_sms->paginate($perPage);
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    $img_bank = '';
                    $transfer_bank = '';
                    $revenue_name = '';
                    $split_from = '';
    
                    // โอนจากธนาคาร
                    $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                    $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
                
                    if (file_exists($filename)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                    } elseif (file_exists($filename2)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                    }
    
                    $transfer_bank = '<div>'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';
    
                    // เข้าบัญชี
                    $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';
    
                    // ประเภทรายได้
                    if ($value->status == 0) { $revenue_name = '-'; } 
                    if($value->status == 5) { $revenue_name = 'Agoda Bank Transfer Revenue'; }

                    if ($value->split_status == 1)
                    {
                        $split_from = '<br>
                        <span class="text-danger">(Split Credit Card From '.number_format($value->fullAmount->amount_before_split, 2).')</span>';
                    }
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'time' => Carbon::parse($value->date)->format('H:i:s'),
                        'transfer_bank' => $transfer_bank,
                        'into_account' => $into_account,
                        'amount' => number_format($value->amount, 2),
                        'remark' => $value->remark ?? 'Auto',
                        'revenue_name' => $revenue_name.$split_from,
                        'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                    ];
                }
            }
        }

        return response()->json([
                'data' => $data,
            ]);
    }

    public function search_table(Request $request)
    {

        $data = [];

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

        if (!empty($request->search_value)) {
            $query_agoda = SMS_alerts::query();

            if ($request->filter_by == "date") {
                $exp = explode('-', $request->date);
                $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d 21:00:00');
                $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d 20:59:59');
    
                $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime($adate)));
                $smsEndDate = $adate2;
    
                $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

                $query_agoda->whereBetween('date', [$smsFromDate, $smsEndDate])->where('status', 5)->whereNull('date_into')->where('amount', 'LIKE', '%'.$request->search_value.'%');
                $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$startDate, $endDate])->where('status', 5)->where('amount', 'LIKE', '%'.$request->search_value.'%');
            }

            if ($request->filter_by == "month") {
                $startDate = $request->date ?? 0;
                $query_agoda->whereBetween('date', [date('Y-m-d 21:00:00', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t 20:59:59', strtotime("$startDate-01"))])->where('status', 5)->whereNull('date_into')->where('amount', 'LIKE', '%'.$request->search_value.'%');
                $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [date($startDate.'-01'), date('Y-m-t', strtotime("$startDate-01"))])->where('status', 5)->where('amount', 'LIKE', '%'.$request->search_value.'%');
            }
    
            if ($request->filter_by == "year") {
                $startDate = $request->date ?? 0;
                $query_agoda->whereYear('date', $startDate)->where('status', 5)->whereNull('date_into')->where('amount', 'LIKE', '%'.$request->search_value.'%');
                $query_agoda->orWhereYear(DB::raw('DATE(date_into)'), $startDate)->where('status', 5)->where('amount', 'LIKE', '%'.$request->search_value.'%');
            }

            $data_query = $query_agoda->paginate($perPage);

        } else {
                if ($request->filter_by == "date") {
                    $exp = explode('-', $request->date);
                    $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d 21:00:00');
                    $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d 20:59:59');
        
                    $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime($adate)));
                    $smsEndDate = $adate2;
        
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');
    
                    $data_query = SMS_alerts::whereBetween('date', [$smsFromDate, $smsEndDate])->where('status', 5)->whereNull('date_into')
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$startDate, $endDate])->where('status', 5)->paginate($perPage);
                }
    
                if ($request->filter_by == "month") {
                    $startDate = $request->date ?? 0;
                    $data_query = SMS_alerts::whereBetween('date', [date('Y-m-d 21:00:00', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t 20:59:59', strtotime("$startDate-01"))])->where('status', 5)->whereNull('date_into')
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [date($startDate.'-01'), date('Y-m-t', strtotime("$startDate-01"))])->where('status', 5)->paginate($perPage);
                }
        
                if ($request->filter_by == "year") {
                    $startDate = $request->date ?? 0;
                    $data_query = SMS_alerts::whereYear('date', $startDate)->where('status', 5)->whereNull('date_into')
                        ->orWhereYear(DB::raw('DATE(date_into)'), $startDate)->where('status', 5)->paginate($perPage);
                }
        }

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                $img_bank = '';
                $transfer_bank = '';
                $revenue_name = '';
                $split_from = '';

                // โอนจากธนาคาร
                $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
            
                if (file_exists($filename)) {
                    $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                } elseif (file_exists($filename2)) {
                    $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                }

                $transfer_bank = '<div>'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';

                // เข้าบัญชี
                $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';

                // ประเภทรายได้
                if ($value->status == 0) { $revenue_name = '-'; }
                if($value->status == 5) { $revenue_name = 'Agoda Bank Transfer Revenue'; }

                $data[] = [
                    'id' => $key + 1,
                    'date' => Carbon::parse($value->date)->format('d/m/Y'),
                    'time' => Carbon::parse($value->date)->format('H:i:s'),
                    'transfer_bank' => $transfer_bank,
                    'into_account' => $into_account,
                    'amount' => number_format($value->amount, 2),
                    'remark' => $value->remark ?? 'Auto',
                    'revenue_name' => $revenue_name.$split_from,
                    'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                ];
            }
        }

        return response()->json([
            'data' => $data,
            ]);
    }
}

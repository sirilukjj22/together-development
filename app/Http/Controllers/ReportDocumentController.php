<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\document_invoices;
use App\Models\proposal_overbill;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\master_template;
use Illuminate\Support\Facades\DB;
use App\Exports\ProposalExport;
use App\Exports\InvoiceExport;
use App\Exports\AdditionalExport;
use App\Exports\BillingfolioExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\receive_payment;
class ReportDocumentController extends Controller
{
    //Dummy Proposal

    public function dummy_today()
    {
        $userid = Auth::user()->id;
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        $data_query = dummy_quotation::query()->orderBy('created_at', 'desc')->get();
        return view('report.document.dummy_today',compact('filter_by','search_date','data_query'));
    }
    //Proposal
    public function proposal()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        $data_query = Quotation::query()->orderBy('created_at', 'desc')->get();
        return view('report.document.proposal',compact('filter_by','search_date','data_query'));
    }
    public function search_proposal(Request $request)
    {
        $data = $request->all();
        $filter_by = $request->filter_by;
        $statusinput = $request->statusinput;
        $query = Quotation::query();


        if ($statusinput) {

            if ($statusinput== 'Pending') {
                $query->whereIn('status_document', [1,3])->where('status_guest', 0);
            } elseif ($statusinput == 2) {
                $query->where('status_document', 2);
            } elseif ($statusinput == 11) {
                $query->where('status_guest', 1)->whereIn('status_document', [1,3]);
            } elseif ($statusinput == 4) {
                $query->where('status_document', 4);
            } elseif ($statusinput == 9) {
                $query->where('status_document', 9);
            } elseif ($request->statusinput == 55) {
                $query->where('status_document', 0);
            }

            $search_date = date('d/m/Y');
            $startDate = '';
            $status = $statusinput;
        }else{
            if ($filter_by == "date") {
                $start = $request->startDate;

                list($startDate, $endDate) = explode(' - ', $start);
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
                if ($startDate == $endDate) {
                    $query = Quotation::query();
                }else{
                    $query->where(function ($query) use ($startDate, $endDate) {
                        // แปลงค่า checkin และ checkout ในฐานข้อมูลจาก d/m/Y เป็น Y-m-d
                        $query->whereRaw("STR_TO_DATE(checkin, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhereRaw("STR_TO_DATE(checkout, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhere(function ($query) use ($startDate, $endDate) {
                                  $query->whereRaw("STR_TO_DATE(checkin, '%d/%m/%Y') >= ? AND STR_TO_DATE(checkin, '%d/%m/%Y') <= ?", [$startDate, $endDate])
                                        ->whereRaw("STR_TO_DATE(checkout, '%d/%m/%Y') >= ? AND STR_TO_DATE(checkout, '%d/%m/%Y') <= ?", [$startDate, $endDate]);
                              });
                    });
                }
                // เพิ่มเงื่อนไขการกรอง

                $search_date = $start;

            }
            if ($filter_by == "month") {
                $date = $request->month ?? 0;
                $monthYear = Carbon::createFromFormat('Y-m', $date)->startOfMonth()->format('m/Y');
                $query->where(function ($query) use ($monthYear) {
                    $query->where('checkin', 'like', "%$monthYear%")
                        ->orWhere('checkout', 'like', "%$monthYear%");
                });
                $startDate = $request->month ?? 0;
                $search_date = Carbon::createFromFormat('Y-m', $startDate)->format('F Y');
            }
            if ($filter_by == "year") {
                $startDate = $request->startDate ?? 0;
                $query->where('checkin', 'like', "%$startDate%");
                $search_date = $startDate;
            }
            $status = '';
        }
        $data_query = $query->get();

        $total_quotation = $query->sum('quotation.Nettotal');
        if ($request->method_name == "search") {
            return view('report.document.proposal',compact('filter_by','search_date','data_query','startDate','status'));
        }elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.proposal.1A', compact('data_query', 'total_quotation', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new ProposalExport($filter_by, $data_query, $total_quotation, $search_date), 'proposal_document.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        // ดึงข้อมูลหลังจากเพิ่มเงื่อนไข


    }

    // invioce
    public function invoice()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        $data_query = document_invoices::query()->orderBy('created_at', 'desc')->get();
        return view('report.document.invoice',compact('filter_by','search_date','data_query'));

    }

    public function search_invoice(Request $request)
    {
        $data = $request->all();
        $filter_by = $request->filter_by;
        $statusinput = $request->statusinput;
        $query = document_invoices::query();

        if ($statusinput) {

            if ($statusinput== 'Paid') {
                $query->where('document_status', 2)->where('Paid', 1);
            } elseif ($statusinput == 1) {
                $query->where('document_status', 1);
            } elseif ($statusinput == 2) {
                $query->where('document_status', 2)->where('Paid', 0);
            }
            $search_date = date('d/m/Y');
            $startDate = '';
            $status = $statusinput;

        }else{
            if ($filter_by == "date") {
                $start = $request->startDate;
                list($startDate, $endDate) = explode(' - ', $start);



                // แยกวันที่


                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
                if ($startDate == $endDate) {
                    $query = document_invoices::query();
                }else{
                    $query->where(function ($query) use ($startDate, $endDate) {
                        // แปลงค่า checkin และ checkout ในฐานข้อมูลจาก d/m/Y เป็น Y-m-d
                        $query->whereRaw("STR_TO_DATE(IssueDate, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhereRaw("STR_TO_DATE(Expiration, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhere(function ($query) use ($startDate, $endDate) {
                                  $query->whereRaw("STR_TO_DATE(IssueDate, '%d/%m/%Y') >= ? AND STR_TO_DATE(IssueDate, '%d/%m/%Y') <= ?", [$startDate, $endDate])
                                        ->whereRaw("STR_TO_DATE(Expiration, '%d/%m/%Y') >= ? AND STR_TO_DATE(Expiration, '%d/%m/%Y') <= ?", [$startDate, $endDate]);
                              });
                    });
                }
                // เพิ่มเงื่อนไขการกรอง

                $search_date = $start;

            }
            if ($filter_by == "month") {
                $date = $request->month ?? 0;
                $monthYear = Carbon::createFromFormat('Y-m', $date)->startOfMonth()->format('m/Y');
                $query->where(function ($query) use ($monthYear) {
                    $query->where('IssueDate', 'like', "%$monthYear%")
                        ->orWhere('Expiration', 'like', "%$monthYear%");
                });
                $startDate = $request->month ?? 0;
                $search_date = Carbon::createFromFormat('Y-m', $startDate)->format('F Y');
            }
            if ($filter_by == "year") {
                $startDate = $request->startDate ?? 0;
                $query->where('IssueDate', 'like', "%$startDate%");
                $search_date = $startDate;
            }
            $status = '';
        }
        $data_query = $query->get();
        $total_invoice = $query->sum('document_invoice.sumpayment');
        if ($request->method_name == "search") {
            return view('report.document.invoice',compact('filter_by','search_date','data_query','startDate','status'));
        }elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.invoice.1A', compact('data_query', 'total_invoice', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new InvoiceExport($filter_by, $data_query, $total_invoice, $search_date), 'proforma_invoice_document.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        // ดึงข้อมูลหลังจากเพิ่มเงื่อนไข


    }

    public function additional()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        $data_query = proposal_overbill::query()->orderBy('created_at', 'desc')->get();
        return view('report.document.additional',compact('filter_by','search_date','data_query'));

    }

    public function search_additional(Request $request)
    {
        $data = $request->all();
        $filter_by = $request->filter_by;
        $statusinput = $request->statusinput;
        $query = proposal_overbill::query();

        if ($statusinput) {

           if ($statusinput == 2) {
                $query->where('status_document', 2);
            } elseif ($statusinput == 3) {
                $query->where('status_document', 3);
            } elseif ($statusinput == 4) {
                $query->where('status_document', 4);
            }elseif ($statusinput == 0) {
                $query->where('status_document', 0);
            }
            $search_date = date('d/m/Y');
            $startDate = '';
            $status = $statusinput;

        }else{
            if ($filter_by == "date") {
                $start = $request->startDate;
                list($startDate, $endDate) = explode(' - ', $start);



                // แยกวันที่


                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
                if ($startDate == $endDate) {
                    $query = proposal_overbill::query();
                }else{
                    $query->where(function ($query) use ($startDate, $endDate) {
                        // แปลงค่า checkin และ checkout ในฐานข้อมูลจาก d/m/Y เป็น Y-m-d
                        $query->whereRaw("STR_TO_DATE(checkin, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhereRaw("STR_TO_DATE(checkout, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhere(function ($query) use ($startDate, $endDate) {
                                  $query->whereRaw("STR_TO_DATE(checkin, '%d/%m/%Y') >= ? AND STR_TO_DATE(checkin, '%d/%m/%Y') <= ?", [$startDate, $endDate])
                                        ->whereRaw("STR_TO_DATE(checkout, '%d/%m/%Y') >= ? AND STR_TO_DATE(checkout, '%d/%m/%Y') <= ?", [$startDate, $endDate]);
                              });
                    });
                }
                // เพิ่มเงื่อนไขการกรอง

                $search_date = $start;

            }
            if ($filter_by == "month") {
                $date = $request->month ?? 0;
                $monthYear = Carbon::createFromFormat('Y-m', $date)->startOfMonth()->format('m/Y');
                $query->where(function ($query) use ($monthYear) {
                    $query->where('checkin', 'like', "%$monthYear%")
                        ->orWhere('checkout', 'like', "%$monthYear%");
                });
                $startDate = $request->month ?? 0;
                $search_date = Carbon::createFromFormat('Y-m', $startDate)->format('F Y');
            }
            if ($filter_by == "year") {
                $startDate = $request->startDate ?? 0;
                $query->where('checkin', 'like', "%$startDate%");
                $search_date = $startDate;
            }
            $status = '';
        }
        $data_query = $query->get();
        $total_additional = $query->sum('proposal_overbill.Nettotal');
        if ($request->method_name == "search") {
            return view('report.document.additional',compact('filter_by','search_date','data_query','startDate','status'));
        }elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.additional.1A', compact('data_query', 'total_additional', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new AdditionalExport($filter_by, $data_query, $total_additional, $search_date), 'additional_charge_document.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        // ดึงข้อมูลหลังจากเพิ่มเงื่อนไข


    }

    public function billingfolio()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        $data_query = receive_payment::query()->orderBy('created_at', 'desc')->get();
        return view('report.document.billingfolio',compact('filter_by','search_date','data_query'));

    }

    public function search_billingfolio(Request $request)
    {
        $data = $request->all();
        $filter_by = $request->filter_by;
        $statusinput = $request->statusinput;
        $query = receive_payment::query();

        if ($statusinput) {

           if ($statusinput == 1) {
                $query->where('document_status', 1);
            } elseif ($statusinput == 2) {
                $query->where('document_status', 2);
            }
            $search_date = date('d/m/Y');
            $startDate = '';
            $status = $statusinput;

        }else{
            if ($filter_by == "date") {
                $start = $request->startDate;
                list($startDate, $endDate) = explode(' - ', $start);



                // แยกวันที่


                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
                if ($startDate == $endDate) {
                    $query = receive_payment::query();
                }else{
                    $query->where(function ($query) use ($startDate, $endDate) {
                        // แปลงค่า checkin และ checkout ในฐานข้อมูลจาก d/m/Y เป็น Y-m-d
                        $query->whereRaw("STR_TO_DATE(paymentDate, '%d/%m/%Y') BETWEEN ? AND ?", [$startDate, $endDate])
                              ->orWhere(function ($query) use ($startDate, $endDate) {
                                  $query->whereRaw("STR_TO_DATE(paymentDate, '%d/%m/%Y') >= ? AND STR_TO_DATE(paymentDate, '%d/%m/%Y') <= ?", [$startDate, $endDate]);
                              });
                    });
                }
                // เพิ่มเงื่อนไขการกรอง

                $search_date = $start;

            }
            if ($filter_by == "month") {
                $date = $request->month ?? 0;
                $monthYear = Carbon::createFromFormat('Y-m', $date)->startOfMonth()->format('m/Y');
                $query->where(function ($query) use ($monthYear) {
                    $query->where('paymentDate', 'like', "%$monthYear%");
                });
                $startDate = $request->month ?? 0;
                $search_date = Carbon::createFromFormat('Y-m', $startDate)->format('F Y');
            }
            if ($filter_by == "year") {
                $startDate = $request->startDate ?? 0;
                $query->where('paymentDate', 'like', "%$startDate%");
                $search_date = $startDate;
            }
            $status = '';
        }
        $data_query = $query->get();
        $total_receipt = $query->sum('document_receive.document_amount');
        if ($request->method_name == "search") {
            return view('report.document.billingfolio',compact('filter_by','search_date','data_query','startDate','status'));
        }elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.billingfolio.1A', compact('data_query', 'total_receipt', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new BillingfolioExport($filter_by, $data_query, $total_receipt, $search_date), 'billingfolio_document.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        // ดึงข้อมูลหลังจากเพิ่มเงื่อนไข


    }
}

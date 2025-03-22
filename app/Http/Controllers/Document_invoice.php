<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\document_invoices;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\log;
use App\Models\Masters;
use App\Models\receive_payment;
use App\Models\log_company;
use App\Models\document_quotation;
use App\Models\master_promotion;
use Illuminate\Support\Arr;
use App\Models\master_document_sheet;
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
use App\Models\Master_company;
use App\Models\phone_guest;
use App\Models\Guest;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\master_document_email;
use App\Models\document_deposit_revenue;
use App\Models\depositrevenue;

class Document_invoice extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
    ->leftJoin('document_invoice', 'quotation.Quotation_ID', '=', 'document_invoice.Quotation_ID')
    ->leftJoin('deposit_revenue', 'quotation.Quotation_ID', '=', 'deposit_revenue.Quotation_ID') // ตรงนี้แก้ไข
    ->where('quotation.status_document', 6)
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('document_invoice')
            ->whereColumn('document_invoice.Quotation_ID', 'quotation.Quotation_ID');
    })
    ->select(
        'quotation.*',
        'deposit_revenue.*',
        'document_invoice.Quotation_ID as QID',
        'document_invoice.document_status',
        'document_invoice.sumpayment',
        DB::raw('1 as status'),
        DB::raw('SUM(CASE WHEN document_invoice.document_status = 1 THEN 1 ELSE 0 END) as invoice_count'),
        DB::raw('SUM(CASE WHEN deposit_revenue.document_status = 1 THEN 1 ELSE 0 END) as deposit_count')
    )
    ->groupBy('quotation.Quotation_ID', 'quotation.Operated_by', 'quotation.status_document')
    ->get();

        $Cancel = document_invoices::query()->where('document_status',0)->get();

        $invoice = document_invoices::query()->get();

        $Pending = document_invoices::query()->where('document_status',1)->get();

        $Generate = document_invoices::query()->where('document_status',3)->get();


        $Complete = Quotation::query()
        ->leftJoin('document_invoice', 'quotation.Quotation_ID', '=', 'document_invoice.Quotation_ID')
        ->where('quotation.status_document', 6)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('document_invoice')
                ->whereColumn('document_invoice.Quotation_ID', 'quotation.Quotation_ID')
                ->where('document_invoice.document_status', 1); // ✅ เงื่อนไขให้ document_status = 1 เท่านั้น
        })
        ->select(
            'quotation.*',
            'document_invoice.Quotation_ID as QID',
            'document_invoice.document_status',
            'document_invoice.sumpayment',
            DB::raw('1 as status'),
            DB::raw('SUM(CASE WHEN document_invoice.document_status = 1 THEN 1 ELSE 0 END) as invoice_count')
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.Operated_by', 'quotation.status_document')
        ->get();


        return view('document_invoice.index',compact('Approved','invoice','Complete','Generate',
        'Cancel','Pending'));
    }
    public function viewList($id)
    {
            // Get the 'perPage' from query parameters, default to 10 if not present
        $perPage = request()->get('perPage', 10);

        // Initialize variables
        $Quotation_ID = null;
        $invoice = null;

        // Try to find the proposal with the provided ID
        $proposal = Quotation::query()->where('id', $id)->first();

        if ($proposal) {
            // If the proposal is found, get the related Quotation_ID
            $Quotation_ID = $proposal->Quotation_ID;

            // Fetch all invoices with the same Quotation_ID and paginate them
            $invoice = document_invoices::where('Quotation_ID', $Quotation_ID)->whereIn('document_status',[1,2])->get();

        } else {
            // Optionally, handle the case when the proposal is not found
            // Redirect or return an error message
            return redirect()->route('invoice.index')->with('error','No Data.');
        }

        // Return the view with the data
        return view('document_invoice.view_invoicelist', compact('invoice', 'Quotation_ID'));
    }
    public function Generate($id){

        $currentDate = Carbon::now();
        $ID = 'PI-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = document_invoices::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $InvoiceID = $ID.$year.$month.$newRunNumber;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $Additional = proposal_overbill::where('Quotation_ID', $QuotationID)->first();
        $Additional_ID = null;
        $Additional_Nettotal = 0;
        if ($Additional) {
            $Additional_ID = $Additional->Additional_ID;
            $Additional_Nettotal = $Additional->Nettotal;


        }
        $Nettotal = $Quotation->Nettotal;
        $vat_type = $Quotation->vat_type;

        $Selectdata =  $Quotation->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $invoices = null;
        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->get();
        $totalinvoice = 0;
        if ($invoices) {

            foreach ($invoices as $item) {
                $totalinvoice +=  $item->sumpayment;
            }
        }

        $Dinvoice = document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->latest()->first();
        $Deposit = 1;
        if ($Dinvoice) {
            $Deposit =$Dinvoice->deposit+ 1;
        }
        $user = Auth::user();
        $Deposit_ID = depositrevenue::where('Quotation_ID',$QuotationID)->where('document_status',2)->where('receipt',0)->get();

        return view('document_invoice.create',compact('InvoiceID','fullName','Identification','address','Quotation','Selectdata','QuotationID','Additional_ID','Additional_Nettotal','settingCompany','invoices','totalinvoice',
                    'phone','Contact_phone','Fax_number','Email','Contact_Name','Deposit','vat_type','user','Contact_Email','Deposit_ID'));
    }
    public function deposit($id){
        $idArray = explode(',', $id);
        $deposit = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้

        foreach ($idArray as $item) {
            $data = depositrevenue::whereIn('id', $idArray)->get();

            $deposit = $data->map(function ($item) {
                return [
                    'Deposit_ID' => $item->Deposit_ID,
                    'Amount'     => $item->amount,
                ];
            });
        }
        return response()->json([
            'deposit'=>$deposit,
        ]);
    }
    public function deposit_edit($id){
        $idArray = explode(',', $id);
        $deposit = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้

        foreach ($idArray as $item) {
            $data = depositrevenue::whereIn('Deposit_ID', $idArray)->get();

            $deposit = $data->map(function ($item) {
                return [
                    'Deposit_ID' => $item->Deposit_ID,
                    'Amount'     => $item->amount,
                ];
            });
        }
        return response()->json([
            'deposit'=>$deposit,
        ]);
    }
    public function save(Request $request){
        try {
            $data = $request->all();

            $userid = Auth::user()->id;
            $preview = $request->preview;
            $save = $request->save;
            $idArray = $request->deposit;
            $datarequest = [
                'Proposal_ID' => $data['QuotationID'] ?? null,
                'InvoiceID' => $data['InvoiceID'] ?? null,
                'IssueDate' => $data['IssueDate'] ?? null,
                'Expiration' => $data['Expiration'] ?? null,
                'Deposit' => $data['Deposit'] ?? null,
                'Sum' => $data['sum'] ?? null,
                'amount' => $data['amount'] ?? null,
                'Payment'=> $data['Payment'] ?? null,
                'paymentPercent'=> $data['PaymentPercent'] ?? null,

            ];

            $deposit = 0;
            if ($idArray) {
                $deposit = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้

                foreach ($idArray as $item) {
                    $data = depositrevenue::whereIn('id', $idArray)->get();

                    $deposit = $data->map(function ($item) {
                        return [
                            'Deposit_ID' => $item->Deposit_ID,
                            'Amount'     => $item->amount,
                        ];
                    });
                }
            }


            {

                $balance = $request->balance;
                $sum = $request->sum ?? 0;
                if ($sum== null) {
                    return redirect()->back()->with('error', 'กรุณากรอกข้อมูลให้ครบ');
                }


                //log
                $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
                $InvoiceID = $datarequest['InvoiceID'] ?? null;
                $Deposit = $datarequest['Deposit'] ?? null;
                $Nettotal = $datarequest['amount'] ?? null;
                $Payment = $datarequest['Payment'] ?? null;
                $depositamount = $Nettotal-$sum;
                $Balance = $Nettotal-$depositamount;
                $formattedProductData = [];
                if ($deposit !== 0) {
                    foreach ($deposit as $item) {
                        $formattedPrice = number_format($item['Amount']).' '.'บาท';
                        $formattedProductData[] = 'Deposit Revenue : ' . $item['Deposit_ID'] . ' , ' . 'Amount : ' . $formattedPrice;
                    }
                }
                $Nettotalcheck = null;
                if ($Nettotal) {
                    $Nettotalcheck = 'ยอดเงินเต็ม : '.number_format($Nettotal). ' บาท';
                }

                $Paymentcheck = null;
                if ($Payment) {
                    $Paymentcheck = 'ยอดเงินที่ต้องชำระ : '. number_format($Payment). ' บาท';
                }

                $Balancecheck = null;
                if ($Balance) {
                    $Balancecheck = 'ยอดเงินคงเหลือชำระ : '.number_format($Balance). ' บาท';
                }
                $fullname = null;
                if ($InvoiceID) {
                    $fullname = 'รหัส : '.$InvoiceID.' + '.'อ้างอิงจาก : '.$Proposal_ID;
                }
                $list = 'รหัสรายการที่หักบิล';
                $datacompany = '';

                $variables = [$fullname, $Nettotalcheck, $Paymentcheck, $Balancecheck,$list];
                $formattedProductDataString = implode(' + ', $formattedProductData);

                // รวม $formattedProductDataString เข้าไปใน $variables
                $variables[] = $formattedProductDataString;
                foreach ($variables as $variable) {
                    if (!empty($variable)) {
                        if (!empty($datacompany)) {
                            $datacompany .= ' + ';
                        }
                        $datacompany .= $variable;
                    }
                }

                $userids = Auth::user()->id;
                $save = new log_company();
                $save->Created_by = $userids;
                $save->Company_ID = $InvoiceID;
                $save->type = 'Generate';
                $save->Category = 'Generate :: Proforma Invoice';
                $save->content =$datacompany;
                $save->save();
            }
            {
                //pdf
                $data = $request->all();
                $datarequest = [
                    'Proposal_ID' => $data['QuotationID'] ?? null,
                    'InvoiceID' => $data['InvoiceID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Deposit' => $data['Deposit'] ?? null,
                    'Sum' => $data['sum'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'Payment'=> $data['Payment'] ?? null,
                ];
                $Payment = $datarequest['Payment'] ?? null;
                $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();
                $Selectdata =  $Quotation->type_Proposal;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $Address = $Data->Address;
                    $Email = $Data->Email;
                    $Identification = $Data->Identification_Number;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.' '.$First_name.' '.$Last_name;
                    //-------------ที่อยู่
                    $CityID=$Data->City;
                    $amphuresID = $Data->Amphures;
                    $TambonID = $Data->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $Fax_number = '-';
                    $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                    $Contact_Name = null;
                    $Contact_phone =null;
                    $Contact_Email = null;
                }else{
                    $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
                    $Address = $Company->Address;
                    $Email = $Company->Company_Email;
                    $Identification = $Company->Taxpayer_Identification;
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }else{
                            $fullName = $comtype->name_th . $Compannyname;
                        }
                    }
                    $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
                    $prename = $representative->prefix;
                    $Contact_Email = $representative->Email;
                    $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                    $name = $prefix->name_th;
                    $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
                    $CityID=$Company->City;
                    $amphuresID = $Company->Amphures;
                    $TambonID = $Company->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $Fax_number =  $company_fax->Fax_number;
                    }else{
                        $Fax_number = '-';
                    }
                    $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                }
                $id = $datarequest['InvoiceID'];
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/cover/document/PDF/$id";

                // Generate the QR code as PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();

                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $date = Carbon::now();
                $date = Carbon::parse($date)->format('d/m/Y');
                $vattype= $Quotation->vat_type;
                $vat_type = master_document::where('id',$vattype)->first();
                $vatname = $vat_type->name_th;
                $Mevent =$Quotation->eventformat;
                $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                $checkin  = $Quotation->checkin;
                $checkout = $Quotation->checkout;
                $Day = $Quotation->day;
                $Night = $Quotation->night;
                $Adult = $Quotation->adult;
                $Children = $Quotation->children;
                $Checkin = $checkin;
                $Checkout = $checkout;

                $Deposit = $datarequest['Deposit'];
                $sum = $sum;
                $payment=$Payment;

                $Nettotal = floatval(str_replace(',', '', $datarequest['amount']));
                if ($payment) {
                    $payment0 = number_format($payment);
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;
                    $SubtotalAll =0;
                    $deposit = 0;
                    if ($vattype == 51) {
                        $Subtotal = $payment;
                        $SubtotalAll =$payment - $sum;
                        $depositall = $payment - $SubtotalAll;
                        $total = $depositall;
                        $addtax = 0;
                        $before = $depositall;
                        $balance = $depositall;
                    }else{
                        $Subtotal = $payment;
                        $SubtotalAll = $Subtotal-$sum;
                        $depositall = $payment - $SubtotalAll;
                        $total = $depositall/1.07;
                        $addtax = $depositall-$total;
                        $before = $depositall-$addtax;
                        $balance = $SubtotalAll-$depositall;
                    }
                }
                // dd($deposit);
                $user = User::where('id',$userid)->first();
                $deposit = 0;
                if ($idArray) {
                    $deposit = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้

                    foreach ($idArray as $item) {
                        $data = depositrevenue::whereIn('id', $idArray)->get();

                        $deposit = $data->map(function ($item) {
                            return [
                                'Deposit_ID' => $item->Deposit_ID,
                                'Amount'     => $item->amount,
                            ];
                        });
                    }
                }
                $data= [
                    'date'=>$date,
                    'settingCompany'=>$settingCompany,
                    'Selectdata'=>$Selectdata,
                    'Invoice_ID'=>$datarequest['InvoiceID'],
                    'IssueDate'=>$datarequest['IssueDate'],
                    'Expiration'=>$datarequest['Expiration'],
                    'qrCodeBase64'=>$qrCodeBase64,
                    'Quotation'=>$Quotation,
                    'fullName'=>$fullName,
                    'Address'=>$Address,
                    'TambonID'=>$TambonID,
                    'amphuresID'=>$amphuresID,
                    'provinceNames'=>$provinceNames,
                    'Fax_number'=>$Fax_number,
                    'phone'=>$phone,
                    'Email'=>$Email,
                    'Taxpayer_Identification'=>$Identification,
                    'Day'=>$Day,
                    'Night'=>$Night,
                    'Adult'=>$Adult,
                    'Children'=>$Children,
                    'Checkin'=>$Checkin,
                    'Checkout'=>$Checkout,
                    'Contact_Name'=>$Contact_Name,
                    'Contact_phone'=>$Contact_phone,
                    'Deposit'=>$Deposit,
                    'payment'=>$payment0,
                    'Nettotal'=>$Nettotal,
                    'Subtotal'=>$Subtotal,
                    'total'=>$total,
                    'addtax'=>$addtax,
                    'before'=>$before,
                    'vattype'=>$vattype,
                    'user'=>$user,
                    'deposit'=>$deposit,
                    'depositall'=>$depositall,
                ];
                $template = master_template::query()->latest()->first();
                $view= $template->name;
                $pdf = FacadePdf::loadView('document_invoice.invoicePDF.'.$view,$data);
                $path = 'PDF/invoice/';
                $pdf->save($path . $InvoiceID . '.pdf');
                // return $pdf->stream();
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $InvoiceID;
                $savePDF->QuotationType = 'invoice';
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();

            }
            {
                //save
                $data = $request->all();
                $datarequest = [
                    'Proposal_ID' => $data['QuotationID'] ?? null,
                    'InvoiceID' => $data['InvoiceID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Deposit' => $data['Deposit'] ?? null,
                    'Sum' => $data['sum'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'Payment'=> $data['Payment'] ?? null,
                    'paymentPercent'=> $data['PaymentPercent'] ?? null,
                ];
                $deposit = 0;
                if (!empty($idArray)) {
                    // ดึงข้อมูลครั้งเดียวจาก depositrevenue
                    $data = depositrevenue::whereIn('id', $idArray)->get();

                    // แปลงข้อมูลเป็น array ของ Deposit_ID
                    $deposit = $data->pluck('Deposit_ID')->toArray();

                    // นำ Deposit_ID มาต่อกันเป็น string
                    $formattedProductDataString = implode(' , ', $deposit);
                }

                $count = $datarequest['Proposal_ID'];

                $countin = document_invoices::where('Quotation_ID',$count)->count();
                $sequence = 1;
                if ($countin) {
                    $sequencenumber = $countin+$sequence;
                }else{
                    $sequencenumber = $sequence;
                }
                $NettotalQuotation = Quotation::where('Quotation_ID',$count)->first();
                $NettotalPD = $NettotalQuotation->Nettotal;
                $data = $request->all();
                $type_Proposal = $NettotalQuotation->type_Proposal;

                $userid = Auth::user()->id;
                $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
                $InvoiceID = $datarequest['InvoiceID'] ?? null;
                $Deposit = $datarequest['Deposit'] ?? null;
                $Nettotal = $datarequest['amount'] ?? null;
                $Payment = $datarequest['Payment'] ?? null;
                $Balance = $Nettotal-$Payment;
                $Quotation = Quotation::where('Quotation_ID', $Proposal_ID)->first();
                $Selectdata =  $Quotation->type_Proposal;
                $Company_ID =  $Quotation->Company_ID;
                $save = new document_invoices();
                $save->deposit =$Deposit;
                $save->payment=$Payment;
                $save->balance=$Balance;
                $save->company=$Company_ID;
                $save->Invoice_ID=$InvoiceID;
                $save->Quotation_ID =$Proposal_ID;
                $save->Nettotal = $Nettotal;
                $save->paymentPercent= $datarequest['paymentPercent'];
                $save->IssueDate= $datarequest['IssueDate'];
                $save->Expiration= $datarequest['Expiration'];
                $save->Operated_by = $userid;
                $save->type_Proposal = $Selectdata;
                $save->Refler_ID = $Proposal_ID;
                $save->sequence = $sequencenumber;
                $save->sumpayment = $datarequest['Sum'];
                $save->total = $NettotalPD;
                $save->Deposit_ID = $formattedProductDataString;
                $save->save();
                if (!empty($idArray)) {
                    depositrevenue::whereIn('id', $idArray)->update(['receipt' => 1]);
                }
                return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('invoice.index')->with('error', $e->getMessage());
        }
    }
    public function view($id){
        $invoice = document_invoices::where('id',$id)->first();
        $Deposit = $invoice->deposit;
        $Quotation_ID = $invoice->Quotation_ID;
        $Invoice_IDold = $invoice->Invoice_ID;
        $InvoiceID = $invoice->Invoice_ID;
        $valid = $invoice->valid;
        $IssueDate=$invoice->IssueDate;
        $Expiration=$invoice->Expiration;
        $sequence = $invoice->sequence;
        $userid = $invoice->Operated_by;
        $totalinvoice = $invoice->sumpayment;
        $user = User::where('id',$userid)->first();
        $Quotation =  Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $Selectdata =  $Quotation->type_Proposal;
        $vat_type = $Quotation->vat_type;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $Additional = proposal_overbill::where('Quotation_ID', $QuotationID)->first();
        $Additional_ID = null;
        $Additional_Nettotal = 0;
        if ($Additional) {
            $Additional_ID = $Additional->Additional_ID;
            $Additional_Nettotal = $Additional->Nettotal;
        }
        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->get();

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Deposit_ID = $invoice->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $Deposittotal = depositrevenue::whereIn('Deposit_ID', $array)->sum('payment');

        return view('document_invoice.view',compact('invoice','Selectdata','fullName','Identification','address','Quotation','Additional_Nettotal','totalinvoice','invoices','QuotationID','Additional_ID',
                    'vat_type','settingCompany','IssueDate','Expiration','InvoiceID','phone','Email','Deposit','user','valid','Fax_number','Contact_Name','Contact_phone','Contact_Email','DepositID','Deposittotal'));
    }
    public function edit($id){
        $invoice = document_invoices::where('id',$id)->where('document_status',1)->first();

        $Deposit = $invoice->deposit;
        $Quotation_ID = $invoice->Quotation_ID;
        $Invoice_IDold = $invoice->Invoice_ID;
        $InvoiceID = $invoice->Invoice_ID;
        $Deposit_ID = $invoice->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $Deposittotal = depositrevenue::whereIn('Deposit_ID', $array)->sum('payment');


        $IssueDate=$invoice->IssueDate;
        $Expiration=$invoice->Expiration;
        $sequence = $invoice->sequence;
        $userid = $invoice->Operated_by;
        $user = User::where('id',$userid)->first();
        $Quotation =  Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $Selectdata =  $Quotation->type_Proposal;
        $vat_type = $Quotation->vat_type;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $Additional = proposal_overbill::where('Quotation_ID', $QuotationID)->first();
        $Additional_ID = null;
        $Additional_Nettotal = 0;
        if ($Additional) {
            $Additional_ID = $Additional->Additional_ID;
            $Additional_Nettotal = $Additional->Nettotal;
        }
        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->where('Invoice_ID', '!=', $Invoice_IDold)->get();
        $totalinvoice = 0;
        if ($invoices) {
            foreach ($invoices as $item) {
                $totalinvoice +=  $item->sumpayment;
            }
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Depositlist = depositrevenue::where('Quotation_ID',$QuotationID)->get();
        return view('document_invoice.edit',compact('invoice','Selectdata','fullName','Identification','address','Quotation','Additional_Nettotal','totalinvoice','invoices','QuotationID','Additional_ID',
                    'vat_type','settingCompany','IssueDate','Expiration','InvoiceID','phone','Email','Deposit','user','Contact_Email','Contact_Name','Contact_phone','Fax_number','array','Depositlist'
                    ,'Deposittotal','DepositID'));
    }

    public function update(Request $request ,$id){
        try {
            $invoice = document_invoices::where('id',$id)->where('document_status',1)->first();
            $data =$request->all();
            // dd($data);
            $ids = $request->id;
            $preview = $request->preview;
            $save = $request->save;
            $userid = $invoice->Operated_by;
            $Deposit_ID = $invoice->Deposit_ID;
            $array = array_map('trim', explode(',', $Deposit_ID));
            $deposit = $request->deposit;
            $invoice = document_invoices::where('id',$id)->first();
            $correct = $invoice->correct;
            if ($correct >= 1) {
                $correctup = $correct + 1;
            }else{
                $correctup = 1;
            }



            $dataArray = [

                'Sum' => $invoice['sumpayment'] ?? null,
                'Balance' => $invoice['balance'] ?? null,
                'Nettotal' => $invoice['Nettotal'] ?? null,
                'deposit' =>  $array,
            ];
            $data =$request->all();
            $datarequest = [
                'Proposal_ID' => $data['QuotationID'] ?? null,
                'InvoiceID' => $data['InvoiceID'] ?? null,
                'IssueDate' => $data['IssueDate'] ?? null,
                'Expiration' => $data['Expiration'] ?? null,
                'Deposit' => $data['Deposit'] ?? null,
                'Sum' => $data['sum'] ?? null,
                'Nettotal' => $data['amount'] ?? null,
                'Payment'=> $data['Payment'] ?? null,
                'Balance' => $request->amount - $request->Payment ?? null,
                'paymentPercent'=> $data['PaymentPercent'] ?? null,
                'deposit' =>  $deposit,
            ];
            {
                $keysToCompare = [ 'Sum', 'Balance','Nettotal','deposit'];
                $differences = [];
                foreach ($keysToCompare as $key) {
                    if (isset($dataArray[$key]) && isset($datarequest[$key])) {
                        // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                        $dataArraySet = collect($dataArray[$key]);
                        $dataSet = collect($datarequest[$key]);

                        // หาค่าที่แตกต่างกัน
                        $onlyInDataArray = $dataArraySet->diff($dataSet)->values()->all();
                        $onlyInRequest = $dataSet->diff($dataArraySet)->values()->all();

                        // ตรวจสอบว่ามีค่าที่แตกต่างหรือไม่
                        if (!empty($onlyInDataArray) || !empty($onlyInRequest)) {
                            $differences[$key] = [
                                'dataArray' => $onlyInDataArray,
                                'request' => $onlyInRequest
                            ];
                        }
                    }
                }
                $extractedData = [];

                // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
                foreach ($differences as $key => $value) {
                    if ($key === 'deposit') {
                        // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                        $extractedData[$key] = $value['request'];
                        $extractedDataA[$key] = $value['dataArray'];
                    } elseif (isset($value['request'][0])) {
                        // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                        $extractedData[$key] = $value['request'][0];
                    }else{
                        $extractedDataA[$key] = $value['dataArray'][0];
                    }
                }
                $Sum = $extractedData['Sum'] ?? null;
                $deposit = $extractedData['deposit'] ?? null;
                $Nettotal = $extractedData['amount'] ?? null;
                $Payment = $extractedData['Payment'] ?? null;
                $Balance = $extractedData['Balance'] ?? null;
                $InvoiceID = $datarequest['InvoiceID'] ?? null;
                $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
                $Validcheck = null;
                $formattedProductData = [];
                $depositnew = 0;
                if ($deposit !== 0) {
                        $depositnew = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้
                        foreach ($deposit as $item) {
                            $data = depositrevenue::whereIn('Deposit_ID', $deposit)->get();
                            $depositnew = $data->map(function ($item) {
                                return [
                                    'Deposit_ID' => $item->Deposit_ID,
                                    'Amount'     => $item->amount,
                                ];
                            });
                        }

                    foreach ($depositnew as $item) {
                        $formattedPrice = number_format($item['Amount']).' '.'บาท';
                        $formattedProductData[] = 'Deposit Revenue : ' . $item['Deposit_ID'] . ' , ' . 'Amount : ' . $formattedPrice;
                    }
                }
                $Nettotalcheck = null;
                if ($Nettotal) {
                    $Nettotalcheck = 'ยอดเงินเต็ม : '.number_format($Nettotal). ' บาท';
                }

                $Paymentcheck = null;
                if ($Sum) {
                    $Paymentcheck = 'ยอดเงินที่ชำระ : '. number_format($Sum). ' บาท';
                }

                $Balancecheck = null;
                if ($Balance) {
                    $Balancecheck = 'ยอดเงินคงเหลือชำระ : '.number_format($Balance). ' บาท';
                }
                $fullname = 'รหัส : '.$InvoiceID.' + '.'อ้างอิงจาก : '.$Proposal_ID;
                $datacompany = '';

                $variables = [$fullname, $Nettotalcheck, $Paymentcheck, $Balancecheck, $Validcheck];
                $formattedProductDataString = implode(' + ', $formattedProductData);

                // รวม $formattedProductDataString เข้าไปใน $variables
                $variables[] = $formattedProductDataString;
                foreach ($variables as $variable) {
                    if (!empty($variable)) {
                        if (!empty($datacompany)) {
                            $datacompany .= ' + ';
                        }
                        $datacompany .= $variable;
                    }
                }

                $userids = Auth::user()->id;
                $save = new log_company();
                $save->Created_by = $userids;
                $save->Company_ID = $InvoiceID;
                $save->type = 'Edit';
                $save->Category = 'Edit :: Proposal Invoice ';
                $save->content =$datacompany;
                $save->save();
            }
            {
                //pdf
                $data = $request->all();
                $datarequest = [
                    'Proposal_ID' => $data['QuotationID'] ?? null,
                    'InvoiceID' => $data['InvoiceID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Deposit' => $data['Deposit'] ?? null,
                    'Sum' => $data['sum'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'Payment'=> $data['Payment'] ?? null,
                ];
                $Payment = $datarequest['Payment'] ?? null;
                $Sum = $datarequest['Sum'] ?? null;
                $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();
                $Selectdata =  $Quotation->type_Proposal;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $Address = $Data->Address;
                    $Email = $Data->Email;
                    $Identification = $Data->Identification_Number;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.' '.$First_name.' '.$Last_name;
                    //-------------ที่อยู่
                    $CityID=$Data->City;
                    $amphuresID = $Data->Amphures;
                    $TambonID = $Data->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $Fax_number = '-';
                    $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                    $Contact_Name = null;
                    $Contact_phone =null;
                    $Contact_Email = null;
                }else{
                    $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
                    $Address = $Company->Address;
                    $Email = $Company->Company_Email;
                    $Identification = $Company->Taxpayer_Identification;
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }else{
                            $fullName = $comtype->name_th . $Compannyname;
                        }
                    }
                    $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
                    $prename = $representative->prefix;
                    $Contact_Email = $representative->Email;
                    $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                    $name = $prefix->name_th;
                    $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
                    $CityID=$Company->City;
                    $amphuresID = $Company->Amphures;
                    $TambonID = $Company->Tambon;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $Fax_number =  $company_fax->Fax_number;
                    }else{
                        $Fax_number = '-';
                    }
                    $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                }
                $id = $datarequest['InvoiceID'];
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/cover/document/PDF/$id";

                // Generate the QR code as PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();

                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $date = Carbon::now();
                $date = Carbon::parse($date)->format('d/m/Y');
                $vattype= $Quotation->vat_type;
                $vat_type = master_document::where('id',$vattype)->first();
                $vatname = $vat_type->name_th;
                $Mevent =$Quotation->eventformat;
                $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                $checkin  = $Quotation->checkin;
                $checkout = $Quotation->checkout;
                $Day = $Quotation->day;
                $Night = $Quotation->night;
                $Adult = $Quotation->adult;
                $Children = $Quotation->children;
                $Checkin = $checkin;
                $Checkout = $checkout;

                $Deposit = $datarequest['Deposit'];
                $sum = $Sum;
                $payment=$Payment;

                $Nettotal = floatval(str_replace(',', '', $datarequest['amount']));
                if ($payment) {
                    $payment0 = number_format($payment);
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;
                    $SubtotalAll =0;
                    $deposit = 0;
                    if ($vattype == 51) {
                        $Subtotal = $payment;
                        $SubtotalAll =$payment - $sum;
                        $depositall = $payment - $SubtotalAll;
                        $total = $depositall;
                        $addtax = 0;
                        $before = $depositall;
                        $balance = $depositall;
                    }else{
                        $Subtotal = $payment;
                        $SubtotalAll = $Subtotal-$sum;
                        $depositall = $payment - $SubtotalAll;
                        $total = $depositall/1.07;
                        $addtax = $depositall-$total;
                        $before = $depositall-$addtax;
                        $balance = $SubtotalAll-$depositall;
                    }
                }
                // dd($deposit);
                $depositnew = $request->deposit;
                $user = User::where('id',$userid)->first();
                $deposit = 0;
                if ($depositnew) {
                    $deposit = []; // กำหนดให้เป็นอาร์เรย์ก่อนใช้

                    foreach ($depositnew as $item) {
                        $data = depositrevenue::whereIn('Deposit_ID', $depositnew)->get();

                        $deposit = $data->map(function ($item) {
                            return [
                                'Deposit_ID' => $item->Deposit_ID,
                                'Amount'     => $item->amount,
                            ];
                        });
                    }
                }
                $data= [
                    'date'=>$date,
                    'settingCompany'=>$settingCompany,
                    'Selectdata'=>$Selectdata,
                    'Invoice_ID'=>$datarequest['InvoiceID'],
                    'IssueDate'=>$datarequest['IssueDate'],
                    'Expiration'=>$datarequest['Expiration'],
                    'qrCodeBase64'=>$qrCodeBase64,
                    'Quotation'=>$Quotation,
                    'fullName'=>$fullName,
                    'Address'=>$Address,
                    'TambonID'=>$TambonID,
                    'amphuresID'=>$amphuresID,
                    'provinceNames'=>$provinceNames,
                    'Fax_number'=>$Fax_number,
                    'phone'=>$phone,
                    'Email'=>$Email,
                    'Taxpayer_Identification'=>$Identification,
                    'Day'=>$Day,
                    'Night'=>$Night,
                    'Adult'=>$Adult,
                    'Children'=>$Children,
                    'Checkin'=>$Checkin,
                    'Checkout'=>$Checkout,
                    'Contact_Name'=>$Contact_Name,
                    'Contact_phone'=>$Contact_phone,
                    'Deposit'=>$Deposit,
                    'payment'=>$payment0,
                    'Nettotal'=>$Nettotal,
                    'Subtotal'=>$Subtotal,
                    'total'=>$total,
                    'addtax'=>$addtax,
                    'before'=>$before,
                    'vattype'=>$vattype,
                    'user'=>$user,
                    'deposit'=>$deposit,
                    'depositall'=>$depositall,
                ];
                $template = master_template::query()->latest()->first();
                $view= $template->name;
                $pdf = FacadePdf::loadView('document_invoice.invoicePDF.'.$view,$data);
                $path = 'PDF/invoice/';

                $pdf->save($path . $InvoiceID.'-'.$correctup . '.pdf');
                // return $pdf->stream();
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $InvoiceID;
                $savePDF->QuotationType = 'invoice';
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->correct = $correctup;
                $savePDF->save();

            }
            {
                $data = $request->all();

                $depositDATA = 0;
                if (!empty($request->deposit)) {
                    // ดึงข้อมูลครั้งเดียวจาก depositrevenue
                    $depositDATA = depositrevenue::whereIn('Deposit_ID', $request->deposit)->get();

                    // แปลงข้อมูลเป็น array ของ Deposit_ID
                    $depositMAIN = $depositDATA->pluck('Deposit_ID')->toArray();

                    // นำ Deposit_ID มาต่อกันเป็น string
                    $DATA = implode(' , ', $depositMAIN);
                }
                $save = document_invoices::find($ids);
                $save->payment = $request->Payment;
                $save->paymentPercent = $request->PaymentPercent;
                $save->balance = $request->amount - $request->Payment;
                $save->sumpayment = $request->sum;
                $save->Nettotal = $request->amount;
                $save->correct = $correctup;
                $save->Deposit_ID = $DATA;
                $save->save();

                depositrevenue::whereIn('Deposit_ID', $request->deposit)->update(['receipt' => 1]);
            }


            return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('invoice.index')->with('error', $e->getMessage());
        }
    }
    public function sheetpdf(Request $request ,$id) {
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->Quotation_ID;
        $vattype= $Quotation->vat_type;
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'DiscountAmount' => $Quotation['SpecialDiscountBath'] ?? null,
            'comment' => $Quotation['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
            'userid'=> $Quotation['Operated_by'] ?? null,
        ];
        $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
        $quantities = $selectproduct->pluck('Quantity')->toArray();
        $discounts = $selectproduct->pluck('discount')->toArray();
        $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
        $Unitmain = $selectproduct->pluck('Unit')->toArray();
        $productItems = [];
        $totaldiscount = [];
        foreach ($Products as $index => $productID) {
            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts) && count($priceUnits) === count($Unitmain)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                $totaldiscount = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $unitValue = intval($Unitmain[$i]); // เปลี่ยนชื่อเป็น $unitValue
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totaldiscount0 = (($priceUnit * $discount)/100);
                    $totaldiscount[] = $totaldiscount0;

                    $totalPrice = ($quantity * $unitValue) * $priceUnit;
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount) / 100);
                    $discountedPrices[] = $discountedPrice;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;

                }
            }

            $items = master_product_item::where('Product_ID', $productID)->get();
            $QuotationVat= $datarequest['Mvat'];
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
            foreach ($items as $item) {
                // ตรวจสอบและกำหนดค่า quantity และ discount
                $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                $unitValue = isset($Unitmain[$index]) ? $Unitmain[$index] : 0;
                $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                $productItems[] = [
                    'product' => $item,
                    'quantity' => $quantity,
                    'unit' => $unitValue,
                    'discount' => $discount,
                    'totalPrices'=>$totalPrices,
                    'discountedPrices'=>$discountedPrices,
                    'discountedPricestotal'=>$discountedPricestotal,
                    'totaldiscount'=>$totaldiscount,
                ];
            }

        }
        {//คำนวน
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;

            $SpecialDistext = $datarequest['DiscountAmount'];
            $SpecialDis = floatval($SpecialDistext);
            $totalguest = 0;
            $totalguest = $datarequest['Adult'] + $datarequest['Children'];
            $guest = $datarequest['PaxToTalall'];
            if ($Mvat->id == 50) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;

                }
            }
            elseif ($Mvat->id == 51) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;

                }
            }
            elseif ($Mvat->id == 52) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $AddTax = $subtotal*7/100;
                    $Nettotal = $subtotal+$AddTax;
                    $totalaverage =$Nettotal/$guest;
                }
            }else
            {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;
                }
            }
            $pagecount = count($productItems);
            $page = $pagecount/10;

            $page_item = 1;
            if ($page > 1.1 && $page < 2.1) {
                $page_item += 1;

            } elseif ($page > 1.1) {
            $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
            }
        }
        {//QRCODE
            $id = $datarequest['Proposal_ID'];
            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
        }
        $userid = $datarequest['userid'];
        $Proposal_ID = $datarequest['Proposal_ID'];
        $IssueDate = $datarequest['IssueDate'];
        $Expiration = $datarequest['Expiration'];
        $Selectdata = $datarequest['Selectdata'];
        $Data_ID = $datarequest['Data_ID'];
        $Adult = $datarequest['Adult'];
        $Children = $datarequest['Children'];
        $Mevent = $datarequest['Mevent'];
        $Mvat = $datarequest['Mvat'];
        $DiscountAmount = $datarequest['DiscountAmount'];
        $Checkin = $datarequest['Checkin'];
        $Checkout = $datarequest['Checkout'];
        $Day = $datarequest['Day'];
        $Night = $datarequest['Night'];
        $comment = $datarequest['comment'];
        $user = User::where('id',$userid)->select('id','name')->first();
        $fullName = null;
        $Contact_Name = null;
        $Contact_phone =null;
        $Contact_Email = null;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Data_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Taxpayer_Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Data_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Taxpayer_Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Data_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = $representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
        }
        $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
        $template = master_template::query()->latest()->first();
        $CodeTemplate = $template->CodeTemplate;
        $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
        $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
        $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
        $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
        $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
        $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
        $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
        $date = Carbon::now();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        if ($Checkin) {
            $checkin =$Checkin;
            $checkout = $Checkout;
        }else{
            $checkin = '-';
            $checkout = '-';
        }
        $data = [
            'settingCompany'=>$settingCompany,
            'page_item'=>$page_item,
            'page'=>$pagecount,
            'Selectdata'=>$Selectdata,
            'date'=>$date,
            'fullName'=>$fullName,
            'provinceNames'=>$provinceNames,
            'Address'=>$Address,
            'amphuresID'=>$amphuresID,
            'TambonID'=>$TambonID,
            'Email'=>$Email,
            'phone'=>$phone,
            'Fax_number'=>$Fax_number,
            'Day'=>$Day,
            'Night'=>$Night,
            'Checkin'=>$checkin,
            'Checkout'=>$checkout,
            'eventformat'=>$eventformat,
            'totalguest'=>$totalguest,
            'Reservation_show'=>$Reservation_show,
            'Paymentterms'=>$Paymentterms,
            'note'=>$note,
            'Cancellations'=>$Cancellations,
            'Complimentary'=>$Complimentary,
            'All_rights_reserved'=>$All_rights_reserved,
            'Proposal_ID'=>$Proposal_ID,
            'IssueDate'=>$IssueDate,
            'Expiration'=>$Expiration,
            'qrCodeBase64'=>$qrCodeBase64,
            'user'=>$user,
            'Taxpayer_Identification'=>$Taxpayer_Identification,
            'Adult'=>$Adult,
            'Children'=>$Children,
            'totalAmount'=>$totalAmount,
            'SpecialDis'=>$SpecialDis,
            'subtotal'=>$subtotal,
            'beforeTax'=>$beforeTax,
            'Nettotal'=>$Nettotal,
            'totalguest'=>$totalguest,
            'guest'=>$guest,
            'totalaverage'=>$totalaverage,
            'AddTax'=>$AddTax,
            'productItems'=>$productItems,
            'unit'=>$unit,
            'quantity'=>$quantity,
            'Mvat'=>$Mvat,
            'comment'=>$comment,
            'Mevent'=>$Mevent,
            'Contact_Name'=>$Contact_Name,
            'Contact_phone'=>$Contact_phone,
            'Contact_Email'=>$Contact_Email,
            'SpecialDistext'=>$SpecialDistext,
            'vattype'=>$vattype,
        ];
        $view= $template->name;
        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
        return $pdf->stream();


    }
    public function GenerateRe($id){
        $document = document_invoices::where('id',$id)->first();
        $Quotation_ID = $document->Quotation_ID;
        $InvoiceID = $document->Invoice_ID;
        $correct = $document->correct;
        $save = document_invoices::find($id);
        $save->document_status = 2;
        $save->save();

        $userids = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userids;
        $save->Company_ID = $InvoiceID;
        $save->type = 'Generate';
        $save->Category = 'Generate :: Proforma Invoice ';
        $save->content = 'Document Proforma Invoice ID : '.$InvoiceID.' to Receipt Payment';
        $save->save();
        return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
    }
    public function Delete($id){
        $document = document_invoices::where('id',$id)->first();
        $Quotation_ID = $document->Invoice_ID;
        $Quotation = $document->Quotation_ID;
        $document_status = $document->document_status;
        $userid = Auth::user()->id;
        $savelogin = new log_company();
        $savelogin->Created_by = $userid;
        $savelogin->Company_ID = $Quotation_ID;
        $savelogin->type = 'Cancel';
        $savelogin->Category = 'Cancel :: Invoice';
        $savelogin->content = 'Cancel Document Invoice ID : '.$Quotation_ID.'+'.'Based on : '.$Quotation ;
        $savelogin->save();
        if ($document_status == 1) {
            $quotation = document_invoices::find($id);
            $quotation->document_status = 0;
            $quotation->save();
        }
        return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
    }
    // public function cancel(Request $request ,$id){
    //     $data = Quotation::where('id',$id)->first();
    //     $Quotation_ID = $data->Quotation_ID;
    //     $userid = Auth::user()->id;
    //     try {

    //         if ($data->status_document == 1) {
    //             $Quotation = Quotation::find($id);
    //             $Quotation->status_document = 0;
    //             $Quotation->remark = $request->note;
    //             $Quotation->save();
    //         }elseif ($data->status_document == 3) {
    //             $Quotation = Quotation::find($id);
    //             $Quotation->status_document = 0;
    //             $Quotation->remark = $request->note;
    //             $Quotation->save();
    //         }elseif ($data->status_document == 6) {
    //             if ($data->additional_discount > 0 || $data->SpecialDiscountBath > 0) {
    //                 $Quotation = Quotation::find($id);
    //                 $Quotation->status_document = 3;
    //                 $Quotation->remark = $request->note;
    //                 $Quotation->save();
    //             }else{
    //                 $Quotation = Quotation::find($id);
    //                 $Quotation->status_document = 1;
    //                 $Quotation->remark = $request->note;
    //                 $Quotation->save();
    //             }
    //         }elseif ($data->status_document == 5) {
    //             $Quotation = Quotation::find($id);
    //             $Quotation->status_document = 6;
    //             $Quotation->remark = $request->note;
    //             $Quotation->save();
    //         }

    //     } catch (\Throwable $e) {
    //         return redirect()->route('invoice.index')->with('error', $e->getMessage());
    //     }


    //     try {
    //         $savelog = new log_company();
    //         $savelog->Created_by = $userid;
    //         $savelog->Company_ID = $Quotation_ID;
    //         $savelog->type = 'Cancel';
    //         $savelog->Category = 'Cancel :: Proposal';
    //         $savelog->content = 'Cancel Document Proposal ID : '.$Quotation_ID.'+'.$request->note;
    //         $savelog->save();
    //     } catch (\Throwable $e) {
    //         return redirect()->route('invoice.index')->with('error', $e->getMessage());
    //     }
    //     return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
    // }
    public function LOG($id)
    {
        $invoice = document_invoices::where('id', $id)->first();
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($invoice) {
            $Invoice_ID = $invoice->Invoice_ID;
            $correct = $invoice->correct;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PI-\d{8})/', $Invoice_ID, $matches)) {
                $InvoiceID = $matches[1];
            }
        }

        $log = log::where('Quotation_ID',$InvoiceID)->get();
        $path = 'PDF/invoice/';
        $loginvoice = log_company::where('Company_ID', $InvoiceID)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('document_invoice.document',compact('log','path','correct','loginvoice','Invoice_ID'));
    }
    public function export(Request $request,$id){
        $Invoice = document_invoices::where('id',$id)->first();
        $datarequest = [
            'Proposal_ID' => $Invoice['Quotation_ID'] ?? null,
            'InvoiceID' => $Invoice['Invoice_ID'] ?? null,
            'Refler_ID' => $Invoice['Refler_ID'] ?? null,
            'IssueDate' => $Invoice['IssueDate'] ?? null,
            'Expiration' => $Invoice['Expiration'] ?? null,
            'Selectdata' => $Invoice['type_Proposal'] ?? null,
            'Valid' => $Invoice['valid'] ?? null,
            'Deposit' => $Invoice['deposit'] ?? null,
            'Payment' => $Invoice['payment'] ?? null,
            'Nettotal' => $Invoice['Nettotal'] ?? null,
            'Company' => $Invoice['company'] ?? null,
            'Balance' => $Invoice['balance'] ?? null,
            'Sum' => $Invoice['sumpayment'] ?? null,
            'PaymentPercent'=> $Invoice['paymentPercent'] ?? null,
        ];
        if ($datarequest['Selectdata'] == 'Company') {
            $Data_ID = $datarequest['Company'];
            $Company = companys::where('Profile_ID',$Data_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Taxpayer_Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $comtypefullname = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $comtypefullname = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Data_ID)->where('status',1)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = $representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $company_phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
        }else{
            $Data_ID = $datarequest['Company'];
            $Company = Guest::where('Profile_ID',$Data_ID)->first();
            $prename = $Company->preface;
            $First_name = $Company->First_name;
            $Last_name = $Company->Last_name;
            $Address = $Company->Address;
            $Email = $Company->Email;
            $Taxpayer_Identification = $Company->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $comtypefullname = $name.' '.$First_name.' '.$Last_name;
            $profilecontact = 0;
            $Contact_phone=0;
            $company_fax =0;
            $Contact_Name =0;
            //-------------ที่อยู่
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $company_phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
        }
        $id = $datarequest['InvoiceID'];
        $protocol = $request->secure() ? 'https' : 'http';
        $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/Quotation/cover/document/PDF/$id";
        $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
        $qrCodeBase64 = base64_encode($qrCodeImage);

        $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $date = Carbon::now();
        $date = Carbon::parse($date)->format('d/m/Y');
        $vattype= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $vatname = $vat_type->name_th;
        $checkin  = $Quotation->checkin;
        $checkout = $Quotation->checkout;
        $Day = $Quotation->day;
        $Night = $Quotation->night;
        $Adult = $Quotation->adult;
        $Children = $Quotation->children;
        $Checkin = $checkin;
        $Checkout = $checkout;
        $valid = $datarequest['Valid'];
        $Deposit = $datarequest['Deposit'];
        $payment=$datarequest['Payment'];
        $Nettotal = floatval(str_replace(',', '', $datarequest['Nettotal']));
        if ($payment) {
            $payment0 = number_format($payment);
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $payment;
                $total = $payment;
                $addtax = 0;
                $before = $payment;
                // $balance = $Nettotal-$Subtotal;
                $balance = $Subtotal;
            }else{
                $Subtotal = $payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }

        }
        $paymentPercent=$datarequest['PaymentPercent'];
        if ($paymentPercent) {
            $payment0 = $paymentPercent.'%';
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            $Nettotal = floatval(str_replace(',', '', $datarequest['Nettotal']));
            $paymentPercent = floatval($paymentPercent);
            if ($vattype == 51) {
                $Subtotal = ($Nettotal*$paymentPercent)/100;
                $total = $Subtotal;
                $addtax = 0;
                $before = $Subtotal;
                // $balance = $Nettotal-$Subtotal;
                $balance = $Subtotal;

            }else{
                $Subtotal = ($Nettotal*$paymentPercent)/100;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }

        }
        $balanceold =$request->balance;
        $data= [
            'date'=>$date,
            'settingCompany'=>$settingCompany,
            'Selectdata'=>$datarequest['Selectdata'],
            'Invoice_ID'=>$datarequest['InvoiceID'],
            'IssueDate'=>$datarequest['IssueDate'],
            'Expiration'=>$datarequest['Expiration'],
            'qrCodeBase64'=>$qrCodeBase64,
            'Quotation'=>$Quotation,
            'fullName'=>$comtypefullname,
            'Address'=>$Address,
            'TambonID'=>$TambonID,
            'amphuresID'=>$amphuresID,
            'provinceNames'=>$provinceNames,
            'Fax_number'=>$Fax_number,
            'phone'=>$company_phone,
            'Email'=>$Email,
            'Taxpayer_Identification'=>$Taxpayer_Identification,
            'Day'=>$Day,
            'Night'=>$Night,
            'Adult'=>$Adult,
            'Children'=>$Children,
            'Checkin'=>$Checkin,
            'Checkout'=>$Checkout,
            'valid'=>$valid,
            'Contact_Name'=>$Contact_Name,
            'Contact_phone'=>$Contact_phone,
            'balance'=>$balance,
            'Deposit'=>$Deposit,
            'payment'=>$payment0,
            'Nettotal'=>$Nettotal,
            'Subtotal'=>$Subtotal,
            'total'=>$total,
            'addtax'=>$addtax,
            'before'=>$before,
            'balanceold'=>$balanceold,
            'vattype'=>$vattype,
        ];
        $template = master_template::query()->latest()->first();
        $view= $template->name;
        $pdf = FacadePdf::loadView('invoicePDF.'.$view,$data);
        return $pdf->stream();
    }

     //----------------------------ส่งอีเมล์---------------------
    public function viewinvoice($id){
        $invoice =document_invoices::where('id',$id)->first();
        $idss =  $invoice->id;
        $id = $idss;
        $Deposit = $invoice->deposit;
        $Quotation_ID = $invoice->Quotation_ID;
        $Invoice_IDold = $invoice->Invoice_ID;
        $InvoiceID = $invoice->Invoice_ID;
        $valid = $invoice->valid;
        $IssueDate=$invoice->IssueDate;
        $Expiration=$invoice->Expiration;
        $sequence = $invoice->sequence;
        $userid = $invoice->Operated_by;
        $totalinvoice = $invoice->sumpayment;
        $user = User::where('id',$userid)->first();
        $Quotation =  Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $Selectdata =  $Quotation->type_Proposal;
        $vat_type = $Quotation->vat_type;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $Additional = proposal_overbill::where('Quotation_ID', $QuotationID)->first();
        $Additional_ID = null;
        $Additional_Nettotal = 0;
        if ($Additional) {
            $Additional_ID = $Additional->Additional_ID;
            $Additional_Nettotal = $Additional->Nettotal;
        }
        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Deposit_ID = $invoice->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $Deposittotal = depositrevenue::whereIn('Deposit_ID', $array)->sum('payment');
        return view('document_invoice.viewinvoice',compact('invoice','Selectdata','fullName','Identification','address','Quotation','Additional_Nettotal','totalinvoice','invoices','QuotationID','Additional_ID',
                    'vat_type','settingCompany','IssueDate','Expiration','InvoiceID','phone','Email','Deposit','user','valid','id','Fax_number','Contact_Name','Contact_phone','Contact_Email','DepositID','Deposittotal'));
    }
    public function email($id){
        $quotation = document_invoices::where('id',$id)->first();
        $comid = $quotation->company;
        $Quotation_ID= $quotation->Invoice_ID;
        $type_Proposal = $quotation->type_Proposal;
        $comtypefullname = null;
        $userid = Auth::user()->id;
        $username = User::where('id',$userid)->first();
        $nameuser = $username->firstname.' '.$username->lastname;
        $teluser = $username->tel;
        if ($type_Proposal == 'Guest') {
            $companys = Guest::where('Profile_ID',$comid)->first();
            $emailCom = $companys->Email;
            $namefirst = $companys->First_name;
            $namelast = $companys->Last_name;
            $name = $namefirst.' '.$namelast;
        }else{
            $companys = companys::where('Profile_ID',$comid)->first();
            $emailCom = $companys->Company_Email;
            $contact = $companys->Profile_ID;
            $Contact_name = representative::where('Company_ID',$contact)->where('status',1)->first();
            $namefirst = $Contact_name->First_name;
            $namelast = $Contact_name->Last_name;
            $name = $namefirst.' '.$namelast;
            $Company_typeID=$companys->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $comtypefullname = "Company : "." บริษัท ". $companys->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $comtypefullname = "Company : "." บริษัท ". $companys->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $comtypefullname = "Company : "." ห้างหุ้นส่วนจำกัด ". $companys->Company_Name ;
            }else {
                $comtypefullname = $comtype->name_th . $companys->Company_Name;
            }
        }

        $Checkin = $quotation->checkin;
        $Checkout = $quotation->checkout;
        if ($Checkin) {
            $checkin = $Checkin.' '.'-'.'';
            $checkout = $Checkout;
        }else{
            $checkin = 'No Check in date';
            $checkout = ' ';
        }
        $day =$quotation->day;
        $night= $quotation->night;
        if ($day == null) {
            $day = ' ';
            $night = ' ';
        }else{
            $day = '( '.$day.' วัน';
            $night =$night.' คืน'.' )';
        }
        $promotiondata = master_promotion::where('status', 1)->where('type', 'Link')->select('name','type')->get();
        $promotions = [];
        foreach ($promotiondata as $promo) {
            $promotions[] = 'Link : ' . $promo->name;
        }

        return view('document_invoice.email.index',compact('emailCom','Quotation_ID','name','comtypefullname','checkin','checkout','night','day','promotions',
                        'quotation','type_Proposal','nameuser','teluser'));
    }

    public function sendemail(Request $request,$id){
        try {

            $file = $request->all();

            $quotation = document_invoices::where('id',$id)->first();

            $QuotationID = $quotation->Invoice_ID;
            $correct = $quotation->correct;
            $type_Proposal = $quotation->type_Proposal;
            $path = 'PDF/invoice/';
            if ($correct > 0) {
                $pdf = $path.$QuotationID.'-'.$correct;
                $pdfPath = $path.$QuotationID.'-'.$correct.'.pdf';
            }else{
                $pdf = $path.$QuotationID;
                $pdfPath = $path.$QuotationID.'.pdf';
            }
            if ($type_Proposal == 'Company') {
                $comid = $quotation->company;
                $Quotation_ID= $quotation->Invoice_ID;
                $companys = companys::where('Profile_ID',$comid)->first();
                $emailCom = $companys->Company_Email;
                $contact = $companys->Profile_ID;
                $Contact_name = representative::where('Company_ID',$contact)->where('status',1)->first();
                $emailCon = $Contact_name->Email;
            }else{
                $comid = $quotation->company;
                $Quotation_ID= $quotation->Invoice_ID;
                $companys = Guest::where('Profile_ID',$comid)->first();
                $emailCon = $companys->Email;
            }
            $Title = $request->tital;
            $detail = $request->detail;
            $comment = $request->Comment;
            $email = $request->email;
            $promotiondata = master_promotion::where('status', 1)->select('name','type')->get();


            $promotions = [];
            foreach ($promotiondata as $promo) {
                if ($promo->type == 'Document') {
                    $promotion_path = 'promotion/';
                    $promotions[] = $promotion_path . $promo->name;
                }
            }
            $fileUploads = $request->file('files'); // ใช้ 'files' ถ้าฟิลด์ในฟอร์มเป็น 'files[]'

            // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
            if ($fileUploads) {
                $filePaths = [];
                foreach ($fileUploads as $file) {
                    $filename = $file->getClientOriginalName();
                    $file->move(public_path($path), $filename);
                    $filePaths[] = public_path($path . $filename);
                }
            } else {
                // หากไม่มีไฟล์ที่อัปโหลด ให้กำหนด $filePaths เป็นอาร์เรย์ว่าง
                $filePaths = [];
            }

            $Data = [
                'title' => $Title,
                'detail' => $detail,
                'comment' => $comment,
                'email' => $email,
                'pdfPath'=>$pdfPath,
                'pdf'=>$pdf,
            ];

            $customEmail = new QuotationEmail($Data,$Title,$pdfPath,$filePaths,$promotions);
            Mail::to($emailCon)->send($customEmail);
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Quotation_ID;
            $save->type = 'Send Email';
            $save->Category = 'Send Email :: Proforma Invoice';
            $save->content = 'Send Email Document Proforma Invoice ID : '.$Quotation_ID;
            $save->save();
            return redirect()->route('invoice.index')->with('success', 'บันทึกข้อมูลและส่งอีเมลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('invoice.index')->with('error', $e->getMessage());
        }
    }
    public function Revise($id){
        try {
            $data = document_invoices::where('id',$id)->first();
            $Quotation_ID = $data->Quotation_ID;
            $Quotation = document_invoices::find($id);
            $Quotation->document_status = 1;
            $Quotation->save();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Quotation_ID;
            $save->type = 'Revise';
            $save->Category = 'Revise :: Proforma Invoice';
            $save->content = 'Revise Document Proforma Invoice ID : '.$Quotation_ID;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('invoice.index')->with('error', $e->getMessage());
        }
        return redirect()->route('invoice.index')->with('success', 'Data has been successfully saved.');
    }
    public function getproposal(Request $request){
        $Approved = Quotation::query()
        ->leftJoin('document_invoice', 'quotation.Quotation_ID', '=', 'document_invoice.Quotation_ID')
        ->leftJoin('proposal_overbill', 'quotation.Quotation_ID', '=', 'proposal_overbill.Quotation_ID')
        ->where('quotation.status_document', 6)
        ->select(
            'quotation.*',
            'document_invoice.Quotation_ID as QID',
            'document_invoice.document_status',
            'proposal_overbill.Nettotal as Adtotal',  // Separate this field for clarity
            DB::raw('1 as status'),
            DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status = 2 THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
            DB::raw('SUM(CASE WHEN document_invoice.document_status >= 1 THEN 1 ELSE 0 END) as invoice_count')
        )
        ->groupBy('quotation.Quotation_ID','quotation.Operated_by','quotation.status_document')
        ->get();
        $invoicecheck = document_invoices::all(); // ดึงข้อมูลทั้งหมดจาก table document_invoices
        $Pending = document_invoices::query()->where('document_status',1)->get();
        $data = $Approved->map(function($item, $key) use ($invoicecheck,$Pending) {
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($rolePermission > 0) {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/list/' . $item->id) . '">View Invoice</a></li>';
                }

                if (($rolePermission == 1 || ($rolePermission == 2 && $item->Operated_by == $CreateBy)) && $canEditProposal == 1) {
                    if (!empty($Pending) && $Pending->count() == 0) {
                        if ($item->Nettotal - $item->total_payment != 0 && $item->Nettotal + $item->Adtotal - $item->total_payment != 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $item->id) . '">Create</a></li>';
                        }
                        if ($item->invoice_count == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $item->id . ')">Cancel</a></li>';
                        }
                    } else {
                        $hasStatusReceiveZero = false;
                        foreach ($invoicecheck as $key2 => $item2) {
                            if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0) {
                                $hasStatusReceiveZero = true;
                                break;
                            }
                        }
                        if (!$hasStatusReceiveZero && $item->Nettotal - $item->total_payment != 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $item->id) . '">Create</a></li>';
                        }
                    }
                } elseif ($rolePermission == 2 || $rolePermission == 3 && $canEditProposal == 1) {
                    if (!empty($Pending) && $Pending->count() == 0) {
                        if ($item->Nettotal - $item->total_payment != 0 && $item->Nettotal + $item->Adtotal - $item->total_payment != 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $item->id) . '">Create</a></li>';
                        }
                        if ($item->invoice_count == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $item->id . ')">Cancel</a></li>';
                        }
                    } else {
                        $hasStatusReceiveZero = false;
                        foreach ($invoicecheck as $key2 => $item2) {
                            if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0) {
                                $hasStatusReceiveZero = true;
                                break;
                            }
                        }
                        if (!$hasStatusReceiveZero && $item->Nettotal - $item->total_payment != 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $item->id) . '">Create</a></li>';
                        }
                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/list/' . $item->id) . '">View Invoice</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';
            if ($item->invoice_count == 0) {
                $btn_status = '<span class="badge rounded-pill "style="background-color: #64748b">Create Invoice</span>';
            }else{
                $btn_status = '<span class="badge rounded-pill " style="background-color: #64748b">Pending</span>';
            }
            return [
                'no' => $key + 1,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->invoice_count,
                'pd_amount' => number_format($item->Nettotal + $item->Adtotal, 2),
                'pi_amount' => $item->total_payment == 0 ? 0 : number_format($item->total_payment, 2),
                'balance' => number_format($item->Nettotal + $item->Adtotal - $item->total_payment, 2),
                'status' => $btn_status,
                'action' => $btn_action
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
    public function getallTable(Request $request){
        $invoice = document_invoices::query()->get();

        $data = $invoice->map(function($item, $key){
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($canViewProposal == 1) {
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
            }

            if ($rolePermission > 0) {
                if ($rolePermission == 1 && $item->Operated_by == $CreateBy) {
                    if ($canEditProposal == 1) {
                        if ($item->document_status == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                        }
                        if ($item->document_status == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                        }
                        if ($item->document_status == 2) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                        }
                    }
                } elseif ($rolePermission == 2) {
                    if ($item->Operated_by == $CreateBy) {
                        if ($canEditProposal == 1) {
                            if ($item->document_status == 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                            }
                            if ($item->document_status == 1) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                            }
                            if ($item->document_status == 2) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                            }
                        }
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal == 1) {
                        if ($item->document_status == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                        }
                        if ($item->document_status == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                        }
                        if ($item->document_status == 2) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                        }

                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';
            if ($item->document_status == 1) {
                $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
            }elseif ($item->document_status == 2) {
                $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
            }elseif ($item->document_status == 0) {
                $btn_status = '<span class="badge rounded-pill  bg-danger" >Cancel</span>';
            }
            return [
                'no' => $key + 1,
                'invoice_id' => $item->Invoice_ID,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company00->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->IssueDate,
                'pd_amount' =>  number_format($item->sumpayment, 2),
                'pi_amount' => $item->userOperated->name ?? 'Auto',
                'status' =>  $btn_status,
                'action' => $btn_action
            ];
        });
        return response()->json([
            'data' => $data
        ]);
    }
    public function PendingTable(Request $request){
        $Pending = document_invoices::query()->where('document_status',1)->get();

        $data = $Pending->map(function($item, $key){
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($canViewProposal == 1) {
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
            }

            if ($rolePermission > 0) {
                if ($rolePermission == 1 && $item->Operated_by == $CreateBy) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                } elseif ($rolePermission == 2) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $item->id) . '">Edit</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $item->id) . '">Generate</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';
            if ($item->document_status == 1) {
                $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
            }
            return [
                'no' => $key + 1,
                'invoice_id' => $item->Invoice_ID,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company00->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->IssueDate,
                'pd_amount' =>  number_format($item->sumpayment, 2),
                'pi_amount' => $item->userOperated->name ?? 'Auto',
                'status' =>  $btn_status,
                'action' => $btn_action
            ];
        });
        return response()->json([
            'data' => $data
        ]);
    }
    public function ApprovedTable(Request $request){
        $Generate = document_invoices::query()->where('document_status',2)->get();

        $data = $Generate->map(function($item, $key){
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($canViewProposal == 1) {
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
            }

            if ($rolePermission > 0) {
                if ($rolePermission == 1 && $item->Operated_by == $CreateBy) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                } elseif ($rolePermission == 2) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Delete(' . $item->id . ')">Cancel</a></li>';
                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';

            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';

            return [
                'no' => $key + 1,
                'invoice_id' => $item->Invoice_ID,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company00->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->IssueDate,
                'pd_amount' =>  number_format($item->sumpayment, 2),
                'pi_amount' => $item->userOperated->name ?? 'Auto',
                'status' =>  $btn_status,
                'action' => $btn_action
            ];
        });
        return response()->json([
            'data' => $data
        ]);
    }
    public function CancelTable(Request $request){
        $Cancel = document_invoices::query()->where('document_status',0)->get();

        $data = $Cancel->map(function($item, $key){
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($canViewProposal == 1) {
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
            }

            if ($rolePermission > 0) {
                if ($rolePermission == 1 && $item->Operated_by == $CreateBy) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                    }
                } elseif ($rolePermission == 2) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revise(' . $item->id . ')">Revise</a></li>';
                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';

            $btn_status = '<span class="badge rounded-pill  bg-danger" >Cancel</span>';

            return [
                'no' => $key + 1,
                'invoice_id' => $item->Invoice_ID,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company00->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->IssueDate,
                'pd_amount' =>  number_format($item->sumpayment, 2),
                'pi_amount' => $item->userOperated->name ?? 'Auto',
                'status' =>  $btn_status,
                'action' => $btn_action
            ];
        });
        return response()->json([
            'data' => $data
        ]);
    }
    public function CompleteTable(Request $request){
        $Complete = document_invoices::query()->where('document_status',3)->get();

        $data = $Complete->map(function($item, $key){
            $CreateBy = Auth::user()->id;
            $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
            $canViewProposal = Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
            $canEditProposal = Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);

            // สร้างปุ่ม Action
            $btn_action = '<div class="dropdown">';
            $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
            $btn_action .= '<ul class="dropdown-menu">';

            if ($canViewProposal == 1) {
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
            }

            if ($rolePermission > 0) {
                if ($rolePermission == 1 && $item->Operated_by == $CreateBy) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                    }
                } elseif ($rolePermission == 2) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/viewinvoice/' . $item->id) . '">Send Email</a></li>';
                    }
                }
            } else {
                if ($canViewProposal == 1) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $item->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $item->id) . '">LOG</a></li>';
                }
            }

            $btn_action .= '</ul>';
            $btn_action .= '</div>';

            $btn_status = '<span class="badge rounded-pill  bg-danger" >Cancel</span>';

            return [
                'no' => $key + 1,
                'invoice_id' => $item->Invoice_ID,
                'quotation_id' => $item->Quotation_ID,
                'company_name' => $item->type_Proposal == 'Company' ? $item->company00->Company_Name : $item->guest->First_name . ' ' . $item->guest->Last_name,
                'pi_doc' => $item->IssueDate,
                'pd_amount' =>  number_format($item->sumpayment, 2),
                'pi_amount' => $item->userOperated->name ?? 'Auto',
                'status' =>  $btn_status,
                'action' => $btn_action
            ];
        });
        return response()->json([
            'data' => $data
        ]);
    }
}

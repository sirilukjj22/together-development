<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\depositrevenue;
use Illuminate\Http\Request;
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
use App\Models\master_document_sheet;
use App\Models\log_company;
use App\Models\master_promotion;
use Illuminate\Support\Arr;
use App\Models\log;
use App\Models\Masters;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\Quotation;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\company_tax;
use App\Models\company_tax_phone;
use App\Models\guest_tax_phone;
use App\Models\guest_tax;
use App\Models\receive_cheque;

use Auth;
class Deposit_Revenue extends Controller
{
    //index
    public function index()
    {
        return view('deposit_revenue.index');
    }
    public function create($id)
    {
        $currentDate = Carbon::now();
        $ID = 'DR-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = depositrevenue::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $DepositID = $ID.$year.$month.$newRunNumber;
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $type = $Quotation->type_Proposal;
        $Nettotal = $Quotation->Nettotal;
        $vat_type = $Quotation->vat_type;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Selectdata =  $Quotation->type_Proposal;
        $guest = $Quotation->Company_ID;
        if ($Selectdata == 'Guest') {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $nameg = $prefix->name_th;
            $fullName = $nameg.' '.$First_name.' '.$Last_name;
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
            $data = companys::where('Profile_ID',$guest)->first();
            $Company_typeID=$data->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $name = "ห้างหุ้นส่วนจำกัด ". $data->Company_Name ;
            }else{
                $name = $comtype->name_th . $data->Company_Name;
            }
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();
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
        $user = Auth::user();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$QuotationID)->where('status',1)->get();
        $Deposit = depositrevenue::where('Quotation_ID',$QuotationID)->count()+1;
        return view('deposit_revenue.create',compact('DepositID','QuotationID','Nettotal','vat_type','fullName','Contact_Name','Contact_phone','Contact_Email','address','Fax_number','phone','Email',
        'Identification','Selectdata','Quotation','settingCompany','user','datasub','name','name_ID','type','data_bank','data_cheque','Deposit'));
    }

    public function deposit($id)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
            }else{

                $company =  company_tax::where('ComTax_ID',$id)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullname = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$id)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullname = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }
        }

        return response()->json([
            'phone'=>$phone,
            'Selectdata'=>$Selectdata,
            'fullname'=>$fullname,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
            'email'=>$email
        ]);
    }
    public function cheque ($id)
    {

        $chequeRe =receive_cheque::where('cheque_number',$id)->first();
        $bank = $chequeRe->bank_cheque;
        $amount= $chequeRe->amount;
        $issue_date= $chequeRe->issue_date;
        $data_bank = Masters::where('id',$bank)->where('category', "bank")->first();
        return response()->json([
            'amount'=>$amount,
            'issue_date'=>$issue_date,
            'data_bank'=>$data_bank,
        ]);
    }

    public function save(Request $request){

        $data = $request->all();
        $userid = Auth::user()->id;
        $datarequest = [
            'Proposal_ID' => $data['QuotationID'] ?? null,
            'IssueDate' => $data['IssueDate'] ?? null,
            'Expiration' => $data['Expiration'] ?? null,
            'Deposit' => $data['Deposit'] ?? null,
            'Sum' => $data['sum'] ?? null,
            'company' => $data['nameid'] ?? null,
            'fullname' => $data['fullname'] ?? null,
            'DepositID' => $data['DepositID'] ?? null,
        ];
        // try {
        //     $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
        //     $IssueDate = $datarequest['IssueDate'] ?? null;
        //     $Expiration = $datarequest['Expiration'] ?? null;
        //     $Deposit = $datarequest['Deposit'] ?? null;
        //     $company = $datarequest['company'] ?? null;
        //     $Payment = $datarequest['Sum'] ?? null;
        //     $fullname = $datarequest['fullname'] ?? null;
        //     $DepositID = $datarequest['DepositID'] ?? null;

        //     $Paymenttotal = null;
        //     if ($Payment) {
        //         $Paymenttotal = 'ยอดเงิน : '.number_format($Payment). ' บาท';
        //     }
        //     $issuedate = null;
        //     if ($IssueDate) {
        //         $issuedate = 'วันที่ออกเอกสาร : '.$IssueDate;
        //     }
        //     $expiration = null;
        //     if ($Expiration) {
        //         $expiration = 'วันที่เอกสารหมดอายุ : '.$Expiration;
        //     }
        //     $full_name = null;
        //     if ($fullname) {
        //         $full_name = 'ชื่อบริษัท/ลูกค้า : '.$fullname.' + '.'รหัส : '.$company;
        //     }
        //     $full = null;
        //     if ($DepositID) {
        //         $full = 'รหัส : '.$DepositID.' + '.'อ้างอิงจาก : '.$Proposal_ID .' + '.'ครั้งที่ : '.$Deposit; ;
        //     }
        //     $datacompany = '';

        //     $variables = [$full,$full_name, $issuedate, $expiration, $Paymenttotal];

        //     foreach ($variables as $variable) {
        //         if (!empty($variable)) {
        //             if (!empty($datacompany)) {
        //                 $datacompany .= ' + ';
        //             }
        //             $datacompany .= $variable;
        //         }
        //     }

        //     $userids = Auth::user()->id;
        //     $save = new log_company();
        //     $save->Created_by = $userids;
        //     $save->Company_ID = $DepositID;
        //     $save->type = 'Generate';
        //     $save->Category = 'Generate :: Deposit Revenue';
        //     $save->content =$datacompany;
        //     // $save->save();
        // } catch (\Throwable $e) {
        //     return redirect()->route('Proposal.index')->with('error', $e->getMessage());
        // }
        try {
            $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
            $IssueDate = $datarequest['IssueDate'] ?? null;
            $Expiration = $datarequest['Expiration'] ?? null;
            $Deposit = $datarequest['Deposit'] ?? null;
            $companyid = $datarequest['company'] ?? null;
            $Payment = $datarequest['Sum'] ?? null;
            $fullnamemain = $datarequest['fullname'] ?? null;
            $DepositID = $datarequest['DepositID'] ?? null;

            $parts = explode('-', $companyid);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $Selectdata =  'Company';
                $company =  companys::where('Profile_ID',$companyid)->first();

                if ($company) {
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                    $Company_typeID=$company->Company_type;
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else{
                        $fullname = $comtype->name_th . $company->Company_Name;
                    }
                }else{

                    $company =  company_tax::where('ComTax_ID',$companyid)->first();
                    $Company_typeID=$company->Company_type;
                    if ($Company_typeID == [30,31,32]) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        }elseif ($Company_typeID > 32){
                            $fullname = $comtype->name_th . $company->Companny_name;
                        }
                    }else{
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="นาย") {
                            $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                        }elseif ($comtype->name_th =="นาง") {
                            $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                        }elseif ($comtype->name_th =="นางสาว") {
                            $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                        }else{
                            $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                        }
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                }
            }else{

                $guestdata =  Guest::where('Profile_ID',$companyid)->first();

                if ($guestdata) {
                    $Selectdata =  'Guest';
                    $Company_typeID=$guestdata->Company_type;
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                    $email = $guestdata->Company_Email;
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();
                    $Company_typeID=$guestdata->Company_type;
                    if ($Company_typeID == [30,31,32]) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        }elseif ($Company_typeID > 32){
                            $fullname = $comtype->name_th . $guestdata->Company_name;
                        }
                    }else{
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="นาย") {
                            $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        }elseif ($comtype->name_th =="นาง") {
                            $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        }elseif ($comtype->name_th =="นางสาว") {
                            $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        }else{
                            $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        }
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                    $email = $guestdata->Company_Email;
                }
            }

            $Quotation = Quotation::where('Quotation_ID', $Proposal_ID)->first();
            $Checkin = $Quotation->checkin;
            $Checkout = $Quotation->checkout;
            $Day = $Quotation->day;
            $Night = $Quotation->night;
            $Adult = $Quotation->adult;
            $Children = $Quotation->children;

            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $id = $DepositID;

            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Deposit/Quotation/cover/document/PDF/$id";
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();
            $data= [
                'settingCompany'=>$settingCompany,
                'DepositID'=>$DepositID,
                'IssueDate'=>$IssueDate,
                'Expiration'=>$Expiration,
                'qrCodeBase64'=>$qrCodeBase64,
                'user'=>$user,
                'fullname'=>$fullname,
                'Address'=>$Address,
                'Identification'=>$Identification,
                'TambonID'=>$TambonID,
                'amphuresID'=>$amphuresID,
                'provinceNames'=>$provinceNames,
                'phone'=>$phone,
                'email'=>$email,
                'Deposit'=>$Deposit,
                'Payment'=>$Payment,
                'Checkin'=>$Checkin,
                'Checkout'=>$Checkout,
                'Day'=>$Day,
                'Night'=>$Night,
                'Adult'=>$Adult,
                'Children'=>$Children,

            ];
            $template = master_template::query()->latest()->first();
            $view= $template->name;
            $pdf = FacadePdf::loadView('deposit_revenue.pdf.' . $view, $data);
            return $pdf->stream();
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

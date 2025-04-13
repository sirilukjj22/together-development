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
use App\Models\document_deposit_revenue;
use Auth;
class Deposit_Revenue extends Controller
{
    //index
    public function index()
    {
        $diposit= depositrevenue::query()->get();
        $pening= depositrevenue::where('document_status',1)->get();
        $success= depositrevenue::where('document_status',2)->where('receipt',0)->get();
        $cancel= depositrevenue::where('document_status',0)->get();
        $invoice= depositrevenue::where('document_status',2)->where('receipt',1)->get();

        return view('deposit_revenue.index',compact('diposit','pening','success','cancel','invoice'));
    }
    public function create($id)
    {
        $currentDate = Carbon::now();
        $ID = 'DI-';
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
        $amountdeposit = depositrevenue::where('Quotation_ID',$QuotationID)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();

        return view('deposit_revenue.create',compact('DepositID','QuotationID','Nettotal','vat_type','fullName','Contact_Name','Contact_phone','Contact_Email','address','Fax_number','phone','Email',
        'Identification','Selectdata','Quotation','settingCompany','user','datasub','name','name_ID','type','data_bank','data_cheque','Deposit','amdeposit','Mvat'));
    }
    public function createnew(){
        $currentDate = Carbon::now();
        $ID = 'DI-';
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
        $user = Auth::user();
        $Quotation = Quotation::where('status_document',6)->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        return view('deposit_revenue.createnew',compact('DepositID','user','Quotation','settingCompany'));
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
                $Company_typeID=$guestdata->preface;
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
            'email'=>$email,
            'nameID'=>$id,
        ]);
    }
    public function cheque($id)
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
    public function depositedit($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $DepositID = $deposit->Deposit_ID;
        $Nettotal = $deposit->amount;
        $QuotationID = $deposit->Quotation_ID;
        $companyid = $deposit->Company_ID;
        $CompanyID = $deposit->Company_ID;
        $fullName = $deposit->fullname;
        $IssueDate = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
        $Deposit = $deposit->count;
        $date = $deposit->date;
        $payment = $deposit->payment;
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$QuotationID)->get();
        $Quotation = Quotation::where('Quotation_ID', $QuotationID)->first();
        $vat_type = $Quotation->vat_type;
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
                $Email = $company->Company_Email;
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
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
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
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        if ($Nettotal) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Nettotal;
                $total = $Nettotal;
                $addtax = 0;
                $before = $Nettotal;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Nettotal;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $list = document_deposit_revenue::where('Deposit_ID', $DepositID)->get();
        $Cheque_Number = null;
        $cheque = null;
        $bank = ' ';
        $deposit_date = ' ';
        $amount = ' ';
        $issue_date = ' ';
        $Amount = 0;
        $databank = null;
        $bankname = null;
        foreach ($list as $value) {
            $Amount += $value->Amount;
            if ($value->PaymentType == 'cheque') {
                $Cheque_Number = $value->Cheque_Number;
                $cheque = receive_cheque::where('cheque_number', $Cheque_Number)->first();

                if ($cheque) { // ตรวจสอบว่า $cheque ไม่เป็น null ก่อนเข้าถึง property
                    $bank = $cheque->bank_cheque;
                    $databank = Masters::where('category', "bank")->where('id',$bank)->first();
                    if ($databank) {
                        $bankname = $databank->name_en;
                    }
                    $deposit_date = $cheque->deposit_date;
                    $amount = $cheque->amount;
                    $issue_date = $cheque->issue_date;
                }
            }
        }

        return view('deposit_revenue.editdeposit',compact('DepositID','QuotationID','Deposit','data_bank','data_cheque','vat_type','fullName','address','Identification','Email','phone'
        ,'Nettotal','Quotation','settingCompany','user','Subtotal','total','addtax','before','balance','deposit','list','date','payment','IssueDate','ExpirationDate','Cheque_Number'
        ,'bank','deposit_date','amount','issue_date','databank','Amount','bankname'));
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
        // dd( $data);
        try {
            $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
            $IssueDate = $datarequest['IssueDate'] ?? null;
            $Expiration = $datarequest['Expiration'] ?? null;
            $Deposit = $datarequest['Deposit'] ?? null;
            $company = $datarequest['company'] ?? null;
            $Payment = $datarequest['Sum'] ?? null;
            $fullname = $datarequest['fullname'] ?? null;
            $DepositID = $datarequest['DepositID'] ?? null;

            $Paymenttotal = null;
            if ($Payment) {
                $Paymenttotal = 'ยอดเงิน : '.number_format($Payment). ' บาท';
            }
            $issuedate = null;
            if ($IssueDate) {
                $issuedate = 'วันที่ออกเอกสาร : '.$IssueDate;
            }
            $expiration = null;
            if ($Expiration) {
                $expiration = 'วันที่เอกสารหมดอายุ : '.$Expiration;
            }
            $full_name = null;
            if ($fullname) {
                $full_name = 'ชื่อบริษัท/ลูกค้า : '.$fullname.' + '.'รหัส : '.$company;
            }
            $full = null;
            if ($DepositID) {
                $full = 'รหัส : '.$DepositID.' + '.'อ้างอิงจาก : '.$Proposal_ID .' + '.'ครั้งที่ : '.$Deposit; ;
            }
            $datacompany = '';

            $variables = [$full,$full_name, $issuedate, $expiration, $Paymenttotal];

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
            $save->Company_ID = $DepositID;
            $save->type = 'Generate';
            $save->Category = 'Generate :: Invoice / Deposit';
            $save->content =$datacompany;
            $save->save();


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
                    $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                }
            }else{
                $guestdata =  Guest::where('Profile_ID',$companyid)->first();
                if ($guestdata) {
                    $Selectdata =  'Guest';
                    $Company_typeID=$guestdata->preface;
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
                    $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
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
            $vattype= $Quotation->vat_type;
            $vat_type = master_document::where('id',$vattype)->first();
            $Nettotal = floatval(str_replace(',', '', $Payment));
            if ($Payment) {
                $Subtotal =0;
                $total =0;
                $addtax = 0;
                $before = 0;
                $balance =0;
                if ($vattype == 51) {
                    $Subtotal = $Payment;
                    $total = $Payment;
                    $addtax = 0;
                    $before = $Payment;
                    $balance = $Subtotal;
                }else{
                    $Subtotal = $Payment;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    $balance = $Subtotal;
                }
            }
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
                'Subtotal'=>$Subtotal,
                'total'=>$total,
                'addtax'=>$addtax,
                'before'=>$before,
                'balance'=>$balance,
                'Children'=>$Children,
                'Quotation'=>$Quotation,
            ];
            $template = master_template::query()->latest()->first();
            $view= $template->name;
            $pdf = FacadePdf::loadView('deposit_revenue.pdf.' . $view, $data);
            $path = 'PDF/Deposit_Revenue/';
            $pdf->save($path . $DepositID . '.pdf');

            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $DepositID;
            $savePDF->Company_Name = $fullnamemain;
            $savePDF->QuotationType = 'Deposit Revenue';
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->save();

            $save = new depositrevenue();
            $save->Deposit_ID = $DepositID;
            $save->Quotation_ID = $Proposal_ID;
            $save->Company_ID = $companyid;
            $save->amount = $Payment;
            $save->fullname = $fullnamemain;
            $save->Issue_date = $IssueDate;
            $save->ExpirationDate = $Expiration;
            $save->count = $Deposit;
            $save->save();

            $deposit = depositrevenue::where('Deposit_ID',$DepositID)->first();
            $ids = $deposit->id;
            return redirect()->route('Deposit.email', ['id' => $ids])->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('Proposal.index')->with('error', $e->getMessage());
        }
    }
    public function viewinvoicedeposit($id)
    {
        $deposit = depositrevenue::where('id',$id)->first();
        $companyid = $deposit->Company_ID;
        $Issue_date = $deposit->Issue_date;
        $Payment = $deposit->amount;
        $Company_ID = $deposit->Company_ID;
        $Quotation = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $Proposal = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        if ($type == 'Company') {
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
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $DepositID = $deposit->Deposit_ID;
        $Issue_date = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
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
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
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
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $QuotationID = $Quotation->Quotation_ID;
        $Deposit = $deposit->count;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type_num= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $Nettotal = floatval(str_replace(',', '', $Payment));
        if ($Payment) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Payment;
                $total = $Payment;
                $addtax = 0;
                $before = $Payment;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $company = $deposit->Company_ID;
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();

        $amountdeposit = depositrevenue::where('Quotation_ID',$QuotationID)->where('document_status',2)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }

        $Nettotal = $Proposal->Nettotal - $amdeposit;
        return view('deposit_revenue.viewinvoicedeposit',compact('name_ID','name','datasub','Payment','type','company','settingCompany','DepositID','Issue_date','ExpirationDate','fullName'
        ,'Email','address','Identification','phone','Quotation','QuotationID','Deposit','Payment','Subtotal','total','addtax','before','balance','user','vat_type','deposit','Mvat','vat_type_num',
        'Nettotal','amdeposit','Company_ID'));
    }
    public function log($id){
        $Quotation = depositrevenue::where('id', $id)->first();
        $QuotationID = $Quotation->Deposit_ID;
        $correct = $Quotation->correct;
        if ($Quotation) {


            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PD-\d{8})/', $QuotationID, $matches)) {
                $QuotationID = $matches[1];
            }

        }
        $log = log::where('Quotation_ID', 'LIKE', $QuotationID . '%')->get();
        $path = 'PDF/Deposit_Revenue/';

        $logproposal = log_company::where('Company_ID', $QuotationID)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('deposit_revenue.document',compact('log','path','correct','logproposal','QuotationID'));
    }
    public function edit($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $companyid = $deposit->Company_ID;
        $Issue_date = $deposit->Issue_date;
        $Payment = $deposit->amount;
        $Quotation = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $Proposal = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        if ($type == 'Company') {
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
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $DepositID = $deposit->Deposit_ID;
        $Issue_date = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
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
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
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
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $QuotationID = $Quotation->Quotation_ID;
        $Deposit = $deposit->count;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type_num= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $Nettotal = floatval(str_replace(',', '', $Payment));
        if ($Payment) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Payment;
                $total = $Payment;
                $addtax = 0;
                $before = $Payment;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $company = $deposit->Company_ID;
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();

        $amountdeposit = depositrevenue::where('Quotation_ID',$QuotationID)->where('document_status',2)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }
        $Nettotal = $Proposal->Nettotal - $amdeposit;
        return view('deposit_revenue.edit',compact('name_ID','name','datasub','Payment','type','company','settingCompany','DepositID','Issue_date','ExpirationDate','fullName'
        ,'Email','address','Identification','phone','Quotation','QuotationID','Deposit','Payment','Subtotal','total','addtax','before','balance','user','vat_type','deposit','Mvat','vat_type_num',
        'Nettotal','amdeposit'));
    }
    public function update(Request $request,$id){
        $data = $request->all();

        $userid = Auth::user()->id;
        $datarequest = [
            'Proposal_ID' => $data['QuotationID'] ?? null,
            'IssueDate' => $data['IssueDate'] ?? null,
            'Expiration' => $data['Expiration'] ?? null,
            'Sum' => $data['totaldeposit'] ?? null,
            'company' => $data['Guest'] ?? null,
            'fullname' => $data['fullname'] ?? null,
        ];
        $deposit = depositrevenue::where('id',$id)->first();
        $deposit_id = $id;
        $IssueDateMain = $deposit->Issue_date;
        $ExpirationMain = $deposit->ExpirationDate;
        $AmountMain = $deposit->amount;
        $fullnameMain = $deposit->fullname;
        $companyMain = $deposit->Company_ID;
        $DepositID = $deposit->Deposit_ID;
        $correct = $deposit->correct;
        $Deposit = $deposit->count;
        if ($correct >= 1) {
            $correctup = $correct + 1;
        }else{
            $correctup = 1;
        }

        $IssueDate = $datarequest['IssueDate'] ?? null;
        $Expiration = $datarequest['Expiration'] ?? null;
        $company = $datarequest['company'] ?? null;
        $Payment = $datarequest['Sum'] ?? null;
        $fullname = $datarequest['fullname'] ?? null;

        $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
        $IssueDate = $datarequest['IssueDate'] ?? null;
        $Expiration = $datarequest['Expiration'] ?? null;

        $companyid = $datarequest['company'] ?? null;
        $Payment = $datarequest['Sum'] ?? null;
        $fullnamemain = $datarequest['fullname'] ?? null;
        $DepositID = $deposit->Deposit_ID;
        try {
            $Paymenttotal = null;
            if ($Payment != $AmountMain) {
                $Paymenttotal = 'ยอดเงิน : '.number_format($Payment). ' บาท';
            }
            $issuedate = null;
            if ($IssueDate != $IssueDateMain) {
                $issuedate = 'วันที่ออกเอกสาร : '.$IssueDate;
            }
            $expiration = null;
            if ($Expiration != $ExpirationMain) {
                $expiration = 'วันที่เอกสารหมดอายุ : '.$Expiration;
            }
            $full_name = null;
            if ($fullname != $fullnameMain) {
                $full_name = 'ชื่อบริษัท/ลูกค้า : '.$fullname.' + '.'รหัส : '.$company;
            }
            $full = 'แก้ไขข้อมูลเป็น';
            $datacompany = '';

            $variables = [$full,$full_name, $issuedate, $expiration, $Paymenttotal];

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
            $save->Company_ID = $DepositID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Invoice / Deposit';
            $save->content =$datacompany;
            $save->save();

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
                    $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                }
            }else{

                $guestdata =  Guest::where('Profile_ID',$companyid)->first();

                if ($guestdata) {
                    $Selectdata =  'Guest';
                    $Company_typeID=$guestdata->preface;
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
                    $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
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
            $vattype= $Quotation->vat_type;
            $vat_type = master_document::where('id',$vattype)->first();
            $Nettotal = floatval(str_replace(',', '', $Payment));
            if ($Payment) {
                $Subtotal =0;
                $total =0;
                $addtax = 0;
                $before = 0;
                $balance =0;
                if ($vattype == 51) {
                    $Subtotal = $Payment;
                    $total = $Payment;
                    $addtax = 0;
                    $before = $Payment;
                    $balance = $Subtotal;
                }else{
                    $Subtotal = $Payment;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    $balance = $Subtotal;
                }
            }
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
                'Subtotal'=>$Subtotal,
                'total'=>$total,
                'addtax'=>$addtax,
                'before'=>$before,
                'balance'=>$balance,
                'Children'=>$Children,
                'Quotation'=>$Quotation,
            ];
            $template = master_template::query()->latest()->first();
            $view= $template->name;
            $pdf = FacadePdf::loadView('deposit_revenue.pdf.' . $view, $data);
            $path = 'PDF/Deposit_Revenue/';
            $pdf->save($path . $DepositID .'-'. $correctup . '.pdf');
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $DepositID;
            $savePDF->Company_Name = $fullnamemain;
            $savePDF->QuotationType = 'Deposit Revenue';
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->correct = $correctup;
            $savePDF->save();

            $save = depositrevenue::find($deposit_id);
            $save->Company_ID = $companyid;
            $save->amount = $Payment;
            $save->fullname = $fullnamemain;
            $save->Issue_date = $IssueDate;
            $save->ExpirationDate = $Expiration;
            $save->correct = $correctup;
            $save->save();
            $deposit = depositrevenue::where('Deposit_ID',$DepositID)->first();
            $ids = $deposit->id;
            return redirect()->route('Deposit.email', ['id' => $ids])->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('Deposit.index')->with('error', $e->getMessage());
        }
    }

    public function viewrevenuedeposit($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $DepositID = $deposit->Deposit_ID;
        $Nettotal = $deposit->amount;
        $payment = $deposit->payment;
        $QuotationID = $deposit->Quotation_ID;
        $companyid = $deposit->Company_ID;
        $CompanyID = $deposit->Company_ID;
        $fullName = $deposit->fullname;
        $IssueDate = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
        $Deposit = $deposit->count;
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$QuotationID)->where('status',1)->get();
        $Quotation = Quotation::where('Quotation_ID', $QuotationID)->first();
        $vat_type = $Quotation->vat_type;
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
                $Email = $company->Company_Email;
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
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
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
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
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
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        if ($Nettotal) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Nettotal;
                $total = $Nettotal;
                $addtax = 0;
                $before = $Nettotal;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Nettotal;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $revenue = document_deposit_revenue::where('Deposit_ID',$DepositID)->get();
        return view('deposit_revenue.viewrevenue',compact('DepositID','QuotationID','Deposit','data_bank','data_cheque','vat_type','fullName','address','Identification','Email','phone'
                    ,'Nettotal','Quotation','settingCompany','user','Subtotal','total','addtax','before','balance','deposit','payment','revenue','IssueDate','ExpirationDate'));
    }

    public function email($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $Quotation_ID= $deposit->Quotation_ID;
        $document_status= $deposit->document_status;
        $Deposit_ID= $deposit->Deposit_ID;
        $comtypefullname = null;
        $userid = Auth::user()->id;
        $username = User::where('id',$userid)->first();
        $nameuser = $username->firstname.' '.$username->lastname;
        $teluser = $username->tel;
        $quotation = Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $comid = $quotation->company;
        $companyid = $deposit->Company_ID;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $emailCom = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $comtypefullname = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $comtypefullname = $comtype->name_th . $company->Company_Name;
                }
            }else{
                $company =  company_tax::where('ComTax_ID',$companyid)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $comtypefullname = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $comtypefullname = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $comtypefullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $comtypefullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $comtypefullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $comtypefullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $emailCom = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $comtypefullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $comtypefullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $comtypefullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $comtypefullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }

                $emailCom = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $comtypefullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $comtypefullname = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $comtypefullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $comtypefullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $comtypefullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $comtypefullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }

                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $emailCom = $guestdata->Company_Email;
            }
        }
        $type_Proposal = $quotation->type_Proposal;
        if ($quotation->type_Proposal == 'Guest') {
            $companys = Guest::where('Profile_ID',$quotation->Company_ID)->first();
            $namefirst = $companys->First_name;
            $namelast = $companys->Last_name;
            $name = $namefirst.' '.$namelast;
            $emailCon = $companys->Email;
        }else{
            $companys = companys::where('Profile_ID',$quotation->Company_ID)->first();
            $contact = $companys->Profile_ID;
            $Contact_name = representative::where('Company_ID',$contact)->where('status',1)->first();
            $namefirst = $Contact_name->First_name;
            $namelast = $Contact_name->Last_name;
            $name = $namefirst.' '.$namelast;
            $emailCon = $Contact_name->Email;
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
        return view('deposit_revenue.email.index',compact('emailCom','Quotation_ID','name','comtypefullname','checkin','checkout','night','day','promotions',
        'quotation','type_Proposal','nameuser','teluser','Deposit_ID','deposit','emailCon','document_status'));
    }
    public function sendemail(Request $request,$id){
        try {

            $file = $request->all();
            $quotation = depositrevenue::where('id',$id)->first();

            $QuotationID = $quotation->Deposit_ID;
            $correct = $quotation->correct;
            $type_Proposal = $quotation->type_Proposal;
            $path = 'PDF/Deposit_Revenue/';
            if ($correct > 0) {
                $pdf = $path.$QuotationID.'-'.$correct;
                $pdfPath = $path.$QuotationID.'-'.$correct.'.pdf';
            }else{
                $pdf = $path.$QuotationID;
                $pdfPath = $path.$QuotationID.'.pdf';
            }
            $Title = $request->tital;
            $detail = $request->detail;
            $comment = $request->Comment;
            $email = $request->email;
            $emailCon = $request->emailCom;
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
            $save->Category = 'Send Email :: Invoice / Deposit';
            $save->content = 'Send Email Document Invoice / Deposit ID : '.$Quotation_ID;
            $save->save();
            return redirect()->route('Deposit.index')->with('success', 'บันทึกข้อมูลและส่งอีเมลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('Deposit.index')->with('error', $e->getMessage());
        }
    }
    public function sheetpdf(Request $request ,$id) {
        $deposit = depositrevenue::where('id',$id)->first();
        $correct = $deposit->correct;
        $Deposit = $deposit->count;
        $Proposal_ID = $deposit->Quotation_ID;
        $IssueDate = 	$deposit->Issue_date;
        $Expiration = $deposit->ExpirationDate;
        $companyid = $deposit->Company_ID;
        $document_status = $deposit->document_status;
        $Amount = $deposit->amount;
        $fullnamemain = $deposit->fullname;
        $DepositID = 	$deposit->Deposit_ID;
        $paymentDate = $deposit->date;
        $Payment = $deposit->payment;
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
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
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
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
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
        $vattype= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $Nettotal = floatval(str_replace(',', '', $Payment));
        if ($Payment) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Payment;
                $total = $Payment;
                $addtax = 0;
                $before = $Payment;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $groupedData = document_deposit_revenue::where('Deposit_ID',$DepositID)->get();
        $productItems  = document_deposit_revenue::where('Deposit_ID',$DepositID)->get();
        $productItems = [];
        foreach ($groupedData as $value) {

            $productItems[] = [
                'detail' => $value['detail'],
                'amount' => $value['Amount'],
            ];
        }
        $count = count($productItems);
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
            'Subtotal'=>$Subtotal,
            'total'=>$total,
            'addtax'=>$addtax,
            'before'=>$before,
            'balance'=>$balance,
            'Children'=>$Children,
            'Quotation'=>$Quotation,
            'paymentDate'=>$paymentDate,
            'Amount'=>$Amount,
            'count'=>$count,
            'productItems'=>$productItems,
        ];
        $template = master_template::query()->latest()->first();
        $view= $template->name;
        if ($document_status == 1) {
            if ($correct > 0 ) {
                $pdf = FacadePdf::loadView('deposit_revenue.pdf.' . $view, $data);
            }
        }else {
            $pdf = FacadePdf::loadView('deposit_revenue.pdf_generate.' . $view, $data);
        }
        $path = 'PDF/Deposit_Revenue/';
        return $pdf->stream();
    }

    public function Revise($id){
        try {
            $data = depositrevenue::where('id',$id)->first();
            $Quotation_ID = $data->Deposit_ID;
            $Quotation = depositrevenue::find($id);
            $Quotation->document_status = 1;
            $Quotation->save();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Quotation_ID;
            $save->type = 'Revise';
            $save->Category = 'Revise :: Invoice / Deposit';
            $save->content = 'Revise Document Invoice / Deposit ID : '.$Quotation_ID;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('Deposit.index')->with('error', $e->getMessage());
        }
        return redirect()->route('Deposit.index')->with('success', 'Data has been successfully saved.');
    }
    public function cancel(Request $request ,$id){
        $data = depositrevenue::where('id',$id)->first();
        $Quotation_ID = $data->Deposit_ID;
        $userid = Auth::user()->id;

        try {

            if ($data->document_status == 1) {

                $Quotation = depositrevenue::find($id);
                $Quotation->document_status = 0;
                $Quotation->remark = $request->note;
                $Quotation->save();
            }elseif ($data->document_status == 2) {
                $Quotation = depositrevenue::find($id);
                $Quotation->document_status = 1;
                $Quotation->remark = $request->note;
                $Quotation->save();
            }
            return redirect()->route('Deposit.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('Deposit.index')->with('error', $e->getMessage());
        }
        try {
            $savelog = new log_company();
            $savelog->Created_by = $userid;
            $savelog->Company_ID = $Quotation_ID;
            $savelog->type = 'Cancel';
            $savelog->Category = 'Cancel :: Invoice / Deposit';
            $savelog->content = 'Cancel Document Invoice / Deposit ID : '.$Quotation_ID.'+'.$request->note;
            $savelog->save();
        } catch (\Throwable $e) {
            return redirect()->route('Deposit.index')->with('error', $e->getMessage());
        }
        return redirect()->route('Deposit.index')->with('success', 'Data has been successfully saved.');
    }

    public function Quotation(Request $request){
        $data = $request->all();

        if (isset($data['value']) && $data['value'] == 'all') {
            $Quotation = Quotation::with('guest', 'company')
            ->leftJoinSub(
                DB::table('document_invoice')
                    ->select(
                        'Quotation_ID',
                        DB::raw('COUNT(CASE WHEN document_status = 1 THEN 1 END) as invoice_count')
                    )
                    ->groupBy('Quotation_ID'),
                'document_invoice',
                'quotation.Quotation_ID',
                '=',
                'document_invoice.Quotation_ID'
            )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('document_invoice')
                    ->whereColumn('document_invoice.Quotation_ID', 'quotation.Quotation_ID'); // ✅ ปิดบรรทัดถูกต้อง
            }) // ✅ ปิด `whereNotExists` ถูกต้อง
            ->where('quotation.status_document', 6)
            ->select(
                'quotation.*',
                'document_invoice.invoice_count'
            )
            ->groupBy('quotation.Quotation_ID', 'document_invoice.invoice_count')
            ->get();


        } elseif (isset($data['value']) && $data['value'] == 'company') {
            $Quotation = Quotation::with('company')
            ->leftJoinSub(
                DB::table('document_invoice')
                    ->select(
                        'Quotation_ID',
                        DB::raw('COUNT(CASE WHEN document_status = 1 THEN 1 END) as invoice_count')
                    )
                    ->groupBy('Quotation_ID'),
                'document_invoice',
                'quotation.Quotation_ID',
                '=',
                'document_invoice.Quotation_ID'
            )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('document_invoice')
                    ->whereColumn('document_invoice.Quotation_ID', 'quotation.Quotation_ID'); // ✅ ปิดบรรทัดถูกต้อง
            }) // ✅ ปิด `whereNotExists` ถูกต้อง
            ->where('quotation.status_document', 6)
            ->where('type_Proposal','Company')
            ->select(
                'quotation.*',
                'document_invoice.invoice_count'
            )
            ->groupBy('quotation.Quotation_ID', 'document_invoice.invoice_count')
            ->get();
        } elseif (isset($data['value']) && $data['value'] == 'guest') {
            $Quotation = Quotation::with('guest')
            ->leftJoinSub(
                DB::table('document_invoice')
                    ->select(
                        'Quotation_ID',
                        DB::raw('COUNT(CASE WHEN document_status = 1 THEN 1 END) as invoice_count')
                    )
                    ->groupBy('Quotation_ID'),
                'document_invoice',
                'quotation.Quotation_ID',
                '=',
                'document_invoice.Quotation_ID'
            )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('document_invoice')
                    ->whereColumn('document_invoice.Quotation_ID', 'quotation.Quotation_ID'); // ✅ ปิดบรรทัดถูกต้อง
            }) // ✅ ปิด `whereNotExists` ถูกต้อง
            ->where('quotation.status_document', 6)
            ->where('type_Proposal','Guest')
            ->select(
                'quotation.*',
                'document_invoice.invoice_count'
            )
            ->groupBy('quotation.Quotation_ID', 'document_invoice.invoice_count')
            ->get();
        }
        return response()->json([
            'products' => $Quotation,
        ]);
    }

    public function deposit_pd($id)
    {
        $Quotation = Quotation::where('id',$id)->first();
        $Company_ID = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $vat = $Quotation->vat_type;
        $Nettotal = $Quotation->Nettotal;
        $Deposit = depositrevenue::where('Quotation_ID',$Quotation_ID)->count()+1;
        $amountdeposit = depositrevenue::where('Quotation_ID',$Quotation_ID)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }
        $nettotal = $Nettotal - $amdeposit;
        $checkin = $Quotation->checkin ?? 'No Check In Date';
        $checkout = $Quotation->checkout ?? ' ' ;
        $day = $Quotation->day;
        $night = $Quotation->night;
        $adult = $Quotation->adult;
        $children = $Quotation->children;
        $Mvat = master_document::where('id',$vat)->select('name_th', 'id')->first();
        $vattype = $Mvat->name_th;
        $parts = explode('-', $Company_ID);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$Company_ID)->first();
            if ($company) {
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
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
                $company =  company_tax::where('ComTax_ID',$Company_ID)->first();
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
                $phone = company_tax_phone::where('ComTax_ID',$Company_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$Company_ID)->first();

            if ($guestdata) {
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
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
                $guestdata =  guest_tax::where('GuestTax_ID',$Company_ID)->first();
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
                $phone = guest_tax_phone::where('GuestTax_ID',$Company_ID)->where('Sequence','main')->first();
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
            'email'=>$email,
            'nameID'=>$Company_ID,
            'proposal_id'=>$Quotation_ID,
            'datasub'=>$datasub,
            'name'=>$name,
            'name_ID'=>$name_ID,
            'vat_type'=>$vattype,
            'nettotal'=>$nettotal,
            'amdeposit'=>$amdeposit,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            'day'=>$day,
            'night'=>$night,
            'adult'=>$adult,
            'children'=>$children,
            'Deposit'=>$Deposit,
            'vat'=>$vat,
        ]);
    }
}

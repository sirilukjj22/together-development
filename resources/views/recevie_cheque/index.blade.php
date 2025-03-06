@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Recevie Cheque </div>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#allSearch" onclick="cheque()">
                        <i class="fa fa-plus"></i> Create Recevie Cheque
                    </button>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="allSearch" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('ReceiveCheque.save')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-12 ml-auto">
                                                                <b >Cheque ID : <span id="Cheque_id_Save"></span></b>
                                                                <b>Issue Date : <span>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span></b>
                                                            </div>
                                                            <input type="hidden" class="form-control" id="Cheque_IDsave" name="Cheque_ID" >

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Proposal</label>
                                                        <select name="Refer" id="Refer" class="select2">
                                                            @foreach($invoice as $item)
                                                                @php
                                                                    $quotation =  DB::table('quotation')->where('Quotation_ID',$item->Quotation_ID)->first();
                                                                    $companyid = $quotation->Company_ID; // เช่น "123-456"
                                                                    $parts = explode('-', $companyid); // แยกด้วย "-"
                                                                    $firstPart = $parts[0]; // ได้ค่าด้านหน้า เช่น "123"
                                                                    if ($firstPart == 'C') {
                                                                        $company =  DB::table('companys')->where('Profile_ID',$companyid)->first();
                                                                        $Company_typeID=$company->Company_type;
                                                                        $comtype = DB::table('master_documents')->where('id',$Company_typeID)->select('name_th', 'id')->first();
                                                                        if ($comtype->name_th =="บริษัทจำกัด") {
                                                                            $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                                                                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                                            $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                                                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                                            $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                                                        }else{
                                                                            $fullName = $comtype->name_th . $company->Company_Name;
                                                                        }
                                                                    }else {
                                                                        $guestdata =  DB::table('guests')->where('Profile_ID',$companyid)->first();
                                                                        $Company_typeID=$guestdata->Company_type;
                                                                        $comtype = DB::table('master_documents')->where('id',$Company_typeID)->select('name_th', 'id')->first();
                                                                        if ($comtype->name_th =="นาย") {
                                                                            $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                                                        }elseif ($comtype->name_th =="นาง") {
                                                                            $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                                                        }elseif ($comtype->name_th =="นางสาว") {
                                                                            $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                                                        }else{
                                                                            $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <option value=""></option>
                                                                <option value="{{ $item->Quotation_ID }}">
                                                                    Proposal : {{$item->Quotation_ID}} {{$fullName}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <select name="bank" id="bank" class="select2" >
                                                            @foreach($data_bank as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name_th }} ({{$item->name_en}})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Branch No. </label>
                                                        <input type="number" class="form-control" id="branch" name="branch">
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumber" name="chequeNumber" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="number" class="form-control" id="Amount" name="Amount">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-color-green lift" id="btn-save">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function cheque() {
                            jQuery.ajax({
                                type: "GET",
                                url: "{!! url('/Document/ReceiveCheque/Number') !!}",
                                datatype: "JSON",
                                async: false,
                                success: function(response) {
                                    var Cheque = response.Cheque;
                                    $('#Cheque_IDsave').val(Cheque);
                                    $('#Cheque_id_Save').text(Cheque);
                                },
                                error: function(xhr, status, error) {
                                    console.error("AJAX request failed: ", status, error);
                                }
                            });

                        }
                    </script>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <div class="col-sm-12 col-12">
                                                    <div class="row">
                                                        <div class="col-sm-4 col-12 ml-auto">
                                                            <b >Cheque ID : <span id="Cheque_ID_View"></span></b>
                                                            <b>Issue Date : <span id="Issue_dateView"></span></b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Proposal</label>
                                                        <input type="text" class="form-control" id="Referview" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <input type="text" class="form-control" id="BankChequeview" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Branch No. </label>
                                                        <input type="text" class="form-control" id="branchview" name="branch" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumberview" name="chequeNumber" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="text" class="form-control" id="Amountview" name="Amount" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Deduct Date</label>
                                                        <input type="text" class="form-control" id="deductdateview" name="deductdate" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Receive Payment</label>
                                                        <input type="text" class="form-control" id="receiveview" name="receive" disabled>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('ReceiveCheque.update')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-12 ml-auto">
                                                                <b>Cheque ID  : <span id="Cheque_ID_Update"></span></b>
                                                                <b>Issue Date : <span id="Issue_date"></span></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Proposal</label>
                                                        <select name="Refer" id="Referedit" class="select2" >
                                                            @foreach($invoice as $item)
                                                            @php
                                                                    $quotation =  DB::table('quotation')->where('Quotation_ID',$item->Quotation_ID)->first();
                                                                    $companyid = $quotation->Company_ID; // เช่น "123-456"
                                                                    $parts = explode('-', $companyid); // แยกด้วย "-"
                                                                    $firstPart = $parts[0]; // ได้ค่าด้านหน้า เช่น "123"
                                                                    if ($firstPart == 'C') {
                                                                        $company =  DB::table('companys')->where('Profile_ID',$companyid)->first();
                                                                        $Company_typeID=$company->Company_type;
                                                                        $comtype = DB::table('master_documents')->where('id',$Company_typeID)->select('name_th', 'id')->first();
                                                                        if ($comtype->name_th =="บริษัทจำกัด") {
                                                                            $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                                                                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                                            $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                                                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                                            $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                                                        }else{
                                                                            $fullName = $comtype->name_th . $company->Company_Name;
                                                                        }
                                                                    }else {
                                                                        $guestdata =  DB::table('guests')->where('Profile_ID',$companyid)->first();
                                                                        $Company_typeID=$guestdata->Company_type;
                                                                        $comtype = DB::table('master_documents')->where('id',$Company_typeID)->select('name_th', 'id')->first();
                                                                        if ($comtype->name_th =="นาย") {
                                                                            $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                                                        }elseif ($comtype->name_th =="นาง") {
                                                                            $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                                                        }elseif ($comtype->name_th =="นางสาว") {
                                                                            $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                                                        }else{
                                                                            $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <option value="{{ $item->Quotation_ID }}">
                                                                    Refer Proposal : {{$item->Quotation_ID}} {{$fullName}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <select name="bank" id="bankedit" class="select2" >
                                                            @foreach($data_bank as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name_th }} ({{$item->name_en}})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Branch No. </label>
                                                        <input type="number" class="form-control" id="branchedit" name="branch">
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumberedit" name="chequeNumber" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="number" class="form-control" id="Amountedit" name="Amount" required>
                                                        <input type="hidden" class="form-control" id="ids" name="ids" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-color-green lift" id="btn-save">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-proposal" role="tab" onclick="nav($id='nav1')"><i class="fa fa-circle fa-xs"style="color: #64748b;" ></i> Cheque</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending"  onclick="nav($id='nav3')"role="tab"><i class="fa fa-circle fa-xs"style="color: #FF6633;" ></i> Await Deduct</a></li>
                        <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><i class="fa fa-circle fa-xs"style="color: #198754;" ></i> Deducted</a></li>
                        <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav5')" role="tab"><i class="fa fa-circle fa-xs"style="color: red;" ></i> Bounced Cheque</a></li>
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-proposal" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;">
                                        <table id="chequeTable" class="table-together table-style" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Bank Cheque</th>
                                                    <th class="text-center" data-priority="1">Cheque Number</th>
                                                    <th class="text-center">Branch No</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Deduct Date</th>
                                                    <th class="text-center">Receive Payment</th>
                                                    <th class="text-center">Deduct By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($cheque))
                                                    @foreach ($cheque as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->issue_date}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{$item->refer_proposal}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{@$item->bank->name_th}} ({{@$item->bank->name_en}})
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->cheque_number}}
                                                        </td>
                                                        <td style="text-align: center;" >
                                                            {{$item->branch ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;" class="target-class">
                                                            {{$item->amount}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->deduct_date ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_payment ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{@$item->userDeduct->name ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if ($item->status == 1)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633"> Await Deduct</span>
                                                            @elseif ($item->status == 2)
                                                                <span class="badge rounded-pill bg-success">Deducted</span>
                                                            @elseif ($item->status == 0)
                                                                <span class="badge rounded-pill "style="background-color: #red">Bounced Cheque</span>
                                                            @endif
                                                        </td>
                                                        {{-- Receive Cheque --}}
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($item->status == 2)
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                    @endif
                                                                    @if ($item->status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="edit({{$item->id}})">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Approved({{$item->id}})">Bounced Cheque</a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>

                                </div>
                                <!-- Modal -->
                                <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" >
                                        <table id="PendingTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Bank Cheque</th>
                                                    <th class="text-center" data-priority="1">Cheque Number</th>
                                                    <th class="text-center">Branch No</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Deduct Date</th>
                                                    <th class="text-center">Receive Payment</th>
                                                    <th class="text-center">Deduct By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($chequepaind))
                                                    @foreach ($chequepaind as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->issue_date}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{$item->refer_proposal}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{@$item->bank->name_th}} ({{@$item->bank->name_en}})
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->cheque_number}}
                                                        </td>
                                                        <td style="text-align: center;" >
                                                            {{$item->branch ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;" class="target-class">
                                                            {{$item->amount}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->deduct_date ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_payment ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{@$item->userDeduct->name ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill "style="background-color: #FF6633"> Await Deduct</span>
                                                        </td>
                                                        {{-- Receive Cheque --}}
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($item->status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="edit({{$item->id}})">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Approved({{$item->id}})">Bounced Cheque</a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade "id="nav-Approved" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <table id="ApprovedTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Bank Cheque</th>
                                                    <th class="text-center" data-priority="1">Cheque Number</th>
                                                    <th class="text-center">Branch No</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Deduct Date</th>
                                                    <th class="text-center">Receive Payment</th>
                                                    <th class="text-center">Deduct By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($chequeDedected))
                                                    @foreach ($chequeDedected as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->issue_date}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{$item->refer_proposal}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{@$item->bank->name_th}} ({{@$item->bank->name_en}})
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->cheque_number}}
                                                        </td>
                                                        <td style="text-align: center;" >
                                                            {{$item->branch ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;" class="target-class">
                                                            {{$item->amount}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->deduct_date ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_payment ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{@$item->userDeduct->name ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Deducted</span>
                                                        </td>
                                                        {{-- Receive Cheque --}}
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade "id="nav-Cancel" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" >

                                        <table id="CancelTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Bank Cheque</th>
                                                    <th class="text-center" data-priority="1">Cheque Number</th>
                                                    <th class="text-center">Branch No</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Deduct Date</th>
                                                    <th class="text-center">Receive Payment</th>
                                                    <th class="text-center">Deduct By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($chequeBounced))
                                                    @foreach ($chequeBounced as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->issue_date}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{$item->refer_proposal}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{@$item->bank->name_th}} ({{@$item->bank->name_en}})
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->cheque_number}}
                                                        </td>
                                                        <td style="text-align: center;" >
                                                            {{$item->branch ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;" class="target-class">
                                                            {{$item->amount}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->deduct_date ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_payment ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{@$item->userDeduct->name ?? '-'}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill "style="background-color: #red">Bounced Cheque</span>
                                                        </td>
                                                        {{-- Receive Cheque --}}
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />

    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#receive_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#receive_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Issue_Date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Issue_Date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });

        function view(id) {
            var id = id;
            jQuery.ajax({
                type: "GET",
                url: `/Document/ReceiveCheque/view/${id}`,  // ใช้ template literal สร้าง URL
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var invoice = response.invoice;
                    var proposal = response.proposal;
                    var bank_cheque = response.bank_cheque;
                    var receive_payment	 = response.receive_payment;
                    var cheque_number = response.cheque_number;
                    var amount = response.amount;
                    var deduct_date = response.deduct_date;
                    var branch = response.branch;
                    var issue_date = response.issue_date;
                    var Cheque_IDView = response.Cheque_ID;
                    var refer = 'อ้างอิงจาก Proposol : ' + proposal;
                    $('#Referview').val(refer);
                    $('#BankChequeview').val(bank_cheque);
                    $('#chequeNumberview').val(cheque_number);
                    $('#branchview').val(branch);
                    $('#Amountview').val(amount);
                    $('#deductdateview').val(deduct_date);
                    $('#receiveview').val(receive_payment);
                    $('#Issue_dateView').text(issue_date);
                    $('#viewModal').modal('show');
                    $('#Cheque_ID_View').text(Cheque_IDView);
                },
                error: function(xhr, status, error) {

                    $('#viewModal').modal('show');
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        function edit(id) {
            var id = id;
            jQuery.ajax({
                type: "GET",
                url: `/Document/ReceiveCheque/edit/${id}`,  // ใช้ template literal สร้าง URL
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var proposal = response.proposal;
                    var bank_cheque = response.bank_cheque;
                    var bank_received = response.bank_received;
                    var cheque_number = response.cheque_number;
                    var amount = response.amount;
                    var issue_date = response.issue_date;
                    var branch = response.branch;
                    var Cheque_IDEdit = response.Cheque_ID;
                    console.log(proposal);

                    $('#ids').val(id);
                    $('#Referedit').val(proposal).trigger('change');
                    $('#bankedit').val(bank_cheque).trigger('change');
                    // $('#Bankreceivedview').val(bank_received);
                    $('#chequeNumberedit').val(cheque_number);
                    $('#Amountedit').val(amount);
                    $('#branchedit').val(branch);
                    $('#Issue_date').text(issue_date);
                    $('#editModal').modal('show');
                    $('#Cheque_ID_Update').text(Cheque_IDEdit);
                },
                error: function(xhr, status, error) {

                    $('#editModal').modal('show');
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        function Approved(id) {
            jQuery.ajax({
                type: "GET",
                url: "/Document/ReceiveCheque/Approved/" + id,
                datatype: "JSON",
                async: false,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }

    </script>
    @include('script.script')


@endsection

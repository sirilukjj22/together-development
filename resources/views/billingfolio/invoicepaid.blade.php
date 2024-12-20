@extends('layouts.masterLayout')
<style>
@media screen and (max-width: 500px) {
    .mobileHidden {
    display: none;
    }

    .mobileLabelShow {
    display: inline;
    }

    #mobileshow {
    margin-top: 60px;
    }
}
.table-revenue-detail {
    display: none;
    margin: 1rem 0;
    padding: 1rem;
    background-color: aliceblue;
    border-radius: 7px;
    color: white;
    min-height: 20rem;
    }
    #arrival{
        z-index: 1000;
    }

    .toggle-button {
        cursor: pointer;
        border-radius: 5px;
        color: rgb(142, 142, 143);
        border: none;
        background-color: none;
        padding: 0px 5px;
    }

    .toggle-button:focus {
        outline: none;
        box-shadow: none;
    }

    .d-grid-120px-230px {
        display: grid;
        grid-template-columns: 120px 230px;
        gap: 10px;
        padding: 5px;
        border: 1px solid rgb(221, 221, 221);
        border-radius: 5px;
        background-color: rgb(230, 230, 230);
    }

    .d-grid-120px-230px select {
        border: none;
        color: #676868;
        border:1px rgb(206, 204, 204) solid;
    }

    .bg-paymentType {
            background-color: rgb(230, 230, 230);
        border-radius: 5px;
        padding: 0.5em;
    }

    .detail-modal-issueBill {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.5em;
        ;
    }

    .detail-modal-issueBill>div:nth-child(1) {
        border: 2px solid rgb(121, 173, 175);
        background-color: white;
        border-radius: 7px;
        padding: 0.5em;
        margin: auto 0px;
        height: 100%;
    }

    .payment-details-3g {
        display: grid;
        height: fit-content;
        border: rgb(39, 85, 79) 1px solid;
        border-radius: 7px;
        overflow: hidden;
        text-transform: capitalize;
    }

    .payment-details-3g li {
        display: grid;
        grid-template-columns: 120px 5px auto;
        padding: 0.4em 0.5em;
        text-align: center;
        text-align: start;
    }

    .payment-details-3g li span:nth-child(2) {
        text-align: end;
        min-width: 100px;
    }

    .payment-details-3g .parent-row {
        border-bottom: rgb(152, 190, 185) 1px solid;
        background-color: #509c97;
        ;
        color: white;
    }

    .payment-details-3g .child-row {
        display: none;
        background-color: #f9f9f9;
        border-bottom: rgb(152, 190, 185) 1px solid;
    }

    .payment-details-3g .parent-row:nth-last-child(1) {
        border: none;
    }

    .box-form-issueBill {
        border-radius: 5px;
        overflow: hidden;
    }

    .box-form-issueBill h4 {
        background-color: #2C7F7A;
        ;
        color: white;
        text-align: center;
        padding: 4px;
        margin-bottom: 0px;
    }

    .box-form-issueBill>section {
        /* background-color: #80e2d2; */
        background-color: white;
        border:#2C7F7A 2px solid;
        padding: 0.5em;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;

    }
    #table-revenueEditBill th {
    border-top: 1px solid #fff !important;
    border-bottom: 1px solid #fff !important;
    text-transform: uppercase;
    }
    #table-revenueEditBill td {
    border-top: 1px solid #fff !important;
    text-transform: capitalize;
    }

    .d-flex-2column {
    display: flex;
    gap:0.5em;
    }
    .d-flex-2column > div {
    flex-grow: 1;
    }

    @media (max-width: 500px) {
    `
    }
</style>
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Create Billing Folio</div>
                </div>
                <div class="col-auto">
                    <button class="bt-tg-normal mr-2" style="position: relative" data-toggle="modal" data-target="#modalAddBill">
                        <span >Issue Bill</span>
                    </button>
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-sm-12 col-12 pi">
                    <div class="">
                        <div class="card-body">
                            <section class="card-container bg-card-container">
                                <section class="card2 gradient-bg">
                                    <div class="card-content bg-card-content-white" class="card-content">
                                        <h5 class="card-title center">Client Details</h5>
                                        <ul class="card-list-withColon">
                                            <li>
                                            <span>Guest Name</span>
                                            @if ($type == 'Guest')
                                                <span>{{$name}}</span>
                                            @else
                                                <span> - </span>
                                            @endif
                                            </li>
                                            <li>
                                            <span>Company</span>
                                            @if ($type == 'Company')
                                                <span>{{$name}}</span>
                                            @else
                                                <span> - </span>
                                            @endif
                                            </li>
                                            <li>
                                                <span>Tax ID/Gst Pass</span>
                                                <span>{{$Identification}}</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span>{{$address}}</span>
                                            </li>
                                            <li>
                                                <span>Check In Date</span>
                                                <span>{{$Proposal->checkin ?? 'No Check In Date'}}</span>
                                            </li>
                                            <li>
                                                <span>Check Out Date</span>
                                                <span>{{$Proposal->checkout ?? '-'}}</span>
                                            </li>
                                            <li>
                                                <span>Valid Date</span>
                                                <span>{{$valid ?? '-'}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                                <section class="card2 card-circle">
                                    <div class="tech-circle-container mx-4" style="background-color: #135d58;">
                                        <div class="outer-glow-circle"></div>
                                        <div class="circle-content">
                                            <p class="circle-text">
                                            <p class="f-w-bold fs-3">{{ number_format($sumpayment, 2, '.', ',') }}</p>
                                            <span class="subtext fs-6" >Total Amount</span>
                                            </p>
                                        </div>
                                        <div class="outer-ring">
                                            <div class="rotating-dot"></div>
                                        </div>
                                    </div>
                                </section>
                            <section class="card2 gradient-bg">
                            <div class="card-content3 bg-card-content-white">
                                <h5 class="card-title center" >Folio</h5>
                                <ul class="card-list-between">
                                    <li class="pb-1 px-2 justify-content-center gap-2 fs-5" >
                                        <span>PI N0. </span>
                                        <span class="hover-effect f-w-bold text-primary">({{$Invoice_ID}}) </i>
                                        </span>
                                    </li>
                                    <li class="px-2">
                                        <span>Price Before Tax</span>
                                            <span class="hover-effect f-w-bold text-primary"> {{ number_format($sumpayment/1.07, 2, '.', ',') }} </i>
                                        </span>
                                    </li>
                                    <li class="px-2">
                                        <span>Value Added Tax</span>
                                        <span class="hover-effect f-w-bold text-primary"> {{ number_format($sumpayment - ($sumpayment/1.07), 2, '.', ',') }} </i></span>
                                    </li>
                                </ul>
                                <li class="outstanding-amount">
                                    <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                    <span class="text-success f-w-bold"> {{ number_format($sumpayment, 2, '.', ',') }}</span>
                                </li>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
                        <input type="hidden" class="form-control" id="InvoiceID" value="{{$Invoice_ID}}" />
                        <input type="hidden" class="form-control" id="additional_type" name="additional_type" value="{{$additional_type}}" />
                        <!-- Modal ออกบิลปกติ-->
                        <div class="modal fade bd-example-modal-lg" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content rounded-lg">
                                <div class="modal-header modal-h" style="border-radius: 0;">
                                    <h3 class="modal-title text-white">Issue Bill</h3>
                                    <span type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </span>
                                </div>
                                <form id="myForm" action="{{route('BillingFolio.savere')}} " method="POST" >
                                    @csrf
                                    <input type="hidden" id="invoice" name="invoice" class="form-control" value="{{$Invoice_ID}}">
                                    <div class="modal-body " style="display: grid;gap:0.5em;background-color: #d0f7ec;">
                                        <div class="col-lg-12 flex-end" style="display: grid; gap:1px" >
                                            <b >Receipt ID : {{$REID}}</b>
                                            <b >Proforma Invoice ID : {{$Invoice_ID}}</b>
                                        </div>
                                        <section class="detail-modal-issueBill">
                                            <div class="p-2" >
                                                <li>
                                                    @if ($type == 'Company')
                                                        <b>Company :</b> {{$name}}
                                                        <input type="hidden" class="form-control " id="company" name="company" value="{{$name}}" disabled  style="background-color: #59a89e81;"/>
                                                    @else
                                                        <b>Company :</b> -
                                                        <input type="hidden" class="form-control " id="company" disabled  style="background-color: #59a89e81;"/>
                                                    @endif

                                                </li>
                                                <li>
                                                    <b>Tax ID/Gst Pass :</b><span id="taxIDspan"></span>
                                                    <input type="hidden" id="taxID" value="auto-select" class="form-control" disabled  style="background-color: #59a89e81;"/>
                                                </li>
                                                <li>
                                                    <b>Address :</b> <span id="addressspan"></span>
                                                    <input type="hidden" id="address" value="auto-select" class="form-control" disabled  style="background-color: #59a89e81;"/>
                                                    <input type="hidden" id="address2" value="auto-select" class="form-control mt-3" disabled  style="background-color: #59a89e81;"/>
                                                </li>
                                            </div>
                                            <div class="payment-details-3g">
                                                <li class="parent-row" data-group="group1">
                                                    <span>
                                                        <button type="button" class="toggle-button mr-2" data-group="group1">⯈</button>Invoice
                                                    </span>: <span>{{ number_format($sumpayment, 2, '.', ',') }}</span>
                                                </li>
                                                <li class="child-row"  style="display: none;" data-group="group1">
                                                    <span>Proposal</span>: <span>{{ number_format($Proposal->Nettotal, 2, '.', ',') }}</span>
                                                </li>
                                                @if ($additional_Nettotal != 0)
                                                    <li class="parent-row" data-group="group2">
                                                        <span>
                                                            <button type="button" class="toggle-button mr-2" data-group="group2">⯈</button>additional </span>: <span> {{ number_format($Cash+$Complimentary, 2, '.', ',') }}</span>
                                                    </li>
                                                    <div id="overbill">
                                                        @if ($additional_type == 'Cash'||$additional_type == 'Cash Manual')
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>Cash</span>: <span>{{ number_format($Cash, 2, '.', ',') }}</span>
                                                            </li>
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>Complimentary </span>: <span>{{ number_format($Complimentary, 2, '.', ',') }}</span>
                                                            </li>
                                                        @else
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>H/G Online</span>: <span>{{ number_format($Cash+$Complimentary, 2, '.', ',') }}</span>
                                                            </li>
                                                        @endif
                                                    </div>
                                                @endif
                                                <li class="parent-row">
                                                    <span style="text-align: center;font-weight: bold;">Outstanding Amount </span>: <span id="total">{{ $sumpayment }}</span>
                                                </li>
                                            </div>
                                        </section>
                                        <div class="box-form-issueBill">
                                            <h4 >
                                                <span>Customer Details</span>
                                            </h4>
                                            <section class="d-grid-2column p-2" >
                                                <div>
                                                    <label for="" class="star-red">Guest Name</label>
                                                    <select name="Guest" id="Guest" class="select2" onchange="data()" required>
                                                        <option value="{{$name_ID}}">{{$name}}</option> @foreach($datasub as $item) @if ($type == 'Company') <option value="{{ $item->ComTax_ID }}"> @php $comtype = DB::table('master_documents') ->where('id', $item->Company_type) ->first(); if ($comtype) { if ($comtype->name_th == "บริษัทจำกัด") { $name = "บริษัท " . $item->Companny_name . " จำกัด"; } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") { $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)"; } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") { $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name; } else { $name = $comtype->name_th . ($item->Companny_name ?? ( $item->first_name . " " . $item->last_name)); } } @endphp {{ $name }}
                                                        </option> @else <option value="{{ $item->GuestTax_ID }}"> @php $comtype = DB::table('master_documents') ->where('id', $item->Company_type) ->first(); if ($comtype) { if ($comtype->name_th == "บริษัทจำกัด") { $name = "บริษัท " . $item->Company_name . " จำกัด"; } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") { $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)"; } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") { $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name; } else { $name = $comtype->name_th . ($item->Company_name ?? ( $item->first_name . " " . $item->last_name)); } } @endphp {{ $name }}
                                                        </option> @endif @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="star-red" for="reservationNo">Reservation No </label>
                                                    <input type="text" class="form-control" name="reservationNo" id="reservationNo" required />
                                                </div>
                                                <div>
                                                    <label class="star-red" for="roomNo">Room No.</label>
                                                    <input type="text" id="roomNo" name="roomNo" class="form-control" required />
                                                </div>
                                                <div>
                                                    <label class="star-red" for="numberOfGuests">Number of Guests</label>
                                                    <input type="text" id="numberOfGuests" name="numberOfGuests" class="form-control" required />
                                                </div>
                                                <div>
                                                    <label for="arrival">Arrival</label>
                                                    <div class="input-group">
                                                        <input type="text" name="arrival" id="arrival" placeholder="DD/MM/YYYY" class="form-control" required>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <!-- ไอคอนปฏิทิน -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="departure">Departure</label>
                                                    <div class="input-group">
                                                        <input type="text" name="departure" id="departure" placeholder="DD/MM/YYY" class="form-control" required>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <!-- ไอคอนปฏิทิน -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="box-form-issueBill">
                                            @if ($additional_type == 'Cash'||$additional_type == 'Cash Manual')
                                                <div class="form-check form-switch"  style="padding-left:35px">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" style="transform:translateY(-20%)"  checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Add Complimentary</label>
                                                </div>
                                                @else
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" style="transform:translateY(-20%)"  >
                                            @endif
                                            <input type="hidden" id="Checked" value="{{$additional_type}}">
                                            <h4 style="display: flex;">
                                                <span class="flex-grow-1 text-center">Payment Details</span>
                                                <div class="center sm mb-0 add" style="max-width: 35px;font-size:20px;background-color:rgb(55, 136, 125);border-radius:5px;" >+</div>
                                            </h4>
                                            <section >
                                                <div class="d-grid-2column" style="height: max-content;">
                                                    <div>
                                                        <label class="star-red" for="paymentDate">Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="paymentDate" id="paymentDate" placeholder="DD/MM/YYYY" class="form-control" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="note">Note</label>
                                                        <textarea id="note" name="note" style="height: 1px" placeholder="Enter details" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="sumpayment" name="sumpayment" class=" form-control" placeholder="Enter sumpayment" value="{{$sumpayment}}">
                                                <div class="container_new">
                                                    <div class="payment-container mt-2">
                                                        <div class="d-grid-120px-230px my-2" style="">
                                                            <label for="paymentType " class="star-red center" style="vertical-align: middle;">Payment Type : </label>
                                                            <select name="paymentType" id="paymentType" class="paymentType select2">
                                                                <option value="" disabled selected>Select Payment Type</option>
                                                                <option value="cash">Cash</option>
                                                                <option value="bankTransfer">Bank Transfer</option>
                                                                <option value="creditCard">Credit Card</option>
                                                                <option value="cheque">Cheque</option>
                                                            </select>
                                                        </div>
                                                        <!-- Cash Input -->
                                                        <div class="cashInput" style="display: none;">
                                                            <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                                <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                                <input type="text" id="Amount" name="cashAmount" class="cashAmount form-control" placeholder="Enter cash amount">
                                                            </div>
                                                            <div  id="complimentaryDiv" class="hidden mt-2" style="gap:1em;vertical-align: middle;">
                                                                <div class="bg-paymentType d-flex align-items-center" >
                                                                    <label for="creditCardAmount" class="star-red"style="white-space: nowrap;transform: translateY(3px);">Complimentary</label>
                                                                    <input type="text" id="Complimentary" name="Complimentary" class=" form-control" placeholder="Enter Complimentary" value="{{$Complimentary}}" readonly>
                                                                    <input type="hidden" id="additional" name="additional" class=" form-control" placeholder="Enter Complimentary" value="{{$Cash+$Complimentary}}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Bank Transfer Input -->
                                                        <div class="bankTransferInput" style="display: none;">
                                                            <div class=" d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="bankName" class="star-red">Bank</label>
                                                                    <select id="bank" name="bank" class="bankName select2"> @foreach ($data_bank as $item) <option value="{{ $item->name_en }}" {{$item->name_en == 'SCB' ? 'selected' : ''}}>{{ $item->name_en }} Bank Transfer - Together Resort Ltd - Reservation Deposit </option> @endforeach </select>
                                                                </div>
                                                                <div>
                                                                    <label for="bankTransferAmount" class="star-red">Amount</label>
                                                                    <input type="text" id="Amount" name="bankTransferAmount" class="bankTransferAmount form-control" placeholder="Enter transfer amount">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Credit Card Input -->
                                                        <div class="creditCardInput" style="display: none;">
                                                            <div class="d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="creditCardNumber" class="star-red">Credit Card Number</label>
                                                                    <input type="text" id="CardNumber" name="CardNumber" class="creditCardNumber form-control" placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19">
                                                                </div>
                                                                <div>
                                                                    <label for="expiryDate" class="star-red">Expiry Date</label>
                                                                    <input type="text" name="Expiry" id="Expiry" class="expiryDate form-control" placeholder="MM/YY" maxlength="5">
                                                                </div>
                                                                <div>
                                                                    <label for="creditCardAmount" class="star-red">Amount</label>
                                                                    <input type="text" id="Amount" name="creditCardAmount" class="creditCardAmount form-control" placeholder="Enter Amount" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Cheque Input -->
                                                        <div id="chequeInput" class="chequeInput" style="display: none;">
                                                            <div class="d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Number</label>
                                                                    <select  id="cheque" name="cheque" class="select2 cheque" >
                                                                        <option value="" disabled selected></option>
                                                                        @foreach ($data_cheque as $item)
                                                                            <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Date</label>
                                                                    <input type="text" class="form-control chequedate" id="chequedate" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Bank</label>
                                                                    <input type="text" class="form-control chequebank" id="chequebank" name="chequebank_name" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeAmount">Amount</label>
                                                                    <input type="text" class="form-control chequeamount" id="chequeamount" name="chequeamount" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeBank">To Account</label>
                                                                    <select  id="chequebank" name="chequebank" class="select2">
                                                                        @foreach ($data_bank as $item)
                                                                            <option value="{{ $item->name_en }}"{{$item->name_en == 'SCB' ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="deposit_date" id="deposit_date" placeholder="DD/MM/YYYY" class="form-control" required>
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                                <!-- ไอคอนปฏิทิน -->
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-0" style="background-color: rgb(255, 255, 255);">
                                        <button type="button" class="bt-tg bt-grey sm" data-dismiss="modal"> Close </button>
                                        <button type="button"  class="bt-tg sm modal_but" data-dismiss="modal" onclick="Preview()">Preview</button>
                                    </div>
                                </form>

                                </div>
                            </div>
                        </div>
                        <div>
                            <section id="billDetailsEditBill" style="border: 1px solid rgba(23, 27, 36, 0.633); padding: 2rem; margin: 2rem 0;" class="wrap-bill">
                                <div class="wrap-all-company-detail">
                                    <header>
                                        <section class="wrap-company-detail">
                                        <div class="company-detail">
                                            <ul>
                                            <h1>{{$settingCompany->name_th}}</h1>
                                            <li class="font-w-600">{{$settingCompany->name}}</li>
                                            <li class="left-4px font-w-600"> *** Head Office / Headquarters </li>
                                            <li>{{$settingCompany->address}}</li>
                                            <li> Tel : {{$settingCompany->tel}} | @if ($settingCompany->fax) Fax : {{$settingCompany->fax}} @endif </li>
                                            <li>HOTEL TAX ID {{$settingCompany->Hotal_ID}}</li>
                                            <li class="w-spaceWrap-less860px"> <span> website: {{$settingCompany->web}} |</span><span> Email: {{$settingCompany->email}} </span> </li>
                                            </ul>
                                        </div>

                                        <div class="img">
                                            <img src="{{ asset('assets/images/' . $settingCompany->image) }}" alt="together-resort" width="200px" />
                                        </div>
                                        </section>
                                        <section>
                                        <h3 class="center font-upper">Receipt / tax invoice</h3>
                                        <div class="receipt-cutomer-detail">
                                            <ul>
                                            <h4 class="font-upper"> Tax invoice {{$REID}}</h4>
                                            <li>
                                                <span>Guest name</span>
                                                <span id="displayGuestNameEditBill">คุณพัชรี</span>
                                            </li>
                                            <li>
                                                <span>Reservation No</span>
                                                <span id="displayReservationNoEditBill">6576</span>
                                            </li>
                                            <li>
                                                <span>Company</span>
                                                <span id="displayCompanyEditBill">Together Resort </span>
                                            </li>
                                            <li>
                                                <span>Tax ID/Gst Pass</span>
                                                <span id="displayTaxIDEditBill">0764559000169</span>
                                            </li>
                                            <li>
                                                <span>Address</span>
                                                <span id="displayAddressEditBill" >168 Moo 2 Kaengkrachan phetchaburi 76170</span>
                                            </li>
                                            </ul>
                                            <ul>
                                            <li>
                                                <span>Page #</span>
                                                <span>1 /1 </span>
                                            </li>
                                            <li>
                                                <span>Room No.</span>
                                                <span id="displayRoomNoEditBill">9643</span>
                                            </li>
                                            <li>
                                                <span>Arrival</span>
                                                <span id="displayArrivalEditBill">01/06/2024</span>
                                            </li>
                                            <li>
                                                <span>Departure</span>
                                                <span id="displayDepartureEditBill">02/06/2024</span>
                                            </li>
                                            <li>
                                                <span>No of Guest</span>
                                                <span id="displayNumberOfGuestsEditBill">3</span>
                                            </li>
                                            <li>
                                                <span>Printed Date</span>
                                                <span  id="date">02/06/2024</span>
                                            </li>
                                            <li>
                                                <span>Printed time</span>
                                                <span  id="dateM">13:26:24 PM</span>
                                            </li>
                                            <li>
                                                <span>Tax invoice Date</span>
                                                <span  id="Invoicedate">02/06/2024</span>
                                            </li>
                                            </ul>
                                        </div>
                                        </section>
                                    </header>
                                    <section class="receipt-cutomer-detail-body">
                                        <div>
                                        <div style="overflow: auto;">
                                            <table id="table-revenueEditBill" >
                                                <thead>
                                                    <tr>
                                                    <th>Date</th>
                                                    <th >Description </th>
                                                    <th>Reference</th>
                                                    <th>amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr style="border: none">
                                                        <td id="displayPaymentDateEditBill">10/04/2024</td>
                                                        <td >
                                                            <span id="displayDescriptionEditBill" > SCB Bank Transfer - Together Resort Ltd - Reservation Deposit </span>
                                                        </td>
                                                        <td id="displayReferenceEditBill"></td>
                                                        <td id="displayAmountEditBill">{{ number_format($sumpayment, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </section>
                                    <section class="receipt-subtotal">

                                    <div class="d-flex  gap-2 flex-wrap justify-content-between w-100" >
                                        <div class="flex-grow-1" style="padding-left: 11%">**<span id="displayNoteEditBill"></span></div>

                                        <div class="right">
                                            <ul class="font-w-500">
                                                <li>
                                                <span>Total Balance(Baht) </span>
                                                <span class="border-total-top">{{ number_format($sumpayment, 2) }}</span>
                                                </li>
                                                <li>
                                                <span>Vatable</span>
                                                <span>{{ number_format($sumpayment/1.07, 2) }}</span>
                                                </li>
                                                <li>
                                                <span>VAT 7 %</span>
                                                <span>{{ number_format($sumpayment-$sumpayment/1.07, 2) }}</span>
                                                </li>
                                                <li>
                                                <span>Non - Vatable</span>
                                                <span>0</span>
                                                </li>
                                                <li>
                                                <span>Total Amount (Baht)</span>
                                                <span>{{ number_format($sumpayment, 2) }}</span>
                                                </li>
                                                <li class="font-w-600">
                                                <span>Net Total</span>
                                                <span class="border-total">{{ number_format($sumpayment, 2) }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    </section>
                                    <section class="body-bottom">
                                        <div>
                                        <p> I agree that my liability for this invoice is not waived and agree to be held personally liable in the event that the indicated person, company, or association fails to pay for any part or the full amount of these charges. </p>
                                        </div>
                                    </section>
                                    <section class="signature">
                                        <div class="left">
                                        <p>Guest's Signature</p>
                                        </div>
                                        <div class="right">
                                        <p>Guest's Signature</p>
                                        </div>
                                    </section>
                                </div>
                            </section>
                            <div class="bottom">
                                <div class="flex-end pr-3">
                                    <button id="nextSteptoSave" class="bt-tg-normal md float-right" onclick="submit()"> Next </button>
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
    <script>
        $(document).ready(function() {

                const checkbox = document.getElementById('flexSwitchCheckChecked');
                const div = document.getElementById('complimentaryDiv');
                const inputs = div.querySelectorAll("input");
                console.log('Checkbox checked:', checkbox.checked);
                if (checkbox.checked == true) {


                    // ค่าเริ่มต้นให้ div แสดงและ inputs เปิดใช้งาน
                    div.classList.remove('hidden');
                    inputs.forEach(input => input.disabled = false);

                    // Event listener สำหรับการเปลี่ยนสถานะ checkbox
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            div.classList.remove('hidden'); // แสดง div
                            inputs.forEach(input => input.disabled = false); // เปิดการใช้งานฟิลด์ใน div
                        } else {
                            div.classList.add('hidden'); // ซ่อน div
                            inputs.forEach(input => input.disabled = true); // ปิดการใช้งานฟิลด์ใน div
                        }
                    });
                } else {
                    console.log(0);
                    // กรณีไม่มี checkbox ให้ div ซ่อนเป็นค่าเริ่มต้น
                    div.classList.add('hidden');
                    inputs.forEach(input => input.disabled = true);
                }

        });

        $(document).ready(function () {
            Total();
            const checkbox = document.getElementById('flexSwitchCheckChecked');
            checkbox.addEventListener('change', function() {
                Total();
            });
            var counter = 0; // ตัวนับสำหรับสร้าง id และ name ที่ไม่ซ้ำกัน

            $('.add').on('click', function () {
                // HTML ฟอร์มใหม่ที่มี id และ name แบบไดนามิก
                const newPaymentForm = `
                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                        <div class="d-grid-120px-230px my-2" style="position:relative">
                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                            </button>
                            <label for="paymentType_${counter}" class="star-red center" style="vertical-align: middle;">Payment Type:</label>
                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                <option value="" disabled selected>Select Payment Type</option>

                                <option value="bankTransfer">Bank Transfer</option>
                                <option value="creditCard">Credit Card</option>
                                <option value="cheque">Cheque</option>
                            </select>

                        </div>
                        <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />

                        <!-- Bank Transfer Input -->
                        <div class="bankTransferInput" style="display: none;">
                            <div class="d-grid-2column bg-paymentType">
                                <div>
                                    <label for="bank_${counter}" class="star-red">Bank</label>
                                    <select id="bank_${counter}" name="bank_${counter}" class="bankName select2">
                                        @foreach ($data_bank as $item)
                                            <option value="{{ $item->name_en }}" {{$item->name_en == 'SCB' ? 'selected' : ''}}>{{ $item->name_en }} Bank Transfer - Together Resort Ltd - Reservation Deposit</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="bankTransferAmount_${counter}" class="star-red">Amount</label>
                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount">
                                </div>
                            </div>
                        </div>
                        <!-- Credit Card Input -->
                        <div class="creditCardInput" style="display: none;">
                            <div class="d-grid-2column bg-paymentType">
                                <div>
                                    <label for="creditCardNumber_${counter}" class="star-red">Credit Card Number</label>
                                    <input type="text" id="creditCardNumber_${counter}" name="CardNumber_${counter}" class="creditCardNumber form-control" placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19">
                                </div>
                                <div>
                                    <label for="expiryDate_${counter}" class="star-red">Expiry Date</label>
                                    <input type="text" name="Expiry_${counter}" id="expiryDate_${counter}" class="expiryDate form-control" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div>
                                    <label for="creditCardAmount_${counter}" class="star-red">Amount</label>
                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value="">
                                </div>
                            </div>
                        </div>
                        <div id="chequeInput" class="chequeInput" style="display: none;">
                            <div class="d-grid-2column bg-paymentType">
                                <div>
                                    <label for="chequeNumber">Cheque Number</label>
                                    <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                        <option value="" disabled selected>Select</option>
                                        @foreach ($data_cheque as $item)
                                            <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="chequeNumber">Cheque Date</label>
                                    <input type="text" class="form-control chequedate" id="chequedate_${counter}" readonly />
                                </div>
                                <div>
                                    <label for="chequeNumber">Cheque Bank</label>
                                    <input type="text" class="form-control chequebank" id="chequebank_${counter}" name="chequebank_name_${counter}" readonly />
                                </div>
                                <div>
                                    <label for="chequeAmount">Amount</label>
                                    <input type="text" class="form-control chequeamount" id="chequeamount_${counter}" name="chequeamount_${counter}" readonly />
                                </div>
                                <div>
                                    <label for="chequeBank">To Account</label>
                                    <select  id="chequebank_${counter}" name="chequebank_${counter}" class="select2">
                                        <option value="" disabled selected></option>
                                        @foreach ($data_bank as $item)
                                            <option value="{{ $item->name_en }}"{{$item->name_en == 'SCB' ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="chequeNumber">Date</label>
                                    <div class="input-group">
                                        <input type="text" name="deposit_date_${counter}" id="deposit_date" placeholder="DD/MM/YYYY" class="deposit_date form-control" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                <i class="fas fa-calendar-alt"></i>
                                                <!-- ไอคอนปฏิทิน -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // เพิ่มฟอร์มใหม่ต่อท้าย payment-container ล่าสุด
                $('.payment-container').last().after(newPaymentForm);

                // อัปเดต Select2 หากมีการใช้
                $('.select2').select2({
                    placeholder: "Please select an option"
                });

                // เพิ่มค่า counter เพื่อให้ id และ name ไม่ซ้ำกัน

                $('.creditCardNumber').on('input', function() {
                    var input = $(this).val().replace(/\D/g, ''); // Remove all non-digit characters
                    input = input.substring(0, 16); // Limit input to 16 digits
                    // Format the input as xxxx-xxxx-xxxx-xxxx
                    var formattedInput = input.match(/.{1,4}/g)?.join('-') || input;
                    $(this).val(formattedInput);
                });
                $(document).on('focus', '.deposit_date', function() {
                    var inputElement = $(this); // ดึง element ที่ถูก focus
                    inputElement.daterangepicker({
                        singleDatePicker: true, // เลือกวันที่เดียว
                        showDropdowns: true, // แสดง dropdowns สำหรับเลือกปีและเดือน
                        autoUpdateInput: false, // ไม่อัปเดต input โดยอัตโนมัติ
                        autoApply: true, // เมื่อเลือกวันที่แล้วจะอัปเดต
                        drops: 'up', // ให้ปฏิทินแสดงขึ้นบน
                        locale: {
                            format: 'DD/MM/YYYY' // ฟอร์แมตวันที่
                        }
                    });

                    // เมื่อเลือกวันที่แล้ว อัปเดตค่าลงใน input
                    inputElement.on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY'));
                    });
                });
                // ปิดปฏิทินเมื่อมีการเลื่อนเมาส์
                $(document).on('wheel', function(e) {
                    // ตรวจสอบว่าปฏิทินยังคงแสดงอยู่
                    if ($('.daterangepicker').is(':visible')) {
                        $('.daterangepicker').hide(); // ปิดปฏิทิน
                    }
                });
                $(document).on('change', '#paymentType_'+ (counter), function () {
                    const selectedType = $(this).val();
                    var id = $(this).attr('id'); // ดึง ID ของ element
                    var counter = id.split('_')[1];

                    const parentContainer = $(this).closest('.payment-container'); // หา parent container
                    parentContainer.find('.cashInput, .bankTransferInput, .creditCardInput, .chequeInput').hide(); // ซ่อนทุกส่วน
                    parentContainer.find(`#cashAmount_${counter}, #bankTransferAmount_${counter}, #creditCardAmount_${counter}, #chequeamount_${counter} ,#chequedate_${counter},#chequebank_${counter},#cheque_${counter}`).val('');
                    if (selectedType === 'bankTransfer') {
                        parentContainer.find('.bankTransferInput').show();
                        Total();
                    } else if (selectedType === 'creditCard') {
                        parentContainer.find('.creditCardInput').show();
                        Total();
                    } else if (selectedType === 'cheque') {
                        parentContainer.find('.chequeInput').show();
                        Total();
                    }
                });
                $(document).on('change', '#cheque_'+ (counter), function () {
                    var id = $('#cheque_'+(counter - 1)).val();
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/cheque/" + id + "') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(response) {
                            // ดึงข้อมูลจาก response
                            var amount = parseFloat(response.amount || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            var issue_date = response.issue_date;
                            var bank = response.data_bank.name_en;
                            // ใช้ counter ในการตั้งค่าฟิลด์ที่ถูกเพิ่มมาใหม่
                            $('#chequedate_' + (counter - 1)).val(issue_date);
                            $('#chequebank_' + (counter - 1)).val(bank);
                            $('#chequeamount_' + (counter - 1)).val(amount);
                            Total();
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request failed: ", status, error);
                        }
                    });
                });
                $(document).on('keyup', `[id^='creditCardAmount_${counter}']`, function() {

                var cash =  Number($(this).val());
                console.log(cash);
                Total();
                });
                $(document).on('keyup', `[id^='bankTransferAmount_${counter}']`, function() {

                var cash =  Number($(this).val());
                console.log(cash);
                Total();
                });
                $(document).on('click', '.remove', function() {
                    let containerId = $(this).closest('.payment-container').attr('id'); // ดึง ID ของ parent container
                    $('#' + containerId).remove(); // ลบ container ตาม ID
                    Total();
                });
                counter++;
            });
            $('#cheque').on('change', function() {
                console.log(1);
                var id = $('#cheque').val();
                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/cheque/" + id + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        var amount = parseFloat(response.amount || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        var issue_date = response.issue_date;
                        var bank = response.data_bank.name_en;

                        $('#chequedate').val(issue_date);
                        $('#chequebank').val(bank);
                        $('#chequeamount').val(amount);
                        Total();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed: ", status, error);
                    }
                });
            });


            $(document).on('change', '#paymentType', function () {

                console.log(1);

                var selectedType = $(this).val();
                var parentContainer = $(this).closest('.payment-container'); // Find the parent container
                // Hide all payment method sections within this specific container
                parentContainer.find('.cashInput, .bankTransferInput, .creditCardInput, .chequeInput').hide();
                parentContainer.find('#Amount, #chequeamount,#chequedate,#chequebank,#cheque').val('');
                // Show the relevant section based on the selected payment type
                if (selectedType === 'cash') {
                    parentContainer.find('.cashInput').show();
                    Total();
                } else if (selectedType === 'bankTransfer') {

                    parentContainer.find('.bankTransferInput').show();
                    Total();
                } else if (selectedType === 'creditCard') {

                    parentContainer.find('.creditCardInput').show();
                    Total();
                } else if (selectedType === 'cheque') {

                    parentContainer.find('.chequeInput').show();
                    Total();
                }
            });
            $('.creditCardNumber').on('input', function() {
                var input = $(this).val().replace(/\D/g, ''); // Remove all non-digit characters
                input = input.substring(0, 16); // Limit input to 16 digits
                // Format the input as xxxx-xxxx-xxxx-xxxx
                var formattedInput = input.match(/.{1,4}/g)?.join('-') || input;
                $(this).val(formattedInput);
            });

            $(document).on('keyup', '#Amount', function() {

                var cash =  Number($(this).val());

                Total();
            });
            function Total() {
                const checkbox = document.getElementById('flexSwitchCheckChecked');
                var chequeamount = parseFloat($('#chequeamount').val().replace(/,/g, '')) || 0; // แปลงค่า chequeamount
                var Amount = parseFloat($('#Amount').val()) || 0; // แปลงค่า Amount
                console.log(Amount);

                var sumpayment = parseFloat($('#sumpayment').val()) || 0; // แปลงค่า Amount
                var Complimentary = parseFloat($('#Complimentary').val()) || 0;
                var amountsArray = [];
                var cashArray = [];  // สร้างอาเรย์เพื่อเก็บค่าทั้งหมด
                var creditCardArray = [];
                var bankTransferArray = [];


                // ใช้ jQuery ดึงค่าของทุกๆ element ที่มี class chequeamount_${counter}
                $("[id^='chequeamount_']").each(function () {
                    var value = $(this).val(); // ดึงค่าจาก input
                    if (value) {
                        value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                        if (!isNaN(value)) {
                            amountsArray.push(value); // เก็บค่าใน array
                        }
                    }
                });
                $("[id^='creditCardAmount_']").each(function () {
                    var value = $(this).val(); // ดึงค่าจาก input
                    if (value) {
                        value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                        if (!isNaN(value)) {
                            creditCardArray.push(value); // เก็บค่าใน array
                        }
                    }
                });
                $("[id^='bankTransferAmount_']").each(function () {
                    var value = $(this).val(); // ดึงค่าจาก input
                    if (value) {
                        value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                        if (!isNaN(value)) {
                            bankTransferArray.push(value); // เก็บค่าใน array
                        }
                    }
                });
                $("[id^='cashAmount_']").each(function () {
                    var value = $(this).val(); // ดึงค่าจาก input
                    if (value) {
                        value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                        if (!isNaN(value)) {
                            cashArray.push(value); // เก็บค่าใน array
                        }
                    }
                });



                // แสดงค่าทั้งหมดใน amountsArray
                var sum =0;
                var Outstanding = 0;
                var amounts = amountsArray.reduce((sum, current) => sum + current, 0);



                var cash = cashArray.reduce((sum, current) => sum + current, 0);
                var credit = creditCardArray.reduce((sum, current) => sum + current, 0);
                var bank = bankTransferArray.reduce((sum, current) => sum + current, 0);
                if (checkbox.checked == false) {
                    var sum = cash+amounts+bank+credit+chequeamount+Amount;
                    var Outstanding = sumpayment-sum;
                }else{
                    var sum = cash+amounts+bank+credit+chequeamount+Amount+Complimentary;
                    var Outstanding = sumpayment-sum;
                }

                // $('#total').val(Outstanding);
                let formattedOutstanding = Outstanding.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                $('#total').text(formattedOutstanding);

                if (Outstanding !== 0) {
                    document.querySelector('.modal_but').disabled = true; // ปิดการใช้งานปุ่ม
                } else {
                    document.querySelector('.modal_but').disabled = false; // เปิดการใช้งานปุ่ม
                }
            }
        });
    </script>


    <script>

        $(".toggle-button").on("click", function () {
                const $button = $(this);
                const group = $button.data("group");
                const $parentRow = $button.closest("tr");
                const $childRows = $(`.child-row[data-group="${group}"]`);
                const isExpanded = $button.text() === "⯆";

                $childRows.toggle(!isExpanded);
                $button.text(isExpanded ? "⯈" : "⯆").css({
                    backgroundColor: isExpanded ? "" : "rgb(68, 192, 171)",
                    color: isExpanded ? "" : "white",
                });
                $parentRow.find("td").css("background-color", isExpanded ? "" : "rgb(196, 202, 201)");
            });
        $(document).ready(function () {
            // เลือกทุก input ที่มี class 'expiryDate'
            $('.expiryDate').on('input', function () {
                let input = $(this).val();

                // ลบเครื่องหมาย / ก่อนที่จะจัดรูปแบบใหม่
                input = input.replace(/\D/g, '');

                // ใส่ / หลังจากที่พิมพ์เดือน 2 ตัวแรก
                if (input.length > 2) {
                input = input.substring(0, 2) + '/' + input.substring(2, 4);
                }

                // จำกัดความยาวเป็น 5 ตัวอักษร (MM/YY)
                $(this).val(input.substring(0, 5));
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
            $('.select2Com').select2({
                placeholder: "Please select an option"
            });
            data();
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#deposit_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#deposit_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Expiry').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Expiry').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#arrival').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#arrival').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#departure').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#departure').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#paymentDate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#paymentDate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        const table_name = ['roomTable','fbTable','banquetTable','entertainmentTable','ProposalTable','InvoiceTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        }

        function data() {
            var idcheck = $('#Guest').val();
            var nameID = document.getElementById('idfirst').value;
            if (idcheck) {
                id = idcheck;
            }else{
                id = nameID;
            }
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Data/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var fullname = response.fullname;
                    var Address = response.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                    var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var TaxpayerIdentification = response.Identification;
                    console.log(fullname);

                    $('#taxID').val(TaxpayerIdentification);
                    $('#taxIDspan').text(TaxpayerIdentification);
                    $('#address').val(Address);
                    $('#address2').val(Address2);
                    $('#addressspan').text(Address +' '+Address2);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }


    </script>
    <script>
        $(document).ready(function() {
            $('.modal_but').on('click', function() {

                Preview();
            });
        });
        function getAllPayments() {
                let payments = [];
                $('.payment-container').each(function () {
                    let paymentType = $(this).find('.paymentType').val();
                    let paymentData = { type: paymentType };
                    var paymentDate = $('#paymentDate').val();
                    var Complimentary = $('#Complimentary').val();
                    const checkbox = document.getElementById('flexSwitchCheckChecked');
                    // ตรวจสอบว่าถูกติ๊กหรือไม่
                    if (checkbox.checked) {
                        var ComplimentaryNum = 1;
                    } else {
                        var ComplimentaryNum = 0;
                    }
                    if (paymentType === 'bankTransfer') {
                        paymentData.Date = paymentDate;
                        paymentData.bank = $(this).find('.bankName').val();
                        paymentData.amount = $(this).find('.bankTransferAmount').val();
                        paymentData.datanamebank = paymentData.bank + ' Bank Transfer - Together Resort Ltd';
                    } else if (paymentType === 'creditCard') {
                        paymentData.Date = paymentDate;
                        paymentData.cardNumber = $(this).find('.creditCardNumber').val();
                        paymentData.expiry = $(this).find('.expiryDate').val();
                        paymentData.amount = $(this).find('.creditCardAmount').val();
                        paymentData.datanamebank = `Credit Card No. ${paymentData.cardNumber} Exp. Date: ${paymentData.expiry}`;
                    }else if (paymentType === 'cheque'){
                        paymentData.Date = paymentDate;
                        paymentData.cheque = $(this).find('.cheque').val();
                        paymentData.chequedate = $(this).find('.chequedate').val();
                        paymentData.chequebank = $(this).find('.chequebank').val();
                        paymentData.amount = $(this).find('.chequeamount').val().replace(/,/g, '').split('.')[0];
                        paymentData.datanamebank = `Cheque Bank ${paymentData.chequebank} Cheque Number ${paymentData.cheque}`;
                    }else if (paymentType === 'cash') {
                        if (ComplimentaryNum == 1) {
                            paymentData.Complimentary = Complimentary;
                            let cashAmount = $(this).find('.cashAmount').val();
                            let complimentary = paymentData.Complimentary;
                            cashAmount = parseFloat(cashAmount.replace(/,/g, '').replace(/\.00$/, ''));
                            paymentData.amount = cashAmount + parseFloat(complimentary);
                        }else{
                            paymentData.amount = $(this).find('.cashAmount').val();
                        }
                        paymentData.Date = paymentDate;
                        paymentData.datanamebank = 'Cash';
                    }
                    payments.push(paymentData);
                    console.log(payments);

                });
                // // หลังจากเพิ่มข้อมูลทั้งหมดแล้ว ให้ย้าย 'cash' ไปไว้ท้ายสุด
                // let cashPayment = payments.filter(payment => payment.type === 'cash');
                // let otherPayments = payments.filter(payment => payment.type !== 'cash');

                // // รวมข้อมูลที่ไม่ใช่ cash กับข้อมูล cash ไว้ท้ายสุด
                // payments = [...otherPayments, ...cashPayment];
                return payments;
            }

            function updateTable(allPayments) {
                $('#table-revenueEditBill tbody').html('');
                allPayments.forEach((payment, index) => {
                    let newRow = `
                        <tr >
                            <td>${payment.Date}</td>
                            <td>
                                <span>${payment.datanamebank}</span>
                            </td>
                            <td>${$('#displayReferenceEditBill').text() || '-'}</td>
                            <td>${Number(payment.amount).toLocaleString('en-th', { minimumFractionDigits: 2 })}</td>
                        </tr>
                    `;
                    $('#table-revenueEditBill tbody').append(newRow);
                });
            }

            function Preview() {
                try {
                    var InvoiceID = $('#InvoiceID').val();
                    var idcheck = $('#Guest').val();
                    var reservationNo = $('#reservationNo').val();
                    var company = $('#company').val();
                    var numberOfGuests = $('#numberOfGuests').val();
                    var arrival = $('#arrival').val();
                    var roomNo = $('#roomNo').val();
                    var departure = $('#departure').val();
                    var nameID = document.getElementById('idfirst').value;
                    var note = $('#note').val();
                    // เลือก id ที่จะใช้
                    var id = idcheck ? idcheck : nameID;
                    var ids = InvoiceID;
                    let payments = getAllPayments();
                    updateTable(payments);


                    // AJAX เรียกข้อมูล
                    $.ajax({
                        type: "GET",
                        url: `/Document/BillingFolio/Proposal/invoice/prewive/${id}/${InvoiceID}`,
                        datatype: "JSON",
                        success: function(response) {
                            var fullname = response.fullname;
                            var Address = response.Address + ' ตำบล' + response.Tambon.name_th + ' อำเภอ' + response.amphures.name_th + ' จังหวัด' + response.province.name_th + ' ' + response.Tambon.Zip_Code;
                            var TaxpayerIdentification = response.Identification;
                            var date = response.date;
                            var valid = response.valid;
                            var Time = response.Time;
                            var fullnameCom = response.fullnameCom;
                            // อัปเดตค่าต่างๆ ลงใน HTML
                            $('#displayGuestNameEditBill').text(fullname);
                            $('#displayTaxIDEditBill').text(TaxpayerIdentification);
                            $('#displayAddressEditBill').text(Address);
                            $('#displayReservationNoEditBill').text(reservationNo);
                            $('#displayCompanyEditBill').text(fullnameCom);
                            $('#displayRoomNoEditBill').text(roomNo); // ต้องตรวจสอบว่าคุณมีข้อมูล roomNo ที่ไหนหรือไม่
                            $('#displayArrivalEditBill').text(arrival);
                            $('#displayDepartureEditBill').text(departure);
                            $('#displayNumberOfGuestsEditBill').text(numberOfGuests);
                            $('#date').text(date);
                            $('#dateM').text(Time);
                            $('#Invoicedate').text(valid);
                            $('#displayNoteEditBill').text(note);
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                        }
                    });

                } catch (error) {
                    console.error("Error in Preview(): ", error);
                }
            }
        function submit() {
            document.getElementById("myForm").submit();
        }
    </script>

@endsection

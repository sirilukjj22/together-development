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
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Billing Folio.</small>
                    <div class=""><span class="span1">Edit Billing Folio (แก้ไขใบเรียกเก็บเงิน)</span></div>
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
                                        <span>RE N0. </span>
                                        <span class="hover-effect f-w-bold text-primary">({{$REID}}) </i>
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
                                    <span class="f-w-bold">Total Amount &nbsp;:</span>
                                    <span class="text-success f-w-bold"> {{ number_format($sumpayment, 2, '.', ',') }}</span>
                                </li>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
                        <input type="hidden" class="form-control" id="InvoiceID" value="{{$Invoice_ID}}" />
                        <input type="hidden" class="form-control" id="additional_type" name="additional_type" value="{{$additional_type}}" />
                        <div class="modal fade bd-example-modal-lg" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content rounded-lg">
                                <div class="modal-header modal-h" style="border-radius: 0;">
                                    <h3 class="modal-title text-white">Issue Bill</h3>
                                    <span type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </span>
                                </div>
                                <form id="myForm" action="{{url('/Document/BillingFolio/Proposal/invoice/Generate/update/'.$re->id)}}" method="POST" >
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
                                                        <button type="button" class="toggle-button mr-2" data-group="group1"></button>Receipt
                                                    </span>: <span>{{ number_format($sumpayment-$additional, 2, '.', ',') }}</span>
                                                </li>
                                                @if ($complimentary != 0)
                                                    <li class="parent-row" data-group="group2">
                                                        <span>
                                                            <button type="button" class="toggle-button mr-2" data-group="group2">⯈</button>additional </span>: <span> {{ number_format($additional, 2, '.', ',') }}</span>
                                                    </li>
                                                    <div id="overbill">
                                                        @if ($additional_type == 'Cash'||$additional_type == 'Cash Manual')
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>Cash</span>: <span>{{ number_format($additional-$complimentary, 2, '.', ',') }}</span>
                                                            </li>
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>Complimentary </span>: <span>{{ number_format($complimentary, 2, '.', ',') }}</span>
                                                            </li>
                                                        @else
                                                            <li class="child-row" style="display: none;" data-group="group2">
                                                                <span>H/G Online</span>: <span>{{ number_format($additional, 2, '.', ',') }}</span>
                                                            </li>
                                                        @endif
                                                    </div>
                                                @endif
                                                <li class="parent-row">
                                                    <span style="text-align: center;font-weight: bold;">Total Amount </span>: <span id="total">{{ number_format($sumpayment, 2, '.', ',') }}</span>
                                                </li>
                                            </div>
                                        </section>
                                        <div class="box-form-issueBill">
                                            <h4 >
                                                <span>Customer Details</span>
                                            </h4>
                                            <section class="d-grid-2column p-2" >
                                                <div>
                                                    <label for="" class="star-red">Guest Name <span style="color: red">( Previous Guest : {{$re->fullname}} )</span></label>
                                                    <select name="Guest" id="Guest" class="select2" onchange="data()" required>
                                                        <option value="{{$name_ID}}">{{$name}}</option>
                                                        @foreach($datasub as $item)
                                                            @if ($type == 'Company')
                                                                <option value="{{ $item->ComTax_ID }}" {{$company == $item->ComTax_ID ? 'selected' : ''}}>
                                                                    @php
                                                                        $comtype = DB::table('master_documents')
                                                                            ->where('id', $item->Company_type)
                                                                            ->first();

                                                                        if ($comtype) {
                                                                            if ($comtype->name_th == "บริษัทจำกัด") {
                                                                                $name = "บริษัท " . $item->Companny_name . " จำกัด";
                                                                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                                                                $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                                                                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                                                                $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                                                                            } else {
                                                                                $name = $comtype->name_th . ($item->Companny_name ?? ( $item->first_name . " " . $item->last_name));
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    {{ $name }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $item->GuestTax_ID }}"{{$company == $item->GuestTax_ID ? 'selected' : ''}}>
                                                                    @php
                                                                        $comtype = DB::table('master_documents')
                                                                            ->where('id', $item->Company_type)
                                                                            ->first();

                                                                        if ($comtype) {
                                                                            if ($comtype->name_th == "บริษัทจำกัด") {
                                                                                $name = "บริษัท " . $item->Company_name . " จำกัด";
                                                                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                                                                $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                                                                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                                                                $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                                                                            } else {
                                                                                $name = $comtype->name_th . ($item->Company_name ?? ( $item->first_name . " " . $item->last_name));
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    {{ $name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="star-red" for="reservationNo">Reservation No </label>
                                                    <input type="text" class="form-control" name="reservationNo" id="reservationNo" value="{{$re->reservationNo}}" />
                                                </div>
                                                <div>
                                                    <label class="star-red" for="roomNo">Room No.</label>
                                                    <input type="text" id="roomNo" name="roomNo" class="form-control" value="{{$re->roomNo}}" />
                                                </div>
                                                <div>
                                                    <label class="star-red" for="numberOfGuests">Number of Guests</label>
                                                    <input type="text" id="numberOfGuests" name="numberOfGuests" class="form-control" value="{{$re->numberOfGuests}}" required />
                                                </div>
                                                <div>
                                                    <label for="arrival">Arrival</label>
                                                    <div class="input-group">
                                                        <input type="text" name="arrival" id="arrival" placeholder="DD/MM/YYYY" class="form-control" value="{{$re->arrival}}" required>
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
                                                        <input type="text" name="departure" id="departure" placeholder="DD/MM/YYY" class="form-control" value="{{$re->departure}}" required>
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
                                                    @foreach($productItems as $item)
                                                    <tr>
                                                        <td >{{$re->paymentDate}}</td>
                                                        <td >
                                                            {{ $item->detail }}
                                                        </td>
                                                        <td ></td>
                                                        <td style="text-align: right">{{ number_format($item->amount, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </section>
                                    <section class="receipt-subtotal">

                                    <div class="d-flex  gap-2 flex-wrap justify-content-between w-100" >
                                        <div class="flex-grow-1" style="padding-left: 11%">**{{$re->note}}<span id="displayNoteEditBill"></span></div>

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
                        $('#displayReferenceEditBill').text(reservationNo);
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

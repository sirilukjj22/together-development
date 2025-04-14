@extends('layouts.masterLayout')
<style>
    .bg-card-container-payment {
        position: relative;
        border-radius: 9px;
        background: #1a4441;
        overflow: hidden;
        width: 100%;
        padding: 1em;
        z-index: 0;
        overflow: hidden;
        }
        .pi .card-container-payment {
            display: flex; /* เปลี่ยนจาก grid เป็น flex */
            gap: 0; /* เอาช่องว่างออก */
            align-items: center; /* จัดให้อยู่ตรงกลางแนวตั้ง (ถ้าจำเป็น) */
            justify-content: space-between; /* หรือใช้ตามที่ต้องการ */
            flex-wrap: wrap;
        }

        .pi .bg-card-container-payment::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #2c7f79;
        background-image: linear-gradient(
            to top,
            rgba(0, 0, 0, 0.127),
            rgba(0, 0, 0, 0.308)
        );
        clip-path: polygon(40% 0, 100% 0, 100% 100%, 60% 100%);
        z-index: -1;
        }
        @media (max-width: 1050px) {
        .pi .card-container-payment::before {
            clip-path: polygon(30% 0, 100% 0, 100% 100%, 70% 100%);
        }

        .outstanding-amount {
            display: flex;
            justify-content: space-between !important;
        }

        .pi .card-circle,
        .pi .bg-card-content-white {
            background-color: rgba(255, 255, 255, 0.73);
            box-shadow: rgba(0, 0, 0, 0.399) 0px 3px 8px;
        }
        }

        @media (max-width: 1050px) and (min-width: 700px) {
        .pi .card-container-payment {
            display: flex; /* เปลี่ยนจาก grid เป็น flex */
            flex-direction: column; /* ให้เรียงแนวตั้ง */
            align-items: center; /* จัดให้อยู่กึ่งกลาง */
            margin: auto;
            background: #ddd;
        }

        .pi .card-container-payment > :nth-child(1) {
            width: 100%; /* ให้เต็มความกว้าง */
        }
        }

        @media (max-width: 700px) {
        .pi .card-container-payment {
            display: flex; /* เปลี่ยนจาก grid เป็น flex */
            flex-direction: column; /* ให้เรียงแนวตั้ง */
            padding: 0.6em;
            margin: auto;
            background: #ddd;
        }

        .pi .bg-card-content-white,
        .pi .card-circle {
            background-color: rgba(255, 255, 255, 0.938);
        }
        }

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
    .styled-hr {
        border: none; /* เอาขอบออก */
        border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
    }
    .tech-circle-container-payment {
        position: relative;
        width: 17rem;
        height: 17rem;
        border-radius: 50%;
        display: grid;
        place-content: center;
        color: white;
        margin: 0.1rem;
    }
    .custom-switch-container {
        padding:10px 10px 10px 15px;
        border-radius: 10px; /* ทำขอบมน */
        border: 3px solid #ddd; /* สีขอบ */
        background: linear-gradient(145deg, #ffffff, #e6e6e6); /* ทำให้ดูนูน */
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1),
                    -5px -5px 10px rgba(255, 255, 255, 0.8); /* เงานูน */
        display: inline-block; /* ให้พอดีกับเนื้อหา */
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

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Save successful.</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Save failed!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
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
        <form id="myForm" action="{{url('/Document/BillingFolio/Proposal/invoice/Generate/createmulti/bill/'.$ids)}}" method="POST" >
            @csrf
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-sm-12 col-12 pi">
                        <div class="card-body">
                            <section class="card-container-payment bg-card-container-payment">
                                <section class="card-container bg-card-container">
                                    <section class="card2 gradient-bg">
                                        <div class="card-content bg-card-content-white" class="card-content">
                                            <h5 class="card-title center">Client Details</h5>
                                            <ul class="card-list-withColon">
                                                <li>
                                                <span>Guest Name</span>
                                                @if ($Selectdata == 'Guest')
                                                    <span>{{$fullName}}</span>
                                                @else
                                                    <span> - </span>
                                                @endif
                                                </li>
                                                <li>
                                                <span>Company</span>
                                                @if ($Selectdata == 'Company')
                                                    <span>{{$fullName}}</span>
                                                @else
                                                    <span> - </span>
                                                @endif
                                                </li>
                                                <li>
                                                    <span>Tax ID/Gst Pass</span>
                                                    <span>{{$Identification ?? '-'}}</span>
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
                                                    <p class="f-w-bold fs-3" id="Outstandingall">{{ number_format($sumpayment+$Cash+$Complimentary, 2, '.', ',') }}</p>
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
                                                    <span>PI Payment</span>
                                                        <span class="hover-effect f-w-bold text-primary"> {{ number_format($Payment, 2, '.', ',') }} </i>
                                                    </span>
                                                </li>

                                                @foreach ($DepositID as $key => $item)
                                                <li class="px-2">
                                                    <span>Deposit ID : {{$item->Deposit_ID}}</span>
                                                        <span class="hover-effect f-w-bold text-primary"> - {{ number_format($item->amount, 2) }} </i>
                                                    </span>
                                                </li>
                                                @endforeach
                                                <li class="px-2">
                                                    <span>Additional Charge</span>
                                                        <span class="hover-effect f-w-bold text-primary" id="Additional_Charge"> {{ number_format($Cash+$Complimentary, 2, '.', ',') }} </i>
                                                    </span>
                                                </li>
                                                <li class="px-2">
                                                    <span>Total</span>
                                                        <span class="hover-effect f-w-bold text-primary" id="Total_invoice"> {{ number_format($sumpayment+$Cash+$Complimentary, 2, '.', ',') }} </i>
                                                    </span>
                                                </li>

                                                <li class="px-2">
                                                    <span>Price Before Tax</span>
                                                        <span class="hover-effect f-w-bold text-primary" id="Price_Before_Tax"> {{ number_format($sumpayment+$Cash+$Complimentary/1.07, 2, '.', ',') }} </i>
                                                    </span>
                                                </li>
                                                <li class="px-2">
                                                    <span>Value Added Tax</span>
                                                    <span class="hover-effect f-w-bold text-primary" id="Value_Added_Tax"> {{ number_format($sumpayment+$Cash+$Complimentary - ($sumpayment+$Cash+$Complimentary/1.07), 2, '.', ',') }} </i></span>
                                                </li>
                                            </ul>
                                            <li class="outstanding-amount">
                                                <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                                <span class="text-success f-w-bold" id="Outstanding"> {{ number_format($sumpayment+$Cash+$Complimentary, 2, '.', ',') }}</span>
                                            </li>
                                        </div>
                                    </section>
                                </section>
                                <div class="modal-body mt-3 " style="display: grid;gap:0.5em;background-color: #d0f7ec;">
                                    <div class="col-lg-12 flex-end" style="display: grid; gap:1px" >
                                        <b >Receipt ID : {{$REID}}</b>
                                        <b >Proforma Invoice ID : {{$Invoice_ID}}</b>
                                    </div>

                                    <div class="box-form-issueBill">
                                        <h4 >
                                            <span>Customer Details</span>
                                        </h4>
                                        <section class="d-grid-2column p-2" >
                                            <div>
                                                <label for="" class="star-red">Guest Name</label>
                                                <select name="Guest" id="Guest" class="select2" onchange="data()" disabled>
                                                    @foreach($data_select as $key => $item)
                                                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                    @endforeach
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
                                                    <input type="text" name="arrival" id="arrival" placeholder="DD/MM/YYYY" class="form-control" value="{{$Proposal->checkin}}" readonly>
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
                                                    <input type="text" name="departure" id="departure" placeholder="DD/MM/YYY" class="form-control" value="{{$Proposal->checkout}}" readonly>
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
                                        <h4 style="display: flex;">
                                            <span class="flex-grow-1 text-center">Payment Details</span>
                                            <div class="center sm mb-0 add" style="max-width: 35px;font-size:20px;background-color:rgb(253, 255, 255);border-radius:5px;color:black" >+</div>
                                        </h4>
                                        <section>
                                            @if ($additional_type == 'Cash'||$additional_type == 'Cash Manual')
                                                <div class="form-check form-switch mt-2 "  style="padding-left:35px">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" style="transform:translateY(-20%)"  checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Add Complimentary</label>
                                                </div>
                                            @elseif ($additional_type == 'H/G')
                                                <div class="form-check form-switch mt-2 "  style="padding-left:35px">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" style="transform:translateY(-20%)"  checked disabled>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Add H/G Online</label>
                                                </div>
                                            @else
                                                <div class="form-check form-switch mt-2 "  style="padding-left:35px;display: none;">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" style="transform:translateY(-20%)"  checked disabled>
                                                </div>

                                            @endif

                                            <div id="complimentaryDiv" class="d-none  mt-2">
                                                @if ($Complimentary > 0)
                                                    <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                        <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                        <input type="text"  class="cashcomp form-control" placeholder="Enter cash amount"  value="{{ number_format($Cash, 2, '.', ',') }}" readonly>
                                                        <input type="hidden" id="comp" name="cashcomp" class="cashcomp form-control" placeholder="Enter cash amount"  value="{{ $Cash }}">
                                                    </div>

                                                    <div class="mt-2" style="gap:1em;vertical-align: middle;">
                                                        <div class="bg-paymentType d-flex align-items-center">
                                                            <label for="creditCardAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Complimentary</label>
                                                            <input type="text" id="Complimentary" name="Complimentary" class="form-control" placeholder="Enter Complimentary" value="{{ number_format($Complimentary, 2, '.', ',') }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="styled-hr mt-3"></div>
                                                @endif
                                                <input type="hidden" id="additionalcash" name="additional" class="form-control" value="{{$Cash+$Complimentary}}" readonly>
                                                <input type="hidden" id="typeadditional" class="form-control" value="{{$additional_type}}" readonly>
                                            </div>
                                            <div class="mt-2">
                                                <div>
                                                    <label class="star-red" for="paymentDate">Date</label>
                                                    <div class="input-group">
                                                        <input type="text" name="paymentDate" id="paymentDate" placeholder="DD/MM/YYYY" class="form-control" required>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" >
                                                                <i class="fas fa-calendar-alt"></i>
                                                                <!-- ไอคอนปฏิทิน -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container_new">
                                                <div class="payment-container mt-2">
                                                    <div class="d-grid-120px-230px my-2" style="">
                                                        <label for="paymentType " class="star-red " >Payment Type </label>
                                                        <select name="paymentType" id="paymentType" class="paymentType form-select">
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
                                                            <input type="text" id="Amount" name="cashAmount" class="cashAmount form-control" placeholder="Enter cash amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
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
                                                                <input type="text" id="Amount" name="bankTransferAmount" class="bankTransferAmount form-control" placeholder="Enter transfer amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
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
                                                                <input type="text" id="Amount" name="creditCardAmount" class="creditCardAmount form-control" placeholder="Enter Amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Cheque Input -->
                                                    <div id="chequeInput" class="chequeInput" style="display: none;">
                                                        <div class="bg-paymentType" >
                                                            <div>
                                                                <label for="chequeNumber">Cheque Number</label>
                                                                <select  id="cheque" name="cheque" class="select2 cheque" >
                                                                    <option value="" disabled selected></option>
                                                                    @foreach ($data_cheque as $item)
                                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="d-grid-2column mt-2">

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
                                                                    <input type="text" class="form-control chequeamountAmount" id="Amount" name="chequeamount" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeBank">To Account</label>
                                                                    <select  id="chequebank" name="chequebank" class="ToAccount select2">
                                                                        <option value="SCB 708-226791-3">SCB 708-226791-3</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="deposit_date" id="deposit_date" placeholder="DD/MM/YYYY" class="deposit_date form-control" required>
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
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <input type="hidden" class="form-control" id="additional_type" name="additional_type" value="{{$additional_type}}" />
                        <input type="hidden" class="form-control" id="InvoiceID" name="invoice" value="{{$Invoice_ID}}" />
                        <input type="hidden" id="paymentsDataInput" name="paymentsData">
                        <input type="hidden" id="paymentsDataInputarray" name="paymentsDataArray">
                    </form>
                    <div>
                        <div class="bottom">
                            <div class="flex-end pr-3">
                                <button type="button" class="bt-tg-secondary  md float-right" onclick="BACKtoEdit()">
                                    Back
                                </button>
                                <button type="button" id="nextSteptoSave" class="bt-tg-normal md" onclick="submittoEdit()">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <input type="hidden" id="checkpayment" name="checkpayment">
    <input type="hidden" id="vat_type" name="vat_type" value="{{$vat_type}}">
    <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
    <input type="hidden" id="invoiceamount" value="{{$sumpayment}}">
    <input type="hidden" id="overbillamount" value="{{$Cash+$Complimentary}}">
    <input type="hidden" id="totalamount" name="totalamount">
    <input type="hidden" id="totalpayment" name="totalpayment">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script>
        $(document).ready(function() {
            const checkbox = document.getElementById('flexSwitchCheckChecked');
            console.log(checkbox);
            const div = document.getElementById('complimentaryDiv');
            const inputs = div.querySelectorAll('input');
            var overbillamount = parseFloat($('#overbillamount').val()) || 0;
            var invoiceamount = parseFloat($('#invoiceamount').val()) || 0;
            let over = overbillamount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
            let invoice = invoiceamount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
            let total_amount = 0;
            if (checkbox.checked) {
                total_amount = overbillamount + invoiceamount;
                let Price_Before_Tax = total_amount / 1.07;
                let Value_Added_Tax = total_amount - Price_Before_Tax;
                // จัดรูปแบบตัวเลขก่อนแสดงผล

                let total_amountshow = total_amount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                let Price_Before_Tax_show = Price_Before_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                let Value_Added_Tax_show = Value_Added_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });

                $('#Additional_Charge').text(over);
                $('#Total_invoice').text(total_amountshow);
                $('#Price_Before_Tax').text(Price_Before_Tax_show);
                $('#Value_Added_Tax').text(Value_Added_Tax_show);
                div.classList.remove('d-none');
                inputs.forEach(input => input.disabled = false);
            } else {
                total_amount = invoiceamount;
                let Price_Before_Tax = total_amount / 1.07;
                let Value_Added_Tax = total_amount - Price_Before_Tax;

                let total_amountshow = total_amount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                let Price_Before_Tax_show = Price_Before_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                let Value_Added_Tax_show = Value_Added_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                $('#Additional_Charge').text('0.00');
                $('#Total_invoice').text(total_amountshow);
                $('#Price_Before_Tax').text(Price_Before_Tax_show);
                $('#Value_Added_Tax').text(Value_Added_Tax_show);
                div.classList.add('d-none');
                inputs.forEach(input => input.disabled = true);
            }
            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    total_amount = overbillamount + invoiceamount;

                    let Price_Before_Tax = total_amount / 1.07;
                    let Value_Added_Tax = total_amount - Price_Before_Tax;

                    // จัดรูปแบบตัวเลขก่อนแสดงผล
                    let total_amountshow = total_amount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    let Price_Before_Tax_show = Price_Before_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    let Value_Added_Tax_show = Value_Added_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    $('#Additional_Charge').text(over);
                    $('#Total_invoice').text(total_amountshow);
                    $('#Price_Before_Tax').text(Price_Before_Tax_show);
                    $('#Value_Added_Tax').text(Value_Added_Tax_show);
                    div.classList.remove('d-none');
                    inputs.forEach(input => input.disabled = false);
                    Total();
                } else {
                    total_amount = invoiceamount;
                    let Price_Before_Tax = total_amount / 1.07;
                    let Value_Added_Tax = total_amount - Price_Before_Tax;

                    let total_amountshow = total_amount.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    let Price_Before_Tax_show = Price_Before_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    let Value_Added_Tax_show = Value_Added_Tax.toLocaleString('th-TH', { minimumFractionDigits: 2 });
                    $('#Additional_Charge').text('0.00');
                    $('#Total_invoice').text(total_amountshow);
                    $('#Price_Before_Tax').text(Price_Before_Tax_show);
                    $('#Value_Added_Tax').text(Value_Added_Tax_show);
                    div.classList.add('d-none');
                    inputs.forEach(input => input.disabled = true);
                    Total();
                }
            });
            Total();
        });

        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#paymentDate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                autoApply: true,
                drops: 'up',
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#paymentDate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
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
            $(document).on('keyup', '#Amount', function() {
                var cash =  Number($(this).val());
                $('#totalamount').val(cash);
                Total();
            });
            $('#cheque').on('change', function() {
                console.log(1);
                var id = $('#cheque').val();
                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('/Document/deposit_revenue/cheque/" + id + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        var amount = parseFloat(response.amount || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        var issue_date = response.issue_date;
                        var bank = response.data_bank.name_en;

                        $('#chequedate').val(issue_date);
                        $('#chequebank').val(bank);
                        $('.chequeamountAmount').val(amount);
                        $('#totalamount').val(response.amount);
                        Total();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed: ", status, error);
                    }
                });
            });
            $('#paymentType').change(function () {

                var selectedType = $(this).val();
                var cashInputDiv = document.querySelector(".cashInput"); // ใช้ class
                var cashAmountInput = document.querySelector(".cashAmount"); // ใช้ class

                //-----------------------------
                var bankTransferDiv = document.querySelector(".bankTransferInput"); // เลือก div ด้วย class
                var bankTransferAmount = document.querySelector(".bankTransferAmount"); // เลือก input สำหรับจำนวนเงิน
                //-----------------------------
                var creditCardDiv = document.querySelector(".creditCardInput"); // เลือก div ด้วย class
                var creditCardAmount = document.querySelector(".creditCardAmount");
                var expiryDate = document.querySelector(".expiryDate");
                //-----------------------------
                var chequeDiv = document.querySelector("#chequeInput");
                var ToAccount = document.querySelector(".ToAccount");
                var deposit_date = document.querySelector(".deposit_date");
                var chequebank = document.querySelector("#chequebank");
                var chequeamount = document.querySelector("#chequeamountAmount");
                var chequedate = document.querySelector("#chequedate");
                var cheque = document.getElementById('cheque');
                if (selectedType === 'cash') {
                    cashInputDiv.style.display = "Block";
                    cashAmountInput.disabled = false;
                    bankTransferDiv.style.display = "none"; // แสดง div
                    bankTransferAmount.disabled = true;
                    bankTransferAmount.value = null;
                    creditCardDiv.style.display = "none"; // แสดง div
                    creditCardAmount.disabled = true;
                    creditCardAmount.value = null;
                    expiryDate.disabled = true;
                    chequeDiv.style.display = "none";
                    ToAccount.disabled = true;
                    deposit_date.disabled = true;
                    ToAccount.value = null;
                    cheque.value = "";
                    chequebank.value = "";  // รีเซ็ตค่า chequebank เป็นค่าว่าง
                    $('.chequeamountAmount').val('');
                } else if (selectedType === 'bankTransfer') {
                    cashInputDiv.style.display = "none";
                    cashAmountInput.disabled = true;
                    cashAmountInput.value = null;
                    bankTransferDiv.style.display = "block"; // แสดง div
                    bankTransferAmount.disabled = false;
                    creditCardDiv.style.display = "none"; // แสดง div
                    creditCardAmount.disabled = true;
                    creditCardAmount.value = null;
                    expiryDate.disabled = true;
                    chequeDiv.style.display = "none";
                    ToAccount.disabled = true;
                    deposit_date.disabled = true;
                    ToAccount.value = null;
                    cheque.value = "";
                    $('.chequeamountAmount').val('');
                } else if (selectedType === 'creditCard') {
                    cashInputDiv.style.display = "none";
                    cashAmountInput.disabled = true;
                    cashAmountInput.value = null;
                    bankTransferDiv.style.display = "none"; // แสดง div
                    bankTransferAmount.disabled = true;
                    bankTransferAmount.value = null;
                    creditCardDiv.style.display = "block"; // แสดง div
                    creditCardAmount.disabled = false;
                    expiryDate.disabled = false;
                    chequeDiv.style.display = "none";
                    ToAccount.disabled = true;
                    deposit_date.disabled = true;
                    ToAccount.value = null;
                    cheque.value = "";
                    chequebank.value = "";  // รีเซ็ตค่า chequebank เป็นค่าว่าง

                    $('.chequeamountAmount').val('');
                } else if (selectedType === 'cheque') {
                    cashInputDiv.style.display = "none";
                    cashAmountInput.disabled = true;
                    cashAmountInput.value = null;
                    bankTransferDiv.style.display = "none"; // แสดง div
                    bankTransferAmount.disabled = true;
                    bankTransferAmount.value = null;
                    creditCardDiv.style.display = "none"; // แสดง div
                    creditCardAmount.disabled = true;
                    creditCardAmount.value = null;
                    expiryDate.disabled = true;
                    chequeDiv.style.display = "block";
                    ToAccount.disabled = false;
                    deposit_date.disabled = false;
                }else{
                    cashInputDiv.style.display = "none";
                    cashAmountInput.disabled = true;
                    bankTransferDiv.style.display = "none"; // แสดง div
                    bankTransferAmount.disabled = true;
                    creditCardDiv.style.display = "none"; // แสดง div
                    creditCardAmount.disabled = true;
                    expiryDate.disabled = true;
                    chequeDiv.style.display = "none";
                    ToAccount.disabled = true;
                    deposit_date.disabled = true;
                    ToAccount.value = null;
                    cheque.value = "";
                    chequebank.value = "";  // รีเซ็ตค่า chequebank เป็นค่าว่าง
                    $('.chequeamountAmount').val('');
                    Total();
                }

            });
            var counter = 0;
            $('.add').on('click', function () {
                counter++;
                var ass = 0;
                $('.payment-container').each(function() {
                    ass++;
                });

                console.log('จำนวน .payment-container: ' + ass);
                if (ass >= 4) {
                    console.log('กำลังปิดปุ่ม .add'); // เพิ่มข้อความนี้เพื่อตรวจสอบว่าเข้าเงื่อนไขนี้หรือไม่
                    $('.add').prop('disabled', true);  // ปิดการใช้งานปุ่ม .add
                } else {
                    let paymentMethods = new Set(); // ใช้ Set เพื่อเก็บค่าที่ไม่ซ้ำ
                    var paymentType = ' ';

                    $('.cashInput:visible, .bankTransferInput:visible, .creditCardInput:visible, .chequeInput:visible').each(function () {
                        if ($(this).hasClass('cashInput')) {
                            paymentType = 'cash';
                            paymentMethods.add('Cash Payment');
                        } else if ($(this).hasClass('bankTransferInput')) {
                            paymentType = 'Bank Transfer';
                            paymentMethods.add('Bank Transfer');
                        } else if ($(this).hasClass('creditCardInput')) {
                            paymentType = 'Credit Card';
                            paymentMethods.add('Credit Card');
                        } else if ($(this).hasClass('chequeInput')) {
                            paymentType = 'Cheque';
                            paymentMethods.add('Cheque');
                        }
                    });

                    paymentMethods = Array.from(paymentMethods);
                    console.log(paymentMethods);
                    if (paymentMethods.length > 3) {
                        console.log('กำลังใช้ช่องทางชำระเงิน: ' + paymentMethods.join(', ') + ' กฟก: ' + paymentMethods.length);
                    }else{
                        if (paymentMethods.length  < 0) {
                            if (paymentType == 'cash') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Bank Transfer') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="creditCard">Credit Card</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Credit Card') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">
                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="bankTransfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Cheque') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="bankTransfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }
                        }else if (paymentMethods.length  == 1) {
                            if (paymentType == 'cash') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Bank Transfer') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="creditCard">Credit Card</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                            if (this.value.startsWith('.')) this.value = '0' + this.value;
                                            let parts = this.value.split('.');
                                            if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Credit Card') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">
                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="bankTransfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                   <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (paymentType == 'Cheque') {
                                const newPaymentForm = `
                                <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                    <div class="d-grid-120px-230px my-2" style="position:relative">
                                        <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                            <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                        </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                        <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                            <option value="" disabled selected>Select Payment Type</option>

                                            <option value="cash">Cash</option>
                                            <option value="bankTransfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>

                                    </div>
                                    <div class="cashInput" style="display: none;">
                                        <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                            <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                        </div>
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }
                        }
                        if (paymentMethods.length > 1 && paymentMethods.length < 3) {
                            if (Array.from(paymentMethods).join(', ') === 'Cash Payment, Bank Transfer' ||
                                Array.from(paymentMethods).join(', ') === 'Bank Transfer, Cash Payment') {
                                console.log('Cash Payment, Bank Transfer');

                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>


                                                <option value="creditCard">Credit Card</option>
                                                <option value="cheque">Cheque</option>
                                            </select>

                                        </div>

                                        <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (  Array.from(paymentMethods).join(', ') === 'Cash Payment, Credit Card' ||
                                        Array.from(paymentMethods).join(', ') === 'Credit Card, Cash Payment'){
                                console.log('Cash Payment, Credit Card');
                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                            <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>
                                                <option value="bankTransfer">Bank Transfer</option>
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Credit Card Input -->

                                        <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (  Array.from(paymentMethods).join(', ') === 'Cash Payment, Cheque' ||
                                        Array.from(paymentMethods).join(', ') === 'Cheque, Cash Payment') {
                                console.log('Cash Payment, Cheque');
                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>

                                                <option value="bankTransfer">Bank Transfer</option>
                                                <option value="creditCard">Credit Card</option>

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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (  Array.from(paymentMethods).join(', ') === 'Bank Transfer, Credit Card' ||
                                        Array.from(paymentMethods).join(', ') === 'Credit Card, Bank Transfer') {
                                console.log('Bank Transfer, Credit Card');


                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>
                                                <option value="cash">Cash</option>
                                                <option value="cheque">Cheque</option>
                                            </select>

                                        </div>
                                        <div class="cashInput" style="display: none;">
                                            <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
                                        <!-- Credit Card Input -->

                                        <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (  Array.from(paymentMethods).join(', ') === 'Bank Transfer, Cheque' ||
                                        Array.from(paymentMethods).join(', ') === 'Cheque, Bank Transfer') {
                                console.log('Bank Transfer, Cheque');
                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>
                                                <option value="cash">Cash</option>
                                                <option value="creditCard">Credit Card</option>
                                            </select>

                                        </div>
                                        <div class="cashInput" style="display: none;">
                                            <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }else if (  Array.from(paymentMethods).join(', ') === 'Cheque, Credit Card' ||
                                        Array.from(paymentMethods).join(', ') === 'Credit Card, Cheque') {
                                console.log('Cheque, Credit Card');
                                const newPaymentForm = `
                                    <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                        <div class="d-grid-120px-230px my-2" style="position:relative">
                                            <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                            </button>
                                        <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                            <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                <option value="" disabled selected>Select Payment Type</option>
                                                <option value="cash">Cash</option>
                                                <option value="bankTransfer">Bank Transfer</option>
                                            </select>

                                        </div>
                                        <div class="cashInput" style="display: none;">
                                            <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                            </div>
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                            }
                        } else if (paymentMethods.length >= 3 ) {
                            if (Array.from(paymentMethods).join(', ') === 'Cash Payment, Bank Transfer, Credit Card' ||
                                Array.from(paymentMethods).join(', ') === 'Cash Payment, Credit Card, Bank Transfer' ||
                                Array.from(paymentMethods).join(', ') === 'Bank Transfer, Cash Payment, Credit Card' ||
                                Array.from(paymentMethods).join(', ') === 'Bank Transfer, Credit Card, Cash Payment' ||
                                Array.from(paymentMethods).join(', ') === 'Credit Card, Cash Payment, Bank Transfer' ||
                                Array.from(paymentMethods).join(', ') === 'Credit Card, Bank Transfer, Cash Payment'
                                ) {
                                    const newPaymentForm = `
                                        <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                            <div class="d-grid-120px-230px my-2" style="position:relative">
                                                <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                    <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                                </button>
                                            <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                                <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                    <option value="" disabled selected>Select Payment Type</option>
                                                    <option value="cheque">Cheque</option>
                                                </select>

                                            </div>
                                            <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
                                            <!-- Credit Card Input -->

                                            <div id="chequeInput" class="chequeInput" style="display: none;">
                                        <div class="bg-paymentType">
                                            <div>
                                                <label for="chequeNumber">Cheque Number</label>
                                                <select  id="cheque_${counter}" name="cheque_${counter}" class="select2 cheque" >
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($data_cheque as $item)
                                                        <option value="{{ $item->cheque_number }}">{{ $item->cheque_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class ="d-grid-2column ">
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
                                            </div>
                                    </div>
                                        </div>
                                    `;
                                    $('.payment-container:last').after(newPaymentForm);
                            }
                            else if (
                                Array.from(paymentMethods).join(', ') === 'Cash Payment, Bank Transfer, Cheque' ||
                                Array.from(paymentMethods).join(', ') === 'Cash Payment, Cheque, Bank Transfer' ||
                                Array.from(paymentMethods).join(', ') === 'Bank Transfer, Cash Payment, Cheque' ||
                                Array.from(paymentMethods).join(', ') === 'Bank Transfer, Cheque, Cash Payment' ||
                                Array.from(paymentMethods).join(', ') === 'Cheque, Cash Payment, Bank Transfer' ||
                                Array.from(paymentMethods).join(', ') === 'Cheque, Bank Transfer, Cash Payment'
                                    ) {
                                    const newPaymentForm = `
                                        <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                            <div class="d-grid-120px-230px my-2" style="position:relative">
                                                <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                    <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                                </button>
                                            <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                                <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                    <option value="" disabled selected>Select Payment Type</option>
                                                    <option value="creditCard">Credit Card</option>
                                                </select>

                                            </div>
                                            <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />

                                            <!-- Bank Transfer Input -->

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
                                                        <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    `;
                                    $('.payment-container:last').after(newPaymentForm);
                            }
                            else if (
                                Array.from(paymentMethods).join(', ') === 'Cash Payment, Credit Card, Cheque' ||
                                Array.from(paymentMethods).join(', ') === 'Cash Payment, Cheque, Credit Card' ||
                                Array.from(paymentMethods).join(', ') === 'Credit Card, Cash Payment, Cheque' ||
                                Array.from(paymentMethods).join(', ') === 'Credit Card, Cheque, Cash Payment' ||
                                Array.from(paymentMethods).join(', ') === 'Cheque, Cash Payment, Credit Card' ||
                                Array.from(paymentMethods).join(', ') === 'Cheque, Credit Card, Cash Payment'
                                    ) {
                                    const newPaymentForm = `
                                        <div class="payment-container mt-2" id="paymentcontainer_${counter}">

                                            <div class="d-grid-120px-230px my-2" style="position:relative">
                                                <button type="button" class="btn remove "   id="remove-${counter}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                    <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                                </button>
                                            <label for="paymentType_${counter}" class="star-red " >Payment Type</label>
                                                <select name="paymentType_${counter}" id="paymentType_${counter}" class="paymentType select2" >
                                                    <option value="" disabled selected>Select Payment Type</option>

                                                    <option value="bankTransfer">Bank Transfer</option>


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
                                                        <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                    if (this.value.startsWith('.')) this.value = '0' + this.value;
                                                    let parts = this.value.split('.');
                                                    if (parts.length > 2) this.value = parts[0] + '.' + parts.slice(1).join('');">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Credit Card Input -->


                                        </div>
                                    `;
                                    $('.payment-container:last').after(newPaymentForm);
                            }
                        }
                    }
                }

                $('.select2').select2({
                    placeholder: "Please select an option"
                });
                $('.creditCardNumber').on('input', function() {
                    var input = $(this).val().replace(/\D/g, ''); // Remove all non-digit characters
                    input = input.substring(0, 16); // Limit input to 16 digits
                    // Format the input as xxxx-xxxx-xxxx-xxxx
                    var formattedInput = input.match(/.{1,4}/g)?.join('-') || input;
                    $(this).val(formattedInput);
                });
                $(document).on('click', '.remove', function() {
                    let containerId = $(this).closest('.payment-container').attr('id'); // ดึง ID ของ parent container
                    $('#' + containerId).remove(); // ลบ container ตาม ID
                });

                $(document).on('change', '#paymentType_'+ (counter), function () {
                    const selectedType = $(this).val();
                    var id = $(this).attr('id'); // ดึง ID ของ element
                    var counter = id.split('_')[1];

                    const parentContainer = $(this).closest('.payment-container'); // หา parent container
                    parentContainer.find('.cashInput, .bankTransferInput, .creditCardInput, .chequeInput').hide(); // ซ่อนทุกส่วน
                    parentContainer.find(`#cash_${counter}, #bankTransferAmount_${counter}, #creditCardAmount_${counter}, #chequeamount_${counter} ,#chequedate_${counter},#chequebank_${counter},#cheque_${counter}`).val('');
                    if (selectedType === 'bankTransfer') {
                        parentContainer.find('.bankTransferInput').show();

                    } else if (selectedType === 'creditCard') {
                        parentContainer.find('.creditCardInput').show();

                    } else if (selectedType === 'cheque') {
                        parentContainer.find('.chequeInput').show();

                    }else if (selectedType === 'cash') {
                        parentContainer.find('.cashInput').show();

                    }
                });

                $(document).on('change', '#cheque_'+ (counter), function () {
                    var id = $('#cheque_'+(counter)).val();
                    console.log(counter);

                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('/Document/deposit_revenue/cheque/" + id + "') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(response) {
                            // ดึงข้อมูลจาก response
                            var amount = parseFloat(response.amount || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            var issue_date = response.issue_date;
                            var bank = response.data_bank.name_en;
                            // ใช้ counter ในการตั้งค่าฟิลด์ที่ถูกเพิ่มมาใหม่
                            $('#chequedate_' + (counter)).val(issue_date);
                            $('#chequebank_' + (counter)).val(bank);
                            $('#chequeamount_' + (counter)).val(amount);

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
                $(document).on('keyup', `[id^='cash_${counter}']`, function() {
                    var cash =  Number($(this).val());
                    console.log(cash);
                    Total();
                });
            });
            $('.creditCardNumber').on('input', function() {
                var input = $(this).val().replace(/\D/g, ''); // Remove all non-digit characters
                input = input.substring(0, 16); // Limit input to 16 digits
                // Format the input as xxxx-xxxx-xxxx-xxxx
                var formattedInput = input.match(/.{1,4}/g)?.join('-') || input;
                $(this).val(formattedInput);
            });
            $('.expiryDate').on('input', function() {
                console.log(1);

                let input = $(this).val().replace(/\D/g, ''); // เอาเฉพาะตัวเลข
                input = input.slice(0, 4); // จำกัด 4 ตัว

                // ใส่ `/` หลังตัวเลข 2 ตัวแรก
                if (input.length >= 3) {
                    input = input.replace(/^(\d{2})(\d{0,2})$/, '$1/$2');
                }

                $(this).val(input);
            });
            $(document).on('keyup', '.expiryDate', function() {
                let input = $(this).val().replace(/\D/g, ''); // เอาเฉพาะตัวเลข
                input = input.slice(0, 4); // จำกัด 4 ตัว

                // ใส่ `/` หลังตัวเลข 2 ตัวแรก
                if (input.length >= 3) {
                    input = input.replace(/^(\d{2})(\d{0,2})$/, '$1/$2');
                }

                $(this).val(input);
            });
        });
        function Total() {
            const checkbox = document.getElementById('flexSwitchCheckChecked');


            var cashamount = parseFloat($('#totalamount').val()) || 0;
            var overbillamount = parseFloat($('#overbillamount').val()) || 0;
            var invoiceamount = parseFloat($('#invoiceamount').val()) || 0;
            var compamount = parseFloat($('#comp').val()) || 0;
            var additionalamount = parseFloat($('#additionalcash').val()) || 0;


            var typeadditional = $('#additional_type').val();

            var amountsArray = [];
            var cashArray = [];  // สร้างอาเรย์เพื่อเก็บค่าทั้งหมด
            var creditCardArray = [];
            var bankTransferArray = [];
            var sumpayment = 0;
            var additional = 0;
            if (checkbox.checked) {
                sumpayment = overbillamount + invoiceamount;
            }else{
                sumpayment = invoiceamount;
            }
            additional=additionalamount;


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
            $("[id^='cash_']").each(function () {
                var value = $(this).val(); // ดึงค่าจาก input
                $('.cashAmount').val($(this).val());
                console.log(1);

                if (value) {
                    value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                    if (!isNaN(value)) {
                        cashArray.push(value); // เก็บค่าใน array
                    }

                }
            });
            var sum =0;
            var amounts = amountsArray.reduce((sum, current) => sum + current, 0);
            var cash = cashArray.reduce((sum, current) => sum + current, 0);
            var credit = creditCardArray.reduce((sum, current) => sum + current, 0);
            var bank = bankTransferArray.reduce((sum, current) => sum + current, 0);



            if (checkbox !== null) {
                if (checkbox.checked) {
                    if (typeadditional =='H/G') {
                        var sum = cash+amounts+bank+credit+cashamount;
                    }else{
                        var sum = cash+amounts+bank+credit+cashamount+additional;
                    }
                }else{
                    var sum = cash+amounts+bank+credit+cashamount;
                }
            }else {
                var sum = cash+amounts+bank+credit+cashamount;
            }


            var Outstanding = sumpayment-sum;
            var all = sum;
            let formattedOutstanding = Outstanding.toLocaleString('th-TH', { minimumFractionDigits: 2 });


            $('#total').text(formattedOutstanding);
            $('#totalcomp').text(formattedOutstanding);
            $('#Outstanding').text(formattedOutstanding);
            $('#Outstandingall').text(formattedOutstanding);
            $('#totalamountall').text(all.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' THB');
            $('#totalpayment').val(all);
            $('#checkpayment').val(Outstanding);
            if (Outstanding == 0) {
                getAllPayments();
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
                    $('#Name_Guest').text(fullname);
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
        function getAllPayments() {
            const checkbox = document.getElementById('flexSwitchCheckChecked');
            var additionalamount = parseFloat($('#additionalcash').val()) || 0;

            var invoiceamount = parseFloat($('#invoiceamount').val()) || 0;



            let payments = [];
            let cashAmount = parseFloat($('.cashAmount').val()) || 0;
            var typeadditional = $('#additional_type').val();
            if (checkbox !== null) {
                if (checkbox.checked) {
                    if (typeadditional == 'H/G') {
                        payments.push({
                            type: 'cash',
                            amount: cashAmount,
                            datanamebank: 'Cash'
                        });
                    }else{
                        payments.push({
                            type: 'cash',
                            amount: cashAmount,
                            datanamebank: 'Cash'
                        });
                    }
                }else{
                    $('.payment-container').each(function () {
                        let paymentType = $(this).find('.paymentType').val();
                        if (paymentType == 'cash') {
                            payments.push({
                                type: 'cash',
                                amount: cashAmount,
                                datanamebank: 'Cash'
                            });
                        }

                    });
                }
            }
            $('.payment-container').each(function () {
                let paymentType = $(this).find('.paymentType').val();
                let paymentData = { type: paymentType };
                // ตรวจสอบว่าถูกติ๊กหรือไม่
                if (paymentType === 'bankTransfer') {
                    paymentData.bank = $(this).find('.bankName').val();
                    paymentData.amount = $(this).find('.bankTransferAmount').val();
                    paymentData.datanamebank = paymentData.bank + ' Bank Transfer - Together Resort Ltd';

                } else if (paymentType === 'creditCard') {

                    paymentData.cardNumber = $(this).find('.creditCardNumber').val();
                    paymentData.expiry = $(this).find('.expiryDate').val();
                    paymentData.amount = $(this).find('.creditCardAmount').val();
                    paymentData.datanamebank = `Credit Card No. ${paymentData.cardNumber} Exp. Date: ${paymentData.expiry}`;

                } else if (paymentType === 'cheque'){

                    paymentData.cheque = $(this).find('.cheque').val();
                    paymentData.chequedate = $(this).find('.chequedate').val();
                    paymentData.chequebank = $(this).find('.chequebank').val();
                    let chequeAmount = $(this).find('.chequeamountAmount').val() || "0";
                    paymentData.amount = chequeAmount.replace(/,/g, '');
                    paymentData.datanamebank = `Cheque Bank ${paymentData.chequebank} Cheque Number ${paymentData.cheque}`;
                }

                if (paymentData.amount) {
                    payments.push(paymentData);
                }
            });
            console.log(payments);

            $('#paymentsDataInput').val(JSON.stringify(payments));
            getAllPaymentsTpye();
        }
        function getAllPaymentsTpye() {
            const checkbox = document.getElementById('flexSwitchCheckChecked');
            var additionalamount = parseFloat($('#additionalcash').val()) || 0;

            var invoiceamount = parseFloat($('#invoiceamount').val()) || 0;
            let payments = [];
            let cashAmount = parseFloat($('.cashAmount').val()) || 0;
            var typeadditional = $('#additional_type').val();
            if (checkbox !== null) {
                if (checkbox.checked) {
                    if (typeadditional !== 'H/G') {
                        payments.push({
                            type: 'Complimentary',
                            amount: additionalamount,
                            datanamebank: 'Cash'
                        });
                    }
                }else{
                    $('.payment-container').each(function () {
                        let paymentType = $(this).find('.paymentType').val();
                        if (paymentType == 'cash') {
                            payments.push({
                                type: 'cash',
                                amount: cashAmount,
                                datanamebank: 'Cash'
                            });
                        }

                    });
                }
            }else {
                $('.payment-container').each(function () {
                    let paymentType = $(this).find('.paymentType').val();
                    if (paymentType == 'cash') {
                        payments.push({
                            type: 'cash',
                            amount: cashAmount,
                            datanamebank: 'Cash'
                        });
                    }

                });
            }

            $('.payment-container').each(function () {
                let paymentType = $(this).find('.paymentType').val();
                let paymentData = { type: paymentType };
                // ตรวจสอบว่าถูกติ๊กหรือไม่
                if (paymentType === 'cash') {
                        console.log('cash');
                        paymentData.amount = $(this).find('.cashAmount').val();
                        paymentData.datanamebank = 'Cash';
                    }
                else if (paymentType === 'bankTransfer') {
                    paymentData.bank = $(this).find('.bankName').val();
                    paymentData.amount = $(this).find('.bankTransferAmount').val();
                    paymentData.datanamebank = paymentData.bank + ' Bank Transfer - Together Resort Ltd';

                } else if (paymentType === 'creditCard') {

                    paymentData.cardNumber = $(this).find('.creditCardNumber').val();
                    paymentData.expiry = $(this).find('.expiryDate').val();
                    paymentData.amount = $(this).find('.creditCardAmount').val();
                    paymentData.datanamebank = `Credit Card No. ${paymentData.cardNumber} Exp. Date: ${paymentData.expiry}`;

                } else if (paymentType === 'cheque'){

                    paymentData.cheque = $(this).find('.cheque').val();
                    paymentData.chequedate = $(this).find('.chequedate').val();
                    paymentData.chequebank = $(this).find('.chequebank').val();
                    let chequeAmount = $(this).find('.chequeamountAmount').val() || "0";
                    paymentData.amount = chequeAmount.replace(/,/g, '');
                    paymentData.datanamebank = `Cheque Bank ${paymentData.chequebank} Cheque Number ${paymentData.cheque}`;
                }

                if (paymentData.amount) {
                    payments.push(paymentData);
                }
            });
            $('#paymentsDataInputarray').val(JSON.stringify(payments));
        }

        function BACKtoEdit(){
            event.preventDefault();
            Swal.fire({
                title: "Do you want to go back?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(1);
                    window.location.href = "{!! route('BillingFolio.CheckPI', $Proposal->id) !!}";
                }
            });
        }

        function submittoEdit() {

            var checkpayment = $('#checkpayment').val() || 1;
            console.log(checkpayment);

            if (checkpayment != 0) {
                Swal.fire({
                    icon: "error",
                    text: "Please pay the amount correctly.",
                });
            }else{
                Swal.fire({
                    title: "You want to save information, right?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    confirmButtonColor: "#2C7F7A",
                    dangerMode: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("myForm").submit();
                    }
                });
            }
        }

    </script>
@endsection

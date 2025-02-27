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
            .modal-dialog-custom-90p {
                min-width: 50%;
            }

        </style>
        <style>
            .image-container {
                display: flex;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .image-container img.logo {
                width: 15%; /* กำหนดขนาดคงที่ */
                height: auto;
                margin-right: 20px;
            }

            .image-container .info {
                margin-top: 0;
            }

            .image-container .info p {
                margin: 5px 0;
            }
            .dataTables_empty {
            display: none; /* ซ่อนข้อความ */
            /* หรือสามารถปรับแต่งสไตล์อื่น ๆ ได้ที่นี่ */
            }
            .image-container .titleh1 {
                font-size: 1.2em;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .titleQuotation{
                display:flex;
                justify-content:center;
                align-items:center;
            }
            .select2{
                margin: 4px 0;
                border: 1px solid #ffffff;
                border-radius: 4px;
                box-sizing: border-box;
            }
            .calendar-icon {
                background: #fff no-repeat center center;
                vertical-align: middle;
                margin-right: 5px;
                transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
            }
            .calendar-icon:hover {
                background: #fff ;
                transform: scale(1.1);
            }
            .calendar-icon:active {
                transform: scale(0.9);
            }
            .com {
                display: inline; /* ทำให้เส้นมีความยาวตามข้อความ */
                border-bottom: 2px solid #2D7F7B;
                padding-bottom: 5px;
                font-size: 20px;
                width: fit-content;
            }
            .Profile{
                width: 15%;
            }
            .styled-hr {
                border: none; /* เอาขอบออก */
                border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
            }
            .table-borderless1{
                border-radius: 6px;
                background-color: #109699;
                color:#fff;
            }
            .centered-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .centered-content4 {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                border: 2px solid #6b6b6b; /* กำหนดกรอบสี่เหลี่ยม */
                padding: 20px; /* เพิ่ม padding ภายในกรอบ */
                border-radius: 5px; /* เพิ่มมุมโค้ง (ถ้าต้องการ) */
                height: 120px;
                width: 120px; /* กำหนดความสูงของกรอบ */
            }
            .proposal{
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                top: 0px;
                right: 6;
                width: 70%;
                height: 60px;
                border: 3px solid #2D7F7B;
                border-radius: 10px;
                background-color: #109699;
            }
            .proposalcode{
                top: 0px;
                right: 6;
                width: 70%;
                height: 90px;
                border: 3px solid #2D7F7B;
                border-radius: 10px;
            }
            .btn-space {
                margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
            }
            @media (max-width: 768px) {
                .image-container {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .image-container img.logo {
                    margin-bottom: 20px;
                    width: 50%;
                }
                .Profile{
                    width: 100%;
                }
            }
            .pagination-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .paginate-btn {
                border: 1px solid #2D7F7B;
                background-color: white;
                color: #2D7F7B;
                padding: 8px 16px;
                margin: 0 2px;
                border-radius: 4px;
                cursor: pointer;
            }
            .paginate-btn.active, .paginate-btn:hover {
                background-color: #2D7F7B;
                color: white;
            }
            .paginate-btn:disabled {
                cursor: not-allowed;
                opacity: 0.5;
            }
            div.PROPOSAL {
                padding: 10px ;
                border: 3px solid #2D7F7B;
                border-radius: 10px;
                background-color: #109699;
            }
            div.PROPOSALfirst {
                padding: 10px ;
                border: 3px solid #2D7F7B;
                border-radius: 10px;
                background-color: #109699;
            }
            .readonly-input {
                background-color: #ffffff !important;/* สีพื้นหลังขาว */
            }

            .readonly-input:focus {
                background-color: #ffffff !important;/* ให้สีพื้นหลังขาวเมื่ออยู่ในสถานะโฟกัส */
                box-shadow: none; /* ลบเงาเมื่อโฟกัส */
                border-color: #ced4da; /* ให้เส้นขอบมีสีเทาอ่อนเพื่อให้เหมือนกับการไม่ได้โฟกัส */
            }
            .disabled-input {
                background-color: #E8ECEF !important; /* Light gray background */
                color: #6c757d; /* Gray text color */
                border-color: #ced4da; /* Gray border */
                cursor: not-allowed; /* Change cursor to indicate disabled state */
            }

            /* Style for enabled inputs */
            .table-custom-borderless {
                border-collapse: collapse;
            }

            .table-custom-borderless th,
            .table-custom-borderless td {
                border: none !important;
            }
            td.today {
                background-color: transparent !important; /* ไม่ให้มีสีพื้นหลัง */
            }
            .pay-cutomer-detail {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                width: 100%;
            }

            .pay-cutomer-detail ul {
                padding: 0;
                margin: 0px;
            }

            .pay-cutomer-detail ul:nth-child(1) {
                padding: 0;
                width: 40%;
                padding-right: 5px;

            }



            .pay-cutomer-detail ul:nth-child(2) {
                padding: 0;
                /* width: 300px; */

                width: 60%;

            }
            .pay-cutomer-detail li {
                display: grid;
                grid-template-columns: 180px auto;
                margin: 0;
                padding: 5px ;
                padding-right: 0.5em;

            }

            .pay-cutomer-detail ul:nth-child(2) li {
                grid-template-columns: 160px auto;
            }



            .pay-cutomer-detail li > :nth-child(1)::before {
                content: ":";
                margin-right: 5px;
                float: right;
            }
        </style>
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class="span3">View Deposit Revenue</div>
            </div>
            <div class="col-auto">
                <button class="bt-tg-normal mr-2" style="position: relative" data-toggle="modal" data-target="#modalAddBill">
                    <span >Issue Deposit</span>
                </button>

                <div class="modal fade bd-example-modal-lg" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content rounded-lg">
                        <div class="modal-header modal-h" style="border-radius: 0;">
                            <h3 class="modal-title text-white">Issue Deposit</h3>
                        </div>

                            <div class="modal-body " style="display: grid;gap:0.5em;background-color: #d0f7ec;">
                                <div class="col-lg-12 flex-end" style="display: grid; gap:1px" >
                                    <b >Invoice / Deposit ID : {{$DepositID}}</b>
                                </div>
                                <section class="detail-modal-issueBill">
                                    <div class="p-2" >
                                        <li>
                                            <b>Guest :</b> {{$fullName}}
                                        </li>
                                        <li>
                                            <b>Tax ID/Gst Pass :</b><span id="taxIDspan">{{$Identification}}</span>
                                        </li>
                                        <li>
                                            <b>Address :</b> <span id="addressspan">{{$address}}</span>
                                        </li>
                                    </div>
                                    <div class="payment-details-3g">
                                        <li class="parent-row" data-group="group1">
                                            <span>Invoice / Deposit</span>: <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                        <li class="parent-row">
                                            <span style="text-align: center;font-weight: bold;">Outstanding Amount </span>: <span id="total">{{ number_format($payment - $Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                    </div>
                                </section>
                                <div class="box-form-issueBill">
                                    <section>
                                        <div  >
                                            <div>
                                                <label class="star-red" for="paymentDate">Date</label>
                                                <div class="input-group">
                                                    <input type="text" name="paymentDate" id="paymentDate" placeholder="DD/MM/YYYY" class="form-control" value="{{$deposit->date}}" disabled>
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
                                                @foreach ($revenue as $item)
                                                    @if ($item->PaymentType == 'cash')
                                                        <div class="cashInput my-3">
                                                            <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                                <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                                <input type="text" id="Amount" name="cashAmount" class="cashAmount form-control" placeholder="Enter cash amount" value="{{ number_format($item->Amount, 2, '.', ',') }}" disabled>
                                                            </div>
                                                        </div>
                                                    @elseif ($item->PaymentType == 'bankTransfer')
                                                        <div class="bankTransferInput my-3" >
                                                            <div class=" d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="bankName" class="star-red">Bank</label>
                                                                    <select id="bank" name="bank" class="bankName form-select" disabled>  <option >{{ $item->bank }} Bank Transfer - Together Resort Ltd - Reservation Deposit </option></select>
                                                                </div>
                                                                <div>
                                                                    <label for="bankTransferAmount" class="star-red">Amount</label>
                                                                    <input type="text" id="Amount" name="bankTransferAmount" class="bankTransferAmount form-control" placeholder="Enter transfer amount"value="{{ number_format($item->Amount, 2, '.', ',') }}" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($item->PaymentType == 'creditCard')
                                                        <div class="creditCardInput my-3">
                                                            <div class="d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="creditCardNumber" class="star-red">Credit Card Number</label>
                                                                    <input type="text" id="CardNumber" name="CardNumber" class="creditCardNumber form-control" placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19" value="{{ $item->CardNumber }}" disabled>
                                                                </div>
                                                                <div>
                                                                    <label for="expiryDate" class="star-red">Expiry Date</label>
                                                                    <input type="text" name="Expiry" id="Expiry" class="expiryDate form-control" placeholder="MM/YY" maxlength="5"  value="{{ $item->Expiry }}"disabled>
                                                                </div>
                                                                <div>
                                                                    <label for="creditCardAmount" class="star-red">Amount</label>
                                                                    <input type="text" id="Amount" name="creditCardAmount" class="creditCardAmount form-control" placeholder="Enter Amount"value="{{ number_format($item->Amount, 2, '.', ',') }}" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($item->PaymentType == 'cheque')
                                                        <div id="chequeInput" class="chequeInput" >
                                                            <div class="d-grid-2column bg-paymentType">
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Number</label>
                                                                    <select  id="cheque" name="cheque" class="form-select cheque" disabled >
                                                                        <option>{{ $item->Cheque_Number }}</option>
                                                                    </select>
                                                                </div>
                                                                @php
                                                                    $chuque =  DB::table('receive_cheque')->where('cheque_number',$item->Cheque_Number)
                                                                    ->first();
                                                                    $date = $chuque->receive_date;
                                                                    $bankname =  DB::table('master')->where('id',$chuque->bank_cheque)->first();
                                                                    $bank_name = $bankname->name_en;
                                                                @endphp
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Date</label>
                                                                    <input type="text" class="form-control chequedate" id="chequedate" value="{{$date}}" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Cheque Bank</label>
                                                                    <input type="text" class="form-control chequebank" id="chequebank" name="chequebank_name" value="{{$bank_name}}" readonly />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeAmount">Amount</label>
                                                                    <input type="text" class="form-control chequeamountAmount" id="Amount" name="chequeamount" value="{{ number_format($item->Amount, 2, '.', ',') }}" disabled />
                                                                </div>
                                                                <div>
                                                                    <label for="chequeBank">To Account</label>
                                                                    <select  id="chequebank" name="chequebank" class="ToAccount form-select" disabled>
                                                                        <option >{{ $item->bank }}</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label for="chequeNumber">Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="deposit_date" id="deposit_date" placeholder="DD/MM/YYYY" class="deposit_date form-control" value="{{ $item->paymentDate}}" disabled>
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
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <div class="modal-footer mt-0" style="background-color: rgb(255, 255, 255);">
                                <button type="button" class="bt-tg bt-grey sm" data-dismiss="modal"> Close </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <span>{{$fullName}}</span>
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
                                            <span>{{$Quotation->checkin ?? 'No Check In Date'}}</span>
                                        </li>
                                        <li>
                                            <span>Check Out Date</span>
                                            <span>{{$Quotation->checkout ?? '-'}}</span>
                                        </li>

                                    </ul>
                                </div>
                            </section>
                            <section class="card2 card-circle">
                                <div class="tech-circle-container mx-4" style="background-color: #135d58;">
                                    <div class="outer-glow-circle"></div>
                                    <div class="circle-content">
                                        <p class="circle-text">
                                        <p class="f-w-bold fs-3">{{ number_format($Nettotal - $payment, 2, '.', ',') }}</p>
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
                                    <span>DR N0. </span>
                                    <span class="hover-effect f-w-bold text-primary">({{$DepositID}}) </i>
                                    </span>
                                </li>
                                <li class="px-2">
                                    <span>Price Before Tax</span>
                                        <span class="hover-effect f-w-bold text-primary"> {{ number_format($Nettotal/1.07, 2, '.', ',') }} </i>
                                    </span>
                                </li>
                                <li class="px-2">
                                    <span>Value Added Tax</span>
                                    <span class="hover-effect f-w-bold text-primary"> {{ number_format($Nettotal - ($Nettotal/1.07), 2, '.', ',') }} </i></span>
                                </li>
                                <li class="px-2">
                                    <span>Total</span>
                                    <span class="hover-effect f-w-bold text-primary"> {{ number_format($Nettotal , 2, '.', ',') }} </i></span>
                                </li>
                                <li class="px-2">
                                    <span>Payment</span>
                                    <span class="hover-effect f-w-bold text-red"> -{{ number_format($payment , 2, '.', ',') }} </i></span>
                                </li>
                            </ul>
                            <li class="outstanding-amount">
                                <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                <span class="text-success f-w-bold"> {{ number_format($Nettotal - $payment, 2, '.', ',') }}</span>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-7 col-md-12 col-sm-12 image-container">
                                <img src="{{ asset('assets/images/' . $settingCompany->image) }}" alt="Together Resort Logo" class="logo"/>
                                <div class="info">
                                    <p class="titleh1">{{$settingCompany->name}}</p>
                                    <p>{{$settingCompany->address}}</p>
                                    <p>Tel : {{$settingCompany->tel}}
                                        @if ($settingCompany->fax)
                                            Fax : {{$settingCompany->fax}}
                                        @endif
                                    </p>
                                    <p>Email : {{$settingCompany->email}} Website : {{$settingCompany->web}}</p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-4"></div>
                                    <div class="PROPOSAL col-lg-7" style="transform: translateX(6px)" >
                                        <div class="row">
                                            <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Deposit Revenue</b>
                                            <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$DepositID}}</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4"></div>
                                    <div class="PROPOSALfirst col-lg-7" style="background-color: #ffffff;">
                                        <div class="col-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Issue Date:</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12" id="reportrange1">
                                                    <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;" value="{{$IssueDate}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Expiration Date:</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;" value="{{$ExpirationDate}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">

                                <div class="proposal-cutomer-detail" id="companyTable" >
                                    <ul>
                                    <b class="font-upper com">Deposit Revenue</b>
                                    <li class="mt-3">
                                        <b>Guest Name</b>
                                        <span id="name">{{$fullName}}</span>
                                    </li>
                                    <li>
                                        <b>Address</b>
                                        <span id="Address">{{$address}} </span>
                                        <b></b>
                                    </li>
                                    <li>
                                        <b>Email</b>
                                        <span id="Email">{{$Email}}</span>
                                    </li>
                                    <li>
                                        <b>Tax ID/Gst Pass</b>
                                        <span id="Taxpayer" >{{$Identification}}</span>
                                    </li>
                                    <li>
                                        <b>Phone Number</b>
                                        <span id="Number" >{{$phone->Phone_number}}</span>
                                    </li>
                                    <li> </li>
                                    </ul>
                                    <ul>
                                    <li> </li>
                                    <li></li>
                                    <li> </li>
                                    <li></li>
                                    <li> </li>
                                    <li></li>
                                    <li>
                                        <b>Check In</b>
                                        <span id="checkinpo">{{$Quotation->checkin ?? 'No Check In Date'}}</span>
                                    </li>
                                    <li>
                                        <b>Check Out</b>
                                        <span id="checkoutpo">{{$Quotation->checkout ?? ' '}}</span>
                                    </li>
                                    <li>
                                        <b>Length of Stay</b>
                                        <span style="display: flex"><p id="daypo" class="m-0">{{$Quotation->day}} วัน</p> <p id="nightpo" class="m-0"> {{' , '.$Quotation->night}} คืน </p></span>
                                    </li>
                                    <li>
                                        <b>Number of Guests</b>
                                        <span style="display: flex"><p id="Adultpo" class="m-0">{{$Quotation->adult}} Adult </p><p id="Childrenpo" class="m-0">{{' , '.$Quotation->children}}  Children</p></span>
                                    </li>
                                    </ul>
                                </div>

                        </div>
                        <div class="styled-hr"></div>

                        <div class="row mt-4">
                            <table class=" table table-hover align-middle mb-0" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">No.</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:15%;text-align:center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="display-selected-items">
                                    <tr>
                                        <td style="text-align:center">1</td>
                                        <td style="text-align:left">
                                            อ้างอิงเอกสาร : {{$QuotationID}} เอกสาร Invoice / Deposit : {{$DepositID}}</span> ครั้งที่ {{$Deposit}}
                                        </td>
                                        <td style="text-align:right"><span id="Subtotal"></span>{{ number_format($Subtotal, 2) }} THB </td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                        <td style="text-align:right">Subtotal :</td>
                                        <td style="text-align:right"><span id="SubtotalAll"></span>{{ number_format($Subtotal, 2) }} THB</td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                        <td style="text-align:right">Price Before Tax :</td>
                                        <td style="text-align:right"><span id="Before"></span>{{ number_format($before, 2) }} THB</td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                        <td style="text-align:right">Value Added Tax :</td>
                                        <td style="text-align:right"><span id="Added"></span>{{ number_format($addtax, 2) }} THB</td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                        <td style="text-align:right">Net Total :</td>
                                        <td style="text-align:right"><span id="Total"></span>{{ number_format($Subtotal, 2) }} THB</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="pay-cutomer-detail mt-3" id="companyTable" >
                            <ul>
                            <b class="font-upper" style="font-size: 18px">Payment</b>
                            <li class="mt-3">
                                <b>Date</b>
                                <span id="paymentday">{{$deposit->date}}</span>
                            </li>
                            <li>
                                <b>Total Amount</b>
                                <span id="totalamountall"> {{ number_format($payment, 2) }} THB</span>
                                <b></b>
                            </li>
                            </ul>
                            <ul>
                                <li></li>

                                <div>
                                    <strong  style="font-size: 16px">List</strong>
                                    <table class="table table-borderless align-middle mb-0" id="table-revenueEditBill" style="width:100%">
                                        <tbody>
                                            @if(!empty($revenue))
                                                @foreach ($revenue as $key => $item2)
                                                    <tr>
                                                        <td style="text-align:center">{{$key +1}}</td>
                                                        <td style="text-align:left">{{$item2->detail}}</td>
                                                        <td style="text-align:right">  {{ number_format($item2->Amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </ul>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="wrap-all-company-detail mt-2">
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
                        <div class="col-12 row mt-5">
                            <div class="col-4">

                                <input type="hidden" id="Deposit" name="Deposit" value="{{$Deposit}}">
                                <input type="hidden" name="QuotationID" id="QuotationID" value="{{$QuotationID}}">
                                <input type="hidden" name="sum"  id="sum">
                                <input type="hidden" id="total" name="total" >

                                <input type="hidden" class="form-control" id="fullname" name="fullname" value="{{$fullName}}">
                                <input type="hidden" id="DepositID" name="DepositID" value="{{$DepositID}}">
                            </div>
                            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                    Back
                                </button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
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
                // If user confirms, submit the form
                window.location.href = "{{ route('Deposit.index') }}";
            }
        });
    }
</script>
@endsection

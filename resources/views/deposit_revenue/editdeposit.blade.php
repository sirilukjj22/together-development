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
    <form id="myForm"  action="{{url('/Deposit/generate/Revenue/edit/save/'.$deposit->id)}}" method="POST" onsubmit="event.preventDefault(); submitsave();">
        @csrf
        <div id="content-index" class="body-header border-bottom d-flex py-3">
            <div class="container-xl">
                <div class="row align-items-center">
                    <div class="col sms-header">
                        <div class="span3">Generate Deposit Revenue</div>
                    </div>
                    <div class="col-auto">
                        <div class="modal fade bd-example-modal-lg" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content rounded-lg">
                                    <div class="modal-header modal-h" style="border-radius: 0;">
                                        <h3 class="modal-title text-white">Payment</h3>
                                        <div class="center sm mb-0 add" style="max-width: 35px;font-size:20px;background-color:rgb(253, 255, 255);border-radius:5px;" >+</div>
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
                                                    <span style="text-align: center;font-weight: bold;">Outstanding Amount </span>: <span id="total">{{ number_format(0, 2, '.', ',') }}</span>
                                                </li>
                                            </div>
                                        </section>
                                        <div class="box-form-issueBill">
                                            <section>
                                                <div  >
                                                    <div>
                                                        <label class="star-red" for="paymentDate">Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="paymentDate" id="paymentDate" placeholder="DD/MM/YYYY" class="form-control" value="{{$date}}" required>
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
                                                    @foreach ($list as $key => $item)
                                                        <div class="payment-container mt-2" id="payment-{{$key}}">
                                                            <div class="d-grid-120px-230px my-2 "style="position:relative">
                                                                <button type="button" class="btn removemain "   id="remove{{$key}}" style=" border: none; position: absolute;  top:50% ;right: 2px;transform: translateY(-50%);">
                                                                    <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                                                </button>
                                                                <label for="paymentType{{$key}}" class="star-red">Payment Type</label>
                                                                <select name="paymentType[]" id="paymentType{{$key}}" class="paymentType select2" data-index="{{$key}}">
                                                                    <option value="" disabled {{ empty($item->PaymentType) ? 'selected' : '' }}>Select Payment Type</option>
                                                                    <option value="cash" @selected($item->PaymentType == 'cash')>Cash</option>
                                                                    <option value="bankTransfer" @selected($item->PaymentType == 'bankTransfer')>Bank Transfer</option>
                                                                    <option value="creditCard" @selected($item->PaymentType == 'creditCard')>Credit Card</option>
                                                                    <option value="cheque" @selected($item->PaymentType == 'cheque')>Cheque</option>
                                                                </select>
                                                            </div>

                                                            <!-- กล่อง Input แต่ละประเภท (ซ่อน/แสดงด้วย jQuery) -->
                                                            <div class="payment-inputs cashInput" data-index="{{$key}}">
                                                                <div class="payment-cash" style="display: {{$item->PaymentType == 'cash' ? 'block' : 'none'}};">
                                                                    <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                                                        <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                                                        <input type="text" id="cashAmount_{{$key}}" name="cashAmount{{$key}}" class="cashAmount form-control" placeholder="Enter cash amount"
                                                                        value="{{ $item->PaymentType == 'cash' && $item->Amount && $item->Amount != 0 ? $item->Amount : '0' }}"
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                                    </div>
                                                                </div>

                                                                <div class="payment-bankTransfer bankTransferInput" style="display: {{$item->PaymentType == 'bankTransfer' ? 'block' : 'none'}};">
                                                                    <div class="d-grid-2column bg-paymentType">
                                                                        <div>
                                                                            <label for="bankName" class="star-red">Bank</label>
                                                                            <select id="bank" name="bank{{$key}}" class="bankName select2">
                                                                                @foreach ($data_bank as $item1)
                                                                                    <option value="{{ $item1->name_en }}" {{$item1->name_en == $item->bank ? 'selected' : ''}}>{{ $item1->name_en }} Bank Transfer - Together Resort Ltd - Reservation Deposit </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label for="bankTransferAmount" class="star-red">Amount</label>
                                                                            <input type="text" id="bankTransfer_{{$key}}" name="bankTransferAmount{{$key}}" class="bankTransferAmount form-control" placeholder="Enter transfer amount" value="{{ $item->Amount }}"  oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="payment-creditCard creditCardInput" style="display: {{$item->PaymentType == 'creditCard' ? 'block' : 'none'}};">
                                                                    <div class="d-grid-2column bg-paymentType">
                                                                        <div>
                                                                            <label for="creditCardNumber" class="star-red">Credit Card Number</label>
                                                                            <input type="text" id="CardNumber" name="CardNumber{{$key}}" class="creditCardNumber form-control" value="{{ $item->CardNumber }}" placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19">
                                                                        </div>
                                                                        <div>
                                                                            <label for="expiryDate" class="star-red">Expiry Date</label>
                                                                            <input type="text" name="Expiry" id="Expiry{{$key}}" class="expiryDate form-control" value="{{ $item->Expiry }}" placeholder="MM/YY" maxlength="5">
                                                                        </div>
                                                                        <div>
                                                                            <label for="creditCardAmount" class="star-red">Amount</label>
                                                                            <input type="text" id="creditCard_{{$key}}" name="creditCardAmount{{$key}}" class="creditCardAmount form-control" placeholder="Enter Amount" value="{{ $item->Amount }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="payment-cheque chequeInput" style="display: {{$item->PaymentType == 'cheque' ? 'block' : 'none'}};">
                                                                    <div class="bg-paymentType">
                                                                        <div>
                                                                            <label for="chequeNumber">Cheque Number</label>
                                                                            <select  id="chequeedit" name="cheque{{$key}}" class="form-select cheque">
                                                                                @foreach ($data_cheque as $item)
                                                                                    <option value="{{ $item->cheque_number }} "{{$item->cheque_number == $Cheque_Number ? 'selected' : ''}}>{{ $item->cheque_number }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-grid-2column  mt-2">
                                                                            <div>
                                                                                <label for="chequeNumber">Cheque Date</label>
                                                                                <input type="text" class="form-control chequedate" id="chequedate" value="{{$issue_date}}" readonly />
                                                                            </div>
                                                                            <div>
                                                                                <label for="chequeNumber">Cheque Bank</label>
                                                                                <input type="text" class="form-control chequebank" id="chequebank" name="chequebank_name" value="{{$bankname}}" readonly />
                                                                            </div>
                                                                            <div>
                                                                                <label for="chequeAmount">Amount</label>
                                                                                <input type="text" class="form-control chequeamountAmount" id="chequeamountAmount_{{$key}}" name="chequeamount" value="{{ $amount }}" readonly />
                                                                                <input type="hidden" class="form-control " id="key"  value="{{ $key }}" readonly />
                                                                            </div>
                                                                            <div>
                                                                                <label for="chequeBank">To Account</label>
                                                                                <select  id="chequebank" name="chequebank" class="ToAccount form-select">
                                                                                    <option value="SCB 708-226791-3">SCB 708-226791-3</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-0" style="background-color: rgb(255, 255, 255);">
                                        <button type="button" class="bt-tg bt-grey sm" data-dismiss="modal"> Close </button>
                                        <button type="button" id="modal_but" class="bt-tg sm modal_but" data-dismiss="modal" onclick="Preview()">Preview</button>
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
                                                <p class="f-w-bold fs-3">{{ number_format($Nettotal, 2, '.', ',') }}</p>
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
                                    </ul>
                                    <li class="outstanding-amount">
                                        <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                        <span class="text-success f-w-bold"> {{ number_format($Nettotal, 2, '.', ',') }}</span>
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
                                    <ul><b class="font-upper" style="font-size: 18px">
                                            <button type="button" class="bt-tg-normal mr-2" style="position: relative" data-toggle="modal" data-target="#modalAddBill">
                                                <span >Payment</span>
                                            </button>
                                        </b>

                                    <li class="mt-3">
                                        <b>Date</b>
                                        <span id="paymentday">{{$date}}</span>
                                    </li>
                                    <li>
                                        <b>Total Amount</b>
                                        <span id="totalamountall">{{ number_format($payment, 2) }} THB</span>
                                        <b></b>
                                    </li>
                                    </ul>
                                    <ul>
                                        <li></li>
                                        <div>
                                            <strong  style="font-size: 16px">List</strong>
                                            <table class="table table-borderless align-middle mb-0" id="table-revenueEditBill" style="width:100%">
                                                <tbody>
                                                    @foreach ($list as $key => $item)
                                                        <tr>
                                                            <td style="text-align:center">{{$key + 1}}</td>
                                                            <td style="text-align:left">{{$item->detail}}</td>
                                                            <td style="text-align:right">{{ number_format($item->Amount, 2) }} THB</td>
                                                        </tr>
                                                    @endforeach
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
                                        <input type="hidden" id="total" name="total">
                                        <input type="hidden" class="form-control" id="fullname" name="fullname" value="{{$fullName}}">
                                        <input type="hidden" id="DepositID" name="DepositID" value="{{$DepositID}}">
                                    </div>
                                    <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                            Back
                                        </button>
                                        <button type="button" class="btn btn-color-green lift btn_modal"  onclick="submitsave()">Save</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="vat_type" name="vat_type" value="{{$vat_type}}">
    <input type="hidden" id="amout" name="amout" value="{{$Nettotal}}" >
    <input type="hidden" id="totalamount" name="totalamount">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.removemain', function() {
            $(this).closest('.payment-container').remove(); // ลบ .payment-container ได้ตรง ๆ
        });
        $('.paymentType').change(function() {
            var index = $(this).data('index');
            var selectedType = $(this).val();
            var selectedValues = [];

            $('.paymentType').each(function() {

                var value = $(this).val()?.toString().trim();
                if (value) {
                    if (selectedValues.includes(value)) { // ใช้ includes() แทน indexOf() (อ่านง่ายกว่า)
                        console.log('ค่าซ้ำกัน: ' + value);
                        alert('This payment type has already been selected.');
                        // $('.payment-inputs[data-index="' + index + '"] > div').hide();
                        $('.payment-inputs[data-index="' + index + '"] .payment-' + selectedType).hide();
                        $('.payment-inputs[data-index="' + index + '"] .payment-' + selectedType + ' input').val('0');


                    } else {
                        selectedValues.push(value);
                        $('.payment-inputs[data-index="' + index + '"] > div').hide();
                        $('.payment-inputs[data-index="' + index + '"] .payment-' + selectedType).show();
                    }
                }
            });


        });

        $('.paymentType, .cashAmount, .bankTransferAmount, .creditCardAmount, .chequeamountAmount').on('change input', function() {
            TotalEdit();
        });
        $('#chequeedit').on('change', function() {
            console.log(1);
            var id = $('#chequeedit').val();
            var key = $('#key').val();
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Document/deposit_revenue/cheque/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var amount = response.amount;
                    var issue_date = response.issue_date;
                    var bank = response.data_bank.name_en;

                    $('#chequedate').val(issue_date);
                    $('#chequebank').val(bank);
                    $('#chequeamountAmount_' + key).val(amount);

                    TotalEdit();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
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

        $(document).on('keyup', '.cashAmount', function() {
            var cash =  Number($(this).val());
            $('.cashAmount').val(cash);
        });
        $(document).on('keyup', '.bankTransferAmount', function() {
            var cash =  Number($(this).val());
            $('.bankTransferAmount').val(cash);
        });
        $(document).on('keyup', '.creditCardAmount', function() {
            var cash =  Number($(this).val());
            $('.creditCardAmount').val(cash);
        });
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
            console.log(selectedType);

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
                Total();
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
                Total();
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
                Total();
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
                Total();
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
                console.log('ยังไม่ถึง 3 .payment-container');

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

                paymentMethods = Array.from(paymentMethods); // แปลง Set กลับเป็นอาร์เรย์
                console.log(paymentMethods.length);
                if (paymentMethods.length == 0) {
                    console.log(1);

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
                                        <option value="creditCard">Credit Card</option>
                                        <option value="cheque">Cheque</option>
                                    </select>

                                </div>
                                <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
                                <div class="cashInput" style="display: none;">
                                    <div class="bg-paymentType d-flex align-items-center" style="gap:1em;vertical-align: middle;">
                                        <label for="cashAmount" class="star-red" style="white-space: nowrap;transform: translateY(3px);">Cash Amount</label>
                                        <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount">
                                    </div>
                                </div>
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
                                            <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                            <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                        $('.container_new').append(newPaymentForm);
                }
                else if (paymentMethods.length > 3) {
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount">
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount">
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount">
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
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount">
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount">
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value="">
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);

                        }
                    }

                    if (paymentMethods.length > 1 && paymentMethods.length < 3) {

                        if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Bank Transfer')) {
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                            $('.payment-container:last').after(newPaymentForm);
                        }else if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Credit Card')) {
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Credit Card Input -->

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
                            $('.payment-container:last').after(newPaymentForm);
                        }else if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Cheque')) {
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            `;
                            $('.payment-container:last').after(newPaymentForm);
                        }else if (paymentMethods.includes('Bank Transfer') && paymentMethods.includes('Credit Card')) {
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control cheque-amount" id="chequenumber" readonly value="${counter}" />
                                    <!-- Credit Card Input -->

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
                            $('.payment-container:last').after(newPaymentForm);
                        }else if (paymentMethods.includes('Bank Transfer') && paymentMethods.includes('Cheque')) {
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            `;
                            $('.payment-container:last').after(newPaymentForm);
                        }else if (paymentMethods.includes('Credit Card') && paymentMethods.includes('Cheque')) {
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
                                            <input type="text" id="cash_${counter}" name="cashAmount_${counter}" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                                <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('.payment-container:last').after(newPaymentForm);
                        }
                    } else if (paymentMethods.length >= 3 ) {

                        if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Credit Card') && paymentMethods.includes('Bank Transfer') ) {
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
                                $('.payment-container:last').after(newPaymentForm);
                        }
                        else if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Cheque') && paymentMethods.includes('Bank Transfer') ) {
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
                                                    <input type="text" id="creditCardAmount_${counter}" name="creditCardAmount_${counter}" class="creditCardAmount form-control" placeholder="Enter Amount" value=""oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                `;
                                $('.payment-container:last').after(newPaymentForm);
                        }
                        else if (paymentMethods.includes('Cash Payment') && paymentMethods.includes('Cheque') && paymentMethods.includes('Credit Card') ) {
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
                                                    <input type="text" id="bankTransferAmount_${counter}" name="bankTransferAmount_${counter}" class="bankTransferAmount form-control" placeholder="Enter transfer amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
        $('.modal_but').on('click', function() {
            Preview();
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
    $(function() {
        var start = moment();
        var end = moment().add(7, 'days');
        function cb(start, end) {
            $('#datestart').val(start.format('DD/MM/Y'));
            $('#dateex').val(end.format('DD/MM/Y'));
            $('#issue_date_document').text(start.format('DD/MM/Y'));
            $('#issue_date_document1').text(start.format('DD/MM/Y'));
        }
        $('#reportrange1').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '3 Days': [moment(), moment().add(3, 'days')],
                '7 Days': [moment(), moment().add(7, 'days')],
                '15 Days': [moment(), moment().add(15, 'days')],
                '30 Days': [moment(), moment().add(30, 'days')],
            }
        },
        cb);
        cb(start, end);
    });
    function TotalEdit() {
        var total = 0;

        $('.paymentType').each(function() {
            var index = $(this).data('index');
            var selectedType = $(this).val();

            // ดึงค่าตาม Payment Type ที่เลือก
            if (selectedType === 'cash') {
                var cashAmount = parseFloat($('#cashAmount_' + index).val()) || 0;
                total += cashAmount;

            }
            else if (selectedType === 'bankTransfer') {
                var bankAmount = parseFloat($('#bankTransfer_' + index).val()) || 0;
                total += bankAmount;
            }
            else if (selectedType === 'creditCard') {
                var creditAmount = parseFloat($('#creditCard_' + index).val()) || 0;
                total += creditAmount;
            }
            else if (selectedType === 'cheque') {
                var chequeAmount = parseFloat($('#chequeamountAmount_' + index).val()) || 0;
                total += chequeAmount;
            }
        });
        $('#totalamount').val(total);
        Total();
    }
    function data() {
        var idcheck = $('#Guest').val();
        var nameID = document.getElementById('idfirst').value;
        var companyTable = document.getElementById('companyTable');
        var guestTable = document.getElementById('guestTable');
        if (idcheck) {
            id = idcheck;
        }else{
            id = nameID;
        }
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Document/deposit_revenue/Data/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(response) {
                var phone = response.phone.Phone_number;
                var Selectdata = response.Selectdata;
                var fullname = response.fullname;
                var email = response.email;
                var Address = response.Address + ' '+ 'ตำบล'+ response.Tambon.name_th + ' '+'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                var TaxpayerIdentification = response.Identification;
                console.log(Selectdata);
                $('#fullname').val(fullname);
                $('#name').text(fullname);
                $('#Address').text(Address);
                $('#Email').text(email);
                $('#Taxpayer').text(TaxpayerIdentification);
                $('#Number').text(phone);

                $('#nameid').val(id);
                if (Selectdata == 'Company') {
                    companyTable.style.display = 'flex';
                    guestTable.style.display = 'none';
                }else{
                    companyTable.style.display = 'none';
                    guestTable.style.display = 'flex';
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    }
    function Total() {
        var uniqueValues = new Set();
        var cashamount = parseFloat($('#totalamount').val()) || 0;
        var sumpayment = parseFloat($('#amout').val()) || 0;
        var amountsArray = [];
        var cashArray = [];  // สร้างอาเรย์เพื่อเก็บค่าทั้งหมด
        var creditCardArray = [];
        var bankTransferArray = [];
        $("[id^='chequeamount_']").each(function () {
            var value = $(this).val(); // ดึงค่าจาก input
            if (value) {
                value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข


                if (!isNaN(value)) {
                    if (!uniqueValues.has(value) && value !== 0) {
                        uniqueValues.add(value);
                        amountsArray.push(value);
                    }
                    console.log(amountsArray);
                }
            }
        });
        $("[id^='creditCardAmount_']").each(function () {
            var value = $(this).val(); // ดึงค่าจาก input
            if (value) {
                value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข
                console.log(value);

                if (!isNaN(value)) {
                    if (!uniqueValues.has(value) && value !== 0) {
                        uniqueValues.add(value);
                        creditCardArray.push(value);
                    }

                }
            }
        });
        $("[id^='bankTransferAmount_']").each(function () {
            var value = $(this).val(); // ดึงค่าจาก input
            if (value) {
                value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข

                if (!isNaN(value)) {
                    if (!uniqueValues.has(value) && value !== 0) {
                        uniqueValues.add(value);
                        bankTransferArray.push(value);
                    }
                    console.log(bankTransferArray);
                }
            }
        });
        $("[id^='cash_']").each(function () {
            var value = $(this).val(); // ดึงค่าจาก input
            if (value) {
                value = parseFloat(value.replace(/,/g, '')); // แปลงเป็นตัวเลข

                if (!isNaN(value)) {
                    if (!uniqueValues.has(value) && value !== 0) {
                        uniqueValues.add(value);
                        cashArray.push(value);
                    }
                    console.log(cashArray);
                }
            }
        });



        var sum =0;
        var amounts = amountsArray.reduce((sum, current) => sum + current, 0);
        var cash = cashArray.reduce((sum, current) => sum + current, 0);
        var credit = creditCardArray.reduce((sum, current) => sum + current, 0);
        var bank = bankTransferArray.reduce((sum, current) => sum + current, 0);

        var sum = cash+amounts+bank+credit+cashamount;
        console.log(amounts);
        console.log(cash);
        console.log(credit);
        console.log(bank);
        console.log(sum);
        var Outstanding = sumpayment-sum;
        var all = sum;
        let formattedOutstanding = Outstanding.toLocaleString('th-TH', { minimumFractionDigits: 2 });
        $('#total').text(formattedOutstanding);
        $('#totalamountall').text(all.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' THB');

        if (Outstanding !== 0) {
            document.querySelector('#modal_but').disabled = true; // ปิดการใช้งานปุ่ม
        } else {
            document.querySelector('#modal_but').disabled = false; // เปิดการใช้งานปุ่ม
        }
    }
    function getAllPayments() {

        let payments = [];
        $('.paymentType').each(function () {
            let index = $(this).data('index');
            let paymentType = $(this).val();
            let paymentData = { type: paymentType };
            // ตรวจสอบว่าถูกติ๊กหรือไม่


            let container = $(this).closest('.payment-container');

            if (paymentType === 'bankTransfer') {
                paymentData.bank = container.find('.bankName').val();
                paymentData.amount = container.find('.bankTransferAmount').val();
                paymentData.datanamebank = paymentData.bank + ' Bank Transfer - Together Resort Ltd';
            } else if (paymentType === 'creditCard') {
                paymentData.cardNumber = container.find('.creditCardNumber').val();
                paymentData.expiry = container.find('.expiryDate').val();
                paymentData.amount = container.find('.creditCardAmount').val();
                paymentData.datanamebank = `Credit Card No. ${paymentData.cardNumber} Exp. Date: ${paymentData.expiry}`;
            } else if (paymentType === 'cheque') {
                paymentData.cheque = container.find('.cheque').val();
                paymentData.chequedate = container.find('.chequedate').val();
                paymentData.chequebank = container.find('.chequebank').val();
                paymentData.amount = container.find('.chequeamountAmount').val().replace(/,/g, '').split('.')[0];
                paymentData.datanamebank = `Cheque Bank ${paymentData.chequebank} Cheque Number ${paymentData.cheque}`;
            } else if (paymentType === 'cash') {
                paymentData.amount = container.find('.cashAmount').val();
                paymentData.datanamebank = 'Cash';
            }
            payments.push(paymentData);
            console.log(payments);

        });
        return payments;
    }
    function updateTable(allPayments) {
        $('#table-revenueEditBill tbody').html('');
        allPayments.forEach((payment, index) => {
            let newRow = `
                <tr >
                    <td>${index + 1}</td>
                    <td>
                        ${payment.datanamebank}
                    </td>
                    <td>${Number(payment.amount).toLocaleString('en-th', { minimumFractionDigits: 2 })} THB</td>
                </tr>
            `;
            $('#table-revenueEditBill tbody').append(newRow);
        });
    }
    function Preview() {

        var paymentDate = $('#paymentDate').val();
        let payments = getAllPayments();
        updateTable(payments);
        $('#paymentday').text(paymentDate);
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
                // If user confirms, submit the form
                window.location.href = "{{ route('Proposal.index') }}";
            }
        });
    }
    function submitsave() {
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
    </script>

@endsection

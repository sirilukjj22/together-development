
@extends('layouts.masterLayout')
<style>
    .wrap-top-show-totalRecieved {
    display: flex;
    justify-content: space-between;

    background-color: rgb(99, 190, 185);
    background-image: linear-gradient(
        to right,
        rgba(73, 144, 144, 0.786),
        rgba(2, 43, 41, 0.685)
    );
    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
        rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    flex-wrap: wrap;
    padding: 0px;
    border-radius: 7px;
    font-size: 0.9em;
    }

    @media (max-width: 768px) {
    .wrap-top-show-totalRecieved {
        flex-direction: column;
    }
    }
    .wrap-top-show-totalRecieved > div:nth-child(1) {
    padding: 7px 10px;
    flex-grow: 1;
    }
    .wrap-top-show-totalRecieved > div:nth-child(1) table {
    width: 100%;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
        rgba(217, 223, 227, 0.15) 0px 2px 6px 2px;
    border-radius: 7px;
    overflow: hidden;
    }

    .wrap-top-show-totalRecieved > div:nth-child(1) table thead tr {
    background-color: rgb(99, 190, 185);
    background-image: linear-gradient(
        to right,
        rgba(5, 53, 53, 0.718),
        rgba(2, 43, 41, 0.685)
    );
    }

    .wrap-top-show-totalRecieved > div:nth-child(1) table thead tr th {
    text-align: center;
    padding: 5px 10px;
    color: white;
    border-left: 1px solid rgba(255, 255, 255, 0.37);
    /* border: rgba(255, 255, 255, 0.37) 1px solid; */
    }
    .wrap-top-show-totalRecieved
    > div:nth-child(1)
    table
    thead
    tr
    th:nth-child(1) {
    border: none;
    }

    .wrap-top-show-totalRecieved > div:nth-child(1) table tbody tr {
    background-color: rgba(187, 185, 185, 0.074);
    text-align: center;
    }

    .wrap-top-show-totalRecieved > div:nth-child(1) table tr td {
    text-align: center;
    color: rgb(27, 26, 26);
    border: rgba(181, 181, 181, 0.696) 1px solid;
    background-color: rgba(243, 241, 241, 0.874);
    }

    .wrap-top-show-totalRecieved > div:nth-child(2) {
    display: grid;
    padding: 7px;
    gap: 6px;
    background-color: rgb(4, 59, 56);
    background-image: linear-gradient(
        to right,
        rgba(2, 65, 47, 0.307),
        rgba(4, 75, 71, 0.685)
    );
    color: rgb(73, 72, 72);
    border-radius: 0px 7px 7px 0px;
    }

    .wrap-top-show-totalRecieved > div:nth-child(2) span {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 19px;
    border: 1px solid rgba(215, 212, 212, 0.31);
    background-color: rgba(255, 255, 255, 0.999);
    gap: 1em;
    border-radius: 5px;
    }

    .table-Custom-Spilte-Bill {
    width: 100%;
    border-radius: 7px;
    border: none;
    font-size: 0.9em;
    }

    .table-Custom-Spilte-Bill tr {
    border-bottom: 1px solid rgba(154, 167, 165, 0.616);
    }

    .table-Custom-Spilte-Bill tbody tr:nth-last-child(1) {
    border-bottom: none;
    }

    .table-Custom-Spilte-Bill tr th {
    /* border: rgba(205, 203, 203, 0.978) 1px solid; */
    border-left: 1px solid grey;
    color: white;
    padding: 7px 10px;
    text-align: center;
    white-space: nowrap;
    background-color: rgba(20, 93, 93, 0.786);
    background-image: linear-gradient(
        to bottom,
        rgba(42, 91, 91, 0.786),
        rgba(2, 43, 41, 0.774)
    );
    overflow: hidden;
    }
    .table-Custom-Spilte-Bill tr th:nth-child(1),
    .table-Custom-Spilte-Bill tr td:nth-child(1) {
    border: none;
    }

    .table-Custom-Spilte-Bill tbody tr td {
    /* border: rgba(205, 203, 203, 0.978) 1px solid; */
    border-left: 1px solid rgba(154, 167, 165, 0.271);
    background-color: white;
    padding: 2px 5px;
    padding-top: 10px;
    vertical-align: top;
    }

    .table-Custom-Spilte-Bill tr td div.wrap-column-bill {
    display: flex;
    align-items: center;
    gap: 5px;
    }
    .br {
    border: red 1px solid;
    }

    .table-Custom-Spilte-Bill input[type="number"],
    .table-Custom-Spilte-Bill select {
    border: 1px solid rgb(227, 227, 227);
    height: 2.3em;
    vertical-align: middle;
    border-radius: 5px;
    padding: 0px 5px;
    font-size: 0.9em;
    min-width: 80px;
    }
    .customer-details-container {
    min-width: 350px;
    padding: 0px;
    font-size: 0.9em;
    }
    .toggle-details {
    cursor: pointer;
    color: rgb(98, 104, 103);
    background-color: rgb(255, 255, 255);
    padding: 0px;
    margin-top: 5px;
    text-align: start;
    border-top: 1px solid rgba(172, 199, 194, 0.521);
    color: rgb(129, 139, 129);
    }
    .toggle-details i,
    .toggle-content i {
    color: rgb(0, 198, 162);
    }

    .customer-details {
    list-style: none;
    padding: 0;
    min-width: 350px;
    width: 350px;
    }
    .customer-details li {
    display: grid;
    grid-template-columns: 130px 1fr;
    }
    .customer-details li > :nth-child(1) {
    color: rgb(1, 88, 74);
    font-weight: 500;
    }

    .customer-details li > :nth-child(2) {
    color: rgb(85, 86, 86);
    }

    .auto-resize {
    min-height: 0px;
    max-height: 150px;
    overflow-y: auto;
    resize: none; /* ปิดการปรับขนาดเอง */
    padding: 5px;
    border-radius: 5px;
    border: none;
    font-size: 14px;
    }

    /* ปรับแต่ง Scrollbar ให้สวยงาม */
    .auto-resize::-webkit-scrollbar {
    width: 1px; /* กำหนดความกว้างของ Scrollbar */
    }

    .auto-resize::-webkit-scrollbar-track {
    background: #f1f1f1; /* สีพื้นหลังของแทร็ก */
    border-radius: 10px;
    }

    .auto-resize::-webkit-scrollbar-thumb {
    background: linear-gradient(
        to bottom,
        #6c757d,
        #495057
    ); /* Gradient */
    border-radius: 10px;
    }

    .auto-resize::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(
        to bottom,
        #495057,
        #343a40
    ); /* เปลี่ยนสีเมื่อ Hover */
    }

    /* สไตล์สำหรับการเลื่อนแนวนอน */
    .table-scroll {
    max-width: 100%;
    overflow: auto;
    }

    /* ปรับแต่ง Scrollbar */
    .table-scroll::-webkit-scrollbar {
    height: 5px;
    width: 5px;
    }

    .table-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar-thumb {
    background: linear-gradient(to right, #026668dd, #029f92);
    border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(
        to right,
        #026668dd,
        #01e4c2
    ); /* สีเข้มขึ้นเมื่อ Hover */
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
</style>
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class="span3">Create Spilte Bill</div>
            </div>
            <div class="col-auto">
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div id="content-index" class="body d-flex py-lg-4 py-3">
    <div class="container-xl">
        <div class="row clearfix">
            <div class="col-sm-12 col-12 pi">
                <div class="card-body">
                    <h6>Payment</h6>
                    <section class="wrap-top-show-totalRecieved">
                        <div class="flex-grow-1">
                        <table>
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Payment by</th>
                                <th>Amount</th>
                                <th>Use for Bill</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @if(!empty($paymentsDataArray))
                                    @foreach ($paymentsDataArray as $key => $item)
                                    @php
                                        $total += $item['amount'];
                                    @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($item['type'] == 'bankTransfer')
                                                    Bank Transfer(SCB)
                                                @elseif ($item['type'] =='cash')
                                                    Cash
                                                @elseif ($item['type'] =='creditCard')
                                                    Credit Card
                                                @elseif ($item['type'] =='cheque')
                                                    Cheque
                                                @else
                                                    {{$item['type']}}
                                                @endif
                                            </td>
                                            <td>{{ number_format($item['amount'], 2, '.', ',') }}
                                                <input type="hidden" id="{{$item['type']}}" value="{{$item['amount']}}">
                                            </td>
                                            <td><span id="payment-{{ $item['type'] }}"></span></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" class="text-center">Total</td>
                                <td>{{number_format($total, 2, '.', ',')}}</td>
                                <td class="text-center"></td>
                            </tr>
                            </tfoot>
                        </table>
                        </div>
                        <div class="rt2">
                            <span>Total Amount : <b id="totalReceived">{{number_format($total, 2, '.', ',')}}</b> </span>
                            <span
                                >Total Add : <b class="text-success" id="totalAmount">0.00</b>
                            </span>
                            <span
                                >Total Remaining :
                                <b class="text-danger" id="totalRemaining">{{number_format($total, 2, '.', ',')}}</b>
                            </span>
                        </div>
                    </section>
                </div>
                <form id="myForm" action="{{ route('BillingFolio.savemulti') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$additional_amount}}" name="additional">
                    <div class="card-body">
                        <div class="table-scroll">
                            <table class="table-Custom-Spilte-Bill">
                                <thead>
                                    <tr>
                                        <th style="width: 100px">Bill No</th>
                                        <th>Customer Company</th>
                                        <th>Customer Details</th>
                                        <th>Payment Method & Amount</th>
                                        <th style="min-width: 150px">Remark</th>
                                    </tr>
                                </thead>
                                <tbody id="billTableBody">
                                    <tr id="row-1">
                                        <td>
                                            <div class="wrap-column-bill">
                                                <button type="button" class="btn btn-success btn-sm addPayment">+</button>
                                                <input
                                                    type="text"
                                                    class="bill-no"
                                                    style="border: none"
                                                    readonly
                                                />
                                            </div>
                                        </td>
                                        <td>
                                            <select id="company-1" name="company-1" class="company-select">
                                                @foreach($data_select as $key => $item)
                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="customer-details-container">
                                            <div class="customer-details">
                                                <li>
                                                    <b>Company Name :</b>
                                                    <span id="companyname-1">{{$fullName ?? '-'}}</span>
                                                </li>
                                                <div class="toggle-content">
                                                <li>
                                                    <b>Company Address:</b>
                                                    <span id="address-1">{{$address ?? '-'}}</span>
                                                </li>
                                                <li>
                                                    <b>Company Number:</b> <span id="number-1">{{$phone->Phone_number ?? '-'}}</span>
                                                </li>
                                                <li><b>Company Fax:</b> <span id="fax-1">{{$fax->Fax_number	?? '-'}}</span></li>

                                                <li>
                                                    <b>Company Email:</b> <span id="email-1">{{$Email ?? '-'}}</span>
                                                </li>

                                                <li>
                                                    <b>Taxpayer Identification:</b>
                                                    <span class="d-flex justify-content-between" id="Identification-1">{{$Identification}}<span>
                                                        <b class="center" style="align-items: end; cursor: pointer">

                                                            <a href="{{ url('/Company/edit/'.$ids) }}" target="_blank"><i class="fa fa-edit"></i></a>
                                                        </b>
                                                    </span>
                                                </li>
                                                </div>
                                                <div class="toggle-details">
                                                <i class="fas fa-caret-square-up"></i> Hide Details
                                                </div>
                                            </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="payment-methods" id="payment-container-1">
                                                <div class="payment-row d-flex gap-2 align-items-center mb-1">
                                                    <select id="payment-type-1-1" name="payment-type-1-1" class="payment-type">
                                                        <option value="" disabled selected>Select Payment Type</option>
                                                        @foreach ($payments as $key => $item)
                                                            <option value="{{$item['type']}}">
                                                                @if ($item['type'] == 'bankTransfer')
                                                                    Bank Transfer(SCB)
                                                                @elseif ($item['type'] =='cash')
                                                                    Cash
                                                                @elseif ($item['type'] =='creditCard')
                                                                    Credit Card
                                                                @elseif ($item['type'] =='cheque')
                                                                    Cheque
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="number" id="amount-1-1" name="amount-1-1" class="amount" placeholder="Amount" />
                                                    <button class="btn btn-danger btn-sm removePayment">-</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea id="remark-1" name="remark-1" class="auto-resize" placeholder="Remark"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex-end my-2">
                            <button type="button" class="btn btn-success btn-sm addRow px-3">+</button>
                            <button type="button" class="btn btn-danger btn-sm removeRow px-3">-</button>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="datadetailbill" id="datadetailbill" value='{{ json_encode($datadetailbill) }}' />
                    <input type="hidden" class="form-control" name="datadetailpayment" id="datadetailpayment" value='{{ json_encode($payments) }}' />
                    <input type="hidden" class="form-control" name="paymentdate" id="paymentdate" value="{{$paymentdate}}" />
                    <input type="hidden" class="form-control" name="invoice" id="invoice" value="{{$invoice}}" />

                </form>
                <div class="bottom">
                    <div class="flex-end pr-3">
                        <button type="button" class="bt-tg-secondary  md float-right" onclick="BACKtoEdit()">
                            Back
                        </button>
                        <button type="button" class="bt-tg-view  md float-right modal_but_view" onclick="submitPreview()">
                            View
                        </button>
                        <button id="nextSteptoSave" class="bt-tg-normal md float-right modal_but" onclick="submit()"> Next </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<input type="hidden" id="checkpayment" name="checkpayment">
<input type="hidden" class="form-control" id="cashcom" value="{{$cashAmount}}" />
<input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
<input type="hidden" name="preview" value="1" id="preview">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
<script>



    $(document).on("change", ".payment-type", function () {
        let id = $(this).attr("id"); // เช่น "payment-type-1-1"
        let parts = id.split("-");
        let rowIndex = parts[2]; // ดึงเลขตัวกลาง เช่น 1
        let lastNumber = parts.pop(); // ดึงเลขตัวสุดท้าย เช่น 1
        let type = $(this).val(); // ดึงค่าที่เลือก (cash, bankTransfer, creditCard, etc.)

        let newValue = "Bill-" + rowIndex; // ค่าที่จะเพิ่ม เช่น Bill-1

        // ค่าก่อนหน้าที่เลือกไว้
        let prevType = $(this).data("prevType") || "";

        // ลบค่าก่อนหน้าจาก <span> เก่า
        if (prevType) {
            let prevSpan = $("#payment-" + prevType);
            let prevText = prevSpan.text().trim();

            // ลบค่าเฉพาะตัวเก่าออก และจัดรูปแบบใหม่
            let updatedText = prevText.replace(newValue, "").replace(/^, |, $|, ,/g, "").trim();
            prevSpan.text(updatedText);
        }

        // เพิ่มค่าไปที่ <span> ใหม่
        let newSpan = $("#payment-" + type);
        let newText = newSpan.text().trim();
        newSpan.text(newText ? newText + ", " + newValue : newValue);

        // อัปเดตค่าก่อนหน้าที่เลือกไว้
        $(this).data("prevType", type);

        // เช็คว่า type == cash แล้วถ้ามี Complimentary ให้เพิ่มค่าเดียวกันกับ cash
        if (type === "cash") {
            let complimentarySpan = $("#payment-Complimentary");
            if (complimentarySpan.length) { // ถ้ามี Complimentary อยู่
                let complimentaryText = complimentarySpan.text().trim();
                complimentarySpan.text(complimentaryText ? complimentaryText + ", " + newValue : newValue);
            }
        }

        // คำนวณผลรวมหลังจากเปลี่ยนแปลงค่า
        calculateTotal();
    });


    $(document).on("input", ".amount", function () {
        calculateTotal();

    });

    function calculateTotal() {
    var totalByRow = {}; // เก็บผลรวมของแต่ละแถว
    var totalAllRowsCash = 0; // รวมเฉพาะ cash
    var totalAllRowsBankTransfer = 0; // รวมเฉพาะ bankTransfer
    var totalAllRowscreditCard = 0; // รวมเฉพาะ creditCard
    var totalAllRowscheque = 0; // รวมเฉพาะ creditCard
    var lastInputId = null; // ตัวแปรเก็บ ID ของช่องที่กรอกล่าสุด
    var totalBeforeLastInput = 0; // ตัวแปรเก็บผลรวมก่อนช่องล่าสุดที่กรอก

    $(".payment-type").each(function () {
        var selectId = $(this).attr("id");
        var selectedValue = $(this).val();
        var idParts = selectId.split("-");
        var rowIndex = idParts[2];
        var inputId = "#amount-" + idParts[2] + "-" + idParts[3];

        // ตรวจสอบว่าเป็นประเภท "cash"
        if (selectedValue === "cash") {
            let value = parseFloat($(inputId).val()) || 0;
            var allcash = parseFloat($('#cashcom').val()) || 0;  // ค่าของ allcash

            // ตรวจสอบว่าแถวนี้ยังไม่เคยมีค่า
            if (!totalByRow[rowIndex]) {
                totalByRow[rowIndex] = 0;
            }

            // บวกค่าในแถว
            totalByRow[rowIndex] += value;
            totalAllRowsCash += value; // รวม totalAllRows สำหรับ cash

            // อัปเดตช่องที่กรอกล่าสุด
            lastInputId = inputId;
            totalBeforeLastInput = totalAllRowsCash - value; // คำนวณผลรวมก่อนช่องล่าสุด

        }
        // ตรวจสอบว่าเป็นประเภท "bankTransfer"
        else if (selectedValue === "bankTransfer") {
            let value = parseFloat($(inputId).val()) || 0;
            var allcash = parseFloat($('#' + selectedValue).val()) || 0; // ค่าของ allcash สำหรับ bankTransfer

            // ตรวจสอบว่าแถวนี้ยังไม่เคยมีค่า
            if (!totalByRow[rowIndex]) {
                totalByRow[rowIndex] = 0;
            }

            // บวกค่าในแถว
            totalByRow[rowIndex] += value;
            totalAllRowsBankTransfer += value; // รวม totalAllRows สำหรับ bankTransfer

            // อัปเดตช่องที่กรอกล่าสุด
            lastInputId = inputId;
            totalBeforeLastInput = totalAllRowsBankTransfer - value; // คำนวณผลรวมก่อนช่องล่าสุด
        }else if (selectedValue === "creditCard") {
            let value = parseFloat($(inputId).val()) || 0;
            var allcash = parseFloat($('#' + selectedValue).val()) || 0; // ค่าของ allcash สำหรับ bankTransfer

            // ตรวจสอบว่าแถวนี้ยังไม่เคยมีค่า
            if (!totalByRow[rowIndex]) {
                totalByRow[rowIndex] = 0;
            }

            // บวกค่าในแถว
            totalByRow[rowIndex] += value;
            totalAllRowscreditCard += value; // รวม totalAllRows สำหรับ bankTransfer

            // อัปเดตช่องที่กรอกล่าสุด
            lastInputId = inputId;
            totalBeforeLastInput = totalAllRowscreditCard - value; // คำนวณผลรวมก่อนช่องล่าสุด
        }else if (selectedValue === "cheque") {
            let value = parseFloat($(inputId).val()) || 0;
            var allcash = parseFloat($('#' + selectedValue).val()) || 0; // ค่าของ allcash สำหรับ bankTransfer

            // ตรวจสอบว่าแถวนี้ยังไม่เคยมีค่า
            if (!totalByRow[rowIndex]) {
                totalByRow[rowIndex] = 0;
            }

            // บวกค่าในแถว
            totalByRow[rowIndex] += value;
            totalAllRowscheque += value; // รวม totalAllRows สำหรับ bankTransfer

            // อัปเดตช่องที่กรอกล่าสุด
            lastInputId = inputId;
            totalBeforeLastInput = totalAllRowscheque - value; // คำนวณผลรวมก่อนช่องล่าสุด
        }

        // ถ้า totalAllRows (cash หรือ bankTransfer) > allcash และมีค่าในช่องที่กรอกล่าสุด
        if ((selectedValue === "cash" && totalAllRowsCash > allcash) || (selectedValue === "bankTransfer" && totalAllRowsBankTransfer > allcash)
            || (selectedValue === "creditCard" && totalAllRowscreditCard > allcash) ||(selectedValue === "cheque" && totalAllRowscheque > allcash)) {
            var remainingAmount = allcash - totalBeforeLastInput; // คำนวณค่าคงเหลือ

            // ตรวจสอบค่าคงเหลือ
            if (remainingAmount >= 0) {
                $(lastInputId).val(remainingAmount); // แสดงค่าคงเหลือในช่องที่กรอกล่าสุด
                console.log("Reset input with remaining amount (value > allcash):", lastInputId, remainingAmount);
            } else {
                $(lastInputId).val(0); // รีเซ็ตช่องให้เป็น 0 หากค่าคงเหลือเป็นค่าลบ
                console.log("Reset input (remainingAmount < 0):", lastInputId);
            }
        }
        let total = 0;
        $(".amount").each(function () {
            let value = parseFloat($(this).val()) || 0;
            total += value;
        });
        $("#totalAmount").text(total.toFixed(2));
        let totalReceived = parseFloat($("#totalReceived").text().replace(/[^0-9.]/g, '')) || "0";
        let remaining = total - totalReceived;
        $("#totalRemaining").text(remaining.toFixed(2));
        $('#checkpayment').val(remaining);
        // if (remaining !== 0) {
        //     document.querySelector('.modal_but').disabled = true; // ปิดการใช้งานปุ่ม
        //     document.querySelector('.modal_but_view').disabled = true; // ปิดการใช้งานปุ่ม
        // } else {
        //     document.querySelector('.modal_but').disabled = false; // เปิดการใช้งานปุ่ม
        //     document.querySelector('.modal_but_view').disabled = false; // ปิดการใช้งานปุ่ม
        // }
    });
}


    $(document).on("change", ".company-select", function () {
        let row = $(this).closest("tr");
        let rowIndex = row.attr("id").split("-")[1];
        var idcheck = $('#company-'+rowIndex).val();
        var nameID = document.getElementById('idfirst').value;

        if (idcheck) {
            id = idcheck;
        }else{
            id = nameID;
        }
       // ดึงเลข index ของแถว


        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Document/BillingFolio/Proposal/invoice/select/data/guest/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(response) {
                var fullname = response.fullname;
                var Address = response.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                var TaxpayerIdentification = response.Identification;
                var email = response.email;
                var faxnumber = response.faxnumber;
                var phonenumber = response.phonenumber;
                console.log(fullname);

                $('#email-'+rowIndex).text(email);
                $('#companyname-'+rowIndex).text(fullname);
                $('#number-'+rowIndex).text(phonenumber);
                $('#fax-'+rowIndex).text(faxnumber);
                $('#Identification-'+rowIndex).text(TaxpayerIdentification);
                $('#address-'+rowIndex).text(Address +' '+Address2);
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    });
    $(document).ready(function () {
        $(document).on("click", ".toggle-details", function () {
            let toggleContent = $(this)
                .closest(".customer-details-container")
                .find(".toggle-content");
            let icon = $(this).find("i");

            if (toggleContent.is(":visible")) {
                toggleContent.slideUp();
                $(this).html(
                '<i class="fas fa-caret-square-up"></i> Hide Details'
                );
            } else {
                toggleContent.slideDown();
                $(this).html(
                '<i class="fas fa-caret-square-down"></i> Show Details'
                );
            }
        });
    });
    $(document).on("input", ".auto-resize", function () {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
    });
    $(document).ready(function () {
        let billCounter = 1;
        // เพิ่มแถวใหม่ในตาราง
        $(document).on("click", ".addRow", function () {
            let rowIndex = $("#billTableBody tr").length + 1; // นับจำนวนแถวที่มีอยู่
            let newRow = `<tr id="row-${rowIndex}">
                <td>
                    <div class="wrap-column-bill">
                        <button type="button" class="btn btn-success btn-sm addPayment">+</button>
                        <input type="text" id="bill-no-${rowIndex}" class="bill-no" style="border: none" readonly />
                    </div>
                </td>
                <td>
                    <select id="company-${rowIndex}" name="company-${rowIndex}" class="company-select">
                        <@foreach($data_select as $key => $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="customer-details-container">
                        <div class="customer-details">
                            <li><b>Company Name :</b> <span id="companyname-${rowIndex}">{{$fullName ?? '-'}}</span></li>
                            <div class="toggle-content">
                                <li><b>Company Address:</b> <span id="address-${rowIndex}">{{$address ?? '-'}}</span ></li>
                                <li><b>Company Number:</b> <span id="number-${rowIndex}">{{$phone->Phone_number ?? '-'}}</span></li>
                                <li><b>Company Fax:</b> <span id="fax-${rowIndex}">{{$fax->Fax_number ?? '-'}}</span></li>
                                <li><b>Company Email:</b> <span id="email-${rowIndex}">{{$Email ?? '-'}} </span></li>
                                <li>
                                    <b>Taxpayer Identification:</b>
                                    <span class="d-flex justify-content-between">
                                        <span id="Identification-${rowIndex}">{{$Identification}}</span>
                                        <b class="center"  style="align-items: end; cursor: pointer">
                                            <a href="{{ url('/Company/edit/'.$ids) }}" target="_blank"><i class="fa fa-edit"></i></a>
                                        </b>
                                    </span>
                                </li>
                            </div>
                            <div class="toggle-details">
                                <i class="fas fa-caret-square-up"></i> Hide Details
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="payment-methods" id="payment-container-${rowIndex}">
                        <div class="payment-row d-flex gap-2 align-items-center mb-1">
                            <select id="payment-type-${rowIndex}-1" name="payment-type-${rowIndex}-1" class="payment-type">
                                <option value="" disabled selected>Select Payment Type</option>
                                @foreach ($payments as $key => $item)
                                    <option value="{{$item['type']}}">
                                        @if ($item['type'] == 'bankTransfer')
                                            Bank Transfer(SCB)
                                        @elseif ($item['type'] =='cash')
                                            Cash
                                        @elseif ($item['type'] =='creditCard')
                                            Credit Card
                                        @elseif ($item['type'] =='cheque')
                                            Cheque
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" id="amount-${rowIndex}-1" name="amount-${rowIndex}-1" class="amount" placeholder="Amount" />
                            <button type="button" class="btn btn-danger btn-sm removePayment">-</button>
                        </div>
                    </div>
                </td>
                <td><textarea id="remark-${rowIndex}" name="remark-${rowIndex}" class="auto-resize" placeholder="Remark"></textarea></td>
            </tr>`;

            $("#billTableBody").append(newRow);
            updateBillNumbers();
        });

        // อัปเดตหมายเลข BILL
        function updateBillNumbers() {
            $(".bill-no").each(function (index) {
                let rowIndex = index + 1;
                $(this).val("BILL-" + rowIndex);
                $(this).closest("tr").attr("id", "row-" + rowIndex); // ตั้งค่า id ของแถว
            });
        }
        var counter =1;
        // เพิ่มวิธีการชำระเงิน
        $(document).on("click", ".addPayment", function () {
            let row = $(this).closest("tr");
            let rowIndex = row.attr("id").split("-")[1]; // ดึงเลข index ของแถว
            let paymentContainer = row.find(".payment-methods");
            counter++;

            let newPaymentRow = `
            <div class="payment-row d-flex gap-2 align-items-center mb-1">
                <select id="payment-type-${rowIndex}-${counter}" name="payment-type-${rowIndex}-${counter}" class="payment-type">
                    <option value="" disabled selected>Select Payment Type</option>
                    @foreach ($payments as $key => $item)
                        <option value="{{$item['type']}}">
                            @if ($item['type'] == 'bankTransfer')
                                Bank Transfer(SCB)
                            @elseif ($item['type'] =='cash')
                                Cash
                            @elseif ($item['type'] =='creditCard')
                                Credit Card
                            @elseif ($item['type'] =='cheque')
                                Cheque
                            @endif
                        </option>
                    @endforeach
                </select>
                <input type="number" id="amount-${rowIndex}-${counter}" name="amount-${rowIndex}-${counter}" class="amount" placeholder="Amount" />
                <button class="btn btn-danger btn-sm removePayment">-</button>
            </div>`;

            paymentContainer.append(newPaymentRow);
        });

        // ลบวิธีการชำระเงิน
        $(document).on("click", ".removePayment", function () {
            let paymentContainer = $(this).closest(".payment-methods");
            let rowIndex = $(this).closest(".payment-row").find(".payment-type").attr("id").split("-")[2]; // ดึง rowIndex
            let type = $(this).closest(".payment-row").find(".payment-type").val(); // ดึงประเภทการชำระเงิน (เช่น cash, bankTransfer)

            // ลบคำจาก <span> เมื่อมีการลบช่องกรอก
            let span = $("#payment-" + type);
            let currentText = span.text().trim();
            let valueToRemove = "Bill-" + rowIndex;
            let updatedText = currentText.replace(valueToRemove, "").replace(/^, |, $|, ,/g, "").trim();
            span.text(updatedText);

            $(this).closest(".payment-row").remove();

            // ถ้าไม่มี payment-row เหลืออยู่ ให้ลบทั้งแถว
            if (paymentContainer.find(".payment-row").length === 0) {
                paymentContainer.closest("tr").remove();
                updateBillNumbers();
            }

            // คำนวณผลรวมหลังจากลบช่องกรอก
            calculateTotal();
        });
        // ลบแถวออกจากตาราง
        $(document).on("click", ".removeRow", function () {
            let table = $("#billTableBody"); // ดึง <tbody> ของตาราง
            let rowCount = table.find("tr").length; // นับจำนวนแถวทั้งหมด

            console.log("จำนวนแถวทั้งหมด:", rowCount);

            if (rowCount > 1) {
                let row = table.find("tr:last"); // หาแถวสุดท้ายใน <tbody>
                console.log("ลบแถว:", row);
                row.remove(); // ลบแถวสุดท้ายออก

                calculateTotal();
                updateBillNumbers();
            } else {
                alert("ไม่สามารถลบแถวสุดท้ายได้!");
            }
        });

        updateBillNumbers();

    });

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
                    // If user confirms, redirect to the correct route
                    window.location.href = "{!! route('BillingFolio.createmulti', $idss) !!}";
                }
            });
        }
        function submit() {
            var checkpayment = $('#checkpayment').val() || 1;
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
                        var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "preview";
                        input.value = 0;

                        // เพิ่ม input ลงในฟอร์ม
                        document.getElementById("myForm").appendChild(input);
                        document.getElementById("myForm").removeAttribute('target');
                        document.getElementById("myForm").submit();
                    }
                });
            }
        }
        function submitPreview() {
            var checkpayment = $('#checkpayment').val() || 1;
            if (checkpayment != 0) {
                Swal.fire({
                    icon: "error",
                    text: "Please pay the amount correctly.",
                });
            }else{
                var previewValue = document.getElementById("preview").value;
                console.log(previewValue);

                // สร้าง input แบบ hidden ใหม่
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "preview";
                input.value = 1;

                // เพิ่ม input ลงในฟอร์ม
                document.getElementById("myForm").appendChild(input);
                document.getElementById("myForm").setAttribute("target","_blank");
                document.getElementById("myForm").submit();
            }
        }
</script>
@endsection

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
    .modal-dialog-custom-90p {
        min-width: 50%;
    }

</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Billing Folio.</small>
                    <div class=""><span class="span1">Billing Folio (ใบเรียกเก็บเงิน)</span></div>
                </div>
                <div class="col-auto">
                    @if ($Nettotal-$totalReceipt !== 0)
                        <button type="button" class="btn btn-color-green lift btn_modal"  onclick="window.location.href='{{ route('invoice.index') }}'">
                            <i class="fa fa-plus"></i> Create Invoice
                        </button>
                    @endif
                    @php
                        $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                    @endphp
                    @if ($statusover == '1')
                        @if ($Nettotal-$totalReceipt == 0)
                            @if ($canEditProposal)
                                <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#OverCreate" onclick="requestConfirmation()">
                                <i class="fa fa-plus"></i> Additional</button>
                            @endif
                        @endif
                    @endif
                    <div class="modal fade" id="OverCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Waiting for confirmation</h1>
                                    <span style="color: red;font-size: 10px;">(รอยืนยัน)</span>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <div class="card-body">
                                            <div class="col-lg-12 col-md-12 col-sm-12">

                                            </div>
                                            <div id="countdownDisplay" style="font-weight: bold; font-size: 16px; color: red;">
                                                รอการยืนยันจากบุคคลที่สอง...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger"  onclick="CancelRequest()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>

                        function requestConfirmation() {
                            let timeLeft = 300;
                            const countdownDisplay = document.getElementById("countdownDisplay");

                            // ส่งคำขอยืนยันไปยังเซิร์ฟเวอร์
                            fetch('/request-confirmation', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.text())  // ใช้ .text() เพื่อดูผลลัพธ์จริง
                            .then(text => {
                                console.log(text);  // ดูว่าได้ HTML หรือ JSON กลับมา
                                return JSON.parse(text);  // แปลงเป็น JSON หลังจากดูข้อมูลแล้ว
                            })
                            .then(data => {
                                if (data.success) {
                                    // เริ่มนับถอยหลัง
                                    const countdownInterval = setInterval(() => {
                                        timeLeft--;
                                        const minutes = Math.floor(timeLeft / 60);
                                        const seconds = timeLeft % 60;

                                        // แสดงผลในรูปแบบ นาที:วินาที
                                        countdownDisplay.innerText = `เหลือเวลา ${minutes}:${seconds < 10 ? '0' : ''}${seconds} นาที`;
                                        console.log(data.request_id);

                                        // เช็คสถานะการยืนยัน
                                        fetch(`/check-confirmation-status/${data.request_id}`, {
                                            method: 'GET',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                        })
                                        .then(response => response.json())
                                        .then(statusData => {
                                            if (statusData.status == '2') {
                                                window.location.href = "/Document/BillingFolio/Over/index";
                                                clearInterval(countdownInterval);
                                            } else if (statusData.status == '0') {
                                                clearInterval(countdownInterval);
                                            }
                                        })
                                        // .catch(error => console.error("Status check error:", error));

                                        // เมื่อหมดเวลา
                                        if (timeLeft <= 0) {
                                            clearInterval(countdownInterval);
                                            countdownDisplay.innerText = "หมดเวลาแล้ว บุคคลที่สองไม่ได้ยืนยัน";
                                            CancelRequest();
                                        }
                                    }, 1000);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }

                        function CancelRequest() {
                            var id = "{{ auth()->user()->id }}";
                            jQuery.ajax({
                                type: "GET",
                                url: "/Cancel-request/" + id,
                                datatype: "JSON",
                                async: false,
                                success: function(response) {
                                    if (response.success) {
                                        $('#OverCreate').modal('hide');
                                        location.reload();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("AJAX request failed: ", status, error);
                                }
                            });
                        }
                    </script>
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
                                            @if ($firstPart == 'C')
                                                <span> - </span>
                                            @else
                                                <span>{{$fullname}}</span>
                                            @endif
                                            </li>
                                            <li>
                                            <span>Company</span>
                                            @if ($firstPart == 'C')
                                                <span>{{$fullname}}</span>
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
                                        </ul>
                                    </div>
                                </section>
                                <section class="card2 card-circle">
                                    <div class="tech-circle-container mx-4" style="background-color: #135d58;">
                                        <div class="outer-glow-circle"></div>
                                        <div class="circle-content">
                                            <p class="circle-text">
                                            <p class="f-w-bold fs-3">{{ number_format($Nettotal-$totalReceipt, 2, '.', ',') }}</p>
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
                                <div class="card-title center" style="position: relative;"><span>Folio </span><span id="switchButton" style='font-size:20px;position: absolute;right: 1em;'>&#8644;</span> </div>
                                <ul class="card-list-between">
                                    <span>
                                    <li class="pr-3">
                                        <span>Proposal ({{$Proposal_ID}})</span>
                                        <span class="hover-effect i  f-w-bold" style="color: #438985;" data-bs-toggle="modal" data-bs-target="#ModalProposalSummary"> {{ number_format($Nettotal, 2, '.', ',') }} <i class="fa fa-file-text-o hover-up"></i></span>
                                    </li>
                                    <li class="pr-3">
                                        <span >Additional ({{$Additional_ID}})</span>
                                        <span class=" hover-effect i  f-w-bold " style="color: #438985;" data-bs-toggle="modal" data-bs-target="#ModalAdditionalSummary"> {{ number_format($AdditionaltotalReceipt, 2, '.', ',') }} <i class="fa fa-file-text-o hover-up"></i></span>
                                    </li>
                                    <li class="pr-3">
                                        <span >Total</span>
                                        <span class="text-danger f-w-bold">{{ number_format($Nettotal+$AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                    </li>
                                </span>


                                    <span id="defaultContent">

                                        <li class="pr-3">
                                            <span>Receipt</span>
                                            <span class="text-danger f-w-bold">{{ number_format($totalReceipt, 2, '.', ',') }}</span>
                                        </li>

                                    </span>
                                    <span id="toggleContent" style="display: none;">
                                        <li class="pr-3 ">
                                            <span>Cash</span>
                                            <span class="text-danger f-w-bold">{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                        <li class="pr-3">
                                            <span>Complimentary</span>
                                            <span class="text-danger f-w-bold">{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                        </li>
                                    </span>
                                </ul>
                                <li class="outstanding-amount">
                                    <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                    <span class="text-success f-w-bold"> {{ number_format($Nettotal-$totalReceipt, 2, '.', ',') }}</span>
                                </li>
                            </div>
                        </div>
                        @if ($status == '0')
                            <div class="card-body">
                                <b>Invoice</b>
                                <table id="InvoiceTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                                    <thead >
                                        <tr>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%;">Proforma Invoice ID</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Payment</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Valid</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Status</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Total Amount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($invoices))
                                            @foreach ($invoices as $key => $item2)
                                                    <tr>
                                                        <th style="text-align:left;">{{$item2->Invoice_ID}}</th>
                                                        <th style="text-align:center;">{{ number_format($item2->sumpayment, 2, '.', ',') }}</th>
                                                        <th style="text-align:center;">{{$item2->valid}}</th>
                                                        <th style="text-align:center;">
                                                            <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                        </th>
                                                        <th style="text-align:center;">{{ number_format($item2->sumpayment, 2, '.', ',') }}</th>
                                                        <th style="text-align:left;">
                                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/'.$item2->id) }}'">
                                                                Paid
                                                            </button>
                                                        </th>
                                                    </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="card-body">
                            <b>Receipt</b>
                            <table id="ReceiptTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Receive ID</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Proforma Invoice ID</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Category</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">paymentDate</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Status</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Total Amount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Receipt))
                                        @foreach ($Receipt as $key => $item3)
                                            <tr>
                                                <th style="text-align:left;">{{$item3->Receipt_ID}}</th>
                                                <th style="text-align:left;">{{$item3->Invoice_ID}}</th>
                                                <th style="text-align:center;">{{ $item3->category}}</th>
                                                <th style="text-align:center;">{{$item3->paymentDate}}</th>
                                                <th style="text-align:center;">
                                                    <span class="badge rounded-pill bg-success">Approved</span>
                                                </th>
                                                <th style="text-align:center;">{{ number_format($item3->Amount, 2, '.', ',') }}</th>
                                                <th style="text-align:left;">
                                                    <a type="button" class="btn btn-light-info" target="_blank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item3->id) }}">
                                                        View
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($statusover == '0')
                        <div class="card-body">
                            <b>Additional</b>
                            <table id="ReceiptTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Receive ID</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Additional ID</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Category</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">paymentDate</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Status</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Total Amount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Receiptover))
                                        @foreach ($Receiptover as $key => $item4)
                                            <tr>
                                                <th style="text-align:left;">{{$item4->Receipt_ID}}</th>
                                                <th style="text-align:left;">{{$item4->Quotation_ID}}</th>
                                                <th style="text-align:center;">{{ $item4->category}}</th>
                                                <th style="text-align:center;">{{$item4->paymentDate}}</th>
                                                <th style="text-align:center;">
                                                    <span class="badge rounded-pill bg-success">Approved</span>
                                                </th>
                                                <th style="text-align:center;">{{ number_format($item4->Amount, 2, '.', ',') }}</th>
                                                <th style="text-align:left;">
                                                    <a type="button" class="btn btn-light-info" target="_blank" href="{{ url('/Document/BillingFolioOverbill/Proposal/invoice/export/'.$item4->id) }}">
                                                        View
                                                    </a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal fade pi" id="ModalProposalSummary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-custom-90p">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #2c7f7a">
                            <h3 class="modal-title fs-3" id="exampleModalLabel" style="color: white"> Proposal Summary </h3>
                            <button type="button" class="btn-close light" data-bs-dismiss="modal" aria-label="Close" style="color: white !important"></button>
                        </div>
                        <div class="modal-body">
                        <div class="">
                            <div class="d-flex-wrap-at-300">
                            <section class="card-content2">
                                <h5 class="card-title">Proposal</h5>
                                <div class="card-list-between">
                                    @if ($vat == 50)
                                        <li>
                                            <span>Subtotal</span>
                                            <span>{{ number_format($Nettotal+$SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Special Discount Bath</span>
                                            <span>{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Total Balance</span>
                                            <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Price Before Tax</span>
                                            <span>{{ number_format($beforeTax, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Value Added Tax</span>
                                            <span>{{ number_format($AddTax, 2, '.', ',') }}</span>
                                        </li>
                                        </div>
                                        <div class="card-list-between">
                                        <li>
                                            <span>Total Balance</span>
                                            <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                    @elseif ($vat == 51)
                                        <li>
                                            <span>Subtotal</span>
                                            <span>{{ number_format($Nettotal+$SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Special Discount Bath</span>
                                            <span>{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <div class="card-list-between">
                                        <li>
                                            <span>Total Balance</span>
                                            <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                    @elseif ($vat == 52)
                                        <li>
                                            <span>Subtotal</span>
                                            <span>{{ number_format($Nettotal+$SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Special Discount Bath</span>
                                            <span>{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</span>
                                        </li>
                                        <li>
                                            <span>Value Added Tax</span>
                                            <span>{{ number_format($AddTax, 2, '.', ',') }}</span>
                                        </li>
                                        <div class="card-list-between">
                                        <li>
                                            <span>Total Balance</span>
                                            <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                        </li>
                                    @endif
                                </div>
                            </section>
                            <section class="card-content2">
                                <h5 class="card-title">Revenue Summary</h5>
                                <div class="card-list-between">
                                <li>
                                    <span>Room Revenue</span>
                                    <span>{{ number_format($totalnetpriceproduct, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>F&B Revenue</span>
                                    <span>{{ number_format($totalnetMeals, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>Banquet Revenue</span>
                                    <span>{{ number_format($totalnetBanquet, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>Entertainment Revenue</span>
                                    <span>{{ number_format($totalentertainment, 2, '.', ',') }}</span>
                                </li>
                                </div>
                                <div class="card-list-between">
                                <li>
                                    <span>Total Balance</span>
                                    <span>{{ number_format($Nettotal, 2, '.', ',') }}</span>
                                </li>
                                </div>
                            </section>
                            </div>
                            <div class="container-sub-table-proposal">
                            <section>
                                <h4>Room Revenue </h4>
                                <details onclick="nav($id='nav1')">
                                    <div class="wrap-table-together">
                                        <table id="roomTable" class="table-together ui striped table nowrap unstackable hover" >
                                            <thead>
                                                <tr>
                                                <th data-priority="1">Description</th>
                                                <th >Quantity</th>
                                                <th>Unit</th>
                                                <th>Price/Unit</th>
                                                <th>Discount</th>
                                                <th>Price Discount</th>
                                                <th data-priority="1">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($room))
                                                    @foreach ($room as $key => $item)
                                                        @foreach ($unit as $singleUnit)
                                                            @foreach ($quantity as $singleQuantity)
                                                                @if($singleUnit->id == @$item->product->unit)
                                                                    @if($singleQuantity->id == @$item->product->quantity)
                                                                        <tr >
                                                                            <td>{{@$item->product->name_th}}</td>
                                                                            <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                            <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                            <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                            <td>{{$item->discount}}%</td>
                                                                            <td>{{ number_format($item->priceproduct * $item->discount /100 * $item->Quantity*$item->Unit , 2, '.', ',') }}</td>
                                                                            <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </details>
                            </section>
                            <!-- Table2 F&B Revenue -->
                            <section>
                                <h4>F&B Revenue</h4>
                                <details onclick="nav($id='nav2')">
                                <div class="wrap-table-together">
                                    <table id="fbTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th data-priority="1">Description</th>
                                            <th >Quantity</th>
                                            <th>Unit</th>
                                            <th>Price/Unit</th>
                                            <th>Discount</th>
                                            <th>Price Discount</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Meals))
                                                @foreach ($Meals as $key => $item)
                                                    @foreach ($unit as $singleUnit)
                                                        @foreach ($quantity as $singleQuantity)
                                                            @if($singleUnit->id == @$item->product->unit)
                                                                @if($singleQuantity->id == @$item->product->quantity)
                                                                    <tr >
                                                                        <td>{{@$item->product->name_th}}</td>
                                                                        <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                        <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                        <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            <!-- Table3 Banquet Revenue-->
                            <section>
                                <h4>Banquet Revenue</h4>
                                <details onclick="nav($id='nav3')">
                                <div class="wrap-table-together">
                                    <table id="banquetTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th data-priority="1">Description</th>
                                            <th >Quantity</th>
                                            <th>Unit</th>
                                            <th>Price/Unit</th>
                                            <th>Discount</th>
                                            <th>Price Discount</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Banquet))
                                                @foreach ($Banquet as $key => $item)
                                                    @foreach ($unit as $singleUnit)
                                                        @foreach ($quantity as $singleQuantity)
                                                            @if($singleUnit->id == @$item->product->unit)
                                                                @if($singleQuantity->id == @$item->product->quantity)
                                                                    <tr >
                                                                        <td>{{@$item->product->name_th}}</td>
                                                                        <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                        <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                        <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            <!-- Table4 Entertainment Revenue -->
                            <section>
                                <h4>Entertainment Revenue</h4>
                                <details onclick="nav($id='nav4')">
                                <div class="wrap-table-together">
                                    <table id="entertainmentTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th data-priority="1">Description</th>
                                            <th >Quantity</th>
                                            <th>Unit</th>
                                            <th>Price/Unit</th>
                                            <th>Discount</th>
                                            <th>Price Discount</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($entertainment))
                                                @foreach ($entertainment as $key => $item)
                                                    @foreach ($unit as $singleUnit)
                                                        @foreach ($quantity as $singleQuantity)
                                                            @if($singleUnit->id == @$item->product->unit)
                                                                @if($singleQuantity->id == @$item->product->quantity)
                                                                    <tr >
                                                                        <td>{{@$item->product->name_th}}</td>
                                                                        <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                        <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                        <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bt-tg-normal btn-secondary sm" style="background-color: grey; margin-right: 5px" data-bs-dismiss="modal"> Close </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade pi" id="ModalAdditionalSummary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-custom-90p">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #2c7f7a">
                            <h3 class="modal-title fs-3" id="exampleModalLabel" style="color: white"> Proposal Summary </h3>
                            <button type="button" class="btn-close light" data-bs-dismiss="modal" aria-label="Close" style="color: white !important"></button>
                        </div>
                        <div class="modal-body">
                        <div class="">
                            <div class="d-flex-wrap-at-300">
                            <section class="card-content2">
                                <h5 class="card-title">Proposal</h5>
                                <div class="card-list-between">
                                    <li>
                                        <span>Subtotal</span>
                                        <span>{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                    </li>
                                    <li>
                                        <span>Price Before Tax</span>
                                        <span>{{ number_format($AdditionaltotalReceipt/1.07, 2, '.', ',') }}</span>
                                    </li>
                                    <li>
                                        <span>Value Added Tax</span>
                                        <span>{{ number_format($AdditionaltotalReceipt-$AdditionaltotalReceipt/1.07, 2, '.', ',') }}</span>
                                    </li>
                                    </div>
                                    <div class="card-list-between">
                                    <li>
                                        <span>Total Balance</span>
                                        <span>{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                    </li>
                                </div>
                            </section>
                            <section class="card-content2">
                                <h5 class="card-title">Revenue Summary</h5>
                                <div class="card-list-between">
                                <li>
                                    <span>Room Revenue</span>
                                    <span>{{ number_format($RmCount, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>F&B Revenue</span>
                                    <span>{{ number_format($FBCount, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>Banquet Revenue</span>
                                    <span>{{ number_format($BQCount, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>Entertainment Revenue</span>
                                    <span>{{ number_format($EMCount, 2, '.', ',') }}</span>
                                </li>
                                <li>
                                    <span>Other items</span>
                                    <span>{{ number_format($ATCount, 2, '.', ',') }}</span>
                                </li>
                                </div>
                                <div class="card-list-between">
                                <li>
                                    <span>Total Balance</span>
                                    <span>{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                </li>
                                </div>
                            </section>
                            </div>
                            <div class="container-sub-table-proposal">
                            <section>
                                <h4>Room Revenue </h4>
                                <details onclick="nav($id='nav1')">
                                    <div class="wrap-table-together">
                                        <table id="roomTable" class="table-together ui striped table nowrap unstackable hover" >
                                            <thead>
                                                <tr>
                                                <th >No</th>
                                                <th data-priority="1">Code</th>
                                                <th data-priority="1">Description</th>
                                                <th data-priority="1">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Rm))
                                                    @foreach ($Rm as $key => $item)
                                                        <tr >
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item['Code'] }}</td>
                                                            <td>{{ $item['Detail'] }}</td>
                                                            <td>{{ number_format($item['Amount'], 2, '.', ',') }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </details>
                            </section>
                            <!-- Table2 F&B Revenue -->
                            <section>
                                <h4>F&B Revenue</h4>
                                <details onclick="nav($id='nav2')">
                                <div class="wrap-table-together">
                                    <table id="fbTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th >No</th>
                                            <th data-priority="1">Code</th>
                                            <th data-priority="1">Description</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($FB))
                                                @foreach ($FB as $key => $item)
                                                    <tr >
                                                        <<td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item['Code'] }}</td>
                                                        <td>{{ $item['Detail'] }}</td>
                                                        <td>{{ number_format($item['Amount'], 2, '.', ',') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            <!-- Table3 Banquet Revenue-->
                            <section>
                                <h4>Banquet Revenue</h4>
                                <details onclick="nav($id='nav3')">
                                <div class="wrap-table-together">
                                    <table id="banquetTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th >No</th>
                                            <th data-priority="1">Code</th>
                                            <th data-priority="1">Description</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($BQ))
                                                @foreach ($BQ as $key => $item)
                                                    <tr >
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item['Code'] }}</td>
                                                        <td>{{ $item['Detail'] }}</td>
                                                        <td>{{ number_format($item['Amount'], 2, '.', ',') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            <!-- Table4 Entertainment Revenue -->
                            <section>
                                <h4>Entertainment Revenue</h4>
                                <details onclick="nav($id='nav4')">
                                <div class="wrap-table-together">
                                    <table id="entertainmentTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th >No</th>
                                            <th data-priority="1">Code</th>
                                            <th data-priority="1">Description</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($EM))
                                                @foreach ($EM as $key => $item)
                                                    <tr >
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item['Code'] }}</td>
                                                        <td>{{ $item['Detail'] }}</td>
                                                        <td>{{ number_format($item['Amount'], 2, '.', ',') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            <section>
                                <h4>Other items</h4>
                                <details onclick="nav($id='nav5')">
                                <div class="wrap-table-together">
                                    <table id="entertainmentTable" class="table-together ui striped table nowrap unstackable hover" >
                                        <thead>
                                            <tr>
                                            <th >No</th>
                                            <th data-priority="1">Code</th>
                                            <th data-priority="1">Description</th>
                                            <th data-priority="1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($AT))
                                                @foreach ($AT as $key => $item)
                                                    <tr >
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item['Code'] }}</td>
                                                        <td>{{ $item['Detail'] }}</td>
                                                        <td>{{ number_format($item['Amount'], 2, '.', ',') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                </details>
                            </section>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bt-tg-normal btn-secondary sm" style="background-color: grey; margin-right: 5px" data-bs-dismiss="modal"> Close </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableBilling.js')}}"></script>
    <script>


    </script>
    <script>
        const table_name = ['ProposalTable','InvoiceTable','ReceiptTable'];
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


    </script>

<script>
    $(document).ready(function() {
 var table = $(".table-together").DataTable({
   paging: false,
   searching: false,
   ordering: true,
   info: false,
   responsive: {
     details: {
       type: "column",
       target: "tr"
     }
   }
 });

   // Function to adjust DataTable
   function adjustDataTable() {
    $.fn.dataTable
      .tables({
        visible: true,
        api: true,
      })
      .columns.adjust()
      .responsive.recalc();

  }
  $("#ModalProposalSummary").on("shown.bs.modal", adjustDataTable);
  $('#ModalProposalSummary details').on('toggle', function() {
    if (this.open) {
      adjustDataTable();
    }
  });
});
   </script>
   <script>
    document.getElementById("switchButton").addEventListener("click", function () {
      const defaultContent = document.getElementById("defaultContent");
      const toggleContent = document.getElementById("toggleContent");

      if (defaultContent.style.display === "none") {
        defaultContent.style.display = "block";
        toggleContent.style.display = "none";
        this.innerHTML = "&#8644;";
      } else {
        defaultContent.style.display = "none";
        toggleContent.style.display = "block";
        this.innerHTML = "&#8646;";
      }
    });
  </script>
@endsection

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
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Additional.</small>
                    @if ($additional_type == 'H/G')
                        <div class=""><span class="span1">Additional (H/G Online)</span></div>
                    @else
                        <div class=""><span class="span1">Additional (Cash + Complimentary)</span></div>
                    @endif
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                        Back
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-color-green text-white  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            <li><a class="dropdown-item py-2 rounded" onclick="Appovel({{$Quotation->id}})">Appovel</a></li>
                            <li><a class="dropdown-item py-2 rounded" onclick="Reject({{$Quotation->id}})">Reject</a></li>
                        </ul>
                    </div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    @if ($additional_type == 'H/G')
                        <div class="span3">Additional (H/G Online) </div>
                    @else
                        <div class="span3">Additional (Cash + Complimentary) </div>
                    @endif
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                        Back
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-color-green text-white  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            <li><a class="dropdown-item py-2 rounded" onclick="Appovel({{$Quotation->id}})">Appovel</a></li>
                            <li><a class="dropdown-item py-2 rounded" onclick="Reject({{$Quotation->id}})">Reject</a></li>
                        </ul>
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
                                            <p class="f-w-bold fs-3">{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</p>
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
                                <div class="card-title center" style="position: relative;"><span>Folio </span></div>
                                <ul class="card-list-between">
                                    <span>
                                        <li class="pr-3">
                                            <span >Additional ({{$Additional_ID}})</span>
                                            <span class=" hover-effect i  f-w-bold " style="color: #438985;" data-bs-toggle="modal" data-bs-target="#ModalAdditionalSummary"> {{ number_format($AdditionaltotalReceipt, 2, '.', ',') }} <i class="fa fa-file-text-o hover-up"></i></span>
                                        </li>
                                        <li class="pr-3">
                                            <span >Total</span>
                                            <span class="text-danger f-w-bold">{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                        </li>
                                    </span>
                                    @if ($additional_type == 'H/G')
                                        {{-- <span id="defaultContent">
                                            <li class="pr-3 ">
                                                <span>Additional</span>
                                                <span class="text-danger f-w-bold">{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                            </li>
                                        </span> --}}
                                    @elseif ($additional_type == 'Cash')
                                        <span id="defaultContent">
                                            <li class="pr-3 ">
                                                <span>Cash</span>
                                                <span class="text-danger f-w-bold">{{ number_format($AdditionaltotalReceipt*0.37, 2, '.', ',') }}</span>
                                            </li>
                                            <li class="pr-3">
                                                <span>Complimentary</span>
                                                <span class="text-danger f-w-bold">{{ number_format($AdditionaltotalReceipt-$AdditionaltotalReceipt*0.37, 2, '.', ',') }}</span>
                                            </li>
                                        </span>
                                    @else
                                        <span id="defaultContent">
                                            <li class="pr-3 ">
                                                <span>Cash</span>
                                                <span class="text-danger f-w-bold">{{ number_format($Additional->Cash, 2, '.', ',') }}</span>
                                            </li>
                                            <li class="pr-3">
                                                <span>Complimentary</span>
                                                <span class="text-danger f-w-bold">{{ number_format($Additional->Complimentary, 2, '.', ',') }}</span>
                                            </li>
                                        </span>
                                    @endif
                                </ul>
                                <li class="outstanding-amount">
                                    <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                    <span class="text-success f-w-bold"> {{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade pi" id="ModalAdditionalSummary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-custom-90p">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #2c7f7a">
                            <h3 class="modal-title fs-3" id="exampleModalLabel" style="color: white"> Additional Summary </h3>
                            <button type="button" class="btn-close light" data-bs-dismiss="modal" aria-label="Close" style="color: white !important"></button>
                        </div>
                        <div class="modal-body">
                        <div class="">
                            <div class="d-flex-wrap-at-300">
                            <section class="card-content2">
                                @if ($Mvat == '50')
                                    <h5 class="card-title">Additional</h5>
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
                                @elseif ($Mvat == '51')
                                    <h5 class="card-title">Proposal</h5>
                                    <div class="card-list-between">
                                        <li>
                                            <span>Subtotal</span>
                                            <span>{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                        </li>
                                    </div>
                                    <div class="card-list-between">
                                        <li>
                                            <span>Total Balance</span>
                                            <span>{{ number_format($AdditionaltotalReceipt, 2, '.', ',') }}</span>
                                        </li>
                                    </div>
                                @elseif ($Mvat == '52')
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
                                @endif

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
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
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
                                        <div class="PROPOSAL col-lg-7" style="margin-left: 5px">
                                            <div class="row">
                                                <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">ADDITIONAL CHARGE</b>
                                                <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$Quotation_IDoverbill}}</b>
                                            </div>
                                            <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                            <input type="hidden" id="Additional_ID" name="Additional_ID" value="{{$Quotation_IDoverbill}}">
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
                                                        <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;" value="{{$Quotation->issue_date}}"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                        <span>Expiration Date:</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;"value="{{$Quotation->Expirationdate}}"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                @if ($Selectdata == 'Company')
                                    <div class="proposal-cutomer-detail" >
                                        <ul>
                                        <b class="font-upper com">Company Information</b>
                                        <li class="mt-3">
                                            <b>Company Name</b>
                                            <span id="Company_name">{{$fullname}}</span>
                                        </li>
                                        <li>
                                            <b>Company Address</b>
                                            <span id="Address">{{$address}}</span>
                                            <b></b>
                                        </li>
                                        <span class="wrap-full">
                                            <li >
                                                <b>Company Number</b>
                                                <span id="Company_Number">{{$phone->Phone_number}}</span>
                                            </li>
                                            <li >
                                                <b>Company Fax</b>
                                                <span id="Company_Fax">{{$Fax_number}}</span>
                                            </li>
                                        </span>
                                        <li>
                                            <b>Company Email</b>
                                            <span id="Company_Email">{{$Email}}</span>
                                        </li>
                                        <li>
                                            <b>Taxpayer Identification</b>
                                            <span id="Taxpayer" >{{$Taxpayer_Identification}}</span>
                                        </li>
                                        <li> </li>
                                        <b class="font-upper com">Personal Information</b>
                                        <li class="mt-3">
                                            <b>Contact Name</b>
                                            <span id="Company_contact">{{$Contact_Name}}</span>
                                        </li>
                                        <li >
                                            <b>Contact Number</b>
                                            <span id="Contact_Phone">{{$Contact_phone->Phone_number}}</span>
                                        </li>
                                        <li>
                                            <b>Contact Email</b>
                                            <span id="Contact_Email" >{{$Quotation->checkin}}</span>
                                        </li>
                                        <li></li>
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
                                            <span id="checkinpo">{{$Quotation->checkin ? $Quotation->checkin : 'No Check In Date' }}</span>
                                        </li>
                                        <li>
                                            <b>Check Out</b>
                                            <span id="checkoutpo">{{$Quotation->checkout ? $Quotation->checkout : '-' }}</span>
                                        </li>
                                        <li>
                                            <b>Length of Stay</b>
                                            <span style="display: flex"><p id="daypo" class="m-0">{{($Quotation->day ?? '-') . ' วัน ';}} </p><p id="nightpo" class="m-0"> {{(' , '.$Quotation->night ?? '-').' คืน'}} </p></span>
                                        </li>
                                        <li>
                                            <b>Number of Guests</b>
                                            <span style="display: flex"><p id="Adultpo" class="m-0">{{($Quotation->adult ?? '-') . ' Adult ';}} </p><p id="Childrenpo" class="m-0"> {{(' , '.$Quotation->children ?? '-').' Children'}} </p></span>
                                        </li>

                                        </ul>

                                    </div>
                                @else
                                    <div class="proposal-cutomer-detail" >
                                        <ul>
                                        <b class="font-upper com">Guest Information</b>
                                        <li class="mt-3">
                                            <b>Guest  Name</b>
                                            <span id="guest_name">{{$fullname}}</span>
                                        </li>
                                        <li>
                                            <b>Guest  Address</b>
                                            <span id="guestAddress">{{$address}}</span>
                                            <b></b>
                                        </li>

                                        <li >
                                            <b>Guest  Number</b>
                                            <span id="guest_Number">{{$phone->Phone_number}}</span>
                                        </li>

                                        <li>
                                            <b>Guest  Email</b>
                                            <span id="guest_Email">{{$Email}}</span>
                                        </li>
                                        <li>
                                            <b>Identification Number</b>
                                            <span id="guestTaxpayer" >{{$Taxpayer_Identification}}</span>
                                        </li>
                                        <li> </li>
                                        <li></li>
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
                                            <span id="checkinpoguest">{{$Quotation->checkin ? $Quotation->checkin : 'No Check In Date' }}</span>
                                        </li>
                                        <li>
                                            <b>Check Out</b>
                                            <span id="checkoutpoguest">{{$Quotation->checkout ? $Quotation->checkout : '-' }}</span>
                                        </li>
                                        <li>
                                            <b>Length of Stay</b>
                                            <span style="display: flex"><p id="daypoguest" class="m-0">{{($Quotation->day ?? '-') . ' วัน ';}} </p><p id="nightpoguest" class="m-0"> {{' , '.$Quotation->night .' คืน'}}</p></span>
                                        </li>
                                        <li>
                                            <b>Number of Guests</b>
                                            <span style="display: flex"><p id="Adultpoguest" class="m-0">{{($Quotation->adult ?? '-') . ' Adult ';}}  </p><p id="Childrenpoguest" class="m-0">{{(' , '.$Quotation->children ?? '-').' Children'}} </p></span>
                                        </li>

                                        </ul>

                                    </div>
                                @endif
                                <div class="styled-hr"></div>
                            </div>
                            <div class="row mt-2">
                                <table id="main" class=" example2 ui striped table nowrap unstackable " style="width:100%">
                                    <thead >
                                        <tr>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%">No.</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%;text-align:center;"data-priority="1">Description</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%"data-priority="1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="display-selected-items">
                                        @if (!empty($selectproduct))
                                            @foreach ($selectproduct as $key => $item)
                                                @php
                                                $var = $item->Code;
                                                @endphp
                                                <tr id="tr-select-main{{$item->Code}}">
                                                    <input type="hidden" id="CheckProduct" name="CheckProduct[]" value="{{$item->Code}}">
                                                    <td style="text-align:center;vertical-align: middle;"><input type="hidden" id="ProductID" name="Code[]" value="{{$item->Code}}">{{$key+1}}</td>
                                                    <td style="text-align:left;vertical-align: middle;">{{$item->Detail}} </td>
                                                    <td class="Quantity" data-value="{{$item->Amount}}" style="text-align:center;">
                                                        <input type="text" id="quantity{{$var}}" name="Amount[]" rel="{{$var}}" style="text-align:center;vertical-align: middle;"class="quantity-input form-control" value="{{number_format($item->Amount)}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                @if (@Auth::user()->roleMenuDiscount('Proposal',Auth::user()->id) == 1)
                                    <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="1">
                                @else
                                    <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="0">
                                @endif
                                <input type="hidden" id="paxold" name="paxold" value="{{$Quotation->TotalPax}}">
                                <input type="hidden" name="discountuser" id="discountuser" value="{{@Auth::user()->discount}}">
                                <div class="wrap-b">
                                    <div class="kw" >
                                        <span >Notes or Special Comment</span>
                                        <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                    </div>
                                    <div class="lek" >
                                        @php
                                            $total = $AdditionaltotalReceipt

                                        @endphp
                                        @if ($Mvat == '50')
                                            <div class="proposal-number-cutomer-detail">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amount">{{number_format($total, 2, '.', ',')}}</span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Price Before Tax</b>
                                                        <span id="Net-price">{{number_format($total/1.07, 2, '.', ',')}}</span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Value Added Tax</b>
                                                        <span id="total-Vat">{{number_format($total -$total/1.07, 2, '.', ',')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        @elseif ($Mvat == '51')
                                            <div class="proposal-number-cutomer-detail">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amountEXCLUDE">{{number_format($total, 2, '.', ',')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        @else
                                            <div class="proposal-number-cutomer-detail">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amountpus">{{number_format($total, 2, '.', ',')}}</span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Value Added Tax</b>
                                                        <span id="total-Vatpus">{{number_format($total -$total/1.07, 2, '.', ',')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-end" >
                                    <b class="text-center text-white p-2" style="font-size: 14px; background-color: #2D7F7B; border-radius: 5px; " ><p class="mr-2" style="width:260px;" >Net Total <span id="Net-Total">{{number_format($total, 2, '.', ',')}}</span></p></b>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <strong class="com" style="font-size: 18px">Method of Payment</strong>
                                    </div>
                                    <span class="col-md-8 col-sm-12 mt-1">
                                        <br>
                                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </span>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                            <div class="col-lg-12 col-md-12 col-sm-12  mt-2">
                                                <div class="row">
                                                    <div class="col-2 mt-3" style="display: flex;justify-content: center;align-items: center;">
                                                        <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 60%;border-radius: 50%;"/>
                                                    </div>
                                                    <div class="col-7 mt-2">
                                                        <strong>The Siam Commercial Bank Public Company Limited <br>Bank Account No. 708-226791-3<br>Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="styled-hr mt-3"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <strong class="titleh1">รับรอง</strong>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 my-2">
                                        <div class="row">
                                            <div class="col-lg-2 centered-content">
                                                <span>สแกนเพื่อเปิดด้วยเว็บไซต์</span>
                                                @php
                                                    use SimpleSoftwareIO\QrCode\Facades\QrCode;
                                                @endphp
                                                <div class="mt-3">
                                                    {!! QrCode::size(90)->generate('No found'); !!}
                                                </div>
                                            </div>
                                            <div class="col-lg-2 centered-content">
                                                <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                                @if ($user->signature)
                                                    <img src="/upload/signature/{{$user->signature}}" style="width: 70%;"/>
                                                @endif
                                                @if ($user->firstname)
                                                    <span>{{$user->firstname}} {{$user->lastname}}</span>
                                                @endif
                                                <span id="issue_date_document"></span>
                                            </div>
                                            <div class="col-lg-2 centered-content">
                                                <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                                <img src="/boss.png" style="width: 70%;"/>
                                                <span>Sopida Thuphom</span>
                                                <span id="issue_date_document1"></span>
                                            </div>
                                            <div class="col-lg-2 centered-content">
                                                <span>ตราประทับ (ผู้ขาย)</span>
                                                <img src="{{ asset('assets/images/' . $settingCompany->image) }}" style="width: 70%;">
                                            </div>
                                            <div class="col-lg-2 centered-content">
                                                <span>ผู้รับเอกสาร (ลูกค้า)</span>
                                                <br><br><br>
                                                ______________________
                                                <span>_____/__________/_____</span>
                                            </div>
                                            <div class="col-lg-2 centered-content">
                                                <span >ตราประทับ (ลูกค้า)</span>
                                                <div class="centered-content4 mt-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="myFormApprove" action="{{route('ProposalReq.Approve')}}" method="POST">
        @csrf
        <input type="hidden" name="approved_id" id="approved_id" value="{{$Additional_ID}}">
    </form>
    <form id="myForm" action="{{route('ProposalReq.Reject')}}" method="POST">
        @csrf
        <input type="hidden" name="approved_id" id="approved_id" value="{{$Additional_ID}}">
    </form>
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
    <script>
        const table_name = ['main'];
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
        function Appovel(id) {
            var Additional_ID = $('#Additional_ID').val();
            Swal.fire({
                title: `คุณต้องการ Approve รหัส ${Additional_ID} เอกสารใช่หรือไม่?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('myFormApprove').submit();
                }
            });
        }
        function Reject(id) {
            Swal.fire({
                title: "คุณต้องการ Reject เอกสารใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {

                    document.getElementById('myForm').submit();
                }
            });
        }


    </script>

@endsection

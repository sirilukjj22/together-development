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
    .bg-together {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        color: white;
        background-color: rgb(73, 184, 179);
        background-image: linear-gradient(
            to right,
            rgba(12, 67, 67, 0.862),
            rgba(8, 97, 92, 0.685)
        );
        box-shadow: inset 2px 2px 3px rgba(255, 255, 255, 0.6),
            inset -2px -2px 3px rgba(0, 0, 0, 0.6);
    }

    .wrap-fieldSet-create {
        margin-top: 1em;
        border-radius: 10px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        padding: 10px;
        display: grid;
        gap: 0.5em;
        background: linear-gradient(to bottom, #2d6a67, #0f3b46);
    }

    .wrap-fieldSet-create .fieldset {
        flex-grow: 1;
        background: #f1f7f6;
        border-radius: 7px;
        max-width: 100%;
    }

    .wrap-fieldSet-create .info-list {
        list-style: none;
        padding: 0.5em;
        display: flex;
        flex-wrap: wrap;

        gap: 0.3em;
        margin-bottom: 0px;
        height: 100%;
    }

    .wrap-fieldSet-create .info-list > section {
        border-radius: 7px;
        flex-grow: 1;
    }

    .wrap-fieldSet-create .info-list > section.bd {
        border: 1px solid rgba(144, 214, 175, 0.375);
        padding: 5px;
    }

    .wrap-fieldSet-create .info-list li {
        font-size: 0.9rem;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px;
        width: 100%;
        height: 45px;
    }

    .wrap-fieldSet-create .info-list li input,
    .wrap-fieldSet-create .info-list li select {
        height: 2em;
    }

    .wrap-fieldSet-create .info-list.neww li {
        display: grid;
        grid-template-columns: 115px 1fr;
        /* border-bottom: 1px solid rgb(231, 235, 225); */
        max-width: 100%;
    }

    .wrap-fieldSet-create .info-label,
    .wrap-fieldSet-create .form-label {
        font-weight: 550;
        color: #2e2a2a;
    }
    .wrap-fieldSet-create .form-select,
    .wrap-fieldSet-create .form-control {
        padding: 2px 5px;
        font-size: 0.9rem;
    }

    .wrap-fieldSet-create .info-label::after {
        content: ":";
        margin: 2px 0.3em;
    }

    .wrap-fieldSet-create .info-label.no-semi::after {
        content: "";
    }

    .wrap-fieldSet-create .top-doc-saleman {
        display: grid;
        grid-template-columns: 7.5fr 3fr;
        gap: 0.3em;
        /* padding: 0.5em; */
        /* background: linear-gradient(to bottom, white, white); */
        /* background: linear-gradient(to bottom, #bac8c7, #e3ecee); */
        /* background: linear-gradient(to bottom, #e7f0ef77, #e1e2e233); */

        border-radius: 7px;
    }

    @media (max-width: 1400px) {
        .wrap-fieldSet-create .top-doc-saleman {
            grid-template-columns: 1fr;
        }
    }

    .wrap-fieldSet-create .top-doc-saleman > div {
        background-color: rgb(2, 41, 39);
        background-image: linear-gradient(
            to top,
            rgba(12, 67, 67, 0.862),
            rgba(8, 97, 92, 0.685)
        );
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgb(240, 247, 245);
        padding: 0.5em 0.5em;
        font-weight: 500;
    }

    @media (max-width: 600px) {
    .wrap-fieldSet-create .top-doc-saleman > div {
        flex-grow: 1;
    }
    }

    .wrap-fieldSet-create .top-doc-saleman > div:nth-child(1) {
    flex-grow: 1;
    }
    @media (max-width: 800px) {
    .sm-width-100 {
        width: 100%;
    }
    }

    .grid-sm-flex {
        display: grid;
        grid-template-columns: 3.5fr 4.7fr;
        gap: 0.3em;
    }
    @media (max-width: 1200px) {
    .grid-sm-flex {
        display: flex;
        flex-wrap: wrap;
    }
    }

    @media (min-width: 1400px) {
    .wrap-dis {
        display: grid;
        grid-template-columns: 3fr 5fr 2fr;
        width: 100%;
    }
    }

    .wrap-dis {
        display: grid;
        grid-template-columns: 5fr 2fr;
        gap: 0.3em;
        padding: 0.5em;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
    }

    .wrap-dis li {
    /* border: red 1px solid; */
        display: flex;
    }

    @media (max-width: 1000px) {
    .wrap-dis {
        display: flex;
        justify-content: end;
        flex-wrap: wrap;
    }

    .wrap-dis > div:nth-child(1) {
        min-width: 350px;
    }

    .wrap-dis > div:nth-child(1) > div {
        min-width: 340px;
        flex-grow: 1;
    }
    }
    .bg-disable-grey {
        background-color: rgb(237, 237, 237);
        pointer-events: none;
    }
    img.rounded-avatar {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        object-fit: cover;
        margin-right: 0.4em;
    }
</style>
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Create Invoice / Deposit</div>
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
            <div class="wrap-fieldSet-create">
                <div class="top-doc-saleman">
                    <div class="d-flex justify-content-around flex-wrap gap-1">
                        <div>Document No : {{$DepositID}}</div>
                        <div>Reference No : {{$QuotationID}} </div>
                    </div>
                    <div> User Account :
                        @if ($user->firstname)
                            <span> {{$user->firstname}} {{$user->lastname}}</span>
                        @endif
                    </div>
                </div>
                <div class="wrap-dis p-0">
                    <div class="grid-sm-flex">
                        <fieldset class="fieldset">
                            <div class="info-list neww flex-column">
                                <li>
                                    <label for="customerNameInput" class="form-label text-nowrap m-0 rrr">Customer Name</label>
                                    <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" id="customerNameInput" placeholder="Select Customer" readonly style="flex-grow: 1;" />
                                    <!-- Search button -->
                                    <button type="button" class="btn p-0 px-1" data-bs-toggle="modal" data-bs-target="#customerSearchModal">
                                        <i style="font-size:18px" class="fa">&#xf002;</i>
                                    </button>
                                    </div>
                                </li>
                                <li>
                                        <span class="info-label">Additional Company</span>
                                        <select id="" class="form-select ">
                                        <option value="">ssssssssss</option>
                                        <option value="">ssssssssss</option>
                                        </select>
                                    </li>
                                <li>
                                    <label for="customerName" class="form-label text-nowrap m-0">Description</label>
                                    <select class="form-select bg-disable-grey" id="customerName">
                                    <option value="y" style="font-size: 0.9em">Deposit Revenue</option>
                                    </select>
                                </li>
                                <li>
                                    <label class="form-label">VAT Type</label>
                                    <select id="" class="form-select bg-disable-grey">
                                        <option value="">Include VAT</option>
                                        <option value="" selected>Exclude VAT</option>
                                        <option value="">Plus VAT</option>
                                        </select>
                                </li>
                                <li class="border-top">
                                    <label for="customerName" class="form-label text-nowrap m-0">Total Amount</label>
                                    <input class="form-control bg-disable-grey" type="number" value="10000" placeholder="1,000.00" />
                                </li>
                                <li>
                                    <label for="customerName" class="form-label text-nowrap m-0">Payment</label>
                                    <div class="d-flex gap-2">
                                    <div class="discount-container" style="max-width:160px">
                                        <input type="text" class="form-control" value="10">
                                    </div>
                                    <div class="discount-container flex-grow-1" style="min-width:75px">
                                        <select id="discountType" class="form-select">
                                        <option value="percent">%</option>
                                        <option value="amount">THB</option>
                                        </select>
                                    </div>
                                    </div>
                                </li>
                                <li class="border-top">
                                    <label for="customerName" class="form-label text-nowrap m-0">Deposit Amount</label>
                                    <input class="form-control bg-disable-grey" type="number" name="vatOption" id="excludeVAT" value="1000" />
                                </li>
                            </div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <ul class="info-list">
                                <section class="bd">
                                    <div class="flex-grow-1">
                                    <li>
                                        <span class="info-label">Customer ID</span>
                                        <span class="info-value">XX22222</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Customer Name</span>
                                        <span class="info-value">สมชายใจ</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Address</span>
                                        <span class="info-value">777 ถนนเจริญกรุง29 ปากคลองภาษีเจริญ ภาษีเจริญ กรุงเทพฯ 100012</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Telephone</span>
                                        <span class="info-value">111-222-3333</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Tax ID</span>
                                        <span class="info-value">100012</span>
                                    </li>
                                    </div>
                                </section>
                            </ul>
                        </fieldset>
                    </div>
                    <fieldset class="fieldset ">
                        <ul class="info-list">
                            <section class="bd">
                                <div class="flex-grow-1">
                                    <li>
                                        <span class="info-label">Issue Date</span>
                                        <span class="info-value">01/02/2024</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Expire Date</span>
                                        <span class="info-value">30/01/2025</span>
                                    </li>
                                    <li>
                                        <span class="info-label">Payment Terms</span>
                                        <span class="info-value">30 วัน</span>
                                    </li>
                                </div>
                            </section>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="modal fade" id="customerSearchModal" tabindex="-1" aria-labelledby="customerSearchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" style="min-width: 70%;">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="customerSearchModalLabel">Select Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="flex-end br" style="gap:0.3em;display: flex;justify-content: end;">
                        <select id="filterCustomerType" class="bt-together m-0" style="height: 2.7em;  margin-right: 10px;width: max-content;">
                            <option value="">All</option>
                            <option value="all">Company / Agent</option>
                            <option value="all">Guest</option>
                        </select>
                    </div>
                    <div class="dataTables_wrapper mt-2">
                        <table class="table-together">
                            <thead>
                                <tr>
                                <th data-priority="1">No</th>
                                <th data-priority="1">Customer Name</th>
                                <th data-priority="2">Customer ID </th>
                                <th data-priority="3">Customer Type</th>
                                <th data-priority="4">From</th>
                                <th data-priority="1">Select</th>
                                </tr>
                            </thead>
                            @if(!empty($cancel))
                                @foreach ($cancel as $key => $item)
                                    <tbody>
                                        <tr>
                                        <td>1</td>
                                        <td>หจก.รุ่งเรือง</td>
                                        <td>XX22222</td>
                                        <td>ลูกค้าเงินเชื่อ (Gold)</td>
                                        <td>Factory</td>
                                        <td>
                                            <button class="btn bg-warning px-2" style="padding-top:2px;padding-bottom:2px;">Select</button>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td>2</td>
                                        <td>Jone</td>
                                        <td>XX2223</td>
                                        <td>ลูกค้าเงินสด (Gold)</td>
                                        <td>Shop</td>
                                        <td>
                                            <button class="btn bg-warning px-2" style="padding-top:2px;padding-bottom:2px;">Select</button>
                                        </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="bt-tg bt-grey bt-md" style="height: 2.4em;" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

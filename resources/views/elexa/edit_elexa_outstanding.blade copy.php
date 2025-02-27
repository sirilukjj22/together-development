@extends('layouts.masterLayout')

@php
    $excludeDatatable = false;
@endphp

@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span2">Elexa EGAT</span><span class="span2"> / Elexa EGAT Revenue / {{ $title }}</span></div>
                <div class="span3">{{ $title }}</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('debit-elexa-revenue') }}" class="bt-tg-normal">Back</a>
            </div>
        </div> <!-- .row end -->
    </div>
</div>

<div>
    <section class="doc my-4">
        <div class="wrapTopAgodaDetails">
            <div class="wrapAdressTogether">
                <div class="top-img">
                    <img src="/image/logo.jpg" alt="logo of Together Resort" width="120" />
                </div>
                <div class="top-dt">
                    <b>Together Resort Limited Partnership</b>
                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                    <p>Tel : 032-708-888, 098-393-944-4</p>
                    <p> Email : reservation@together-resort.com &nbsp; Website : www.together-resort.com</p>
                </div>
            </div>
            <div class="">
                <div class="codeDoc">
                    <p>Document No</p>
                    <p>{{ $document_no ?? '' }}</p>
                </div>
                <div class="center" style="border: #1c504c 1px solid; border-radius: 7px">
                    <li>Issue Date : {{ date('d/m/Y') }}</li>
                </div>
            </div>
        </div>
        <hr />
        <div class="wrapAdressAgoda">
            <div class="mb-2">
                <img src="/image/front/elexa.png" alt="" width="80" height="75" style="border-radius: 5px;"/>
                <b>การไฟฟ้าฝ่ายผลิตแห่งประเทศไทย</b>
            </div>
            <p> 53 หมู่ 2 ถนนจรัญสนิทวงศ์ ตำบลบางกรวย อำเภอบางกรวย</p>
            <p>จังหวัดนนทบุรี ประเทศไทย 11130</p>
            <p>
                <b>Tel :</b> 02-114-3350
            </p>
        </div>
        <div class="text-center my-3">
            <b class="title-top-table">Debit Elexa EGAT Revenue</b>
        </div>
        <div class="wrap-detailPaid">
            <div class="detailPaid">
                <div>
                    <b>Date :</b> {{ date('d/m/Y', strtotime($elexa_revenue->sms_date)) }}
                </div>
                <div>
                    <b>Bank :</b>
                    <span>
                        <img src="/image/bank/SCB.jpg" alt="" width="30" style="margin: 5px; border-radius: 50px" /> Siam Commercial Bank PCL. </span>
                </div>
                <div>
                    <b>Bank Account :</b>
                    <span> -</span>
                </div>
                <div>
                    <b>Amount : </b> {{ number_format($elexa_revenue->amount ?? 0, 2) }}
                </div>
            </div>
            <div class="flex-end">
                <button type="button" class="bt-tg-normal" data-bs-toggle="modal" data-bs-target="#ElexaRevenueList">Add </button>
            </div>
        </div>

        <div class="wrap-table-together mt-3">
            <table id="myDataTableDebit" class="table-together table-style">
                <thead>
                    <tr class="text-capitalize">
                        <th data-priority="1">Date</th>
                        <th data-priority="1">Order ID</th>
                        <th data-priority="1">amount</th>
                        <th data-priority="3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_debit = 0;
                        $outstanding_amount = 0;
                        $debit_amount = 0;
                        $key_num = 0;
                    ?>
                    @foreach ($elexa_debit_revenue as $key => $item)
                        <tr id="tr_row_{{ $item->id }}" class="checkbox-debit-outstanding{{ $key_num += 1 }}">
                            <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                            <td>{{ $item->batch }}</td>
                            <td class="text-end target-class">{{ $item->ev_revenue }}</td>
                            <td>
                                <a href="#" onclick="delete_receive_payment(this, {{ $item->id}}, {{ $item->ev_revenue }})">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            $total_debit += $item->ev_revenue; 
                            $debit_amount += 1;
                        ?>
                    @endforeach
                </tbody>
                <tfoot style="background-color: #d7ebe1; font-weight: bold">
                    <tr>
                        <td colspan="2" class="text-center" style="padding: 10px">Total</td>
                        <td class="text-end" id="tfoot-total-debit">{{ number_format($total_debit, 2) }}</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="flex-end mt-5">
            <div style="text-align: center">
                <p>ผู้ออกเอกสาร (ผู้ขาย)</p>
                <p style="border-bottom: 1px solid grey; height: 5em; width: 170px;"></p>
            </div>
        </div>
        <hr />
        <div class="text-center" id="btn-save-hidden2">
            <button type="button" class="btn bt-tg-normal" id="btn-save">Save</button>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="ElexaRevenueList" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Elexa EGAT Outstanding Revenue</h5>
            <button type="button" style="border: 1px solid rgb(196, 194, 194);border-radius: 5px; width: 35px;" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 24px;">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="mt-2 top-3-card">
                <div>
                    <span>Total Selected </span>
                    <br />
                    <b><span id="txt-total-selected">0</span></b>
                </div>
                <div>
                    <span>Total Selected Amount</span>
                    <br />
                    <b id="txt-total-selected-amount">0.00</b>
                </div>
                <div>
                    <span>Outstanding </span>
                    <br />
                    <b id="txt-total-selected-outstanding">{{ number_format($total_elexa_outstanding_revenue, 2) }}</b>
                </div>
            </div>
            <div class="wrap-table-together mt-3">
                <table id="myDataTableOutstandingSelect" class="table-style table-together" style="width: 100%;">
                    <thead>
                        <tr class="text-capitalize">
                            <th data-priority="1">Date</th>
                            <th data-priority="1">Order ID</th>
                            <th data-priority="1">amount</th>
                            <th data-priority="5">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="wrap-table-together">
                <div class="flex-end mt-2">
                    <div class="filter-section bd-select-cl d-flex" style="gap: 0.3em">
                        <select id="elexaRevenueDayMonthFilter" class="form-select" style="width: max-content" onchange="filterSearch()">
                            <option value="all">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
                <table id="myDataTableOutstanding" class="table-style table-together" style="width: 100%;">
                    <thead>
                        <tr class="text-capitalize">
                            <th hidden></th>
                            <th data-priority="1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">เลือกทั้งหมด</label>
                                </div>
                            </th>
                            <th data-priority="1">Date</th>
                            <th data-priority="1">Order ID</th>
                            <th data-priority="1">amount</th>
                            <th data-priority="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_outstanding_amount = 0; 
                            $outstanding_amount = 0;
                        ?>
                        @foreach ($elexa_outstanding as $key => $item)
                            <tr id="tr_row_{{ $item->id }}" class="checkbox-outstanding{{ $outstanding_amount += 1 }}">
                                <td hidden>{{ Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                                <td style="text-align: center;">
                                    <div class="form-check" style="text-align: center;">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault"></label>
                                    </div>
                                </td>
                                <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td>{{ $item->batch }}</td>
                                <td class="target-class">{{ $item->ev_revenue }}</td>
                                <td>
                                    @if ($item->receive_payment == 0)
                                        <button type="button" class="btn btn-color-green rounded-pill text-white btn-receive-pay" id="btn-receive-{{ $item->id }}" value="0"
                                        onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_revenue }})">รับชำระ</button>
                                    @else
                                        <button type="button" class="btn btn-color-green rounded-pill text-white btn-receive-pay" id="btn-receive-{{ $item->id }}" value="0"
                                        onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_revenue }})" disabled>รับชำระ</button>
                                    @endif
                                </td>
                                <input type="hidden" name="" id="elexa_revenue{{ $item->id }}" value="{{ $item->ev_revenue }}">
                            </tr>
                            
                            <?php $total_outstanding_amount += $item->ev_revenue; ?>
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #d7ebe1; font-weight: bold">
                        <tr>
                            <td></td>
                            <td style="padding: 10px">Total</td>
                            <td><span id="tfoot-total-outstanding">{{ $total_outstanding_amount }}</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="bt-tg-normal bt-grey sm mx-2" data-bs-dismiss="modal"> Close </button>
                <button type="button" class="bt-tg-normal sm" onclick="btnConfirm()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="total_receive_payment" value="{{ round($total_debit, 2) }}"> <!-- ยอดรายการที่เลือกทั้งหมด -->
<input type="hidden" id="total_revenue_amount" value="{{ isset($elexa_revenue) ? $elexa_revenue->amount : 0 }}"> <!-- ยอดจาก SMS -->
<input type="hidden" id="debit_amount" value="{{ $debit_amount }}">
<input type="hidden" id="outstanding_amount" value="{{ $outstanding_amount }}">

<!-- Total Selected, Total Selected Amount, Outstanding -->
<input type="hidden" id="input-outstanding-amount" value="{{ $total_outstanding_amount }}">
<input type="hidden" id="input-selected-amount" value="0">
<input type="hidden" id="input-selected-item" value="0">

<form action="#" id="form-elexa">
    @csrf
    <input type="hidden" name="doc_no" value="{{ $document_no ?? '' }}">
    <input type="hidden" name="issue_date" value="{{ date('Y-m-d') }}">
    <input type="hidden" id="revenue_id" name="sms_id" value="{{ isset($elexa_revenue) ? $elexa_revenue->id : 0 }}"> <!-- ID รายได้ที่มาจาก SMS -->

    @foreach ($elexa_all as $key => $item)
        @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
            <input type="hidden" id="receive-id-{{ $item->id }}" name="receive_id[]" value="{{ $item->id }}">
        @endif
    @endforeach
</form>

<form action="#" id="form-elexa-select">
    @csrf
</form>

<style>
    .table-together tr th{
        text-align: center !important;
    }
</style>

<link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

<!-- เพิ่ม SweetAlert2 CSS และ JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.js"></script>

<!-- Moment Date -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Custom Scripts -->
<script src="{{ asset('assets/js/table-together.js') }}"></script>

<script>

    $("#ElexaRevenueList").on("shown.bs.modal", function () {
        var table = $(".table-together").DataTable();
        table.columns.adjust().responsive.recalc();
    });

    // Number Format
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    // Function to adjust DataTable
    function adjustDataTable() {
        $.fn.dataTable.tables({
            visible: true,
            api: true,
        }).columns.adjust().responsive.recalc();
    }

    function select_receive_payment(ele, id, amount) 
    {
        var revenueID = $('#revenue_id').val();
        var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Elexa EGAT Revenue (SMS)
        var total = Number($('#total_outstanding').val());
        var total_receive_payment = Number($('#total_receive_payment').val());
        var debit_amount = Number($('#debit_amount').val()) + 1;
        $('#debit_amount').val(debit_amount);

        if (revenueID != "") {

            if ($('#btn-receive-' + id).val() == 0) {
                // Update ยอดที่เลือก
                // var elexa_revenue = Number($('#elexa_revenue'+id).val());
                var elexa_revenue_outstanding = Number($('#input-outstanding-amount').val());
                var elexa_num = Number($('#input-selected-item').val());
                var elexa_revenue_amount = Number($('#input-selected-amount').val());

                $('#input-selected-item').val(elexa_num + 1);
                $('#input-selected-amount').val(elexa_revenue_amount + amount);
                $('#input-outstanding-amount').val(elexa_revenue_outstanding - amount);

                $('#txt-total-selected').text(elexa_num + 1);
                $('#txt-total-selected-amount').text(currencyFormat(elexa_revenue_amount + amount));
                $('#txt-total-selected-outstanding').text(currencyFormat(elexa_revenue_outstanding - amount));
                $('#tfoot-total-outstanding').text(currencyFormat(elexa_revenue_outstanding - amount));
                // END

                $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
                $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment + amount)));
                $('#btn-receive-' + id).val(1);

                $('#txt_total_received').text(currencyFormat(Number(total_receive_payment + amount)));

                $('#total_outstanding').val(total - amount);
                $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard

                $('#form-elexa-select').append('<input type="hidden" id="receive-select-id-' + id + '" name="receive_select_id[]" value="' + id + '">');
                // $('#form-elexa').append('<input type="hidden" id="receive-id-' + id + '" name="receive_id[]" value="' + id + '">');

                $('#tr_row_' + id).remove();
                var tb_select = new DataTable('#myDataTableOutstanding');
                var removingRow = $(ele).closest('tr');
                tb_select.row(removingRow).remove().draw();

                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('debit-select-elexa-outstanding/"+id+"') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        if (response.data) {
                            var status = "";
                            $('#myDataTableOutstandingSelect').DataTable().destroy();
                            var table = $('#myDataTableOutstandingSelect').DataTable(
                                    {
                                        searching: true,
                                        paging: true,
                                        info: true,
                                        order: true,
                                        serverSide: false,
                                        responsive: {
                                        details: {
                                                type: "column",
                                                target: "tr",
                                            },
                                        },
                                        initComplete: function () {
                                            $(".btn-dropdown-menu").dropdown(); // ทำให้ dropdown ทำงาน
                                        },
                                        columnDefs: [
                                            {
                                                targets: [3], className: 'dt-center text-center',
                                            },
                                            {
                                                targets: [2], className: 'text-end',
                                            },
                                            {
                                                targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
                                                createdCell: function (td, cellData, rowData, row, col) {
                                                    // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
                                                    if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
                                                        $(td).text(
                                                        parseFloat(cellData).toLocaleString("en-US", {
                                                            minimumFractionDigits: 2,
                                                            maximumFractionDigits: 2,
                                                        })
                                                        );
                                                    }
                                                },
                                            },
                                        ],
                                    }
                                );

                            $(window).on("resize", adjustDataTable);

                            $('input[type="search"]').attr("placeholder", "Type to search...");
                            $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();

                            table.rows.add(
                                [
                                    [
                                        moment(response.data.date).format('DD/MM/YYYY'),
                                        response.data.batch,
                                        currencyFormat(response.data.ev_revenue),
                                        '<button type="button" class="btn" id="btn-receive-' + id + '" value="1"' +
                                        'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_revenue + ')"><i class="fa fa-trash-o"></i></button>'
                                    ]
                                ]
                            ).draw();
                        }
                    }
                });

            } else {

                // Update ยอดที่เลือก
                // var elexa_revenue = Number($('#elexa_revenue'+id).val());
                var elexa_revenue_outstanding = Number($('#input-outstanding-amount').val());
                var elexa_num = Number($('#input-selected-item').val());
                var elexa_revenue_amount = Number($('#input-selected-amount').val());

                $('#input-selected-item').val(elexa_num - 1);
                $('#input-selected-amount').val(elexa_revenue_amount - amount);
                $('#input-outstanding-amount').val(elexa_revenue_outstanding + amount);

                $('#txt-total-selected').text(elexa_num - 1);
                $('#txt-total-selected-amount').text(currencyFormat(elexa_revenue_amount - amount));
                $('#txt-total-selected-outstanding').text(currencyFormat(elexa_revenue_outstanding + amount));
                $('#tfoot-total-outstanding').text(currencyFormat(elexa_revenue_outstanding + amount));
                // END

                $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
                $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment - amount))); // ยอดที่รับชำระ แสดงแบบ Text
                $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

                // console.log(Number(total_receive_payment).toFixed(2) - Number(amount).toFixed(2));
                $('#total_outstanding').val(total + amount);
                $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment - amount)))); // ยอดคงเหลือ Dashboard

                $('#receive-select-id-' + id).remove();
                // $('#receive-id-' + id).remove();

                var tb_select = new DataTable('#myDataTableOutstandingSelect');
                var removingRow = $(ele).closest('tr');
                tb_select.row(removingRow).remove().draw();

                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('debit-select-elexa-outstanding/"+id+"') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        if (response.data) {
                            var status = "";
                            $('#myDataTableOutstanding').DataTable().destroy();
                            var table = $('#myDataTableOutstanding').DataTable(
                                    {
                                        searching: true,
                                        paging: true,
                                        info: true,
                                        ordering: true,
                                        serverSide: false,
                                        responsive: {
                                        details: {
                                                type: "column",
                                                target: "tr",
                                            },
                                        },
                                        initComplete: function () {
                                            $(".btn-dropdown-menu").dropdown(); // ทำให้ dropdown ทำงาน
                                        },
                                        columnDefs: [
                                            { targets: 0, visible: false }, // ซ่อนคอลัมน์ที่เก็บข้อมูล ISO 8601
                                            {
                                                targets: [4], className: 'dt-center text-center',
                                            },
                                            {
                                                targets: [3], className: 'text-end',
                                            },
                                            {
                                                targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
                                                createdCell: function (td, cellData, rowData, row, col) {
                                                    // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
                                                    if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
                                                        $(td).text(
                                                        parseFloat(cellData).toLocaleString("en-US", {
                                                            minimumFractionDigits: 2,
                                                            maximumFractionDigits: 2,
                                                        })
                                                        );
                                                    }
                                                },
                                            },
                                        ],
                                    }
                                );

                            $(window).on("resize", adjustDataTable);

                            $('input[type="search"]').attr("placeholder", "Type to search...");
                            $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();

                            table.rows.add(
                                [
                                    [
                                        response.data.date, // คอลัมน์ที่ซ่อน ใช้ ISO 8601 สำหรับการจัดเรียง
                                        moment(response.data.date).format('DD/MM/YYYY'),
                                        response.data.batch,
                                        currencyFormat(response.data.ev_revenue),
                                        '<button type="button" class="btn btn-color-green rounded-pill text-white btn-receive-pay" id="btn-receive-' + id + '" value="0"' +
                                        'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_revenue + ')">รับชำระ</button>'
                                    ]
                                ]
                            ).draw();
                        }

                        $('#btn-receive-' + id).val(0);
                        table.order([0, 'asc']).draw();

                    }
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถบันทึกข้อมูลได้',
                text: 'กรุณาเลือกยอด Elexa EGAT Revenue ที่ต้องการชำระก่อน!',
            });
        }

    }

    function btnConfirm() {
        var total_debit = Number($('#total_receive_payment').val());
        var tableSelect = $('#myDataTableOutstandingSelect').DataTable();
        tableSelect.clear().draw();

        jQuery.ajax({
            type: "POST",
            url: "{!! url('debit-confirm-select-elexa-outstanding') !!}",
            datatype: "JSON",
            data: $('#form-elexa-select').serialize(),
            async: false,
            success: function(response) {
                if (response.status == 200 && response.data && typeof response.data === 'object') {

                    Swal.fire({
                        title: 'กำลังโหลด...',
                        didOpen: () => {
                            Swal.showLoading(); // แสดงการโหลด
                        },
                        timer: 500, // ปิดหลังจาก 0.5 วินาที
                        timerProgressBar: true, // แสดงแถบความคืบหน้า
                    });

                    $('#myDataTableDebit').DataTable().destroy();
                    var table = $('#myDataTableDebit').DataTable(
                            {
                                searching: true,
                                paging: true,
                                info: true,
                                order: true,
                                serverSide: false,
                                responsive: {
                                details: {
                                        type: "column",
                                        target: "tr",
                                    },
                                },
                                columnDefs: [
                                    {
                                        targets: [3], className: 'dt-center text-center',
                                    },
                                    {
                                        targets: [2], className: 'text-end',
                                    },
                                    {
                                        targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
                                        createdCell: function (td, cellData, rowData, row, col) {
                                            // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
                                            if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
                                                $(td).text(
                                                parseFloat(cellData).toLocaleString("en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2,
                                                })
                                                );
                                            }
                                        },
                                    },
                                ],
                            }
                        );

                    $(window).on("resize", adjustDataTable);

                    $('input[type="search"]').attr("placeholder", "Type to search...");
                    $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();

                    response.data.forEach(function(item) {
                        $('#form-elexa').append('<input type="hidden" id="receive-id-' + item.id + '" name="receive_id[]" value="' + item.id + '">'); // เพิ่มค่าใน form-elexa รายการที่ยืนยันแล้ว
                        $('#receive-select-id-' + item.id).remove(); // ลบค่าใน form-elexa-select

                        table.rows.add(
                            [
                                [
                                    moment(item.date).format('DD/MM/YYYY'),
                                    item.batch,
                                    currencyFormat(item.ev_revenue),
                                    '<button type="button" class="btn" id="btn-receive-' + item.id + '" value="1"' +
                                    'onclick="delete_receive_payment(this, ' + item.id + ', ' + item.ev_revenue + ')"><i class="fa fa-trash-o"></i></button>'
                                ]
                            ]
                        ).draw();
                    });

                    $('#ElexaRevenueList').modal('hide');
                    $('#input-selected-item').val(0);
                    $('#input-selected-amount').val(0);
                    $('#total_receive_payment').val(total_debit);

                    $('#txt-total-selected').text(0);
                    $('#txt-total-selected-amount').text(currencyFormat(0)); 
                    $('#tfoot-total-debit').text(currencyFormat(total_debit));
                } else {
                    Swal.fire({
                        title: 'กรุณาเลือกข้อมูลก่อนยืนยัน',
                        text: 'ไม่สามารถโหลดข้อมูลได้',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }

    // ปุ่มลบรายการตารางที่ยืนยันแล้ว
    function delete_receive_payment(ele, id, amount) 
    { 
        var revenueID = $('#revenue_id').val();
        var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Elexa EGAT Revenue (SMS)
        var total = Number($('#total_outstanding').val());
        var total_receive_payment = Number($('#total_receive_payment').val());
        var debit_amount = Number($('#debit_amount').val()) + 1;
        $('#debit_amount').val(debit_amount);

        // Update ยอดที่เลือก
        var elexa_revenue_outstanding = Number($('#input-outstanding-amount').val());
        var elexa_num = Number($('#input-selected-item').val());
        var elexa_revenue_amount = Number($('#input-selected-amount').val());

        $('#input-selected-item').val(elexa_num - 1);
        $('#input-selected-amount').val(elexa_revenue_amount - amount);
        $('#input-outstanding-amount').val(elexa_revenue_outstanding + amount);

        $('#txt-total-selected').text(elexa_num - 1);
        $('#txt-total-selected-amount').text(currencyFormat(elexa_revenue_amount - amount));
        $('#txt-total-selected-outstanding').text(currencyFormat(elexa_revenue_outstanding + amount));
        $('#tfoot-total-outstanding').text(currencyFormat(elexa_revenue_outstanding + amount)); // tfoot Outstanding
        $('#tfoot-total-debit').text(currencyFormat(total_receive_payment - amount)); // tfoot Debit
        // END

        $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
        $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment - amount))); // ยอดที่รับชำระ แสดงแบบ Text
        $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

        $('#total_outstanding').val(total + amount);
        $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

        $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment - amount)))); // ยอดคงเหลือ Dashboard

        $('#receive-id-' + id).remove();

        var tb_select = new DataTable('#myDataTableDebit');
        var removingRow = $(ele).closest('tr');
        tb_select.row(removingRow).remove().draw();

        jQuery.ajax({
            type: "GET",
            url: "{!! url('debit-select-elexa-outstanding/"+id+"') !!}",
            datatype: "JSON",
            async: false,
            success: function(response) {
                if (response.data) {
                    var status = "";
                    $('#myDataTableOutstanding').DataTable().destroy();
                    var table = $('#myDataTableOutstanding').DataTable(
                            {
                                searching: true,
                                paging: true,
                                info: true,
                                order: false,
                                serverSide: false,
                                responsive: {
                                details: {
                                        type: "column",
                                        target: "tr",
                                    },
                                },
                                initComplete: function () {
                                    $(".btn-dropdown-menu").dropdown(); // ทำให้ dropdown ทำงาน
                                },
                                columnDefs: [
                                    { targets: 0, visible: false }, // ซ่อนคอลัมน์ที่เก็บข้อมูล ISO 8601
                                    {
                                        targets: [4], className: 'dt-center text-center',
                                    },
                                    {
                                        targets: [3], className: 'text-end',
                                    },
                                    {
                                        targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
                                        createdCell: function (td, cellData, rowData, row, col) {
                                            // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
                                            if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
                                                $(td).text(
                                                parseFloat(cellData).toLocaleString("en-US", {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2,
                                                })
                                                );
                                            }
                                        },
                                    },
                                ],
                            }
                        );

                    table.rows.add(
                        [
                            [
                                response.data.date, // คอลัมน์ที่ซ่อน ใช้ ISO 8601 สำหรับการจัดเรียง
                                moment(response.data.date).format('DD/MM/YYYY'),
                                response.data.batch,
                                currencyFormat(response.data.ev_revenue),
                                '<button type="button" class="btn btn-color-green rounded-pill text-white btn-receive-pay" id="btn-receive-' + id + '" value="0"' +
                                'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_revenue + ')">รับชำระ</button>'
                            ]
                        ]
                    ).draw();
                }

                $('#btn-receive-' + id).val(0);
                table.order([0, 'asc']).draw(); // refresh table

                $(window).on("resize", adjustDataTable);

                $('input[type="search"]').attr("placeholder", "Type to search...");
                $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();

            }
        });
    }

    document.querySelector("#btn-save").addEventListener('click', function() 
    {
        var total_receive_payment = Number($('#total_receive_payment').val()).toFixed(2);
        var total_revenue_amount = Number($('#total_revenue_amount').val()).toFixed(2);

        if (total_revenue_amount > total_receive_payment) {
            return Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถบันทึกข้อมูลได้',
                text: 'ยอด Elexa EGAT Outstanding ที่เลือกน้อยกว่า Elexa EGAT Revenue!',
            });
        }

        if (total_revenue_amount < total_receive_payment) {
            return Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถบันทึกข้อมูลได้',
                text: 'ยอด Elexa EGAT Outstanding ที่เลือกมากกว่า Elexa EGAT Revenue!',
            });
        }

        if (total_revenue_amount == total_receive_payment) {

            Swal.fire({
                icon: "info",
                title: 'ต้องการบันทึกข้อมูลใช่หรือไม่?',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    jQuery.ajax({
                        type: "POST",
                        url: "{!! route('debit-elexa-store') !!}",
                        datatype: "JSON",
                        data: $('#form-elexa').serialize(),
                        async: false,
                        success: function(result) {
                            // ใช้ window.location เพื่อไปยัง URL ที่ต้องการหลังจากบันทึก
                            window.location.href = "{!! route('debit-elexa-revenue') !!}";
                        },
                    });

                } else if (result.isDenied) {
                    Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', '', 'info');
                }
            });
        }

    });
</script>

@endsection

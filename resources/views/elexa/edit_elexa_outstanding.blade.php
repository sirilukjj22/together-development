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
                <button type="button" class="bt-tg-normal sm" id="btn-modal-add">Add </button>
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
                            <td class="text-start">{{ $item->batch }}</td>
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
                    
                    @if (!empty($elexa_debit_out))
                        @foreach ($elexa_debit_out as $key => $item)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td class="text-start">Debit Revenue</td>
                                <td class="text-end target-class">-{{ $item->amount }}</td>
                                <td>
                                    <button type="button" class="btn" value="1" onclick="delete_debit(this, {{ $key }}, {{ $item->amount }})"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>
                            <?php 
                                $total_debit -= $item->amount; 
                            ?>
                        @endforeach
                    @endif
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
            <div class="flex-end">
                {{-- <input type="text" id="input-debit-amount" class="form-control mt-2" style="width: 100px; height: 35px;"> --}}
                <button type="button" id="btn-debit-amount" class="bt-tg-normal sm">Debit</button>
            </div>
            <div class="mt-2 top-3-card">
                <div>
                    <span>Total Selected </span>
                    <br />
                    <b><span id="txt-total-selected">0</span></b>
                </div>
                <div>
                    <span>Total Debit </span>
                    <br />
                    <b><span id="txt-total-debit-revenue">0</span></b>
                </div>
                <div>
                    <span>Total Selected Amount</span>
                    <br />
                    <b id="txt-total-selected-amount">0.00</b>
                </div>
                <div>
                    <span>Total Debit Amount</span>
                    <br />
                    <b id="txt-total-debit-amount">0.00</b>
                </div>
                <div>
                    <span>Outstanding </span>
                    <br />
                    <b id="txt-total-selected-outstanding">{{ number_format(round($elexa_revenue->amount - $total_debit, 2), 2) }}</b>
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
                    <tfoot style="background-color: #d7ebe1; font-weight: bold">
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td class="text-end"><span id="tfoot-total-outstanding-select">0.00</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="wrap-table-together">
                <div class="flex-end mt-2">
                    <div class="filter-section bd-select-cl d-flex" style="gap: 0.3em">
                        <select id="elexaYearFilter" class="form-select" style="width: max-content" onchange="filterOutstanding()">
                            <option value="all">All Years</option>
                            <?php
                                $startYear = 2024; // ปีเริ่มต้น
                                $endYear = date("Y"); // ปีปัจจุบัน

                                for ($year = $startYear; $year <= $endYear; $year++) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                            ?>
                        </select>

                        <select id="elexaMonthFilter" class="form-select" style="width: max-content" onchange="filterOutstanding()">
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
                <div class="flex-end">
                    <button type="button" class="bt-tg-normal sm" id="btn-add-multi">Receive multiple payments</button>
                </div>
                <table id="myDataTableOutstanding" class="table-style table-together" style="width: 100%;">
                    <thead>
                        <tr>
                            <th data-priority="1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="select-all-outstanding" id="select-all-outstanding">
                                    <label class="form-check-label" for="select-all-outstanding">Select </label>
                                </div>                                
                            </th>
                            <th data-priority="2">#</th>
                            <th data-priority="1">Date</th>
                            <th data-priority="1">Order ID</th>
                            <th data-priority="1">EV Charge</th>
                            <th data-priority="1">EV Fee</th>
                            <th data-priority="1">EV VAT</th>
                            <th data-priority="1">amount</th>
                            <th data-priority="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot style="background-color: #d7ebe1; font-weight: bold">
                        <tr>
                            <td></td>
                            <td style="padding: 10px">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><span id="tfoot-total-outstanding">0.00</span></td>
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

<div class="modal fade" id="ElexaDebitRevenue" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="formModalLabel">Debit Revenue</h5>
            {{-- <button type="button" style="border: 1px solid rgb(196, 194, 194);border-radius: 5px; width: 35px;" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 24px;">&times;</span>
            </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-12 mt-2">
                    <div class="col-md-6 d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input select-status-debit" type="radio" name="flexRadioDefault" id="credit" value="1" checked>
                            <label class="form-check-label" for="credit">Credit</label> <!-- บวก -->
                        </div>
                        <div class="form-check">
                            <input class="form-check-input select-status-debit" type="radio" name="flexRadioDefault" id="debit" value="1">
                            <label class="form-check-label" for="debit">Debit</label> <!-- ลบ -->
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="">Amount</label>
                        <input type="text" class="form-control" name="" id="input-debit-amount" placeholder="0.00">
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="">Remark</label>
                        <textarea class="form-control" name="" id="input-debit-remark" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="bt-tg-normal bt-grey sm mx-2" id="btn-close-debit" data-bs-dismiss="modal"> Close </button>
                <button type="button" class="bt-tg-normal sm" onclick="btnConfirmDebitRevenue()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="total_receive_payment" value="{{ $total_debit }}"> <!-- ยอดรายการที่เลือกทั้งหมด -->
<input type="hidden" id="total_revenue_amount" value="{{ isset($elexa_revenue) ? $elexa_revenue->amount : 0 }}"> <!-- ยอดจาก SMS -->
<input type="hidden" id="debit_amount" value="{{ $debit_amount }}">
<input type="hidden" id="outstanding_amount" value="{{ $outstanding_amount }}">

<!-- Total Selected, Total Selected Amount, Outstanding -->
<input type="hidden" id="input-outstanding-amount" value="{{ round($elexa_revenue->amount - $total_debit, 2) }}">
<input type="hidden" id="input-selected-amount" value="0">
<input type="hidden" id="input-selected-item" value="0">

<!-- Debit Revenue -->
<input type="hidden" id="input-total-debit-revenue" value="0"> <!-- จำนวนรายการที่ Debit -->
<input type="hidden" id="input-total-debit-amount" value="0"> <!-- ยอดเงินที่ Debit -->

<form action="#" id="form-elexa">
    @csrf
    <input type="hidden" name="doc_no" value="{{ $document_no ?? '' }}">
    <input type="hidden" name="issue_date" value="{{ date('Y-m-d') }}">
    <input type="hidden" id="revenue_id" name="sms_id" value="{{ isset($elexa_revenue) ? $elexa_revenue->id : 0 }}"> <!-- ID รายได้ที่มาจาก SMS -->
    <input type="hidden" id="debit-out-amount" name="debit_out_amount" value="{{ (isset($document_query) && !empty($document_query) ? $document_query->debit_amount : 0) }}">
    <input type="hidden" id="input-total-debit-status" name="status_type" value="0"> <!-- 0 = Credit, 1 = Debit -->

    @foreach ($elexa_all as $key => $item)
        @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
            <input type="hidden" id="receive-id-{{ $item->id }}" name="receive_id[]" value="{{ $item->id }}">
        @endif
    @endforeach

    @if (!empty($elexa_debit_out))
        @foreach ($elexa_debit_out as $key => $item)
            <input type="hidden" id="debit-revenue-amount-{{ $key }}" name="debit_revenue_amount[]" value="{{ $item->amount }}">
            <input type="hidden" id="debit-revenue-remark-{{ $key }}" name="debit_revenue_remark[]" value="{{ $item->remark }}">
        @endforeach
    @endif
</form>

<form action="#" id="form-elexa-select">
    {{-- @csrf --}}
</form>

<style>
    .table-together tr th {
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
    $(document).on('click', '#btn-close-debit', function () {
        $('#ElexaDebitRevenue').on('hidden.bs.modal', function () {
            // เช็คว่า Modal ปิดแล้วจริงๆ
            if (!$('#ElexaDebitRevenue').hasClass('show')) {
                $('body').addClass('modal-open'); // เพิ่มคลาส modal-open ให้กับ body
            }
        });
    });

    // เมื่อกดปุ่ม Add จะเปิด Add Modal
    $('#btn-modal-add').click(function() {
        var sms_amount = Number($('#total_revenue_amount').val());
        var outstanding = Number($('#input-outstanding-amount').val());

        // Total Selected, Total Selected Amount
        $('#txt-total-selected').text('0');
        $('#txt-total-selected-amount').text('0.00');
        $('#input-selected-amount').val(0);
        $('#input-selected-item').val(0);

        // Total Debit, Total Debit Amount
        $('#txt-total-debit-revenue').text('0');
        $('#txt-total-debit-amount').text('0.00');
        $('#input-total-debit-revenue').val(0);
        $('#input-total-debit-amount').val(0);

        // Outstanding
        $('#txt-total-selected-outstanding').text(currencyFormat(outstanding));
        $('#input-outstanding-amount').val(outstanding);

        $('#myDataTableOutstandingSelect').DataTable().clear().draw();
        $('#tfoot-total-outstanding-select').text('0.00');

        $('#form-elexa-select').children().remove().end();

        selectedItems = [];
        getTableOutstanding();

        $('#ElexaRevenueList').modal('show');
    });

    // เมื่อกดปุ่ม Debit ใน Add Modal, จะเปิด Debit Modal โดยไม่ปิด Add Modal
    $('#btn-debit-amount').click(function() {
        if (Number($('#input-selected-item').val()) > 0) {
            $('#input-debit-amount').val('');
            $('#input-debit-remark').val('');
            $('#ElexaDebitRevenue').modal('show');
        } else {
                return Swal.fire({
                    icon: 'error',
                    title: 'Error occurred.',
                    text: 'กรุณาเลือกยอด Elexa EGAT Revenue ที่ต้องการชำระก่อน!',
                });
            }
    });

    function clearDebit() {
        $('#txt-total-debit-revenue').val(0);
        $('#txt-total-debit-amount').val(0);
        $('#input-total-debit-revenue').val(0);
        $('#input-total-debit-amount').val(0);

        $('input[name="debit_revenue[]"]').remove();
        $('input[name="remark_debit_revenue[]"]').remove();

        $('#myDataTableOutstandingSelect').DataTable().rows('.row-debit-revenue').remove().draw(false); // อัปเดตตาราง
    }

    function btnConfirmDebitRevenue() 
    {
        // Clear 
        if ($('#debit').is(':checked') && $('#input-total-debit-status').val() != '1') {
            $('#input-total-debit-status').val(1);
            clearDebit();

        } if ($('#credit').is(':checked') && $('#input-total-debit-status').val() != '0') {
            $('#input-total-debit-status').val(0);
            clearDebit();
        }

        var radio_credit = $('#credit').val();
        var radio_debit = $('#debit').val();
        var debit_amount = Number($('#input-debit-amount').val());
        var debit_amount_old = Number($('#debit-out-amount').val());
        var number_debit = Number($('#input-total-debit-revenue').val());
        var total_debit_amount = Number($('#input-total-debit-amount').val());
        var outstanding = Number($('#input-outstanding-amount').val());
        var sms_amount = Number($('#total_revenue_amount').val());

        if ($('#debit').is(':checked') && debit_amount != '' && debit_amount != 0) {
            var selected_amount = Math.floor((Number($('#input-selected-amount').val()) - debit_amount) * 100) / 100;
            
            // Update
            // Text
            $('#txt-total-debit-revenue').text(number_debit + 1);
            $('#txt-total-debit-amount').text(total_debit_amount + debit_amount);
            $('#txt-total-selected-amount').text(currencyFormat(selected_amount));
            $('#txt-total-selected-outstanding').text(currencyFormat(sms_amount - selected_amount));

            // Input
            $('#input-total-debit-revenue').val(number_debit + 1);
            $('#input-total-debit-amount').val(total_debit_amount + debit_amount);
            $('#input-selected-amount').val(selected_amount);
            $('#input-outstanding-amount').val(sms_amount - selected_amount);
            $('#debit-out-amount').val(debit_amount - debit_amount_old); 

            $('#tfoot-total-outstanding-select').text(currencyFormat(selected_amount));
            $('#form-elexa-select').append('<input type="hidden" name="debit_revenue[]" value="' + debit_amount + '">');
            $('#form-elexa-select').append('<input type="hidden" name="remark_debit_revenue[]" value="' + debit_amount + '">');

            var table = $('#myDataTableOutstandingSelect').DataTable();

            var newRow = table.row.add([
                moment().format('DD/MM/YYYY'),
                'Debit Revenue',
                '-' + currencyFormat(debit_amount),
                '<button type="button" class="btn" value="1"' +
                ' onclick="select_delete_debit(this, ' + (debit_amount) + ')">' +
                '<i class="fa fa-trash-o"></i></button>'
            ]).draw(false).node();  

            $(newRow).addClass('row-debit-revenue');

        }

        if ($('#credit').is(':checked') && debit_amount != '' && debit_amount != 0) {
            var selected_amount = Math.floor((Number($('#input-selected-amount').val()) + debit_amount) * 100) / 100;
            
            // Update
            // Text
            $('#txt-total-debit-revenue').text(number_debit + 1);
            $('#txt-total-debit-amount').text(total_debit_amount + debit_amount);
            $('#txt-total-selected-amount').text(currencyFormat(selected_amount));
            $('#txt-total-selected-outstanding').text(currencyFormat(sms_amount - selected_amount));

            // Input
            $('#input-total-debit-revenue').val(number_debit + 1);
            $('#input-total-debit-amount').val(total_debit_amount + debit_amount);
            $('#input-selected-amount').val(selected_amount);
            $('#input-outstanding-amount').val(sms_amount - selected_amount);
            $('#debit-out-amount').val(debit_amount + debit_amount_old); 

            $('#tfoot-total-outstanding-select').text(currencyFormat(selected_amount));
            $('#form-elexa-select').append('<input type="hidden" name="debit_revenue[]" value="' + debit_amount + '">');
            $('#form-elexa-select').append('<input type="hidden" name="remark_debit_revenue[]" value="' + debit_amount + '">');

            var table = $('#myDataTableOutstandingSelect').DataTable();

            var newRow = table.row.add([
                moment().format('DD/MM/YYYY'),
                'Credit Revenue',
                "+" + currencyFormat(debit_amount),
                '<button type="button" class="btn" value="1"' +
                ' onclick="select_delete_debit(this, ' + (debit_amount) + ')">' +
                '<i class="fa fa-trash-o"></i></button>'
            ]).draw(false).node();  // ดึง node ของแถวที่เพิ่ม

            $(newRow).addClass('row-debit-revenue');

        }

        $('#ElexaDebitRevenue').modal('hide'); // ปิด Modal

        // ใช้ event hidden.bs.modal เพื่อให้แน่ใจว่า Modal ถูกปิดแล้ว
        $('#ElexaDebitRevenue').on('hidden.bs.modal', function () {
            // เช็คว่า Modal ปิดแล้วจริงๆ
            if (!$('#ElexaDebitRevenue').hasClass('show')) {
                $('body').addClass('modal-open'); // เพิ่มคลาส modal-open ให้กับ body
            }
        });
        
    }

    function filterOutstanding() {
        selectedItems = [];
        getTableOutstanding();
    }

    // Number Format
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    function currencyFormat2(num) {
        let str = num.toString();
        let parts = str.split("."); // แยกส่วนจำนวนเต็มและทศนิยม
        parts[1] = parts[1] ? parts[1].slice(0, 2) : "00"; // ตัดทศนิยมให้เหลือ 2 ตำแหน่ง
        return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "." + parts[1];
    }

    // Function to adjust DataTable
    function adjustDataTable() {
        $.fn.dataTable.tables({ visible: true, api: true, }).columns.adjust().responsive.recalc();
    } 

    var selectedItems = []; // ตัวแปรเก็บค่าที่เลือก
    var selectAllChecked = false;

    $(document).on('click', '#select-all-outstanding', function () {
        var isChecked = $(this).prop('checked');
        selectAllChecked = isChecked;  // บันทึกสถานะของ Select All

        $('input[type="checkbox"]').each(function() {
            var value = $(this).val();

            if (isChecked) {
                $(this).prop('checked', true);
                if (!selectedItems.includes(value)) {
                    selectedItems.push(value); // เพิ่มค่าใน selectedItems ถ้ายังไม่มี
                }
            } else {
                $(this).prop('checked', false);
                selectedItems = selectedItems.filter(item => item !== value); // ลบค่าจาก selectedItems
            }
        });

        getTableOutstanding();
    });

    // ฟังก์ชันสำหรับโหลด DataTable
    function getTableOutstanding() 
    {
        var year = $('#elexaYearFilter').val();
        var month = $('#elexaMonthFilter').val();
        var select_all = $('#select-all-outstanding').prop('checked');
        var receiveID = $('[name="receive_select_id[]"]').map(function() { return $(this).val(); }).get();
        var confirmReceiveID = $('[name="receive_id[]"]').map(function() { return $(this).val(); }).get();

        var table = $("#myDataTableOutstanding").DataTable({
            paging: true,
            searching: true,
            ordering: false,
            info: true,
            destroy: true, // ทำลายก่อนสร้างใหม่
            // serverSide: false,
            responsive: {
                details: {
                    type: "column",
                    target: "tr"
                }
            },
            language: {
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoFiltered: "" 
            },
            ajax: {
                url: '/debit-get-outstanding',
                type: 'POST',
                data: {
                    year: year,
                    month: month,
                    confirmReceiveID: confirmReceiveID,
                    receiveID: receiveID,
                    select_all: select_all
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataSrc: function (json) {
                    totalAmount = json.totalAmount;
                    return json.data; // ส่งข้อมูลที่แสดงใน DataTable
                }
            },
            initComplete: function (settings, json) {
                $('#tfoot-total-outstanding').text(totalAmount);
            },
            columnDefs: [
                { targets: [4, 5, 6, 7], className: 'text-end' },
                {
                    targets: [5],
                    createdCell: function(td, cellData, rowData, row, col) {
                        if ($.isNumeric(cellData)) {
                            $(td).text(parseFloat(cellData).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        }
                    }
                },
                {
                    targets: [4, 6, 7],
                    createdCell: function(td, cellData, rowData, row, col) {
                        if ($.isNumeric(cellData)) {
                            let formattedNumber = Math.floor(cellData * 100) / 100; // ตัดเศษออกที่ทศนิยม 2 ตำแหน่ง
                            $(td).text(formattedNumber.toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        }
                    }
                }
            ],
            columns: [
                { data: 'check_box' },
                { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'date', "render": function (data, type, full) { return moment(data).format('DD/MM/YYYY') } },
                { data: 'order_id' },
                { data: 'ev_charge' },
                { data: 'ev_fee' },
                { data: 'ev_vat' },
                { data: 'amount' },
                { data: 'btn_action' },
            ],
        });

        // คืนค่าที่เลือกไว้หลังจากโหลดข้อมูลใหม่
        table.on('draw', function() {
            $('input[type="checkbox"]').each(function() {
                var value = $(this).val();
                if (selectedItems.includes(value)) {
                    $(this).prop('checked', true); // ตั้งค่า checked ให้ตรงกับ selectedItems
                }
            });
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    // บันทึกค่า checkbox เมื่อมีการเปลี่ยนแปลง
    $(document).on('change', 'input[type="checkbox"]', function() {
        var value = $(this).val();
        if ($(this).prop('checked')) {
            if (!selectedItems.includes(value)) {
                selectedItems.push(value);
            }
        } else {
            selectedItems = selectedItems.filter(item => item !== value);
        }
    });

    $(document).on('click', '#btn-add-multi', function () {
        // ตรวจสอบว่ามีการเลือก checkbox ไหม
        if (selectedItems.length === 0) {
            alert('กรุณาเลือกอย่างน้อยหนึ่งรายการ');
            return;
        } else {
            // $('#myDataTableOutstandingSelect').DataTable().clear().draw();

            if ($('#select-all-outstanding').prop('checked')) {
                var year = $('#elexaYearFilter').val();
                var month = $('#elexaMonthFilter').val();
                var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด SMS
                
                jQuery.ajax({
                    type: "POST",
                    url: "{!! url('debit-select-all-elexa-outstanding') !!}",
                    datatype: "JSON",
                    data: { 
                        total_revenue_amount: total_revenue_amount,
                        selectedItems: selectedItems,
                        year: year,
                        month: month, 
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    async: false,
                    success: function(response) {
                        if (response.status == 200) {
                            var sumTotalAmount = 0;
                            var status = "";
                            var table = $('#myDataTableOutstandingSelect').DataTable(
                                    {
                                        searching: true,
                                        paging: true,
                                        info: true,
                                        order: true,
                                        // serverSide: false,
                                        destroy: true,
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
                                            { targets: [3], className: 'dt-center text-center', },
                                            { targets: [2], className: 'text-end', },
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

                            table.page('last').draw('page');

                            response.data.forEach(function(value) {
                                // Update ยอดที่เลือก
                                var elexa_revenue_outstanding = Number($('#input-outstanding-amount').val());
                                var elexa_num = Number($('#input-selected-item').val());
                                var elexa_revenue_amount = Number($('#input-selected-amount').val());
                                // END

                                table.rows.add(
                                    [
                                        [
                                            moment(value.date).format('DD/MM/YYYY'),
                                            value.batch,
                                            currencyFormat2(value.ev_revenue),
                                            '<button type="button" class="btn" id="btn-receive-' + value.id + '" value="1"' +
                                            'onclick="select_receive_payment(this, ' + value.id + ', ' + value.ev_revenue + ')"><i class="fa fa-trash-o"></i></button>'
                                        ]
                                    ]
                                ).draw();

                                table.page('last').draw('page');

                                $('#form-elexa-select').append('<input type="hidden" id="receive-select-id-' + value.id + '" name="receive_select_id[]" value="' + value.id + '">');

                                sumTotalAmount += value.ev_revenue;

                                $('#input-selected-item').val(elexa_num + 1);
                                $('#input-selected-amount').val(elexa_revenue_amount + value.ev_revenue);
                                $('#input-outstanding-amount').val(elexa_revenue_outstanding - value.ev_revenue);

                                $('#txt-total-selected').text(elexa_num + 1);
                                $('#txt-total-selected-amount').text(currencyFormat(elexa_revenue_amount + value.ev_revenue));
                                $('#txt-total-selected-outstanding').text(currencyFormat(elexa_revenue_outstanding - value.ev_revenue));
                                $('#tfoot-total-outstanding').text(currencyFormat(elexa_revenue_outstanding - value.ev_revenue));
                            });

                            $('#tfoot-total-outstanding-select').text(currencyFormat(Number($('#input-selected-amount').val())));
                            
                        } else {
                            return Swal.fire({
                                icon: 'error',
                                title: 'ไม่สามารถรับชำระได้!',
                                text: 'ยอดที่รับชำระมากกว่ายอดคงเหลือ',
                                customClass: {
                                    title: 'swal-title-custom'
                                },
                            });
                        }
                    }
                });

                $('#select-all-outstanding').prop('checked', false);
                selectedItems = [];

            } else {
                selectedItems.forEach(function(value) {
                    // Update ยอดที่เลือก
                    // var elexa_revenue = Number($('#elexa_revenue'+id).val());
                    var elexa_revenue_outstanding = Number($('#input-outstanding-amount').val());
                    var elexa_num = Number($('#input-selected-item').val());
                    var elexa_revenue_amount = Number($('#input-selected-amount').val());
                    // END

                    // ถ้าเลือกไว้ใน selectedItems ให้ append ค่าไปยังฟอร์ม
                    if (value != "select-all-outstanding") {
                        $('#form-elexa-select').append('<input type="hidden" id="receive-select-id-' + value + '" name="receive_select_id[]" value="' + value + '">');

                        jQuery.ajax({
                            type: "GET",
                            url: "{!! url('debit-select-elexa-outstanding/"+value+"') !!}",
                            datatype: "JSON",
                            async: false,
                            success: function(response) {
                                if (response.data) {
                                    var status = "";
                                    var table = $('#myDataTableOutstandingSelect').DataTable(
                                            {
                                                searching: true,
                                                paging: true,
                                                info: true,
                                                order: true,
                                                serverSide: false,
                                                destroy: true,
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
                                                    { targets: [3], className: 'dt-center text-center', },
                                                    { targets: [2], className: 'text-end', },
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
                                                '<button type="button" class="btn" id="btn-receive-' + value + '" value="1"' +
                                                'onclick="select_receive_payment(this, ' + value + ', ' + response.data.ev_revenue + ')"><i class="fa fa-trash-o"></i></button>'
                                            ]
                                        ]
                                    ).draw();

                                    table.page('last').draw('page');

                                    $('#input-selected-item').val(elexa_num + 1);
                                    $('#input-selected-amount').val(elexa_revenue_amount + response.data.ev_revenue);
                                    $('#input-outstanding-amount').val(elexa_revenue_outstanding - response.data.ev_revenue);

                                    $('#txt-total-selected').text(elexa_num + 1);
                                    $('#txt-total-selected-amount').text(currencyFormat(elexa_revenue_amount + response.data.ev_revenue));
                                    $('#txt-total-selected-outstanding').text(currencyFormat(elexa_revenue_outstanding - response.data.ev_revenue));
                                    $('#tfoot-total-outstanding').text(currencyFormat(elexa_revenue_outstanding - response.data.ev_revenue));
                                }
                            }
                        });
                    }
                });
            }
        }

        // เรียกใช้ getTableOutstanding() เพื่อรีเฟรช DataTable
        selectedItems = [];
        getTableOutstanding();
    });


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
                $('#tfoot-total-outstanding-select').text(currencyFormat(elexa_revenue_amount + amount));
                // END

                $('#total_receive_payment').val(total_receive_payment + amount);
                $('#txt_total_receive_payment').text(currencyFormat(total_receive_payment + amount));
                $('#btn-receive-' + id).val(1);

                $('#txt_total_received').text(currencyFormat(Number(total_receive_payment + amount)));

                $('#total_outstanding').val(total - amount);
                $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard

                $('#form-elexa-select').append('<input type="hidden" id="receive-select-id-' + id + '" name="receive_select_id[]" value="' + id + '">');

                getTableOutstanding();
                selectedItems = [];

                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('debit-select-elexa-outstanding/"+id+"') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        if (response.data) {
                            var status = "";
                            var table = $('#myDataTableOutstandingSelect').DataTable(
                                    {
                                        searching: true,
                                        paging: true,
                                        info: true,
                                        order: true,
                                        destroy: true,
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
                                            { targets: [3], className: 'dt-center text-center', },
                                            { targets: [2], className: 'text-end', },
                                            // {
                                            //     targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
                                            //     createdCell: function (td, cellData, rowData, row, col) {
                                            //         // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
                                            //         if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
                                            //             $(td).text(
                                            //             parseFloat(cellData).toLocaleString("en-US", {
                                            //                 minimumFractionDigits: 2,
                                            //                 maximumFractionDigits: 2,
                                            //             })
                                            //             );
                                            //         }
                                            //     },
                                            // },
                                            {
                                                targets: [2],
                                                createdCell: function(td, cellData, rowData, row, col) {
                                                    if ($.isNumeric(cellData)) {
                                                        let formattedNumber = Math.floor(cellData * 100) / 100; // ตัดเศษออกที่ทศนิยม 2 ตำแหน่ง
                                                        $(td).text(formattedNumber.toLocaleString("en-US", {
                                                            minimumFractionDigits: 2,
                                                            maximumFractionDigits: 2
                                                        }));
                                                    }
                                                }
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
                                        response.data.ev_revenue,
                                        '<button type="button" class="btn" id="btn-receive-' + id + '" value="1"' +
                                        'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_revenue + ')"><i class="fa fa-trash-o"></i></button>'
                                    ]
                                ]
                            ).draw();

                            table.page('last').draw('page');
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

                $('#tfoot-total-outstanding-select').text(currencyFormat(elexa_revenue_amount - amount));

                $('#receive-select-id-' + id).remove();
                // $('#receive-id-' + id).remove();

                var tb_select = new DataTable('#myDataTableOutstandingSelect');
                var removingRow = $(ele).closest('tr');
                tb_select.row(removingRow).remove().draw();

                getTableOutstanding();
                selectedItems = [];
            }
        } else {
           return Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถบันทึกข้อมูลได้',
                text: 'กรุณาเลือกยอด Elexa EGAT Revenue ที่ต้องการชำระก่อน!',
            });
        }

    }

    function btnConfirm() 
    {
        var total_debit = 0;
        var debit_status = Number($('#input-total-debit-status').val()); // 0 = Credit, 1 = Debit
        var debit_out = Number($('#debit-out-amount').val());
        var sms_amount = Number($('#total_revenue_amount').val());
        var total_receive_payment = Number($('#total_receive_payment').val());
        var total_selected_amount = Number($('#input-selected-amount').val());
        var debit_revenue = $('input[name="debit_revenue[]"]').map(function() {
            return $(this).val();
        }).get();

        var debit_remark = $('input[name="remark_debit_revenue[]"]').map(function() {
            return $(this).val();
        }).get();

        var tableSelect = $('#myDataTableOutstandingSelect').DataTable();
        
        if (total_selected_amount == sms_amount) {
            tableSelect.clear().draw();

            jQuery.ajax({
                type: "POST",
                url: "{!! url('debit-confirm-select-elexa-outstanding') !!}",
                datatype: "JSON",
                data: $('#form-elexa-select').serialize(),
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
    
                        var table = $('#myDataTableDebit').DataTable(
                                {
                                    searching: true,
                                    paging: true,
                                    info: true,
                                    order: true,
                                    destroy: true,
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
    
                            total_debit += item.ev_revenue;
                        });

                        debit_revenue.forEach(element => {
                            $('#form-elexa').append('<input type="hidden" name="debit_revenue_amount[]" value="' + element + '">'); // เพิ่มค่าใน form-elexa รายการที่ยืนยันแล้ว

                            table.rows.add(
                                [
                                    [
                                        moment().format('DD/MM/YYYY'),
                                        'Debit Revenue',
                                        '-' + element,
                                        '<button type="button" class="btn" value="1"' +
                                        'onclick="select_delete_debit(this, ' + (element) + ')"><i class="fa fa-trash-o"></i></button>'
                                    ]
                                ]
                            ).draw();
                        }); 

                        debit_remark.forEach(element => {
                            $('#form-elexa').append('<input type="hidden" name="debit_revenue_remark[]" value="' + element + '">'); // เพิ่มค่าใน form-elexa รายการที่ยืนยันแล้ว
                        });
    
                        $('#ElexaRevenueList').modal('hide');
                        $('#input-selected-item').val(0);
                        $('#input-selected-amount').val(0);

                        if (debit_status == 0) {
                            $('#total_receive_payment').val(total_debit + debit_out);
                            $('#tfoot-total-debit').text(currencyFormat(total_debit + debit_out));
                        } else {
                            $('#total_receive_payment').val(total_debit - debit_out);
                            $('#tfoot-total-debit').text(currencyFormat(total_debit - debit_out));
                        }
    
                        $('#txt-total-selected').text(0);
                        $('#txt-total-selected-amount').text(currencyFormat(0)); 
                        
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
        } else {
            if (total_selected_amount > sms_amount) {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Elexa EGAT Outstanding ที่เลือกมากกว่า Elexa EGAT Revenue!',
                });
            } else {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Elexa EGAT Outstanding ที่เลือกน้อยกว่า Elexa EGAT Revenue!',
                });
            }
        }
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

        getTableOutstanding();

        // jQuery.ajax({
        //     type: "GET",
        //     url: "{!! url('debit-select-elexa-outstanding/"+id+"') !!}",
        //     datatype: "JSON",
        //     async: false,
        //     success: function(response) {
        //         if (response.data) {
        //             var status = "";
        //             $('#myDataTableOutstanding').DataTable().destroy();
        //             var table = $('#myDataTableOutstanding').DataTable(
        //                     {
        //                         searching: true,
        //                         paging: true,
        //                         info: true,
        //                         order: false,
        //                         serverSide: false,
        //                         responsive: {
        //                         details: {
        //                                 type: "column",
        //                                 target: "tr",
        //                             },
        //                         },
        //                         initComplete: function () {
        //                             $(".btn-dropdown-menu").dropdown(); // ทำให้ dropdown ทำงาน
        //                         },
        //                         columnDefs: [
        //                             { targets: 0, visible: false }, // ซ่อนคอลัมน์ที่เก็บข้อมูล ISO 8601
        //                             {
        //                                 targets: [4], className: 'dt-center text-center',
        //                             },
        //                             {
        //                                 targets: [3], className: 'text-end',
        //                             },
        //                             {
        //                                 targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
        //                                 createdCell: function (td, cellData, rowData, row, col) {
        //                                     // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
        //                                     if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
        //                                         $(td).text(
        //                                         parseFloat(cellData).toLocaleString("en-US", {
        //                                             minimumFractionDigits: 2,
        //                                             maximumFractionDigits: 2,
        //                                         })
        //                                         );
        //                                     }
        //                                 },
        //                             },
        //                         ],
        //                     }
        //                 );

        //             table.rows.add(
        //                 [
        //                     [
        //                         response.data.date, // คอลัมน์ที่ซ่อน ใช้ ISO 8601 สำหรับการจัดเรียง
        //                         moment(response.data.date).format('DD/MM/YYYY'),
        //                         response.data.batch,
        //                         currencyFormat(response.data.ev_revenue),
        //                         '<button type="button" class="btn btn-color-green rounded-pill text-white btn-receive-pay" id="btn-receive-' + id + '" value="0"' +
        //                         'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_revenue + ')">รับชำระ</button>'
        //                     ]
        //                 ]
        //             ).draw();
        //         }

        //         $('#btn-receive-' + id).val(0);
        //         table.order([0, 'asc']).draw(); // refresh table

        //         $(window).on("resize", adjustDataTable);

        //         $('input[type="search"]').attr("placeholder", "Type to search...");
        //         $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();

        //     }
        // });
    }

    // ปุ่มลบรายการยอด Debit ตารางที่ยืนยันแล้ว
    function delete_debit(ele, number, amount) {
        var total_receive_payment = Number($('#total_receive_payment').val());
        var sms_amount = Number($('#total_revenue_amount').val());
        var outstanding = Number($('#input-outstanding-amount').val());
        // var selected_amount = Number($('#input-selected-amount').val());
        // var outstanding = Number($('#outstanding_amount').val());

        // // Input
        // $('#input-selected-amount').val((Math.floor((selected_amount + amount) * 100) / 100).toFixed(2));

        // var sms_amount = Number($('#total_revenue_amount').val());
        // var sum_selected_amount = Number($('#input-selected-amount').val());

        $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
        $('#tfoot-total-debit').text(currencyFormat(total_receive_payment + amount)); // Total Select Amount
        $('#txt-total-selected-outstanding').text(currencyFormat(sms_amount - $('#total_receive_payment').val())); // Outstanding
        $('#input-outstanding-amount').val(sms_amount - $('#total_receive_payment').val());
        // $('#tfoot-total-outstanding-select').text(currencyFormat(sum_selected_amount)); // Footer ตารางรายการรับชำระ

        $('#debit-revenue-amount-' + number).remove().end();
        $(ele).parent().parent().remove();
    }

    // ปุ่มลบรายการยอด Debit ตารางใน Modal
    function select_delete_debit(ele, amount) {
        var debit_amount_old = Number($('#debit-out-amount').val());
        var selected_amount = Number($('#input-selected-amount').val());
        var outstanding = Number($('#outstanding_amount').val());

        // Input
        $('#input-selected-amount').val((Math.floor((selected_amount + amount) * 100) / 100).toFixed(2));

        var sms_amount = Number($('#total_revenue_amount').val());
        var sum_selected_amount = Number($('#input-selected-amount').val());

        $('#debit-out-amount').val(debit_amount_old - amount);
        $('#txt-total-selected-amount').text(currencyFormat(sum_selected_amount)); // Total Select Amount
        $('#txt-total-selected-outstanding').text(currencyFormat(sms_amount - sum_selected_amount)); // Outstanding
        $('#tfoot-total-outstanding-select').text(currencyFormat(sum_selected_amount)); // Footer ตารางรายการรับชำระ

        $(ele).parent().parent().remove();
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

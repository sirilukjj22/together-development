@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('debit-elexa') }}">Elexa Revenue</a></li>
                    <li class="breadcrumb-item active">Debit Elexa Revenue</li>
                </ol>
                <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('debit-elexa-revenue', [$month, $year]) }}" title="Back" class="btn btn-outline-dark lift">
                    Back
                </a>
                <a href="#" title="Print" class="btn btn-outline-dark lift">
                    <i class="fa fa-print"></i>
                    Print
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row clearfix">
            <div class="row g-2 mb-5">
                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i class="fa fa-circle me-2 text-info"></i>Elexa Revenue</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format(isset($elexa_revenue) ? $elexa_revenue->amount : 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-success"></i> Debit Elexa Outstanding</h6>
                        <div>
                            <button type="button" id="btn-receive-multi" class="btn btn-danger rounded-pill text-white lift" onclick="select_receive_payment_multi()">ยกเลิกหลายรายการ</button>
                        </div>
                    </div>
                    <table id="myDataTableDebit" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="checkDebitAll" name="checkbox-debit-all">
                                        <label class="form-check-label" for="checkDebitAll">All</label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_debit = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-outstanding{{ $key + 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding{{ $key + 1 }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger rounded-pill close lift" id="btn-receive-{{ $item->id}}" value="1"
                                        onclick="select_receive_payment(this, {{ $item->id}}, {{ $item->ev_charge }})">ยกเลิก</button>
                                    </td>
                                </tr>
                                <?php $total_debit += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">Total</td>
                                <td>
                                    <span id="txt_total_received">{{ number_format($total_debit, 2) }}</span>
                                    <input type="hidden" id="total_received" value="{{ $total_debit }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="btn-save-hidden2">
                        <button type="button" id="btn-save" class="btn btn-color-green text-white lift mt-3">บันทึก</button>
                    </div>
                </div> <!-- .card end -->
            </div>
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-danger"></i> Elexa Outstanding Revenue</h6>
                        <div>
                            <button type="button" id="btn-receive-multi" class="btn btn-color-green rounded-pill text-white lift" onclick="select_receive_payment_multi()">รับชำระหลายรายการ</button>
                        </div>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 mb-3">
                        <div class="col-md-12">
                            <label for="" class="fw-bold">Filter by Month</label>
                            <select class="form-select" name="" id="filter-month">
                                <option value="0">All</option>
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
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="checkAll" name="checkbox-all">
                                        <label class="form-check-label" for="checkAll">All</label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 0)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-outstanding{{ $key + 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-item" id="checkbox-outstanding{{ $key + 1 }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <button type="button" class="btn btn-color-green text-white lift rounded-pill btn-receive-pay btn-outstanding{{ $key + 1 }}"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})">รับชำระ</button>
                                        @else
                                            <button type="button" class="btn btn-color-green text-white lift rounded-pill btn-receive-pay btn-outstanding{{ $key + 1 }}"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})" disabled>รับชำระ</button>
                                        @endif
                                    </td>
                                    <input type="hidden" name="" id="ev_charge{{ $item->id }}" value="{{ $item->ev_charge }}">
                                </tr>
                                <?php $total += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total</td>
                                <td>
                                    <span id="txt_total_outstanding">{{ number_format($total, 2) }}</span>
                                    <input type="hidden" id="total_outstanding" value="{{ $total }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
        </div> <!-- .row end -->
    </div>

    <input type="hidden" id="total_receive_payment" value="{{ $total_debit }}">
    <input type="hidden" id="total_revenue_amount" value="{{ isset($elexa_revenue) ? $elexa_revenue->amount : 0 }}">

    <form action="#" id="form-elexa">
        @csrf
        <input type="hidden" id="revenue_id" name="revenue_id" value="{{ isset($elexa_revenue) ? $elexa_revenue->id : 0 }}"> <!-- ID รายได้ที่มาจาก SMS -->

        @foreach ($elexa_outstanding as $key => $item)
            @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
                <input type="hidden" id="receive_id_{{ $item->id }}" name="receive_id[]" value="{{ $item->id }}">
            @endif
        @endforeach

    </form>



    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<script>
    $(document).ready(function() {
        // $('#myDataTableOutstanding').dataTable().destroy();
        var table = $('#myDataTableOutstanding').dataTable({
                        responsive: true,
                        searching: true,
                        paging: true,
                        ordering: false,
                        info: true,
                        scrollX: true,
                        columnDefs: [
                            { 
                                "order": [[0, "asc"]], 
                                "orderable": true, "targets": [0] 
                            }
                        ]
                    });

        var table2 = $('#myDataTableDebit').dataTable({
                        responsive: true,
                        searching: true,
                        paging: true,
                        ordering: false,
                        info: true,
                        scrollX: true,
                        columnDefs: [
                            { 
                                "order": [[0, "asc"]], 
                                "orderable": true, "targets": [0] 
                            }
                        ]
                    });

        // Object to hold the checkbox states
        var checkedRows = {};
        var checkedRows2 = {};

        // Handle check all functionality
        $('#checkAll').on('click', function() {
            var isChecked = this.checked;
            // Toggle checkboxes for all rows in the current page
            $('.checkbox-item').each(function() {
                $(this).prop('checked', isChecked);
                var rowId = $(this).val();
                checkedRows[rowId] = isChecked;
            });
        });

        $('#checkDebitAll').on('click', function() {
            console.log(444556666);
            
            var isChecked2 = this.checked;
            // Toggle checkboxes for all rows in the current page
            $('.checkbox-debit-item').each(function() {
                $(this).prop('checked', isChecked2);
                var rowId2 = $(this).val();
                checkedRows2[rowId2] = isChecked2;
            });
        });

        // Handle individual checkbox click
        $('#myDataTableOutstanding tbody').on('click', '.checkbox-item', function() {
            var rowId = $(this).val();
            checkedRows[rowId] = $(this).prop('checked');

            // Check if all checkboxes in the current page are selected
            if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }
        });

        $('#myDataTableDebit tbody').on('click', '.checkbox-debit-item', function() {
            var rowId2 = $(this).val();
            checkedRows2[rowId2] = $(this).prop('checked');

            // Check if all checkboxes in the current page are selected
            if ($('.checkbox-debit-item:checked').length === $('.checkbox-debit-item').length) {
                $('#checkDebitAll').prop('checked', true);
            } else {
                $('#checkDebitAll').prop('checked', false);
            }
        });

        // When the table is drawn (such as when changing page), restore checkbox states
        table.on('draw', function() {
            // Check if all rows are selected in the current page
            var allChecked = true;

            $('.checkbox-item').each(function() {
                var rowId = $(this).val();
                // If the row was previously checked, mark it as checked again
                if (checkedRows[rowId]) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                    allChecked = false;
                }
            });
        });

        table2.on('draw', function() {
            // Check if all rows are selected in the current page
            var allChecked2 = true;

            $('.checkbox-debit-item').each(function() {
                var rowId2 = $(this).val();
                // If the row was previously checked, mark it as checked again
                if (checkedRows2[rowId2]) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                    allChecked2 = false;
                }
            });
        });

            // If all checkboxes in the current page are checked, check "Check All"
            $('#checkAll').prop('checked', allChecked);
            $('#checkDebitAll').prop('checked', allChecked2);
    });

        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        function select_receive_payment_multi() {
            var revenueID = $('#revenue_id').val();
            var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Agoda Revenue (SMS)
            var total = Number($('#total_outstanding').val());
            var total_receive_payment = Number($('#total_receive_payment').val());
            var SumTotalDebit = total_receive_payment;

            if (revenueID != "") {

                for (let index = 1; index <= 50; index++) {
                    if ($('#checkbox-outstanding'+index).is(':checked')) {
                        var itemID = $('#checkbox-outstanding'+index).val();
                        var amount = Number($('#ev_charge'+itemID).val());
                        SumTotalDebit += amount; 

                        if ($('#btn-receive-' + itemID).val() == 0) {
        
                            $('#total_receive_payment').val(Number(SumTotalDebit).toFixed(2));
                            $('#txt_total_receive_payment').text(currencyFormat(Number(SumTotalDebit)));
                            $('#btn-receive-' + itemID).val(1);
        
                            $('#txt_total_received').text(currencyFormat(SumTotalDebit));
        
                            $('#total_outstanding').val(total - amount);
                            $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));
        
                            $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard
        
                            $('#form-elexa').append('<input type="hidden" id="receive_id_' + itemID + '" name="receive_id[]" value="' + itemID + '">');
        
                            $('#tr_row_' + itemID).remove();
                            var tb_select = new DataTable('#myDataTableOutstanding');
                            tb_select.row().remove().draw();
        
                            jQuery.ajax({
                                type: "GET",
                                url: "{!! url('debit-select-elexa-outstanding/"+itemID+"') !!}",
                                datatype: "JSON",
                                async: false,
                                success: function(response) {
                                    if (response.data) {
                                        var status = "";
                                        var table = new DataTable('#myDataTableDebit');
        
                                        table.rows.add(
                                            [
                                                [
                                                    '<div class="form-check form-check-inline">'+
                                                        '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ itemID +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                        '<label class="form-check-label"></label>'+
                                                    '</div>',
                                                    moment( response.data.date).format('DD/MM/YYYY'),
                                                    response.data.batch,
                                                    currencyFormat(response.data.ev_charge),
                                                    '<button type="button" class="btn btn-danger rounded-pill lift close" id="btn-receive-' +
                                                    itemID + '" value="1"' + ' onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.ev_charge + ')">ยกเลิก</button>'
                                                ]
                                            ]
                                        ).draw();
                                    }
        
                                }
                            });
                        } else {
                            $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
                            $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment - amount))); // ยอดที่รับชำระ แสดงแบบ Text
                            $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

                            // console.log(Number(total_receive_payment).toFixed(2) - Number(amount).toFixed(2));
                            $('#total_outstanding').val(total + amount);
                            $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                            $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment -
                                amount)))); // ยอดคงเหลือ Dashboard

                            $('#receive_id_' + itemID).remove();

                            var tb_select = new DataTable('#myDataTableDebit');
                            // var removingRow = $(ele).closest('tr');
                            tb_select.row().remove().draw();

                            jQuery.ajax({
                                type: "GET",
                                url: "{!! url('debit-select-elexa-outstanding/"+itemID+"') !!}",
                                datatype: "JSON",
                                async: false,
                                success: function(response) {
                                    if (response.data) {
                                        var status = "";
                                        var table = new DataTable('#myDataTableOutstanding');

                                        table.rows.add(
                                            [
                                                [
                                                    '<div class="form-check form-check-inline">'+
                                                        '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ itemID +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                        '<label class="form-check-label"></label>'+
                                                    '</div>',
                                                    moment( response.data.date).format('DD/MM/YYYY'),
                                                    response.data.batch,
                                                    currencyFormat(response.data.ev_charge),
                                                    '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' +
                                                    itemID + '" value="0"' +
                                                    'onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.ev_charge + ')">รับชำระ</button>'
                                                ]
                                            ]
                                        ).draw();
                                    }

                                    $('#btn-receive-' + itemID).val(0);


                                }
                            });

                        }
                    }
                }

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาเลือกยอด Agoda Revenue ที่ต้องการชำระก่อน!',
                });
            }

        }

        function select_receive_payment(ele, id, amount) {
            var revenueID = $('#revenue_id').val();
            var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Agoda Revenue (SMS)
            var total = Number($('#total_outstanding').val());
            var total_receive_payment = Number($('#total_receive_payment').val());

            if (revenueID != "") {

                if ($('#btn-receive-' + id).val() == 0) {

                    $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment + amount)));
                    $('#btn-receive-' + id).val(1);

                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment + amount)));

                    $('#total_outstanding').val(total - amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment')
                        .val()))); // ยอดคงเหลือ Dashboard

                    $('#form-elexa').append('<input type="hidden" id="receive_id_' + id + '" name="receive_id[]" value="' +
                        id + '">');

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
                                var table = new DataTable('#myDataTableDebit');

                                table.rows.add(
                                    [
                                        [
                                            '<div class="form-check form-check-inline">'+
                                                '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ id +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                '<label class="form-check-label"></label>'+
                                            '</div>',
                                            moment( response.data.date).format('DD/MM/YYYY'),
                                            response.data.batch,
                                            currencyFormat(response.data.ev_charge),
                                            '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' +
                                            id + '" value="1"' + ' onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_charge + ')">ยกเลิก</button>'
                                        ]
                                    ]
                                ).draw();
                            }

                        }
                    });

                } else {
                    $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment - amount))); // ยอดที่รับชำระ แสดงแบบ Text
                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

                    // console.log(Number(total_receive_payment).toFixed(2) - Number(amount).toFixed(2));
                    $('#total_outstanding').val(total + amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment -
                        amount)))); // ยอดคงเหลือ Dashboard

                    $('#receive_id_' + id).remove();

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
                                var table = new DataTable('#myDataTableOutstanding');

                                table.rows.add(
                                    [
                                        [
                                            '<div class="form-check form-check-inline">'+
                                                '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ id +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                '<label class="form-check-label"></label>'+
                                            '</div>',
                                            moment( response.data.date).format('DD/MM/YYYY'),
                                            response.data.batch,
                                            currencyFormat(response.data.ev_charge),
                                            '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' +
                                            id + '" value="0"' +
                                            'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_charge + ')">รับชำระ</button>'
                                        ]
                                    ]
                                ).draw();
                            }

                            $('#btn-receive-' + id).val(0);


                        }
                    });

                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาเลือกยอด Agoda Revenue ที่ต้องการชำระก่อน!',
                });
            }

        }

        document.querySelector("#btn-save").addEventListener('click', function() {
            var total_receive_payment = Number($('#total_receive_payment').val()).toFixed(2);
            var total_revenue_amount = Number($('#total_revenue_amount').val()).toFixed(2);

            if (total_revenue_amount > total_receive_payment) {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Elexa Outstanding ที่เลือกมียอดน้อยกว่า Elexa Revenue!',
                });
            }

            if (total_revenue_amount < total_receive_payment) {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Elexa Outstanding ที่เลือกมียอดมากกว่า Elexa Revenue!',
                });
            }

            if (total_revenue_amount == total_receive_payment) {
                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('debit-elexa-store') !!}",
                    datatype: "JSON",
                    data: $('#form-elexa').serialize(),
                    async: false,
                    success: function(result) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    },
                });
            }

        });
    </script>
@endsection

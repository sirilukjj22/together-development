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
                            <button type="button" id="btn-receive-multi" class="btn btn-danger rounded-pill text-white lift" onclick="select_receive_payment_multi('delete')">ยกเลิกหลายรายการ</button>
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
                            <?php 
                                $total_debit = 0;
                                $debit_amount = 0;
                                $key_num = 0;
                            ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-debit-outstanding{{ $key_num += 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding{{ $key_num }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
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
                                    <input type="hidden" name="" id="ev_charge{{ $item->id }}" value="{{ $item->ev_charge }}">
                                </tr>
                                <?php 
                                    $total_debit += $item->ev_charge; 
                                    $debit_amount += 1;
                                ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total</td>
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
                            <button type="button" id="btn-receive-multi" class="btn btn-color-green rounded-pill text-white lift" onclick="select_receive_payment_multi('receive')">รับชำระหลายรายการ</button>
                        </div>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 mb-3">
                        <div class="col-md-12">
                            <label for="" class="fw-bold">Filter by Month</label>
                            <select class="form-select" name="" id="search-month" onchange="searchMonth()">
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
                            <?php 
                                $total = 0; 
                                $outstanding_amount = 0;
                            ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 0)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-outstanding{{ $outstanding_amount += 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-item" id="checkbox-outstanding{{ $outstanding_amount }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <button type="button" class="btn btn-color-green text-white lift rounded-pill btn-receive-pay btn-outstanding{{ $outstanding_amount }}"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})">รับชำระ</button>
                                        @else
                                            <button type="button" class="btn btn-color-green text-white lift rounded-pill btn-receive-pay btn-outstanding{{ $outstanding_amount }}"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})" disabled>รับชำระ</button>
                                        @endif
                                    </td>
                                    <input type="hidden" name="" id="ev_charge{{ $item->id }}" value="{{ $item->ev_charge }}">
                                </tr>
                                <?php 
                                    $total += $item->ev_charge; 
                                ?>
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
    <input type="hidden" id="debit_amount" value="{{ $debit_amount }}">
    <input type="hidden" id="outstanding_amount" value="{{ $outstanding_amount }}">

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
        // Initialize DataTable for Outstanding
        $('#myDataTableOutstanding').dataTable({
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

        // Initialize DataTable for Debit
        $('#myDataTableDebit').dataTable({
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
    });

    $('#checkDebitAll').on('click', function() {
        for (let index = 1; index <= 100; index++) {
            if ($('#checkbox-debit-outstanding'+index).is(':checked')) {
                $('#checkbox-debit-outstanding'+index).prop('checked', false);
            } else {
                $('#checkbox-debit-outstanding'+index).prop('checked', true);
            }
        }
    });

    $('#checkAll').on('click', function() {
        for (let index = 1; index <= 100; index++) {
            if ($('#checkbox-outstanding'+index).is(':checked')) {
                $('#checkbox-outstanding'+index).prop('checked', false);
            } else {
                $('#checkbox-outstanding'+index).prop('checked', true);
            }
        }
    });



        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        function searchMonth() {
            var month = $('#search-month').val();
            $('#myDataTableOutstanding').DataTable().destroy();
            jQuery.ajax({
                type: "GET",
                url: "{!! url('debit-elexa-search/"+month+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                
                    $('#txt_total_outstanding').text(currencyFormat(response.total_amount));
                    $('#total_outstanding').val(response.total_amount);
                    
                    if (response.data) {
                        var status = "";
                        var table = new DataTable('#myDataTableOutstanding');
                        table.clear().draw();

                        $.each(response.data, function(index, value) {
                            $('#myDataTableOutstanding').DataTable().destroy();
                            var table = $('#myDataTableOutstanding').DataTable({
                                            responsive: false,
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

                            var newRowData = [
                                        [
                                            '<div class="form-check form-check-inline">'+
                                                '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ (index + 1) +'" type="checkbox" name="checkbox" value="'+ value.id +'">'+
                                                '<label class="form-check-label"></label>'+
                                            '</div>',
                                            value.date,
                                            value.orderID,
                                            value.ev_charge,
                                            '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' + value.id + '" value="0"' + 'onclick="select_receive_payment(this, ' + value.id + ', ' + value.ev_charge + ')">รับชำระ</button>'
                                        ]
                                    ];

                            // เพิ่มแถวใหม่
                            var addedRow = table.rows.add(newRowData).draw();

                            // เข้าถึงแถวที่เพิ่มและเพิ่ม class
                            var rowNode = table.row(addedRow.indexes()).node();
                            $(rowNode).attr('id', 'tr_row_' + value.id);
                            $(rowNode).addClass('checkbox-outstanding' + (index + 1));

                            $('#btn-receive-' + value.id).val(0);
                        });
                    }
                }
            });
        }

        function select_receive_payment_multi(type_action) {
            
            var revenueID = $('#revenue_id').val();
            var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Agoda Revenue (SMS)
            var total_receive_payment = Number($('#total_receive_payment').val());
            var SumTotalDebit = total_receive_payment;

            if (revenueID != "") {

                for (let index = 1; index <= 200; index++) {
                    var total = Number($('#total_outstanding').val());

                    if ($('#checkbox-outstanding'+index).is(':checked') || $('#checkbox-debit-outstanding'+index).is(':checked')) {
                        var itemID = $('#checkbox-outstanding'+index).val();

                        if (type_action == 'receive' && $('#btn-receive-' + itemID).val() == 0) {

                            var amount = Number($('#ev_charge'+itemID).val());
                            SumTotalDebit += amount; 
        
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
                                    var debit_amount = Number($('#debit_amount').val()) + 1;
                                    $('#debit_amount').val(debit_amount);

                                    if (response.data) {
                                        var status = "";
                                        $('#myDataTableDebit').DataTable().destroy();
                                        var table = $('#myDataTableDebit').DataTable(
                                                {
                                                    responsive: false,
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
                                                }
                                            );

                                        var newRowData = [
                                                    [
                                                        '<div class="form-check form-check-inline">'+
                                                            '<input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding'+ debit_amount +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                            '<label class="form-check-label"></label>'+
                                                        '</div>',
                                                        moment( response.data.date).format('DD/MM/YYYY'),
                                                        response.data.batch,
                                                        currencyFormat(response.data.ev_charge),
                                                        '<button type="button" class="btn btn-danger rounded-pill lift close" id="btn-receive-' + itemID + '" value="1"' + ' onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.ev_charge + ')">ยกเลิก</button>'
                                                    ]
                                                ];

                                        // เพิ่มแถวใหม่
                                        var addedRow = table.rows.add(newRowData).draw();

                                        // เข้าถึงแถวที่เพิ่มและเพิ่ม class
                                        var rowNode = table.row(addedRow.indexes()).node();
                                        $(rowNode).attr('id', 'tr_row_' + itemID);
                                        $(rowNode).addClass('checkbox-debit-outstanding' + debit_amount);
                                    }
        
                                }
                            });
                        } else {                            
                            var itemID = $('#checkbox-debit-outstanding'+index).val();
                            var amount = Number($('#ev_charge'+itemID).val());
                            SumTotalDebit -= amount; 

                            $('#total_receive_payment').val(Number(SumTotalDebit).toFixed(2)); // ยอดที่รับชำระ
                            $('#txt_total_receive_payment').text(currencyFormat(Number(SumTotalDebit))); // ยอดที่รับชำระ แสดงแบบ Text
                            $('#txt_total_received').text(currencyFormat(Number(SumTotalDebit)));

                            // console.log(Number(total_receive_payment).toFixed(2) - Number(amount).toFixed(2));
                            $('#total_outstanding').val(total + amount);
                            $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                            $('#balance').text(currencyFormat(Number(total_revenue_amount - (SumTotalDebit)))); // ยอดคงเหลือ Dashboard

                            $('#receive_id_' + itemID).remove();

                            $('tr #tr_row_' + itemID).remove();
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

                                        $('#myDataTableOutstanding').DataTable().destroy();
                                        var table = $('#myDataTableOutstanding').DataTable(
                                                {
                                                    responsive: false,
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
                                                }
                                            );

                                        var newRowData = [
                                                    [
                                                        '<div class="form-check form-check-inline">'+
                                                            '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ index +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                            '<label class="form-check-label"></label>'+
                                                        '</div>',
                                                        moment( response.data.date).format('DD/MM/YYYY'),
                                                        response.data.batch,
                                                        currencyFormat(response.data.ev_charge),
                                                        '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' + itemID + '" value="0"' + 'onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.ev_charge + ')">รับชำระ</button>'
                                                    ]
                                                ];

                                        // เพิ่มแถวใหม่
                                        var addedRow = table.rows.add(newRowData).draw();

                                        // เข้าถึงแถวที่เพิ่มและเพิ่ม class
                                        var rowNode = table.row(addedRow.indexes()).node();
                                        $(rowNode).attr('id', 'tr_row_' + itemID);
                                        $(rowNode).addClass('checkbox-outstanding' + index);
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
            var debit_amount = Number($('#debit_amount').val()) + 1;
            $('#debit_amount').val(debit_amount);

            if (revenueID != "") {

                if ($('#btn-receive-' + id).val() == 0) {

                    $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment + amount)));
                    $('#btn-receive-' + id).val(1);

                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment + amount)));

                    $('#total_outstanding').val(total - amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard

                    $('#form-elexa').append('<input type="hidden" id="receive_id_' + id + '" name="receive_id[]" value="' + id + '">');

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
                                $('#myDataTableDebit').DataTable().destroy();
                                var table = $('#myDataTableDebit').DataTable(
                                                {
                                                    responsive: false,
                                                    searching: true,
                                                    paging: true,
                                                    ordering: false,
                                                    info: true,
                                                    scrollX: true,
                                                    columnDefs: [
                                                        { 
                                                            "order": [[0, "asc"]], 
                                                            // "orderable": true, "targets": [0] 
                                                        }
                                                    ]
                                                }
                                            );

                                var newRowData = [
                                            [
                                                '<div class="form-check form-check-inline">'+
                                                    '<input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding'+ debit_amount +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                    '<label class="form-check-label"></label>'+
                                                '</div>',
                                                moment(response.data.date).format('DD/MM/YYYY'),
                                                response.data.batch,
                                                currencyFormat(response.data.ev_charge),
                                                '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' + id + '" value="1"' + ' onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_charge + ')">ยกเลิก</button>'
                                            ]
                                        ];

                                // เพิ่มแถวใหม่
                                var addedRow = table.rows.add(newRowData).draw();

                                // เข้าถึงแถวที่เพิ่มและเพิ่ม class
                                var rowNode = table.row(addedRow.indexes()).node();
                                $(rowNode).attr('id', 'tr_row_' + id);
                                $(rowNode).addClass('checkbox-debit-outstanding' + debit_amount);
                            }

                        }
                    });

                } else {
                    var outstanding_amount = Number($('#outstanding_amount').val()) + 1;
                    $('#outstanding_amount').val(outstanding_amount);

                    $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment - amount))); // ยอดที่รับชำระ แสดงแบบ Text
                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

                    $('#total_outstanding').val(total + amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment - amount)))); // ยอดคงเหลือ Dashboard

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

                                $('#myDataTableOutstanding').DataTable().destroy();
                                var table = $('#myDataTableOutstanding').DataTable(
                                        {
                                            responsive: false,
                                            searching: true,
                                            paging: true,
                                            ordering: false,
                                            info: true,
                                            scrollX: true,
                                            columnDefs: [
                                                { 
                                                    "order": [[0, "asc"]], 
                                                    // "orderable": true, "targets": [0] 
                                                }
                                            ]
                                        }
                                    );

                                var newRowData = [
                                            [
                                                '<div class="form-check form-check-inline">'+
                                                    '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ outstanding_amount +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                    '<label class="form-check-label"></label>'+
                                                '</div>',
                                                moment( response.data.date).format('DD/MM/YYYY'),
                                                response.data.batch,
                                                currencyFormat(response.data.ev_charge),
                                                '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' + id + '" value="0"' + 'onclick="select_receive_payment(this, ' + id + ', ' + response.data.ev_charge + ')">รับชำระ</button>'
                                            ]
                                        ];

                                // เพิ่มแถวใหม่
                                var addedRow = table.rows.add(newRowData).draw();

                                // เข้าถึงแถวที่เพิ่มและเพิ่ม class
                                var rowNode = table.row(addedRow.indexes()).node();
                                $(rowNode).attr('id', 'tr_row_' + id);
                                $(rowNode).addClass('checkbox-outstanding' + outstanding_amount);
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

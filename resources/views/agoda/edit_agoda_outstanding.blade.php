{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('debit-agoda') }}">Agoda Revenue</a></li>
                    <li class="breadcrumb-item active">Debit Agoda Revenue</li>
                </ol>
                <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('debit-agoda-revenue', [$month, $year]) }}" title="ย้อนกลับ" class="btn btn-outline-dark lift">
                    ย้อนกลับ
                </a>
                <a href="#" title="พิมพ์เอกสาร" class="btn btn-outline-dark lift">
                    <i class="fa fa-print"></i>
                    พิมพ์เอกสาร
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row clearfix">
            <div class="row g-2 mb-5">
                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i class="fa fa-circle me-2 text-info"></i>Agoda Revenue</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format(isset($agoda_revenue) ? $agoda_revenue->amount : 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12"></div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body">
                                            <div class="text-muted text-uppercase"><i class="fa fa-circle me-2 text-primary"></i>Number of items</div>
                                            <div class="mt-1">
                                                <span class="fw-bold h4 mb-0" id="txt-total-item">{{ number_format(0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body">
                                            <div class="text-muted text-uppercase"><i class="fa fa-circle me-2 text-danger"></i>Outstanding Revenue</div>
                                            <div class="mt-1">
                                                <span class="fw-bold h4 mb-0" id="txt-total-debit">{{ number_format(0, 2) }}</span>
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
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-success"></i> Debit Agoda Outstanding</h6>
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
                                <th>Booking Number</th>
                                <th>วันที่ Check in</th>
                                <th>วันที่ Check out</th>
                                <th>จำนวนเงิน</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total_debit = 0;
                                $outstanding_amount = 0;
                                $debit_amount = 0;
                                $key_num = 0;
                            ?>
                            @foreach ($agoda_outstanding as $key => $item)
                                @if ($item->receive_payment == 1 && $item->sms_revenue == $agoda_revenue->id)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-debit-outstanding{{ $key_num += 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding{{ $key_num }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-{{ $item->id}}" value="1"
                                        onclick="select_receive_payment(this, {{ $item->id}}, {{ $item->agoda_outstanding }})">ยกเลิก</button>
                                    </td>
                                </tr>
                                <?php 
                                    $total_debit += $item->agoda_outstanding; 
                                    $debit_amount += 1;
                                ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>
                                    <span id="txt_total_received">{{ number_format($total_debit, 2) }}</span>
                                    <input type="hidden" id="total_received" value="{{ $total_debit }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="btn-save-hidden2">
                        <button type="button" id="btn-save" class="btn btn-primary mt-3">บันทึก</button>
                    </div>
                </div> <!-- .card end -->
            </div>
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-danger"></i> Agoda Outstanding Revenue</h6>
                        <div>
                            <button type="button" id="btn-receive-multi" class="btn btn-color-green rounded-pill text-white lift" onclick="select_receive_payment_multi('receive')">รับชำระหลายรายการ</button>
                        </div>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkbox-item-all" type="checkbox" id="checkAll" name="checkbox-all">
                                        <label class="form-check-label" for="checkAll">All</label>
                                    </div>
                                </th>
                                <th>Booking Number</th>
                                <th>วันที่ Check in</th>
                                <th>วันที่ Check out</th>
                                <th>จำนวนเงิน</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total = 0; 
                                $outstanding_amount = 0;
                            ?>
                            @foreach ($agoda_outstanding as $key => $item)
                                @if ($item->receive_payment == 0)
                                <tr id="tr_row_{{ $item->id }}" class="checkbox-outstanding{{ $outstanding_amount += 1 }}">
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input checkbox-item" id="checkbox-outstanding{{ $outstanding_amount }}" type="checkbox" name="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <button type="button" class="btn btn-primary rounded-pill btn-receive-pay" id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->agoda_outstanding }})">รับชำระ</button>
                                        @else
                                            <button type="button" class="btn btn-secondary rounded-pill btn-receive-pay" id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->agoda_outstanding }})" disabled>รับชำระ</button>
                                        @endif
                                    </td>
                                    <input type="hidden" name="" id="agoda_revenue{{ $item->id }}" value="{{ $item->agoda_outstanding }}">
                                </tr>
                                
                                <?php $total += $item->agoda_outstanding; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">ยอดรวมทั้งหมด</td>
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
    <input type="hidden" id="total_revenue_amount" value="{{ isset($agoda_revenue) ? $agoda_revenue->amount : 0 }}">
    <input type="hidden" id="debit_amount" value="{{ $debit_amount }}">
    <input type="hidden" id="outstanding_amount" value="{{ $outstanding_amount }}">
    <input type="hidden" name="" id="input-total-item" value="0">
    <input type="text" name="" id="input-total-debit" value="0">

    <form action="#" id="form-agoda">
        @csrf
        <input type="hidden" id="revenue_id" name="revenue_id" value="{{ isset($agoda_revenue) ? $agoda_revenue->id : 0 }}"> <!-- ID รายได้ที่มาจาก SMS -->

        @foreach ($agoda_outstanding as $key => $item)
            @if ($item->receive_payment == 1 && $item->sms_revenue == $agoda_revenue->id)
                <input type="hidden" id="receive_id_{{ $item->id }}" name="receive_id[]" value="{{ $item->id }}">
            @endif
        @endforeach

    </form>



    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif


    <script>

    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#myDataTableOutstanding').DataTable();

        // Object to hold the checkbox states
        var checkedRows = {};

        // Handle 'check all' for all pages
        $('#checkAll').on('click', function() {
            var isChecked = this.checked;
            // Loop through all rows in the table, including those not currently visible
            table.rows().every(function() {
                var rowId = this.node().id; // Assuming row ID is set in each <tr> (you can adjust if needed)
                $('input[type="checkbox"].checkbox-item', this.node()).prop('checked', isChecked);
                checkedRows[rowId] = isChecked;
            });
        });

        // Handle individual checkbox click for visible rows
        $('#myDataTableOutstanding tbody').on('click', '.checkbox-item', function() {
            var rowId = $(this).closest('tr').attr('id'); // Assuming each row has a unique ID
            checkedRows[rowId] = $(this).prop('checked');

            // Check if all checkboxes in the current page are selected
            if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }
        });

        // When the table is redrawn (e.g. when switching pages), restore checkbox states
        table.on('draw', function() {
            var allChecked = true;
            // Loop through visible rows and set the checkbox state
            table.rows({ page: 'current' }).every(function() {
                var rowId = this.node().id; // Assuming each row has a unique ID
                if (checkedRows[rowId]) {
                    $('input[type="checkbox"].checkbox-item', this.node()).prop('checked', true);
                } else {
                    $('input[type="checkbox"].checkbox-item', this.node()).prop('checked', false);
                    allChecked = false;
                }
            });
            // Set the 'check all' checkbox state based on current page
            $('#checkAll').prop('checked', allChecked);
        });
    });

    $('#checkDebitAll').on('click', function() {
        for (let index = 1; index <= 50; index++) {
            if ($('#checkbox-debit-outstanding'+index).is(':checked')) {
                $('#checkbox-debit-outstanding'+index).prop('checked', false);
            } else {
                $('#checkbox-debit-outstanding'+index).prop('checked', true);
            }
        }
    });

    $('#checkAll').on('click', function() {
        for (let index = 1; index <= 50; index++) {
            if ($('#checkbox-outstanding'+index).is(':checked')) {
                $('#checkbox-outstanding'+index).prop('checked', false);
            } else {
                $('#checkbox-outstanding'+index).prop('checked', true);
            }
        }
    });

        $(document).ready(function() {
            $('#myDataTableOutstanding').DataTable().destroy();

            $('#myDataTableOutstanding').dataTable({
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

            $('#myDataTableDebit').dataTable({
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
        });
        
        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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

                    // Update ยอดที่เลือก
                    var agoda_revenue = Number($('#agoda_revenue'+id).val());
                    var agoda_revenue_amount = Number($('#input-total-debit').val());
                    var agoda_num = Number($('#input-total-item').val());

                        if ($('#checkbox-outstanding'+id).is(':checked')) {
                            $('#txt-total-item').text(agoda_num += 1);
                            $('#txt-total-debit').text(currencyFormat(agoda_revenue_amount += agoda_revenue));
                        } else {
                            $('#txt-total-item').text(agoda_num -= 1);
                            $('#txt-total-debit').text(currencyFormat(agoda_revenue_amount -= agoda_revenue));
                        }

                    $('#input-total-item').val(agoda_num);
                    $('#input-total-debit').val(agoda_revenue_amount);
                    // END

                    $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment + amount)));
                    $('#btn-receive-' + id).val(1);

                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment + amount)));

                    $('#total_outstanding').val(total - amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard

                    $('#form-agoda').append('<input type="hidden" id="receive_id_' + id + '" name="receive_id[]" value="' + id + '">');

                    $('#tr_row_' + id).remove();
                    var tb_select = new DataTable('#myDataTableOutstanding');
                    var removingRow = $(ele).closest('tr');
                    tb_select.row(removingRow).remove().draw();

                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('debit-select-agoda-outstanding/"+id+"') !!}",
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
                                                    "orderable": true, "targets": [0] 
                                                }
                                            ]
                                        }
                                    );

                                if (response.data.agoda_check_in) {
                                    var exp = response.data.agoda_check_in.split('-');
                                    var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                                } else {
                                    var check_in = "-";
                                }

                                if (response.data.agoda_check_out) {
                                    var exp = response.data.agoda_check_out.split('-');
                                    var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                                } else {
                                    var check_out = "-";
                                }
                                table.rows.add(
                                    [
                                        [
                                            '<div class="form-check form-check-inline">'+
                                                '<input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding'+ debit_amount +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                '<label class="form-check-label"></label>'+
                                            '</div>',
                                            response.data.batch,
                                            check_in,
                                            check_out,
                                            currencyFormat(response.data.agoda_outstanding),
                                            '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' + id + '" value="1"' +
                                            'onclick="select_receive_payment(this, ' + id + ', ' + response.data.agoda_outstanding + ')">ยกเลิก</button>'
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

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment - amount)))); // ยอดคงเหลือ Dashboard

                    $('#receive_id_' + id).remove();

                    var tb_select = new DataTable('#myDataTableDebit');
                    var removingRow = $(ele).closest('tr');
                    tb_select.row(removingRow).remove().draw();

                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('debit-select-agoda-outstanding/"+id+"') !!}",
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

                                if (response.data.agoda_check_in) {
                                    var exp = response.data.agoda_check_in.split('-');
                                    var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                                } else {
                                    var check_in = "-";
                                }

                                if (response.data.agoda_check_out) {
                                    var exp = response.data.agoda_check_out.split('-');
                                    var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                                } else {
                                    var check_out = "-";
                                }

                                table.rows.add(
                                    [
                                        [
                                            '<div class="form-check form-check-inline">'+
                                                '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ index +'" type="checkbox" name="checkbox" value="'+ id +'">'+
                                                '<label class="form-check-label"></label>'+
                                            '</div>',
                                            response.data.batch,
                                            check_in,
                                            check_out,
                                            currencyFormat(response.data.agoda_outstanding),
                                            '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' + id + '" value="0"' +
                                            'onclick="select_receive_payment(this, ' + id + ', ' + response.data.agoda_outstanding + ')">รับชำระ</button>'
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

        function select_receive_payment_multi(type_action) {
            var revenueID = $('#revenue_id').val();
            var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Agoda Revenue (SMS)
            var total_receive_payment = Number($('#total_receive_payment').val());
            var SumTotalDebit = total_receive_payment;

            // เคลียร์ให้เป็นค่าว่าง
            $('#txt-total-item').text("0");
            $('#txt-total-debit').text("0.00");
            $('#input-total-item').val(0);
            $('#input-total-debit').val(0);

            if (revenueID != "") {

                for (let index = 1; index <= 100; index++) {
                    var total = Number($('#total_outstanding').val());

                    if ($('#checkbox-outstanding'+index).is(':checked') || $('#checkbox-debit-outstanding'+index).is(':checked')) {
                        var itemID = $('#checkbox-outstanding'+index).val();

                        if (type_action == 'receive' && $('#btn-receive-' + itemID).val() == 0) {

                            var amount = Number($('#agoda_revenue'+itemID).val());
                            SumTotalDebit += amount; 

                            $('#total_receive_payment').val(Number(SumTotalDebit).toFixed(2));
                            $('#txt_total_receive_payment').text(currencyFormat(Number(SumTotalDebit)));
                            $('#btn-receive-' + itemID).val(1);

                            $('#txt_total_received').text(currencyFormat(Number(SumTotalDebit)));

                            $('#total_outstanding').val(total - amount);
                            $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                            $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment').val()))); // ยอดคงเหลือ Dashboard

                            $('#form-agoda').append('<input type="hidden" id="receive_id_' + itemID + '" name="receive_id[]" value="' + itemID + '">');

                            $('#tr_row_' + itemID).remove();
                            var tb_select = new DataTable('#myDataTableOutstanding');
                            tb_select.row().remove().draw();

                            jQuery.ajax({
                                type: "GET",
                                url: "{!! url('debit-select-agoda-outstanding/"+itemID+"') !!}",
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
                                                            "orderable": true, "targets": [0] 
                                                        }
                                                    ]
                                                }
                                            );

                                        if (response.data.agoda_check_in) {
                                            var exp = response.data.agoda_check_in.split('-');
                                            var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                                        } else {
                                            var check_in = "-";
                                        }

                                        if (response.data.agoda_check_out) {
                                            var exp = response.data.agoda_check_out.split('-');
                                            var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                                        } else {
                                            var check_out = "-";
                                        }
                                        table.rows.add(
                                            [
                                                [
                                                    '<div class="form-check form-check-inline">'+
                                                        '<input class="form-check-input checkbox-debit-item" id="checkbox-debit-outstanding'+ debit_amount +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                        '<label class="form-check-label"></label>'+
                                                    '</div>',
                                                    response.data.batch,
                                                    check_in,
                                                    check_out,
                                                    currencyFormat(response.data.agoda_outstanding),
                                                    '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' + itemID + '" value="1"' +
                                                    'onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.agoda_outstanding + ')">ยกเลิก</button>'
                                                ]
                                            ]
                                        ).draw();
                                    }

                                }
                            });

                        } else {
                            var itemID = $('#checkbox-debit-outstanding'+index).val();
                            var amount = Number($('#agoda_revenue'+itemID).val());
                            SumTotalDebit -= amount; 

                            $('#total_receive_payment').val(Number(SumTotalDebit).toFixed(2)); // ยอดที่รับชำระ
                            $('#txt_total_receive_payment').text(currencyFormat(Number(SumTotalDebit))); // ยอดที่รับชำระ แสดงแบบ Text
                            $('#txt_total_received').text(currencyFormat(Number(SumTotalDebit)));

                            $('#total_outstanding').val(total + amount);
                            $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                            $('#balance').text(currencyFormat(Number(total_revenue_amount - (SumTotalDebit)))); // ยอดคงเหลือ Dashboard

                            $('#receive_id_' + id).remove();

                            $('tr #tr_row_' + itemID).remove();
                            var tb_select = new DataTable('#myDataTableDebit');
                            // var removingRow = $(ele).closest('tr');
                            tb_select.row().remove().draw();

                            jQuery.ajax({
                                type: "GET",
                                url: "{!! url('debit-select-agoda-outstanding/"+id+"') !!}",
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

                                        if (response.data.agoda_check_in) {
                                            var exp = response.data.agoda_check_in.split('-');
                                            var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                                        } else {
                                            var check_in = "-";
                                        }

                                        if (response.data.agoda_check_out) {
                                            var exp = response.data.agoda_check_out.split('-');
                                            var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                                        } else {
                                            var check_out = "-";
                                        }

                                        table.rows.add(
                                            [
                                                [
                                                    '<div class="form-check form-check-inline">'+
                                                        '<input class="form-check-input checkbox-item" id="checkbox-outstanding'+ index +'" type="checkbox" name="checkbox" value="'+ itemID +'">'+
                                                        '<label class="form-check-label"></label>'+
                                                    '</div>',
                                                    response.data.batch,
                                                    check_in,
                                                    check_out,
                                                    currencyFormat(response.data.agoda_outstanding),
                                                    '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' + itemID + '" value="0"' +
                                                    'onclick="select_receive_payment(this, ' + itemID + ', ' + response.data.agoda_outstanding + ')">รับชำระ</button>'
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

        document.querySelector("#btn-save").addEventListener('click', function() {
            var total_receive_payment = Number($('#total_receive_payment').val()).toFixed(2);
            var total_revenue_amount = Number($('#total_revenue_amount').val()).toFixed(2);

            if (total_revenue_amount > total_receive_payment) {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Agoda Outstanding ที่เลือกมียอดน้อยกว่า Agoda Revenue!',
                });
            }

            if (total_revenue_amount < total_receive_payment) {
                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'ยอด Agoda Outstanding ที่เลือกมียอดมากกว่า Agoda Revenue!',
                });
            }

            if (total_revenue_amount == total_receive_payment) {
                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('debit-agoda-store') !!}",
                    datatype: "JSON",
                    data: $('#form-agoda').serialize(),
                    async: false,
                    success: function(result) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    },
                });
            }

        });

        $(document).on('click', '.checkbox-item', function () {
            var id = $(this).attr('id');
            var agoda_value = $(this).val();
            var agoda_revenue = Number($('#agoda_revenue'+agoda_value).val());
            var amount = Number($('#input-total-debit').val());
            var num = Number($('#input-total-item').val());

                if ($('#'+id).is(':checked')) {
                    $('#txt-total-item').text(num += 1);
                    $('#txt-total-debit').text(currencyFormat(amount += agoda_revenue));
                } else {
                    amount -= agoda_revenue;
                    if (amount < 0) {
                        amount = 0;
                    }
                    $('#txt-total-item').text(num -= 1);
                    $('#txt-total-debit').text(currencyFormat(amount));
                }

            $('#input-total-item').val(num);
            $('#input-total-debit').val(amount);
        });

        $(document).on('click', '.checkbox-item-all', function () {            
            var amount = Number($('#total_outstanding').val());
            var num = Number($('#outstanding_amount').val());

            $('#txt-total-item').text(num);
            $('#txt-total-debit').text(currencyFormat(amount));

            if ($('.checkbox-item-all').is(':checked')) {
                $('#txt-total-item').text(num);
                $('#txt-total-debit').text(currencyFormat(amount));
            } else {
                $('#txt-total-item').text(0);
                $('#txt-total-debit').text(currencyFormat(0));
            }

            $('#input-total-item').val(num);
            $('#input-total-debit').val(amount);
        });
    </script>
@endsection

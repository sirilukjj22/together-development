{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="javascript:history.back(1)">Revenue</a></li>
                    <li class="breadcrumb-item active">รายละเอียด</li>
                </ol>
                <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
            </div>
            <div class="col-auto">
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
            <div class="col-md-12 col-12">
                <div class="card p-4 mb-4">
                    <h6 class="mb-3" style="font-weight: bold;">Agoda Revenue</h6>
                    <table id="myTable" class="table display dataTable table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($agoda_revenue as $key => $item)
                                <tr style="font-weight: bold; color: black;">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                        {{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        @if ($item->status_receive_agoda == 0)
                                            <span class="badge bg-danger">Credit</span>
                                        @else
                                            <span class="badge bg-success">Debit</span>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-info rounded-pill text-white dropdown-toggle"
                                                type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                ทำรายการ
                                            </button>
                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                @if ($item->status_receive_agoda == 0)
                                                    <li>
                                                        <button type="button" class="dropdown-item py-2 rounded"
                                                            id="revenue-{{ $item->id }}" value="0"
                                                            onclick="select_revenue({{ $item->id }}, {{ $item->amount }})">เลือกรายการ</button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item py-2 rounded"
                                                            id="revenue-{{ $item->id }}" value="0"
                                                            onclick="select_revenue({{ $item->id }}, {{ $item->amount }})">แก้ไข</button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-2 rounded"
                                                            id="revenue-{{ $item->id }}" value="0"
                                                            onclick="select_revenue_detail({{ $item->id }}, {{ $item->amount }})">รายละเอียด</button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php $total += $item->amount; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>{{ number_format($total, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
            <div class="row g-2 mb-5">
                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i
                                                class="fa fa-circle me-2 text-info"></i>Agoda Revenue</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($total, 2) }}</span>
                                            {{-- <span class="ms-1">5% <i class="fa fa-caret-up"></i></span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-5">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i
                                                class="fa fa-circle me-2 text-success"></i>Credit Agoda Revenue Outstanding
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($total_outstanding_all, 2) }}</span>
                                            <input type="hidden" id="total_outstanding_all" value="{{ $total_outstanding_all }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i
                                                class="fa fa-circle me-2 text-danger"></i>Debit Agoda Outstanding</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($agoda_debit_outstanding, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i
                                                class="fa fa-circle me-2 text-warning"></i>Balance</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($total_outstanding_all - $agoda_debit_outstanding, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="card p-4 mb-4">
                    <div
                        class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0">Agoda Outstanding Revenue</h6>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                สถานะการรับชำระ
                            </button>
                            <ul class="dropdown-menu border-0 shadow p-3">
                                <li><a class="dropdown-item py-2 rounded" href="#"
                                        onclick="status_receive(1)">Debit</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="#"
                                        onclick="status_receive(0)">Credit</a></li>
                            </ul>
                        </div>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Booking Number</th>
                                <th>วันที่ Check in</th>
                                <th>วันที่ Check out</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($agoda_outstanding as $key => $item)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <span class="badge bg-danger">Credit</span>
                                        @else
                                            <span class="badge bg-success">Debit</span>
                                        @endif    
                                    </td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <button type="button" class="btn btn-primary rounded-pill btn-receive-pay"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->agoda_outstanding }})">รับชำระ</button>
                                        @else
                                            <button type="button" class="btn btn-secondary rounded-pill btn-receive-pay"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->agoda_outstanding }})" disabled>รับชำระ</button>
                                        @endif
                                    </td>
                                </tr>
                                <?php $total += $item->agoda_outstanding; ?>
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
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
            <div class="col-md-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0">Debit Agoda Outstanding</h6>
                        <div>
                            <h6 class="fw-bold text-danger m-0">0.00</h6>
                        </div>
                    </div>
                    <table id="myDataTableAll" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Booking Number</th>
                                <th>วันที่ Check in</th>
                                <th>วันที่ Check out</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody class="add-received">

                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="3" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>
                                    <span id="txt_total_received" class="">0.00</span>
                                    <input type="hidden" id="total_received" value="0">
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="btn-save-hidden" hidden>
                        <button type="button" id="btn-save" class="btn btn-primary mt-3">บันทึก</button>
                    </div>
                </div> <!-- .card end -->
            </div>
        </div> <!-- .row end -->
    </div>

    <input type="hidden" id="total_receive_payment" value="0">
    <input type="hidden" id="total_revenue_amount" value="0">

    <form action="#" id="form-agoda">
        @csrf
        <input type="hidden" id="revenue_id" name="revenue_id" value=""> <!-- ID รายได้ที่มาจาก SMS -->
    </form>



    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif


    <script>
        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        function select_revenue(id, amount) {
            var revenueID = $('#revenue_id').val();
            $('#total_revenue_amount').val(amount);
            $('#txt_total_revenue_amount').text(currencyFormat(amount)); // text ยอด Agoda Revenue
            $('#btn-save-hidden').prop('hidden', false);
            $('.btn-receive-pay').prop('disabled', false);

            if (revenueID != "" && revenueID != id) {
                $('#revenue-' + id).removeClass("btn-primary");
                $('#revenue-' + id).addClass("btn-secondary");

                $('#revenue-' + revenueID).removeClass("btn-secondary");
                $('#revenue-' + revenueID).addClass("btn-primary");

                $('#revenue_id').val(id);

            }

            if (revenueID == "") {
                $('#revenue-' + id).removeClass("btn-primary");
                $('#revenue-' + id).addClass("btn-secondary");

                $('#revenue_id').val(id);
            }

            jQuery.ajax({
                type: "GET",
                url: "{!! url('debit-select-agoda-received/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var status = "";
                        var total_received_amount = 0;
                        var table = new DataTable('#myDataTableAll');
                        table.clear().draw();

                        jQuery.each(response.data, function(key, value) {
                            if (value.agoda_check_in) {
                                var exp = value.agoda_check_in.split('-');
                                var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_in = "-";
                            }

                            if (value.agoda_check_out) {
                                var exp = value.agoda_check_out.split('-');
                                var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_out = "-";
                            }

                            if (value.receive_payment == 1) {
                                status = '<span class="badge bg-success">Debit</span>';
                            } else {
                                status = '<span class="badge bg-danger">Credit</span>';
                            }

                            table.rows.add(
                                [
                                    [
                                        value.batch,
                                        check_in,
                                        check_out,
                                        currencyFormat(value.agoda_outstanding),
                                        status,
                                        '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' +
                                        value.id + '" value="1"' +
                                        'onclick="select_receive_payment(this, ' + value.id + ', ' +
                                        value.agoda_outstanding + ')">ยกเลิก</button>'
                                    ]
                                ]
                            ).draw();

                            total_received_amount += value.agoda_outstanding;
                        });
                        $('#txt_total_received').text(currencyFormat(Number(total_received_amount)));
                        $('#txt_total_receive_payment').text(currencyFormat(Number(
                            total_received_amount))); // ยอดรับชำระ Dashboard

                        $('#total_receive_payment').val(total_received_amount); // ยอดที่รับชำระ
                        $('#balance').text(currencyFormat(Number(amount -
                            total_received_amount))); // ยอดคงเหลือ Dashboard

                    }
                }
            });
        }

        function select_revenue_detail(id, amount) {
            var revenueID = $('#revenue_id').val();
            $('#total_revenue_amount').val(amount);
            $('#txt_total_revenue_amount').text(currencyFormat(amount)); // text ยอด Agoda Revenue
            $('#btn-save-hidden').prop('hidden', true);
            $('.btn-receive-pay').prop('disabled', true);

            if (revenueID != "" && revenueID != id) {
                $('#revenue-' + id).removeClass("btn-primary");
                $('#revenue-' + id).addClass("btn-secondary");

                $('#revenue-' + revenueID).removeClass("btn-secondary");
                $('#revenue-' + revenueID).addClass("btn-primary");

                $('#revenue_id').val(id);

            }

            if (revenueID == "") {
                $('#revenue-' + id).removeClass("btn-primary");
                $('#revenue-' + id).addClass("btn-secondary");

                $('#revenue_id').val(id);
            }

            jQuery.ajax({
                type: "GET",
                url: "{!! url('debit-select-agoda-received/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var status = "";
                        var total_received_amount = 0;
                        var table = new DataTable('#myDataTableAll');
                        // var tableOutstanding = new DataTable('#myDataTableOutstanding');
                        table.clear().draw();
                        // tableOutstanding.clear().draw();

                        jQuery.each(response.data, function(key, value) {
                            if (value.agoda_check_in) {
                                var exp = value.agoda_check_in.split('-');
                                var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_in = "-";
                            }

                            if (value.agoda_check_out) {
                                var exp = value.agoda_check_out.split('-');
                                var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_out = "-";
                            }

                            if (value.receive_payment == 1) {
                                status = '<span class="badge bg-success">Debit</span>';
                            } else {
                                status = '<span class="badge bg-danger">Credit</span>';
                            }

                            table.rows.add(
                                [
                                    [
                                        value.batch,
                                        check_in,
                                        check_out,
                                        currencyFormat(value.agoda_outstanding),
                                        status,
                                        '<button type="button" class="btn btn-secondary rounded-pill close" id="btn-receive-' +
                                        value.id + '" value="1"' +
                                        'onclick="select_receive_payment(this, ' + value.id + ', ' +
                                        value.agoda_outstanding + ')" disabled>ยกเลิก</button>'
                                    ]
                                ]
                            ).draw();

                            total_received_amount += value.agoda_outstanding;
                        });
                        $('#txt_total_received').text(currencyFormat(Number(total_received_amount)));
                        $('#txt_total_receive_payment').text(currencyFormat(Number(
                            total_received_amount))); // ยอดรับชำระ Dashboard

                        $('#total_receive_payment').val(total_received_amount); // ยอดที่รับชำระ
                        $('#balance').text(currencyFormat(Number(amount -
                            total_received_amount))); // ยอดคงเหลือ Dashboard

                    }
                }
            });
        }

        function select_receive_payment(ele, id, amount) {
            var revenueID = $('#revenue_id').val();
            var total_revenue_amount = $('#total_revenue_amount').val(); // ยอด Agoda Revenue
            var total = Number($('#total_outstanding').val());
            var total_receive_payment = Number($('#total_receive_payment').val());

            if (revenueID != "") {

                if ($('#btn-receive-' + id).val() == 0) {
                    $('#total_receive_payment').val(Number(total_receive_payment + amount).toFixed(2));
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment + amount)));
                    $('#btn-receive-' + id).val(1);

                    $('#total_outstanding').val(total - amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total - amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - $('#total_receive_payment')
                        .val()))); // ยอดคงเหลือ Dashboard

                    $('#form-agoda').append('<input type="hidden" id="receive_id_' + id + '" name="receive_id[]" value="' +
                        id + '">');

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
                                var table = new DataTable('#myDataTableAll');
                                // table.clear().draw();

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
                                            response.data.batch,
                                            check_in,
                                            check_out,
                                            currencyFormat(response.data.agoda_outstanding),
                                            '<span class="badge bg-warning">รอรับชำระ</span>',
                                            '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' +
                                            id + '" value="1"' +
                                            'onclick="select_receive_payment(this, ' + id + ', ' + response
                                            .data.agoda_outstanding + ')">ยกเลิก</button>'
                                        ]
                                    ]
                                ).draw();
                            }

                        }
                    });

                } else {
                    $('#total_receive_payment').val(Number(total_receive_payment - amount).toFixed(2)); // ยอดที่รับชำระ
                    $('#txt_total_receive_payment').text(currencyFormat(Number(total_receive_payment -
                        amount))); // ยอดที่รับชำระ แสดงแบบ Text
                    $('#txt_total_received').text(currencyFormat(Number(total_receive_payment - amount)));

                    // console.log(Number(total_receive_payment).toFixed(2) - Number(amount).toFixed(2));
                    $('#total_outstanding').val(total + amount);
                    $('#txt_total_outstanding').text(currencyFormat(Number(total + amount)));

                    $('#balance').text(currencyFormat(Number(total_revenue_amount - (total_receive_payment -
                        amount)))); // ยอดคงเหลือ Dashboard

                    $('#receive_id_' + id).remove();

                    var tb_select = new DataTable('#myDataTableAll');
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
                                var table = new DataTable('#myDataTableOutstanding');
                                // table.draw();

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

                                if (response.data.receive_payment == 1) {
                                    status = '<span class="badge bg-success">Debit</span>';
                                } else {
                                    status = '<span class="badge bg-danger">Credit</span>';
                                }

                                table.rows.add(
                                    [
                                        [
                                            response.data.batch,
                                            check_in,
                                            check_out,
                                            currencyFormat(response.data.agoda_outstanding),
                                            status,
                                            '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' +
                                            id + '" value="0"' +
                                            'onclick="select_receive_payment(this, ' + id + ', ' + response
                                            .data.agoda_outstanding + ')">รับชำระ</button>'
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


        function status_receive(status) {

            var total_outstanding_all = $('#total_outstanding_all').val();

            jQuery.ajax({
                type: "GET",
                url: "{!! url('debit-status-agoda-receive/"+status+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var status = "";
                        var btn_receive = "";
                        var total_received_amount = 0;
                        var total_debit_amount = 0;
                        var balance = 0;
                        var table = new DataTable('#myDataTableOutstanding');
                        table.clear().draw();

                        jQuery.each(response.data, function(key, value) {
                            if (value.agoda_check_in) {
                                var exp = value.agoda_check_in.split('-');
                                var check_in = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_in = "-";
                            }

                            if (value.agoda_check_out) {
                                var exp = value.agoda_check_out.split('-');
                                var check_out = exp[2] + "/" + exp[1] + "/" + exp[0];
                            } else {
                                var check_out = "-";
                            }

                            if (value.receive_payment == 1) {
                                status = '<span class="badge bg-success">Debit</span>';
                                btn_receive =
                                    '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' +
                                    value.id + '" value="0"' +
                                    'onclick="select_receive_payment(this, ' + value.id + ', ' +
                                    response.data.agoda_outstanding + ')" disabled>รับชำระ</button>';
                            } else {
                                status = '<span class="badge bg-danger">Credit</span>';
                                btn_receive =
                                    '<button type="button" class="btn btn-primary rounded-pill btn-receive-pay close" id="btn-receive-' +
                                    value.id + '" value="0"' +
                                    'onclick="select_receive_payment(this, ' + value.id + ', ' +
                                    response.data.agoda_outstanding + ')">รับชำระ</button>';
                            }

                            table.rows.add(
                                [
                                    [
                                        value.batch,
                                        check_in,
                                        check_out,
                                        currencyFormat(value.agoda_outstanding),
                                        status,
                                        btn_receive
                                    ]
                                ]
                            ).draw();

                            total_received_amount += value.agoda_outstanding;

                            if (value.receive_payment == 1) {
                                total_debit_amount = total_received_amount;
                                balance = total_outstanding_all - total_received_amount;
                            } else {
                                total_debit_amount = 0;
                                balance = total_received_amount;
                            }

                        });
                        $('#txt_total_outstanding').text(currencyFormat(Number(
                        total_received_amount))); // ยอดรับชำระด้านล่างตาราง Agoda Outstanding Revenue (แบบ Text)
                        $('#total_outstanding').val(
                        total_received_amount); // ยอดรับชำระด้านล่างตาราง Agoda Outstanding Revenue 
                        $('#txt_total_debit_payment').text(currencyFormat(Number(
                        total_debit_amount))); // ยอดรับชำระ Debit (แบบ Text)
                        $('#total_debit_payment').val(total_debit_amount); // ยอดที่รับชำระ Debit
                        $('#balance').text(currencyFormat(Number(balance))); // ยอดคงเหลือ Dashboard

                    }
                }
            });
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
    </script>
@endsection

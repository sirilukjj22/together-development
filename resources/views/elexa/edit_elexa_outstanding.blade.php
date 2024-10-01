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
                        {{-- <div>
                            <h6 class="fw-bold text-danger m-0">0.00</h6>
                        </div> --}}
                    </div>
                    <table id="myDataTableAll" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_debit = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-{{ $item->id}}" value="1"
                                        onclick="select_receive_payment(this, {{ $item->id}}, {{ $item->ev_charge }})">ยกเลิก</button>
                                    </td>
                                </tr>
                                <?php $total_debit += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td style="text-align: right;">Total</td>
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
                    <div
                        class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-danger"></i> Elexa Outstanding Revenue</h6>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 0)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <button type="button" class="btn btn-primary rounded-pill btn-receive-pay"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})">รับชำระ</button>
                                        @else
                                            <button type="button" class="btn btn-secondary rounded-pill btn-receive-pay"
                                            id="btn-receive-{{ $item->id }}" value="0"
                                            onclick="select_receive_payment(this, {{ $item->id }}, {{ $item->ev_charge }})" disabled>รับชำระ</button>
                                        @endif
                                    </td>
                                </tr>
                                <?php $total += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td style="text-align: right;">Total</td>
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
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif


    <script>
        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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
                                var table = new DataTable('#myDataTableAll');

                                table.rows.add(
                                    [
                                        [
                                            response.data.batch,
                                            currencyFormat(response.data.ev_charge),
                                            '<button type="button" class="btn btn-danger rounded-pill close" id="btn-receive-' +
                                            id + '" value="1"' + ' onclick="select_receive_payment(this, ' + id + ', ' + response
                                            .data.ev_charge + ')">ยกเลิก</button>'
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

                    var tb_select = new DataTable('#myDataTableAll');
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

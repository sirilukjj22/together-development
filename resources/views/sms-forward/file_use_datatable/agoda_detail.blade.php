@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">Bank Transaction Revenue</span><span class="span2"> / {{ $title }}</span></div>
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    <a href="javascript:history.back(1)" type="button" class="btn btn-color-green text-white lift">Back</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    @php
        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
    @endphp
    <div id="content-index" class="body d-flex py-lg-4 py-3">

        <div class="container-xl">
            <div class="row clearfix">
                <div class="card p-4 mb-4">
                    <table id="smsAgodaTable" class="table-together table-style">
                        <thead>
                            <tr>
                                <th style="text-align: center;" data-priority="1">#</th>
                                <th style="text-align: center;" data-priority="1">Date</th>
                                <th style="text-align: center;">Time</th>
                                <th style="text-align: center;">Bank</th>
                                <th style="text-align: center;">Bank Account</th>
                                <th style="text-align: center;" data-priority="1">Amount</th>
                                <th style="text-align: center;">Creatd By</th>
                                <th style="text-align: center;">Income Type</th>
                                <th style="text-align: center;">Transfer Date</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_sms = 0; ?>
    
                            @foreach ($data_sms as $key => $item)
                            <tr>
                                <td class="td-content-center">{{ $key + 1 }}</td>
                                <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                <td class="td-content-center text-start">
                                    <?php
                                    $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                    $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                    ?>
                                    <div class="flex-jc p-left-4">
                                        @if (file_exists($filename))
                                            <img  src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="" class="img-bank" />
                                        @elseif (file_exists($filename2))
                                            <img  src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.png" alt="" class="img-bank" />
                                        @endif
                                        {{ @$item->transfer_bank->name_en }}
                                    </div>
                                </td>
                                <td class="td-content-center">
                                    <div class="flex-jc p-left-4 center">
                                        <img  src="../../../image/bank/SCB.jpg" alt="" class="img-bank" />{{ 'SCB ' . $item->into_account }}
                                    </div>
                                </td>
                                <td class="td-content-center target-class text-end">
                                    {{ $item->amount_before_split > 0 ? $item->amount_before_split : $item->amount }}
                                </td>
                                <td class="td-content-center">{{ $item->remark ?? 'Auto' }}</td>
                                <td class="td-content-center">Agoda Bank Transfer Revenue</td>
                                <td class="td-content-center">
                                    {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="td-content-center" style="text-align: center;">
                                    @if ($item->close_day == 0 || Auth::user()->edit_close_day == 1)
                                        <div class="dropdown">
                                            <button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                                Select <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (@$role_revenue->front_desk == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                        Front Desk Bank <br>Transfer Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->guest_deposit == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                        Guest Deposit Bank <br> Transfer Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->all_outlet == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                        All Outlet Bank <br> Transfer Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->agoda == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                        Agoda Bank <br>Transfer Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->credit_card_hotel == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                        Credit Card Hotel <br> Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->elexa == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                        Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->no_category == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                        No Category
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->water_park == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                        Water Park Bank <br> Transfer Revenue 
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->credit_water_park == 1)
                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                        Credit Card Water <br>Park Revenue 
                                                    </li>
                                                @endif
                                                @if (@@$role_revenue->other_revenue == 1)
                                                    <li class="button-li" onclick="other_revenue_data({{ $item->id }})">
                                                        Other Revenue <br> Bank Transfer
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->transfer == 1)
                                                    <li class="button-li" onclick="transfer_data({{ $item->id }})">
                                                        Transfer
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->time == 1)
                                                    <li class="button-li" onclick="update_time_data({{ $item->id }})">
                                                        Update Time
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->split == 1)
                                                    <li class="button-li" onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                        Split Revenue
                                                    </li>
                                                @endif
                                                @if (@$role_revenue->edit == 1)
                                                    <li class="button-li" onclick="edit({{ $item->id }})">Edit</li>
                                                    <li class="button-li" onclick="deleted({{ $item->id }})">Delete</li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <?php $total_sms += $item->amount; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="fw-bold" style="background-color: #dff8f0;">Total</td>
                                <td colspan="5" class="fw-bold text-start" style="background-color: #dff8f0;">{{ number_format($total_sms, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div> <!-- .row end -->
        </div>
    </div>

    <!-- Modal -->
    <!-- Modal: Other Revenue -->
    <div class="modal fade" id="modalOtherRevenue" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="modalOtherRevenueLabel">Other Revenue Bank Transfer</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" class="basic-form" id="form-other">
                    @csrf
                    <div class="modal-body">
                        <label>หมายเหตุ</label>
                        <textarea class="form-control" name="other_revenue_remark" id="other_revenue_remark" rows="7" cols="50" placeholder="กรุณาระบุหมายเหตุ..." required></textarea>
                        <input type="hidden" name="dataID" id="otherDataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift" id="btn-save-other-revenue">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Transfer -->
    <div class="modal fade" id="exampleModalCenter2" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalCenter2Label">โอนย้าย</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" id="form-transfer" class="basic-form">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-12 col-12">
                            <label>วันที่โอนย้ายไป</label>
                            <input type="date" class="form-control" name="date_transfer" id="date_transfer">
                        </div>
                        <div class="col-md-12 col-12 mt-3">
                            <label>หมายเหตุ</label>
                            <textarea class="form-control" name="transfer_remark" id="transfer_remark" rows="5" cols="10" required>ปิดยอดช้ากว่ากำหนด</textarea>
                        </div>
                        <input type="hidden" name="dataID" id="dataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift" id="btn-save-transfer">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Time -->
    <div class="modal fade" id="exampleModalCenter1" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalCenter1Label">แก้ไขเวลาการโอน</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 col-12">
                        <label>เวลา</label>
                        <input type="time" class="form-control" name="update_time" id="update_time" value="<?php echo date('H:i:s'); ?>" step="any">
                    </div>
                    <input type="hidden" name="timeID" id="timeID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-color-green lift" onclick="change_time()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Split -->
    <div class="modal fade" id="SplitModalCenter" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="SplitModalCenterLabel">Split Revenue</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" class="form-split">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <label>วันที่</label>
                            <input type="date" class="form-control" name="date-split" id="date-split">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="col-md-6">
                            <label>จำนวนเงิน <span class="text-danger fw-bold" id="text-split-amount"></span></label>
                            &nbsp;<label>คงเหลือ <span class="text-danger fw-bold" id="text-split-balance"></span></label>
                            <input type="hidden" name="balance_amount" id="balance_amount">
                            <input type="text" class="form-control" name="split-amount" id="split-amount" placeholder="0.00">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="button" class="btn btn-color-green lift btn-split-add">Add</button>
                            <button type="button" class="btn btn-secondary lift btn-split-add" onclick="toggleHide()">Delete All</button>

                            <span class="split-todo-error text-danger" style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                            <span class="split-error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="print_invoice">
                                <table class="items">
                                    <thead>
                                        <tr>
                                            <th>วันที่</th>
                                            <th>จำนวนเงิน</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="split-todo-list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="split_total_number" value="0">
                        <input type="hidden" id="split_number" value="0">
                        <input type="hidden" id="split_list_num" name="split_list_num" value="0">
                        <input type="hidden" name="splitID" id="splitID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift btn-save-split" onclick="change_split()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่มข้อมูล modal fade -->
    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter5Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-lg">
                <div class="modal-header md-header">
                    <h5 class="modal-title text-white" id="exampleModalCenter5Label">เพิ่มข้อมูล
                    </h5>
                    <button type="button" class="close text-white text-2xl" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sms-store') }}" method="POST" class="" id="form-id">
                    @csrf
                    <div class="modal-body">
                        <label for="">ประเภทรายได้</label>
                        <br>
                        <select class="form-control form-select" id="status_type" name="status" onchange="select_type()">
                            <option value="0">เลือกข้อมูล</option>
                            <option value="1">Room Revenue</option>
                            <option value="2">All Outlet Revenue</option>
                            <option value="3">Water Park Revenue</option>
                            <option value="4">Credit Revenue</option>
                            <option value="5">Agoda Revenue</option>
                            <option value="6">Front Desk Revenue</option>
                            <option value="8">Elexa EGAT Revenue</option>
                            <option value="9">Other Revenue Bank Transfer</option>
                        </select>
                        <div class="dg-gc2-g2">
                            <div class="wf-py2 ">
                                <label for="">วันที่โอน <sup class="t-red600">*</sup></label>
                                <br>
                                <input class="form-control" type="date" name="date" id="sms-date" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เวลาที่โอน <sup class="text-danger">*</sup></label>
                                <br>
                                <input class="form-control" type="time" name="time" id="sms-time">
                            </div>
                            <div class="wf-py2 Amount agoda" hidden>
                                <label for="">Booking ID <sup class="text-danger">*</sup></label>
                                <br>
                                <input type="text" class="form-control" name="booking_id" id="booking_id" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">โอนจากบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="transfer_from" name="transfer_from" data-placeholder="Select">
                                    <option value="0">เลือกข้อมูล</option>
                                    @foreach ($data_bank as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}
                                            ({{ $item->name_en }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เข้าบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="add_into_account" name="into_account" data-placeholder="Select">
                                    <option value="0">เลือกข้อมูล</option>
                                    <option value="708-2-26791-3">ธนาคารไทยพาณิชย์ (SCB) 708-2-26791-3</option>
                                    <option value="708-2-26792-1">ธนาคารไทยพาณิชย์ (SCB) 708-2-26792-1</option>
                                    <option value="708-2-27357-4">ธนาคารไทยพาณิชย์ (SCB) 708-2-27357-4</option>
                                    <option value="076355900016902">ชำระผ่าน QR 076355900016902</option>
                                </select>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup></label>
                                <br>
                                <input class="form-control" type="text" id="amount" name="amount" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 15px;">Close</button>
                        <button type="button" class="btn btn-color-green sa-button-submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        {{-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        {{-- <script src="http://code.jquery.com/jquery-1.10.2.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

    <script>
        function transfer_data(id) {
            $('#dataID').val(id);
            $('#exampleModalCenter2').modal('show');
        }

        function update_time_data(id) {
            $('#timeID').val(id);
            $('#exampleModalCenter1').modal('show');
        }

        function split_data($id, $amount) {
            $('#splitID').val($id);
            $('#text-split-amount').text("(" + currencyFormat($amount) + ")");
            $('#balance_amount').val($amount);
            $('#SplitModalCenter').modal('show');
        }

        $('#add-data').on('click', function() {
            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');

            $('#id').val('');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            $('#exampleModalCenter5').modal('show');

        });

        function change_time() {
            var time = $('#update_time').val();
            var id = $('#timeID').val();

            $.ajax({
                type: "GET",
                url: "{!! url('sms-update-time/"+id+"/"+time+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    location.reload();
                },
            });
        }

        function change_split() {
            jQuery.ajax({
                url: "{!! url('sms-update-split') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: $('.form-split').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        }

        $('.btn-split-add').on('click', function() {
            var date_split = $('#date-split').val();
            var amount = $('#split-amount').val();
            var list = parseInt($('#split_list_num').val());
            var number = parseInt($('#split_number').val()) + 1;
            var total_amount = $('#split_total_number').val();
            var balance = $('#balance_amount').val();
            $('#split_number').val(number);

            if (date_split && amount) {
                if (parseFloat(total_amount) + parseFloat(amount) <= balance) {
                    var date = "";
                    var date_fm = new Date(date_split);
                    var year = date_fm.getFullYear();
                    var month = (1 + date_fm.getMonth()).toString().padStart(2, '0');
                    var day = date_fm.getDate().toString().padStart(2, '0');
                    date = day + '/' + month + '/' + year;

                    $('.split-todo-list').append(
                        '<tr>' +
                        '<td>' + date + '</td>' +
                        '<td>' + currencyFormat(parseFloat(amount)) + '</td>' +
                        '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose(this, ' +
                        amount + ')"></i></td>' +
                        '<input type="hidden" name="date_split[]" value="' + date_split + '">' +
                        '<input type="hidden" name="amount_split[]" value="' + amount + '">' +
                        '</tr>'
                    );

                    $('#split_total_number').val(parseFloat(total_amount) + parseFloat(amount));
                    $('#date-split').val('');
                    $('#split-amount').val('');
                    $('.split-todo-error').hide();
                    $('.split-error').text("");

                    if ($('#split_total_number').val() == balance) {
                        $('.btn-save-split').prop('disabled', false);
                    } else {
                        $('.btn-save-split').prop('disabled', true);
                    }
                } else {
                    $('.split-error').text("จำนวนเงินมากกว่ายอดคงเหลือ");
                }

            } else {
                $('.split-todo-error').show();
            }

            $('#text-split-balance').text("("+currencyFormat(balance - Number($('#split_total_number').val()))+")");

        });

        $('.split-todo-list .close').on('click', function() {
            toggleClose(this);
        });

        function toggleClose(ele, amount) {
            var total_amount = $('#split_total_number').val();
            $('#split_total_number').val(parseFloat(total_amount) - parseFloat(amount));
            $(ele).parent().parent().remove();

            if ($('#split_total_number').val() == $('#balance_amount').val()) {
                $('.btn-save-split').prop('disabled', false);
            } else {
                $('.btn-save-split').prop('disabled', true);
            }

            $('#text-split-balance').text("("+currencyFormat(Number($('#balance_amount').val()) - Number($('#split_total_number').val()))+")");
        }

        function toggleHide() {
            $('#split_total_number').val(0);
            $('.split-todo-list tr').remove();
        }

        function edit($id) {
            $('#exampleModalCenter5').modal('show');
            $('#id').val($id);
            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-edit/"+$id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var myArray = response.data.date.split(" ");
                        $('#status').val(response.data.status).trigger('change');
                        $('#sms-date').val(myArray[0]);
                        $('#sms-time').val(myArray[1]);
                        $('#booking_id').val(response.data.booking_id);
                        $('#transfer_from').val(response.data.transfer_from).trigger('change');
                        $('#add_into_account').val(response.data.into_account).trigger('change');
                        $('#amount').val(response.data.amount);
                    }
                },
            });
        }

        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        // ประเภทรายได้
        function change_status($id, $status) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-change-status/"+$id+"/"+$status+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    location.reload();
                },
            });
        }

        // ประเภทรายได้ Other Revenue
        function other_revenue_data(id) {
            $('#otherDataID').val(id);
            $('#modalOtherRevenue').modal('show');

            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-get-remark-other-revenue/"+id+"') !!}",
                datatype: "JSON",
                cache: false,
                async: false,
                success: function(response) {
                    if (response.data !== null) {
                        $('#other_revenue_remark').val(response.data.other_remark);
                    } else {
                        $('#other_revenue_remark').val('');
                    }
                },
            });
        }

        function deleted($id) {
            Swal.fire({
                icon: "info",
                title: 'คุณต้องการลบใช่หรือไม่?',
                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',
                showCancelButton: true,
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('sms-delete/"+$id+"') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(result) {
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                    });

                } else if (result.isDenied) {
                    Swal.fire('ลบข้อมูลไม่สำเร็จ!', '', 'info');
                    location.reload();
                }
            });
        }

        // Sweetalert2 #กรอกข้อมูลไม่ครบ
        // document.querySelector(".sa-button-submit").addEventListener('click', function() {
        //     var date = $('#sms-date').val();
        //     var time = $('#sms-time').val();
        //     var transfer = $('#error-transfer').val();
        //     var into = $('#error-into').val();
        //     var amount = $('#amount').val();
        //     var type = $('#status').val();

        //     $('#sms-date').css('border-color', '#f0f0f0');
        //     $('#sms-time').css('border-color', '#f0f0f0');
        //     $('#error-transfer').css('border-color', '#f0f0f0');
        //     $('#error-into').css('border-color', '#f0f0f0');
        //     $('#amount').css('border-color', '#f0f0f0');

        //     if (date == '') {
        //         $('#sms-date').css('border-color', 'red');

        //         return Swal.fire({
        //             icon: 'error',
        //             title: 'ไม่สามารถบันทึกข้อมูลได้',
        //             text: 'กรุณาระบุข้อมูลให้ครบ!',
        //         });
        //     }

        //     if (type != 5 && time == '') {
        //         $('#sms-time').css('border-color', 'red');

        //         return Swal.fire({
        //             icon: 'error',
        //             title: 'ไม่สามารถบันทึกข้อมูลได้',
        //             text: 'กรุณาระบุข้อมูลให้ครบ!',
        //         });
        //     }

        //     if (transfer == 0) {
        //         $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

        //         return Swal.fire({
        //             icon: 'error',
        //             title: 'ไม่สามารถบันทึกข้อมูลได้',
        //             text: 'กรุณาระบุข้อมูลให้ครบ!',
        //         });
        //     }

        //     if (into == 0) {
        //         $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

        //         return Swal.fire({
        //             icon: 'error',
        //             title: 'ไม่สามารถบันทึกข้อมูลได้',
        //             text: 'กรุณาระบุข้อมูลให้ครบ!',
        //         });
        //     }

        //     if (amount == '') {
        //         $('#amount').css('border-color', 'red');

        //         return Swal.fire({
        //             icon: 'error',
        //             title: 'ไม่สามารถบันทึกข้อมูลได้',
        //             text: 'กรุณาระบุข้อมูลให้ครบ!',
        //         });

        //     } else {

        //         jQuery.ajax({
        //             type: "POST",
        //             url: "{!! route('sms-store') !!}",
        //             datatype: "JSON",
        //             data: $('#form-id').serialize(),
        //             async: false,
        //             success: function(result) {
        //                 Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
        //                 location.reload();
        //             },
        //         });
        //     }
        // });
    </script>
@endsection
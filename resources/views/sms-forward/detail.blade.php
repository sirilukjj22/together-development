@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span1">Daily Bank Transaction Revenue</span><span class="span2"> / {{ $title ?? '' }}</span></div>
                <div class="span3">{{ $title ?? '' }}</div>
            </div>
            <div class="col-auto">
                <a href="javascript:history.back(1)" class="btn btn-color-green text-white lift">ย้อนกลับ</a>
            </div>
        </div> <!-- .row end -->
    </div>
@endsection

@section('content')
        @php
            $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
        @endphp
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <table id=""
                            class="example ui striped table nowrap unstackable hover" style="width:60%">
                            <caption class="caption-top">
                                <div>
                                    <div class="flex-end-g2">
                                        <label class="entriespage-label" >entries per page : </label>
                                        <select class="enteriespage-button">
                                            <option value="7" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                            <option value="15" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                            <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                            <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                        </select>
                                        <input class="search-button" placeholder="Search" /><i
                                            class="fa fa-search fa-searh-middle"></i>
                                    </div>
                            </caption>

                            <thead>
                                <tr>
                                    <th class="t-center" data-priority="1">#</th>
                                    <th class="t-center" data-priority="1">วันที่</th>
                                    <th class="t-center" data-priority="1">เวลา</th>
                                    <th class="t-center">โอนจากบัญชี</th>
                                    <th class="t-center">เข้าบัญชี</th>
                                    <th class="t-center" data-priority="1">จำนวนเงิน</th>
                                    <th class="t-center">ผู้ทำรายการ</th>
                                    <th class="t-center">ประเภทรายได้</th>
                                    <th class="t-center">วันที่โอนย้าย</th>
                                    <th class="t-center" data-priority="1">คำสั่ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                @foreach ($data_sms as $key => $item)
                                    @if ($item->split_status == 3)
                                    <tr class="my-row table-secondary">
                                    @else
                                    <tr>
                                    @endif
                                        <td data-label="#">{{ $key + 1 }}</td>
                                        <td data-label="วันที่">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td data-label="เวลา">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                        <td data-label="โอนจากบัญชี">
                                            <?php
                                            $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                            $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                            ?>
                                            <div class="flex-jc p-left-4">
                                                @if (file_exists($filename))
                                                    <img  src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="" class="img-bank" />
                                                @elseif (file_exists($filename2))
                                                    <img  src="../../../image/bank/SCB.jpg" alt="" class="img-bank" />
                                                @endif
                                                {{ @$item->transfer_bank->name_en }}
                                            </div>
                                        </td>
                                        <td data-label="เข้าบัญชี">
                                            <div class="flex-jc p-left-4">
                                                <img  src="../../../image/bank/SCB.jpg" alt="" class="img-bank" />{{ 'SCB ' . $item->into_account }}
                                            </div>
                                        </td>
                                        <td data-label="จำนวนเงิน">
                                            {{ number_format($item->amount_before_split > 0 ? $item->amount_before_split : $item->amount, 2) }}
                                        </td>
                                        <td data-label="ผู้ทำรายการ">{{ $item->remark ?? 'Auto' }}</td>
                                        <td data-label="ประเภทรายได้">
                                            @if ($item->status == 1) Guest Deposit Revenue
                                            @elseif($item->status == 2) All Outlet
                                            @elseif($item->status == 3) Water Park Revenue
                                            @elseif($item->status == 4) Credit Card Hotel Revenue
                                            @elseif($item->status == 5) Credit Card Agoda Revenue
                                            @elseif($item->status == 6) Front Desk Revenue
                                            @elseif($item->status == 7) Credit Card Water Park Revenue
                                            @endif

                                            @if ($item->split_status == 1)
                                                <br>
                                                <span class="text-danger">(Split Credit Card From {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                                            @endif
                                        </td>

                                        <td data-label="วันที่โอนย้าย">
                                            {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>
                                            @if ($item->split_status < 3)
                                                <div class="dropdown">
                                                    <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" type="button" data-toggle="dropdown" >ทำรายการ
                                                        <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            @if ($role_revenue->front_desk == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                                    Front Desk Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->guest_deposit == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                                    Guest Deposit Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->all_outlet == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                                    All Outlet Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->agoda == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                                    Agoda Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->credit_card_hotel == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                                    Credit Card Hotel Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->elexa == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                    Elexa EGAT Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->no_category == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                    No Category
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->water_park == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                    Water Park Bank Transfer Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->credit_water_park == 1)
                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                    Credit Card Water Park Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->transfer == 1)
                                                                <li class="button-li" onclick="transfer_data({{ $item->id }})">Transfer</li>
                                                            @endif
                                                            @if ($role_revenue->time == 1)
                                                                <li class="button-li" onclick="update_time_data({{ $item->id }})">Update Time</li>
                                                            @endif
                                                            @if ($role_revenue->split == 1)
                                                                <li class="button-li" onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                                    Split Revenue
                                                                </li>
                                                            @endif
                                                            @if ($role_revenue->edit == 1)
                                                                <li class="button-li" onclick="edit({{ $item->id }})">Edit</li>
                                                                <li class="button-li" onclick="deleted({{ $item->id }})">Delete</li>
                                                            @endif
                                                        </ul>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $total += $item->amount; ?>
                                @endforeach
                            </tbody>
                            <caption class="caption-bottom ">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2">Showing 1 to 7 of 7 entries</p>
                                    <div class="font-bold">ยอดรวมทั้งหมด 00.00 บาท</div>
                                    <div class="dp-flex js-center">
                                        <div class="pagination">
                                            <a href="{{ $data_sms->previousPageUrl() }}" class="r-l-md">&laquo;</a>
                                            @for($i=1;$i<=$data_sms->lastPage();$i++)
                                                <!-- a Tag for another page -->
                                                <a class="{{ @$_GET['page'] == $i || empty(@$_GET['page']) && $i == 1 ? 'active' : '' }}" href="{{$data_sms->url($i)}}">{{$i}}</a>
                                            @endfor
                                            <a href="{{ $data_sms->nextPageUrl() }}" class="r-r-md">&raquo;</a>
                                        </div>
                                    </div>
                                </div>
                            </caption>
                        </table>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter1Title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">แก้ไขเวลาการโอน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="">เวลา</label>
                    <input type="time" name="update_time" id="update_time" value="<?php echo date('H:i:s'); ?>" step="any">
                </div>
                <input type="hidden" name="timeID" id="timeID">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black;"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="button-10" style="background-color: #109699;" onclick="change_time()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter2Title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">โอนย้าย</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sms-transfer') }}" method="POST" enctype="multipart/form-data"
                    class="basic-form">
                    @csrf
                    <div class="modal-body">
                        <div class="revenue_type_modal">
                            <label for="">วันที่โอนย้ายไป</label>
                            <input type="date" name="date_transfer" id="date_transfer">
                        </div>
                        <div class="box_modal">
                            <label>หมายเหตุ</label>
                            <textarea name="transfer_remark" id="transfer_remark" rows="7" cols="50" required>ปิดยอดช้ากว่ากำหนด</textarea>
                        </div>
                        <input type="hidden" name="dataID" id="dataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="color: black;"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="button-10" style="background-color: #109699;">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter5" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter2Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มข้อมูล</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sms-store') }}" method="POST" class="" id="form-id">
                    @csrf
                    <div class="modal-body-split">
                        <h2>เพิ่มข้อมูล</h2>
                        <label for="">ประเภทรายได้</label><br>
                        <select class="select2" id="status" name="status" onChange="select_type()">
                            <option value="0">เลือกข้อมูล</option>
                            <option value="1">Room Revenue</option>
                            <option value="2">F&B Revenue</option>
                            <option value="3">Water Park Revenue</option>
                            <option value="4">Credit Revenue</option>
                            <option value="5">Agoda Revenue</option>
                            <option value="6">Front Desk Revenue</option>
                            <option value="8">Elexa EGAT Revenue</option>
                        </select>

                        <div class="transfer_date">
                            <label for="">วันที่โอน <sup class="text-danger">*</sup></label><br>
                            <input type="date" name="date" id="sms-date" required>
                        </div>

                        <div class="transfer_time">
                            <label for="">เวลาที่โอน <sup class="text-danger">*</sup></label><br>
                            <input type="time" name="time" id="sms-time">
                        </div>

                        <div class="Amount agoda" hidden>
                            <label for="">Booking ID <sup class="text-danger">*</sup></label><br>
                            <input type="text" name="booking_id" id="booking_id" required>
                        </div>

                        <div class="transfer_from">
                            <label for="">โอนจากบัญชี <sup class="text-danger">*</sup></label><br>
                            <select class="select2" id="transfer_from" name="transfer_from" data-placeholder="Select">
                                <option value="0">เลือกข้อมูล</option>
                                @foreach ($data_bank as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }} ({{ $item->name_en }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="transfer_to">
                            <label for="">เข้าบัญชี <sup class="text-danger">*</sup></label><br>
                            <select class="select2" id="add_into_account" name="into_account" data-placeholder="Select">
                                <option value="0">เลือกข้อมูล</option>
                                <option value="708-226791-3">ธนาคารไทยพาณิชย์ (SCB) 708-226791-3</option>
                                <option value="708-226792-1">ธนาคารไทยพาณิชย์ (SCB) 708-226792-1</option>
                                <option value="708-227357-4">ธนาคารไทยพาณิชย์ (SCB) 708-227357-4</option>
                                <option value="076355900016902">ชำระผ่าน QR 076355900016902</option>
                            </select>

                        </div>
                        <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup></label><br>
                        <div class="Amount">
                            <input type="text" id="amount" name="amount" placeholder="0.00" required>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="color: black;"
                            data-dismiss="modal">Close</button>
                        <button type="button" class="button-10 sa-button-submit" style="background-color: #109699;">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SplitModalCenter" tabindex="-1" role="dialog" aria-labelledby="SplitModalCenter"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SplitModalCenter">Split Revenue</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" class="form-split">
                    @csrf
                    <div class="modal-body-split">
                        <div class="split_date">
                            <label for="">วันที่</label>
                            <input type="date" class="" name="date-split" id="date-split">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>

                        <div class="split_price">
                            <label for="">จำนวนเงิน <span class="text-danger fw-bold"
                                    id="text-split-amount"></span></label>
                            <input type="hidden" class="" name="balance_amount" id="balance_amount">
                            <input type="text" class="" name="split-amount" id="split-amount"
                                placeholder="0.00">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="button-7">
                            <button type="button" class="btn-split-add" style="margin-top: 10px;">เพิ่ม</button>
                        </div>

                        <button type="button" class="button-10" style="background-color: #555555!important; "
                            onmouseover="this.style.backgroundColor='#555555';"
                            onmouseout="this.style.backgroundColor='#555555'" onclick="toggleHide()">ลบทั้งหมด</button>

                        <span class="split-todo-error text-danger" style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                        <span class="split-error text-danger" style=""></span>

                        <table id="example" class="display3">
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

                    <input type="hidden" id="split_total_number" value="0">
                    <input type="hidden" id="split_number" value="0">
                    <input type="hidden" id="split_list_num" name="split_list_num" value="0">
                    <input type="hidden" name="splitID" id="splitID">
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black;"
                        data-dismiss="modal">Close</button>

                    <button type="button" class="button-10 btn-save-split" onclick="change_split()"
                        style="background-color: #109699;" disabled>Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- table design css -->
    <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css')}}">

    <!-- table design js -->
    <script src="{{ asset('assets/js/semantic.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.semanticui.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.js')}}"></script>
    <script src="{{ asset('assets/js/responsive.semanticui.js')}}"></script>

    <script>
        $(document).ready(function() {

            new DataTable('.example', {
                responsive: true,
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    },
                    {
                        width: '7%',
                        targets: 0
                    },
                    {
                        width: '10%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    }

                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });

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
                    location.reload();
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
        document.querySelector(".sa-button-submit").addEventListener('click', function() {
            var date = $('#sms-date').val();
            var time = $('#sms-time').val();
            var transfer = $('#error-transfer').val();
            var into = $('#error-into').val();
            var amount = $('#amount').val();
            var type = $('#status').val();

            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');

            if (date == '') {
                $('#sms-date').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (type != 5 && time == '') {
                $('#sms-time').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (transfer == 0) {
                $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (into == 0) {
                $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (amount == '') {
                $('#amount').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });

            } else {

                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('sms-store') !!}",
                    datatype: "JSON",
                    data: $('#form-id').serialize(),
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

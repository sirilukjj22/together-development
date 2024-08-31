@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">SMS Alert</span><span class="span2"> / {{ $title }}</span></div>
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    <a href="javascript:history.back(1)" type="button" class="btn btn-color-green text-white lift">ย้อนกลับ</a>
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
                <div class="col-md-12">
                    <div class="card p-4 mb-4">
                        <h4 class="" style="color:rgba(44,127,122,.95);">Revenue</h4>
                        <table id="revenueTable" class="example ui striped table nowrap unstackable hover">
                            <caption class="caption-top">
                                    <div class="flex-end-g2">
                                        <label class="entriespage-label">entries per page :</label>
                                        <select class="entriespage-button" id="search-per-page-revenue" onchange="getPage(1, this.value, 'revenue')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                            <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "revenue" ? 'selected' : '' }}>10</option>
                                            <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "revenue" ? 'selected' : '' }}>25</option>
                                            <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "revenue" ? 'selected' : '' }}>50</option>
                                            <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "revenue" ? 'selected' : '' }}>100</option>
                                        </select>
                                        <input class="search-button search-data" id="revenue" style="text-align:left;" placeholder="Search" />
                                    </div>
                            </caption>
                            <thead>
                                <tr>
                                    <th style="text-align: center;" data-priority="1">#</th>
                                    <th style="text-align: center;" data-priority="1">Date</th>
                                    <th style="text-align: center;" data-priority="1">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                @foreach ($sum_revenue as $key => $item)
                                    <tr style="text-align: center;">
                                        <td class="td-content-center">{{ $key + 1 }}</td>
                                        <td class="td-content-center">{{ $item->date == '' ? '' : Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td style="text-align: right;">{{ number_format($item->agoda_outstanding, 2) }}</td>
                                    </tr>
                                    <?php $total += $item->agoda_outstanding; ?>
                                @endforeach
                            </tbody>
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="revenue-showingEntries">{{ showingEntriesTable($sum_revenue, 'revenue') }}</p>
                                    <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format($total, 2) }} บาท</div>
                                        <div id="revenue-paginate">
                                            {!! paginateTable($sum_revenue, 'revenue') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </table>
                    </div> <!-- .card end -->
                </div>
                <div class="col-md-12">
                    <div class="card p-4 mb-4">
                        <h4 class="" style="color:rgba(44,127,122,.95);">SMS</h4>
                        <table id="smsTable" class="example2 ui striped table nowrap unstackable hover">
                            <caption class="caption-top mt-2">
                                <div>
                                    <div class="flex-end-g2">
                                        <label class="entriespage-label">entries per page :</label>
                                        <select class="entriespage-button" id="search-per-page-smsAgoda" onchange="getPage(1, this.value, 'smsAgoda')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                            <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "smsAgoda" ? 'selected' : '' }}>10</option>
                                            <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "smsAgoda" ? 'selected' : '' }}>25</option>
                                            <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "smsAgoda" ? 'selected' : '' }}>50</option>
                                            <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "smsAgoda" ? 'selected' : '' }}>100</option>
                                        </select>
                                        <input class="search-button search-data" id="smsAgoda" style="text-align:left;" placeholder="Search" />
                                    </div>
                            </caption>
                            <thead>
                                <tr>
                                    <th style="text-align: center;" data-priority="1">#</th>
                                    <th style="text-align: center;" data-priority="1">Date</th>
                                    <th style="text-align: center;">Time</th>
                                    <th style="text-align: center;">Bank</th>
                                    <th style="text-align: center;">Bank Account</th>
                                    <th style="text-align: center;" data-priority="1">Amount</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_sms = 0; ?>
        
                                @foreach ($data_sms as $key => $item)
                                <tr style="font-weight: bold; color: black;">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != "" ? $item->date_into : $item->date)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != "" ? $item->date_into : $item->date)->format('H:i:s') }}</td>
                                    <td>
                                        <?php 
        
                                            $filename = base_path()."/public/image/bank/".@$item->transfer_bank->name_en.".jpg";
        
                                            $filename2 = base_path()."/public/image/bank/".@$item->transfer_bank->name_en.".png";
        
                                        ?>
                                        @if (file_exists($filename)) 
                                            <img class="rounded-circle avatar" src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="avatar" title="">
                                        @elseif (file_exists($filename2)) 
                                            <img class="rounded-circle avatar" src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.png" alt="avatar" title="">
                                        @endif
                                        {{ @$item->transfer_bank->name_en }}
                                    </td>
                                    <td>
                                        <img class="rounded-circle avatar" src="../../../image/bank/SCB.jpg" alt="avatar" title="">
                                        <span style="color: black;">{{ "SCB ".$item->into_account }}</span>
                                    </td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td class="text-center">
                                        @if ($item->close_day == 0 || Auth::user()->edit_close_day == 1)
                                            <a href="{{ route('sms-agoda-receive-payment', $item->id) }}" class="btn btn-primary rounded-pill" type="button">รับชำระ</a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $total_sms += $item->amount; ?>
                                @endforeach
                            </tbody>
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="smsAgoda-showingEntries">{{ showingEntriesTable($data_sms, 'smsAgoda') }}</p>
                                    <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format($total_sms, 2) }} บาท</div>
                                        <div id="smsAgoda-paginate">
                                            {!! paginateTable($data_sms, 'smsAgoda') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </table>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <input type="hidden" id="filter-by" name="filter_by" value="{{ !empty($_GET['filterBy']) ? $_GET['filterBy'] : 'date' }}">
    <input type="hidden" id="input-search-day" name="day" value="{{ !empty($_GET['day']) ? $_GET['day'] : date('d') }}">
    <input type="hidden" id="input-search-month" name="month" value="{{ !empty($_GET['month']) ? $_GET['month'] : date('m') }}">
    <input type="hidden" id="input-search-month-to" name="month_to" value="{{ !empty($_GET['monthTo']) ? $_GET['monthTo'] : date('m') }}">
    <input type="hidden" id="input-search-year" name="year" value="{{ !empty($_GET['year']) ? $_GET['year'] : date('Y') }}">
    <input type="hidden" id="status" value="5">
    <input type="hidden" id="account" value="{{ !empty($_GET['account']) ? $_GET['account'] : '' }}">
    <input type="time" id="time" name="time" value="<?php echo date('20:59:59'); ?>" hidden>
    <input type="hidden" id="get-total-revenue" value="{{ $sum_revenue->total() }}">
    <input type="hidden" id="get-total-smsAgoda" value="{{ $data_sms->total() }}">
    <input type="hidden" id="currentPage-smsAgoda" value="1">
    <input type="hidden" id="currentPage-revenue" value="1">

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTable.js')}}"></script>

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
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 2
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

            new DataTable('.example2', {
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

        // Search 
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var total = parseInt($('#get-total-'+id).val());
            var table_name = id+'Table';

            var filter_by = $('#filter-by').val();
            var day = $('#input-search-day').val();
            var month = $('#input-search-month').val();
            var year = $('#input-search-year').val();
            var month_to = $('#input-search-month-to').val();
            var type_status = $('#status').val();
            var account = $('#account').val();
            var getUrl = window.location.pathname;         
                
            $('#'+table_name).DataTable().destroy();
            if (id != "revenue") {
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                        url: '/sms-search-table',
                        type: 'POST',
                        dataType: "json",
                        cache: false,
                        data: {
                            search_value: search_value,
                            table_name: table_name,
                            filter_by: filter_by,
                            day: day,
                            month: month,
                            year: year,
                            month_to: month_to,
                            status: type_status,
                            into_account: account
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    },
                    "initComplete": function (settings, json) {

                        if ($('#'+id+'Table .dataTables_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        } else {
                            var count = 0;
                            $('.dataTables_empty').addClass('dt-center');
                        }
                        
                        if (search_value == '') {
                            count_total = total;
                        } else {
                            count_total = count;
                        }
                    
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearch(1, count_total, id));
                        $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 2, 3, 4, 5, 6], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'date' },
                        { data: 'time' },
                        { data: 'transfer_bank' },
                        { data: 'into_account' },
                        { data: 'amount' },
                        { data: 'btn_action' },
                    ],
                        
                });   
            } else {
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    // "ajax": "sms-search-table/"+search_value+"/"+table_name+"",
                    ajax: {
                        url: '/sms-search-table',
                        type: 'POST',
                        dataType: "json",
                        cache: false,
                        data: {
                            search_value: search_value,
                            table_name: table_name,
                            filter_by: filter_by,
                            day: day,
                            month: month,
                            year: year,
                            month_to: month_to,
                            status: type_status,
                            into_account: account
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    },
                    "initComplete": function (settings, json) {

                        if ($('#'+id+'Table .dataTables_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        } else {
                            var count = 0;
                            $('.dataTables_empty').addClass('dt-center');
                        }
                        
                        if (search_value == '') {
                            count_total = total;
                        } else {
                            count_total = count;
                        }
                    
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearch(1, count_total, id));
                        $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 2], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'date' },
                        { data: 'agoda_outstanding' },
                    ],
                        
                }); 
            }

            document.getElementById(id).focus();
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

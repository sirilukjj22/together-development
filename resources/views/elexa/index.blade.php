@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="title-top-table">{{ $title ?? '' }}</div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('debit-elexa-revenue') }}" class="bt-tg-normal">Action</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body-header d-flex">
        <div class="container-xl">
            <section class="">
                <!-- Overview elexa Sale -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h4 class="title-top-table">Overview Elexa Sale</h4>
                    </div>
                    <div class="wrap-overview-agoda-sale">
                        <div class="wrap-card-revenue">
                            <div class="agoda-revenue">
                                <span>Elexa Revenue</span> <!-- Elexa Charge -->
                                <span>{{ number_format($total_outstanding_all, 2) }}</span>
                                <input type="hidden" id="input-total-elexa-charge-revenue" value="{{ $total_outstanding_all }}">
                            </div>
                            <div class="agoda-paid">
                                <span>Elexa Paid</span> <!-- Elexa SMS -->
                                <span>{{ number_format($total_elexa_revenue, 2) }}</span>
                                <input type="hidden" id="input-total-elexa-paid" value="{{ $total_elexa_revenue }}">
                            </div>
                            <div class="agoda-ac-rec"> 
                                <span>Account Receivable</span> <!-- Elexa ที่กดรับชำระแล้ว -->
                                <span>{{ number_format($totalAccountReceivableAll, 2) }}</span>
                                <input type="hidden" id="input-total-account-receivable" value="{{ $totalAccountReceivableAll }}">
                            </div>
                            <div class="agoda-pending-ac-rec"> <!-- Elexa ยอดที่เข้าแล้ว แต่ยังไม่ได้กดรับชำระ สถานะเป็น Pending -->
                                <span>Pending Account Receivable</span>
                                <span>{{ number_format(($totalPendingAccountReceivableAll), 2) }}</span>
                                <input type="hidden" id="input-total-pending-account-receivable" value="{{ ($totalPendingAccountReceivableAll) }}">
                            </div>
                            <div class="agoda-outstanding">
                                <span>Elexa Outstanding</span> <!-- Elexa Charge ลบ Elexa SMS -->
                                <span>{{ number_format(($total_outstanding_all - $total_elexa_revenue), 2) }}</span>
                                <input type="hidden" id="input-total-elexa-outstanding" value="{{ ($total_outstanding_all - $total_elexa_revenue) }}">
                            </div>
                        </div>
                        <div style="max-width: 300px">
                            <canvas id="salesPieChart"></canvas>
                        </div>
                        <div>
                            <canvas id="salesLineChart" height="260" style="max-height: 300px;max-width: 100%;"></canvas>
                        </div>
                    </div>
                </section>
                
                <!-- Credit Elexa Revenue Outstanding -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h4 class="title-top-table">Credit Elexa Revenue Outstanding</h4>
                    </div>
                    <div class="d-flex box-sh p-2">
                        <div class="CreditAgodaOutstanding">
                            <div>
                                <div class="wrap-card-graph-revenue mb-2">
                                    <div>
                                        <span>Elexa Charge</span> <!-- Elexa Charge ยังไม่หักค่าธรรมเนียม -->
                                        <span>{{ number_format($total_elexa_charge_all, 2) }}</span>
                                    </div>
                                    <div>
                                        <span>Elexa Fee</span>
                                        <span>{{ number_format($total_elexa_fee, 2) }}</span>
                                    </div>
                                    <div id="agodaCharge">
                                        <span>Elexa Revenue</span>
                                        <span>0.00</span>
                                    </div>
                                </div>
                                <div class="wrap-card-revenue">
                                    <div id="agodaPaid" class="agoda-paid">
                                        <span>Elexa Paid</span>
                                        <span>0.00</span>
                                    </div>
                                    <div id="AccountRe" class="agoda-ac-rec">
                                        <span>Account Receivable</span>
                                        <span>0.00</span>
                                    </div>
                                    <div id="pendingAccount" class="agoda-pending-ac-rec">
                                        <span>Pending Account Receivable</span>
                                        <span>{{ number_format(($total_elexa_revenue - $total_elexa_debit_outstanding), 2) }}</span>
                                    </div>
                                    <div id="outstandingBalance" class="agoda-outstanding">
                                        <span>Elexa Outstanding Balance</span>
                                        <span>{{ number_format(($total_outstanding_all - $total_elexa_revenue), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <section class="chart-button mt-3 pt-0">
                                <div>
                                    <div class="flex-center text-center mb-2">
                                        <select id="yearSelect" style="max-width: 130px;">
                                            @for ($i = 2024; $i <= (date('Y') + 1); $i++)
                                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>ปี {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="card-month-agoda">
                                        <p class="px-2">
                                            <span class="month-name">Jan</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Feb</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Mar</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Apr</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">May</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Jun</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Jul</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Aug</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Sep</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Oct</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Nov</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                        <p class="px-2">
                                            <span class="month-name">Dec</span> ⯈ <span class="month-value">0.00</span>
                                        </p>
                                    </div>
                                </div>
                                <div style="max-width: 100%">
                                    <canvas id="salesLineChart2" height="350"
                                        style="max-height: 400px;max-width: 100%;"></canvas>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>

                {{-- Table 1 --}}
                <section class="mb-4">
                    <div class="flex-between-2end">
                        <h4 class="title-top-table">Elexa Revenue</h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="elexaRevenueYearFilter" class="form-select" style="width: max-content;" onchange="filterElexaRevenueSearch()">
                                    <option value="all">All Years</option>
                                    @for ($i = 2024; $i <= (date('Y') + 1); $i++)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wrap-table-together">
                        <table id="elexaRevenueTable" class="table-together table-style">
                            <thead>
                                <tr>
                                    <th data-priority="1">#</th>
                                    <th data-priority="2">Month</th>
                                    <th data-priority="2">Elexa paid</th>
                                    <th data-priority="4">Items</th>
                                    <th data-priority="3">Status</th>
                                    <th data-priority="1">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $totalAllItem = 0;
                                    $totalAllReceive = 0;
                                @endphp

                                @foreach ($elexa_revenue as $key => $item)
                                    @php
                                        $total = $item->total_sum;
                                        $totalAllItem += $item->total_item;
                                        $totalAllReceive += $item->total_receive;
                                    @endphp
                                    <tr class="parent-row" data-group="group{{ $item->id }}">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-start">{{ Carbon\Carbon::parse($item->date)->format('F Y') }}</td>
                                        <td class="target-class text-end">{{ $item->total_sum }}</td>
                                        <td class="target-class">{{ $item->total_receive }}/{{ $item->total_item }}</td>
                                        <td>
                                            @if ($item->total_receive == 0)
                                                <i class="fa fa-check-square" style="font-size:20px;color:rgb(131, 133, 131)"></i>
                                            @elseif ($item->total_receive < $item->total_item)
                                                <i class="fa fa-check-square" style="font-size:20px;color:#da8404;"></i>
                                            @else 
                                                <i class="fa fa-check-square" style="font-size:20px;color:#44a768;"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown center viewbt">
                                                <button class="toggle-button btn-detail" data-group="group{{ $item->id }}" value="0">
                                                    View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td class="text-center" style="padding: 10px;">Total</td>
                                    <td></td>
                                    <td class="text-end format-number-table" id="tfoot-total-revenue">{{ $total_elexa_revenue }}</td>
                                    <td class="text-center" id="tfoot-total-item">{{ $totalAllReceive }}/{{ $totalAllItem }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

                {{-- Table 2 --}}
                <section class="mb-4">
                    <div class="flex-between-2end">
                        <h4 class="title-top-table">Elexa outstanding revenue</h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="elexaOutstandingYearFilter" class="form-select" style="width: max-content;" onchange="filterElexaOutstandingSearch()">
                                    <option value="all">All Years</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                                <select id="elexaOutstandingMonthFilter" class="form-select " style="width: max-content;" onchange="filterElexaOutstandingSearch()">
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
                    </div>
                    <div class="wrap-table-together">
                        <table id="elexaOutstandingTable" class="table-together table-style">
                            <thead>
                                <tr class="text-capitalize">
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">วันที่ทำรายการ</th>
                                    <th data-priority="3">Order ID</th>
                                    <th data-priority="1">amount</th>
                                    <th data-priority="2">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elexa_outstanding as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->batch }}</td>
                                        <td class="text-end target-class">{{ $item->ev_revenue }}</td>
                                        <td><span class="wrap-status-unpaid">unpaid</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="padding: 10px">Total</td>
                                    <td></td>
                                    <td class="text-end format-number-table" id="tfoot-total-outstanding">{{ $total_elexa_outstanding_revenue }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

                {{-- Table 3 --}}
                <section class="mb-4">
                    <div class="flex-between-2end">
                        <h4 class="title-top-table">Debit Elexa Outstanding </h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="elexaDebitYearFilter" class="form-select" style="width: max-content;" onchange="filterElexaDebitSearch()">
                                    <option value="all">All Years</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                                <select id="elexaDebitMonthFilter" class="form-select " style="width: max-content;" onchange="filterElexaDebitSearch()">
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
                    </div>
                    <div class="wrap-table-together">
                        <table id="elexaDebitTable" class="example table-together table-style">
                            <thead>
                                <tr class="text-capitalize">
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">วันที่ทำรายการ</th>
                                    <th data-priority="3">Order ID</th>
                                    <th data-priority="1">amount</th>
                                    <th data-priority="2">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elexa_debit as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->batch }}</td>
                                        <td class="text-end target-class">{{ $item->ev_revenue }}</td>
                                        <td><span class="wrap-status-paid">paid</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="padding: 10px">Total </td>
                                    <td></td>
                                    <td class="text-end format-number-table" id="tfoot-total-debit">{{ $total_elexa_debit_outstanding }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
        </div>
    </div>

    <style>
        .child-row {
            display: none;
            /* ซ่อนแถวลูกโดยเริ่มต้น */
            background-color: #f9f9f9;
        }

        .toggle-button {
            cursor: pointer;
            border-radius: 5px;
            color: white;
            border: 1px solid grey;
            padding: 0px 5px;
            background-color: #2c7f7a;
        }

        .toggle-button:focus {
            outline: none;
            box-shadow: none;
        }

        .table-together tr th{
            text-align: center !important;
        }
    </style>


    <link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

    <!-- Chart.js and Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- Litepicker.js -->
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    <script src="{{ asset('assets/js/revenueElexa.js') }}"></script>

<script>
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

        // Function to adjust DataTable
    function adjustDataTable() {
        $.fn.dataTable.tables({ visible: true, api: true, }).columns.adjust().responsive.recalc();
    }

    function filterElexaRevenueSearch() {
        var year = $('#elexaRevenueYearFilter').val();
        var search_value = $('#dt-search-0').val();
        var table_name = "elexaRevenueTable";

        $('#elexaRevenueTable').DataTable().destroy();
        var table = $("#elexaRevenueTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-elexa-search-table',
                type: 'POST',
                data: {
                    search_value: search_value,
                    table_name: table_name,
                    year: year,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataSrc: function (json) {
                    // เก็บค่า total จาก response
                    total = json.total;
                    totalAllReceive = json.totalAllReceive;
                    totalAllItem = json.totalAllItem;
                    return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                }
            },
            initComplete: function (settings, json) {
                $('#tfoot-total-revenue').text(total);
                $('#tfoot-total-item').text(totalAllReceive+"/"+totalAllItem);
            },
            order: [0, 'asc'],
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [
                { targets: [0, 3, 4, 5], className: 'dt-center td-content-center' },
                { targets: [1], className: 'text-start' },
                { targets: [2], className: 'text-end' },
                { targets: [2], className: 'target-class' },
                {
                    targets: [2], // ใช้กับคอลัมน์ที่ต้องการแสดงตัวเลข
                    createdCell: function(td, cellData, rowData, row, col) {
                        if ($.isNumeric(cellData)) {
                            // แสดงตัวเลขพร้อม comma
                            $(td).text(parseFloat(cellData).toLocaleString("en-US", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                        }
                    }
                }
            ],
            columns: [
                { data: 'number' },
                { data: 'month' },
                { data: 'elexa_paid' },
                { data: 'item' },
                { data: 'status' },
                { data: 'btn_detail' },
            ],
            createdRow: function (row, data, dataIndex) {
                // เพิ่ม attribute data-group ให้ tr
                $(row).attr('class', 'parent-row');
                $(row).attr('data-group', 'group' + data.id);

                // ค้นหา parent row ที่เกี่ยวข้อง
                var parentRow = $('tr.parent-row[data-group="group' + data.id + '"]');
                
                // สร้าง child row ใหม่
                var childRow = `
                    <tr class="child-row" data-group="group${data.id}" style="display: none;">
                    </tr>`;

                // เพิ่ม child row หลัง parent row
                parentRow.after(childRow);
            },
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    function filterElexaOutstandingSearch() {
        var year = $('#elexaOutstandingYearFilter').val();
        var month = $('#elexaOutstandingMonthFilter').val();
        var search_value = $('#dt-search-1').val();
        var table_name = "elexaOutstandingTable";

        $('#elexaOutstandingTable').DataTable().destroy();
        var table = $("#elexaOutstandingTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-elexa-search-table',
                type: 'POST',
                data: {
                    search_value: search_value,
                    table_name: table_name,
                    year: year,
                    month: month,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataSrc: function (json) {
                    // เก็บค่า total จาก response
                    total = json.total;
                    return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                }
            },
            initComplete: function (settings, json) {
                $('#tfoot-total-outstanding').text(total);
            },
            columnDefs: [
                            { targets: [3], className: 'text-end' },
                            {
                                targets: [3], // ใช้กับคอลัมน์ที่ต้องการแสดงตัวเลข
                                createdCell: function(td, cellData, rowData, row, col) {
                                    if ($.isNumeric(cellData)) {
                                        // แสดงตัวเลขพร้อม comma
                                        $(td).text(parseFloat(cellData).toLocaleString("en-US", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));
                                    }
                                }
                            }
                        ],
            columns: [
                { data: 'number' },
                { data: 'date' },
                { data: 'orderID' },
                { data: 'amount' },
                { data: 'status' },
            ],
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    function filterElexaDebitSearch() {
        var year = $('#elexaDebitYearFilter').val();
        var month = $('#elexaDebitMonthFilter').val();
        var search_value = $('#dt-search-2').val();
        var table_name = "elexaDebitTable";

        $('#elexaDebitTable').DataTable().destroy();
        var table = $("#elexaDebitTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-elexa-search-table',
                type: 'POST',
                data: {
                    search_value: search_value,
                    table_name: table_name,
                    year: year,
                    month: month,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataSrc: function (json) {
                    // เก็บค่า total จาก response
                    total = json.total;
                    return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                }
            },
            initComplete: function (settings, json) {
                $('#tfoot-total-debit').text(total);
            },
            columnDefs: [
                            { targets: [3], className: 'text-end' },
                            {
                                targets: [3], // ใช้กับคอลัมน์ที่ต้องการแสดงตัวเลข
                                createdCell: function(td, cellData, rowData, row, col) {
                                    if ($.isNumeric(cellData)) {
                                        // แสดงตัวเลขพร้อม comma
                                        $(td).text(parseFloat(cellData).toLocaleString("en-US", {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }));
                                    }
                                }
                            }
                        ],
            columns: [
                { data: 'number' },
                { data: 'date' },
                { data: 'orderID' },
                { data: 'amount' },
                { data: 'status' },
            ],
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    // Toggle Child
    $(document).on('click', '.btn-detail', function () {
        var status = $(this).val();
        var group = $(this).attr("data-group");

        if (status == 0) {
            addChildRow(group);
            $(this).val(1);
            $(this).text('Close');
        } else {
            $('tr.child-row[data-group="' + group + '"]').remove();
            $(this).val(0);
            $(this).text('View');
        }
    });

    // ฟังก์ชันเพิ่ม child row
    function addChildRow(parentGroupId) {
        // ค้นหา child rows ที่เกี่ยวข้องกับ groupId
        $('tr.child-row[data-group="' + parentGroupId + '"]').remove();

        // ค้นหา parent row ที่เกี่ยวข้อง
        var parentRow = $('tr.parent-row[data-group="' + parentGroupId + '"]');
        var childRow = '';

        // ตรวจสอบว่าพบ parent row หรือไม่
        if (parentRow.length === 0) {
            console.error("Parent row not found for group: " + parentGroupId);
            return;
        }

        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('debtor-elexa-search-detail-child/"+parentGroupId+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(response) {
                $.each(response.data, function (key, val) {
                    // สร้าง child row ใหม่
                    childRow += `
                        <tr class="child-row" data-group="${parentGroupId}" style="display: table-row;">
                            <td>${key + 1}</td>
                            <td class="text-start">${moment(val.date).format('DD/MM/YYYY H:mm:ss')}</td>
                            <td class="text-end">${currencyFormat(val.amount)}</td>
                            <td>
                                ${val.status_receive_elexa == 1 ? '<span class="wrap-status-paid">paid</span>' : '<span class="wrap-status-pending">pending</span>'}
                            </td>
                            <td></td>
                            <td>
                                <a href="/debit-elexa-detail/${val.id}">
                                    <i class="fa fa-file-text-o" style="font-size:24px;color:rgb(95, 94, 94)"></i>
                                </a>
                            </td>
                        </tr>`;
                    });
                },
            });

        // เพิ่ม child row หลัง parent row
        parentRow.after(childRow);
    }
</script>
@endsection

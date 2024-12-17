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
                    <a href="{{ route('debit-agoda-revenue') }}" class="bt-tg-normal">Action</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body-header d-flex">
        <div class="container-xl">
            <section class="">
                <!-- Overview agoda Sale -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h4 class="title-top-table">Overview agoda Sale</h4>
                    </div>
                    <div class="wrap-overview-agoda-sale">
                        <div class="wrap-card-revenue">
                            <div class="agoda-revenue">
                                <span>Agoda Revenue</span> <!-- Agoda Charge -->
                                <span>{{ number_format($total_outstanding_all, 2) }}</span>
                                <input type="hidden" id="input-total-agoda-charge-revenue" value="{{ $total_outstanding_all }}">
                            </div>
                            <div class="agoda-paid">
                                <span>Agoda Paid</span> <!-- Agoda SMS -->
                                <span>{{ number_format($total_agoda_revenue, 2) }}</span>
                                <input type="hidden" id="input-total-agoda-paid" value="{{ $total_agoda_revenue }}">
                            </div>
                            <div class="agoda-ac-rec">
                                <span>Account Receivable</span> <!-- Agoda ที่กดรับชำระแล้ว -->
                                <span>{{ number_format($totalAccountReceivableAll, 2) }}</span>
                                <input type="hidden" id="input-total-account-receivable" value="{{ $totalAccountReceivableAll }}">
                            </div>
                            <div class="agoda-pending-ac-rec"> <!-- Agoda ยอดที่เข้าแล้ว แต่ยังไม่ได้กดรับชำระ สถานะเป็น Pending -->
                                <span>Pending Account Receivable</span>
                                <span>{{ number_format(($totalPendingAccountReceivableAll), 2) }}</span>
                                <input type="hidden" id="input-total-pending-account-receivable" value="{{ ($totalPendingAccountReceivableAll) }}">
                            </div>
                            <div class="agoda-outstanding">
                                <span>Agoda Outstanding</span> <!-- Agoda Charge ลบ Agoda SMS -->
                                <span>{{ number_format(($total_outstanding_all - $total_agoda_revenue), 2) }}</span>
                                <input type="hidden" id="input-total-agoda-outstanding" value="{{ ($total_outstanding_all - $total_agoda_revenue) }}">
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
                
                <!-- Credit Agoda Revenue Outstanding -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h4 class="title-top-table">Credit Agoda Revenue Outstanding</h4>
                    </div>
                    <div class="d-flex box-sh p-2">
                        <div class="CreditAgodaOutstanding">
                            <div>
                                <div class="wrap-card-graph-revenue mb-2">
                                    <div>
                                        <span>Agoda Charge</span> <!-- Agoda Charge ยังไม่หักค่าธรรมเนียม -->
                                        <span>{{ number_format($total_agoda_charge_all, 2) }}</span>
                                    </div>
                                    <div>
                                        <span>Agoda Fee</span>
                                        <span>{{ number_format($total_agoda_fee, 2) }}</span>
                                    </div>
                                    <div id="agodaCharge">
                                        <span>Agoda Revenue</span>
                                        <span>0.00</span>
                                    </div>
                                </div>
                                <div class="wrap-card-revenue">
                                    <div id="agodaPaid" class="agoda-paid">
                                        <span>Agoda Paid</span>
                                        <span>0.00</span>
                                    </div>
                                    <div id="AccountRe" class="agoda-ac-rec">
                                        <span>Account Receivable</span>
                                        <span>0.00</span>
                                    </div>
                                    <div id="pendingAccount" class="agoda-pending-ac-rec">
                                        <span>Pending Account Receivable</span>
                                        <span>{{ number_format(($total_agoda_revenue - $total_agoda_debit_outstanding), 2) }}</span>
                                    </div>
                                    <div id="outstandingBalance" class="agoda-outstanding">
                                        <span>Agoda Outstanding Balance</span>
                                        <span>{{ number_format(($total_outstanding_all - $total_agoda_revenue), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <section class="chart-button mt-3 pt-0">
                                <div>
                                    <div class="flex-center text-center mb-2">
                                        <select id="yearSelect" style="max-width: 130px;">
                                            <option value="2024"> ปี 2024</option>
                                            <option value="2025"> ปี 2025</option>
                                            <option value="2026"> ปี 2026</option>
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
                        <h4 class="title-top-table">Agoda Revenue</h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="agodaRevenueYearFilter" class="form-select" style="width: max-content;" onchange="filterAgodaRevenueSearch()">
                                    <option value="all">All Years</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wrap-table-together">
                        <table id="agodaRevenueTable" class="table-together table-style">
                            <thead>
                                <tr>
                                    <th data-priority="1">#</th>
                                    <th data-priority="2">Month</th>
                                    <th data-priority="2">Agoda paid</th>
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

                                @foreach ($agoda_revenue as $key => $item)
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
                                    <td class="text-end format-number-table" id="tfoot-total-revenue">{{ $total_agoda_revenue }}</td>
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
                        <h4 class="title-top-table">Agoda outstanding revenue</h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="agodaOutstandingYearFilter" class="form-select" style="width: max-content;" onchange="filterAgodaOutstandingSearch()">
                                    <option value="all">All Years</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                                <select id="agodaOutstandingMonthFilter" class="form-select " style="width: max-content;" onchange="filterAgodaOutstandingSearch()">
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
                        <table id="agodaOutstandingTable" class="table-together table-style">
                            <thead>
                                <tr class="text-capitalize">
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">วันที่ทำรายการ</th>
                                    <th data-priority="3">Booking number</th>
                                    <th data-priority="4">Check in date</th>
                                    <th data-priority="5">Check out date</th>
                                    <th data-priority="1">amount</th>
                                    <th data-priority="2">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agoda_outstanding as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->batch }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                        <td class="text-end target-class">{{ $item->agoda_outstanding }}</td>
                                        <td><span class="wrap-status-unpaid">unpaid</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="padding: 10px">Total</td>
                                    <td colspan="3"></td>
                                    <td class="text-end format-number-table" id="tfoot-total-outstanding">{{ $total_agoda_outstanding_revenue }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

                {{-- Table 3 --}}
                <section class="mb-4">
                    <div class="flex-between-2end">
                        <h4 class="title-top-table">Debit Agoda Outstanding </h4>
                        <div class="flex-end">
                            <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
                                <select id="agodaDebitYearFilter" class="form-select" style="width: max-content;" onchange="filterAgodaDebitSearch()">
                                    <option value="all">All Years</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                                <select id="agodaDebitMonthFilter" class="form-select " style="width: max-content;" onchange="filterAgodaDebitSearch()">
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
                        <table id="agodaDebitTable" class="example table-together table-style">
                            <thead>
                                <tr class="text-capitalize">
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">วันที่ทำรายการ</th>
                                    <th data-priority="3">Booking number</th>
                                    <th data-priority="4">Check in date</th>
                                    <th data-priority="5">Check out date</th>
                                    <th data-priority="1">amount</th>
                                    <th data-priority="2">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agoda_debit as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->batch }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                        <td class="text-end target-class">{{ $item->agoda_outstanding }}</td>
                                        <td><span class="wrap-status-paid">paid</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="padding: 10px">Total </td>
                                    <td colspan="3"></td>
                                    <td class="text-end format-number-table" id="tfoot-total-debit">{{ $total_agoda_debit_outstanding }}</td>
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
    <script src="{{ asset('assets/js/revenueAgoda.js') }}"></script>

<script>
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

        // Function to adjust DataTable
    function adjustDataTable() {
        $.fn.dataTable.tables({ visible: true, api: true, }).columns.adjust().responsive.recalc();
    }

    function filterAgodaRevenueSearch() {
        var year = $('#agodaRevenueYearFilter').val();
        var search_value = $('#dt-search-0').val();
        var table_name = "agodaRevenueTable";

        $('#agodaRevenueTable').DataTable().destroy();
        var table = $("#agodaRevenueTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-agoda-search-table',
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
                { data: 'agoda_paid' },
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

    function filterAgodaOutstandingSearch() {
        var year = $('#agodaOutstandingYearFilter').val();
        var month = $('#agodaOutstandingMonthFilter').val();
        var search_value = $('#dt-search-1').val();
        var table_name = "agodaOutstandingTable";

        $('#agodaOutstandingTable').DataTable().destroy();
        var table = $("#agodaOutstandingTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-agoda-search-table',
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
                            { targets: [5], className: 'text-end' },
                            {
                                targets: [5], // ใช้กับคอลัมน์ที่ต้องการแสดงตัวเลข
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
                { data: 'booking' },
                { data: 'check_in' },
                { data: 'check_out' },
                { data: 'amount' },
                { data: 'status' },
            ],
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    function filterAgodaDebitSearch() {
        var year = $('#agodaDebitYearFilter').val();
        var month = $('#agodaDebitMonthFilter').val();
        var search_value = $('#dt-search-2').val();
        var table_name = "agodaDebitTable";

        $('#agodaDebitTable').DataTable().destroy();
        var table = $("#agodaDebitTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 10,
            serverSide: false,
            responsive: true,
            ajax: {
                url: 'debtor-agoda-search-table',
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
                            { targets: [5], className: 'text-end' },
                            {
                                targets: [5], // ใช้กับคอลัมน์ที่ต้องการแสดงตัวเลข
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
                { data: 'booking' },
                { data: 'check_in' },
                { data: 'check_out' },
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
            url:    "{!! url('debtor-agoda-search-detail-child/"+parentGroupId+"') !!}",
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
                                ${val.status_receive_agoda == 1 ? '<span class="wrap-status-paid">paid</span>' : '<span class="wrap-status-pending">pending</span>'}
                            </td>
                            <td></td>
                            <td>
                                <a href="/debit-agoda-detail/${val.id}">
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

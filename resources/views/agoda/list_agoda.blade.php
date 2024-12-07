@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span1">Agoda</span><span class="span2"> / {{ $title }}</span></div>
                <div class="span3">{{ $title }}</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('debit-agoda') }}" class="bt-tg-normal">Back</a>
            </div>
        </div> <!-- .row end -->
    </div>
</div>

<div>
    <section class="doc my-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
            <h4 class="title-top-table">Agoda Revenue</h4>
        </div>

        <div class="flex-end">
            <div class="filter-section bd-select-cl d-flex mb-2" style="gap: 0.3em">
                <select id="agodaRevenueDayYearFilter" class="form-select" style="width: max-content" onchange="filterSearch()">
                    <option value="all">All Years</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>

                <select id="agodaRevenueDayMonthFilter" class="form-select" style="width: max-content" onchange="filterSearch()">
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

                <select id="agodaRevenueDayStatusFilter" class="form-select" style="width: max-content" onchange="filterSearch()">
                    <option value="all">All Status</option>
                    <option value="1">Paid</option>
                    <option value="0">Pending</option>
                </select>
            </div>
        </div>

        <div class="wrap-table-together">
            <table id="agodaRevenueDayTable" class="table-together table-style">
                <thead>
                    <tr>
                        <th data-priority="1">#</th>
                        <th data-priority="1">Date</th>
                        <th data-priority="1">Amount</th>
                        <th data-priority="2">Status</th>
                        <th data-priority="2">Lock/Unlock</th>
                        <th data-priority="1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agoda_revenue as $key => $item)
                        @php
                            $month = Carbon\Carbon::parse($item->date)->format('m');
                            $year = Carbon\Carbon::parse($item->date)->format('Y');
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                {{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}
                            </td>
                            <td class="text-end target-class">{{ $item->amount }}</td>
                            <td>
                                @if ($item->status_receive_agoda == 0)
                                    <span class="wrap-status-pending">pending</span>
                                @else
                                    <span class="wrap-status-paid">paid</span>
                                @endif
                            </td>
                            <td>
                                @if (@$item->statusLock->status_lock == 0)
                                    <i class="fa fa-unlock"></i>
                                @else
                                    <i class="fa fa-lock"></i>
                                @endif
                            </td>
                            <td>
                                <!-- Dropdown -->
                                <div class="dropdown center">
                                    <div style="width: 90px" class="dropdown-shoose-items dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select
                                    </div>
                                    <ul class="dropdown-menu btn-dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($item->status_receive_agoda == 0)
                                            <li>
                                                <a href="{{ route('debit-agoda-update-receive', [$item->id, $month, $year]) }}" class="dropdown-item">Create</a>
                                            </li>
                                        @else
                                            @php
                                                $checkReceiveDate = App\Models\Revenue_credit::getAgodaReceiveDate($item->id);
                                            @endphp

                                            {{-- Permission 1 และ 2 สามารถเห็นปุ่ม Lock/Unlock ได้ --}}
                                            @if (Auth::user()->permission == 1 || Auth::user()->permission == 2)
                                                @if (@$item->statusLock->status_lock == 0)
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item lock-item" onclick="lockItem({{$item->id}}, 1)">Lock</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item unlock-item" onclick="lockItem({{$item->id}}, 0)">Unlock</a>
                                                    </li>
                                                @endif
                                            @endif

                                            {{-- หากต้องการแก้ไขรายการ ต้องให้ Admin Unlock ให้ก่อน **Admin ต้อง Unlock ก่อนเหมือนกัน จะสามารถแก้ไขได้ --}}
                                            @if (@$item->statusLock->status_lock == 0)
                                                <li>
                                                    <a href="{{ route('debit-agoda-update-receive', [$item->id]) }}" class="dropdown-item">Edit</a>
                                                </li>
                                            @endif
                                        @endif
                                        <li>
                                            <a href="{{ route('debit-agoda-detail', [$item->id]) }}" class="dropdown-item">View</a>
                                        </li>
                                        @if ($item->status_receive_agoda == 1)
                                            <li>
                                                <a href="{{ route('debtor-agoda-logs', $item->id) }}" class="dropdown-item">Log</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="background-color: #d7ebe1; font-weight: bold">
                    <tr>
                        <td class="text-center" style="padding: 10px">Total</td>
                        <td colspan="1"></td>
                        <td class="text-end format-number-table" id="tfoot-total-revenue">{{ $total_agoda_revenue }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>

<style>
    .table-together tr th{
        text-align: center !important;
    }

    .dropdown-menu {
        position: fixed !important;
        z-index: 1050;
    }
</style>

<link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

<!-- เพิ่ม SweetAlert2 CSS และ JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.js"></script>

<!-- Moment Date -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Custom Scripts -->
<script src="{{ asset('assets/js/table-together.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/helper/searchTableDebtorAgoda.js')}}"></script>

<script>

    // Function to adjust DataTable
    function adjustDataTable() {
        $.fn.dataTable.tables({ visible: true, api: true, }).columns.adjust().responsive.recalc();
    }

    function filterSearch() {
        var year = $('#agodaRevenueDayYearFilter').val();
        var month = $('#agodaRevenueDayMonthFilter').val();
        var status_paid = $('#agodaRevenueDayStatusFilter').val();
        var search_value = $('input[type="search"]').val();
        var table_name = "agodaRevenueDayTable";

        $('#agodaRevenueDayTable').DataTable().destroy();
        var table = $("#agodaRevenueDayTable").DataTable({
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
                    status_paid: status_paid,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataSrc: function (json) {
                    // เก็บค่า total จาก response
                    total = json.total;
                    return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                }
            },
            initComplete: function (settings, json) {
                $('#tfoot-total-revenue').text(total);
                
            },
            columnDefs: [
                            { targets: [2], className: 'text-end' },
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
                { data: 'date' },
                { data: 'amount' },
                { data: 'status' },
                { data: 'lock_unlock' },
                { data: 'btn_detail' },
            ]
        });

        $(window).on("resize", adjustDataTable);
        $('input[type="search"]').attr("placeholder", "Type to search...");
        $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
    }

    function lockItem(id, status) {
        var text_title = "";

        if (status == 0) {
            text_title = "Do you want to unlock this item?";
        } else {
            text_title = "Do you want to lock this item?";
        }

        Swal.fire({
            icon: "info",
            title: text_title,
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('debtor-agoda-change-status-lock/"+id+"/"+status+"') !!}",
                    datatype: "JSON",
                    data: $('#form-agoda').serialize(),
                    async: false,
                    success: function(result) {
                        if (status == 0) {
                            Swal.fire('This item has been unlocked successfully.', '', 'success');
                        } else {
                            Swal.fire('This item has been locked successfully.', '', 'success');
                        }
                        
                        location.reload();
                    },
                });

            } else if (result.isDenied) {
                if (status == 0) {
                    Swal.fire('Failed to unlock this item.', '', 'error');
                } else {
                    Swal.fire('Failed to lock this item.', '', 'error');
                }
            }
        });
    }
</script>

@endsection

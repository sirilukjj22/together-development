{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.masterLayout')

@section('pretitle')
<div class="container">
    <div class="row align-items-center">
        <div class="col">
            <small class="text-muted ">Welcome to Agoda.</small>
            <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
        </div>

        <div class="col-auto">
            
        </div>
    </div> <!-- .row end -->
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
                                <th>เดือน</th>
                                <th>จำนวนเงิน</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $thaiMonths = [
                                    1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
                                    4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
                                    7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
                                    10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                                ];

                                $total = 0;
                            @endphp
                            @foreach ($agoda_revenue as $key => $item)
                            @php
                                $date = Carbon\Carbon::parse($item->date);
                            @endphp
                                <tr style="font-weight: bold; color: black;">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{$thaiMonths[$date->format('n')]}} {{$date->format('Y') + 543}}
                                    <td>{{ number_format($item->total_sum, 2) }}</td>
                                    <td>
                                        <a href="{{ route('debit-agoda-revenue', [$date->format("m"), $date->format("Y")]) }}" title="ทำรายการ" class="btn btn-primary rounded-pill text-white lift">
                                            <i class="fa fa-plus"></i>
                                            ทำรายการ
                                        </a>
                                    </td>
                                </tr>
                                <?php $total += $item->total_sum; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>{{ number_format($total, 2) }}</td>
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
                                                class="fa fa-circle me-2 text-danger"></i>Credit Agoda Revenue Outstanding
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
                                                class="fa fa-circle me-2 text-success"></i>Agoda Paid</div>
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
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                                <h6 class="fw-bold m-0">Agoda Outstanding Revenue</h6>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <input type="text" id="search-date" class="form-control me-2" name="dates" style="text-align: left;" disabled>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="date-all" checked>
                                    <label class="form-check-label" for="date-all">All</label>
                                </div>
                            </div>

                            <select name="" id="search-status" class="form-select mt-3 mb-3" onchange="status_receive()">
                                <option value="all">All</option>
                                <option value="1">Paid</option>
                                <option value="0">Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>วันที่ทำรายการ</th>
                                <th>Booking Number</th>
                                <th>Check in</th>
                                <th>Check out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="4" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>
                                    <span id="txt_total_outstanding"></span>
                                    <input type="hidden" id="total_outstanding" value="{{ $total }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
            <div class="col-md-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 mb-2">
                        <h6 class="fw-bold m-0">Debit Agoda Outstanding</h6>
                    </div>
                    <table id="myDataTableAll" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>วันที่ทำรายการ</th>
                                <th>Booking Number</th>
                                <th>วันที่ Check in</th>
                                <th>วันที่ Check out</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_debit = 0; ?>
                            @foreach ($agoda_outstanding as $key => $item)
                                @if ($item->receive_payment == 1)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif    
                                    </td>
                                </tr>
                                <?php $total_debit += $item->agoda_outstanding; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="4" style="text-align: right;">ยอดรวมทั้งหมด</td>
                                <td>
                                    <span id="txt_total_outstanding">{{ number_format($total_debit, 2) }}</span>
                                    <input type="hidden" id="total_outstanding" value="{{ $total_debit }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
        </div> <!-- .row end -->
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(document).ready(function () {
            var dateRange = $('#search-date').val();
            var status = $('#search-status').val();
            var [startDate, endDate] = dateRange.split(" - ");

            if ($('#date-all').is(':checked')) {
                startDate = 'startAll';
                endDate = 'endAll';
            } else {
                startDate = convertDateFormat(startDate);
                endDate = convertDateFormat(endDate);
            }

            var table = $('#myDataTableOutstanding').dataTable({
                searching: true,
                paging: true,
                info: true,
                "ajax": {
                            "url": "debit-status-agoda-receive/all/"+startDate+"/"+endDate+"",
                            "dataSrc": function(json) {
                                // Access the total field from the response
                                var total = json.total_amount;

                                $('#txt_total_outstanding').text(currencyFormat(total)); // เช่น แสดง total ใน element ที่มี id เป็น total-amount
                                $('#total_outstanding').val(total);

                                return json.data; // ส่งข้อมูล data กลับไปที่ DataTables
                            }
                        },
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'date' },
                    { data: 'batch' },
                    { data: 'check_in' },
                    { data: 'check_out' },
                    { data: 'agoda_outstanding' },
                    { data: 'status' },
                ],
                    
            }); 

        });

        $('input[name="dates"]').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'  // หรือรูปแบบวันที่ที่คุณต้องการ เช่น 'MM-DD-YYYY', 'YYYY-MM-DD' เป็นต้น
            }
        });


        function status_receive() {
            var dateRange = $('#search-date').val();
            var status = $('#search-status').val();
            var [startDate, endDate] = dateRange.split(" - ");

            if ($('#date-all').is(':checked')) {
                startDate = 'startAll';
                endDate = 'endAll';
            } else {
                startDate = convertDateFormat(startDate);
                endDate = convertDateFormat(endDate);
            }

            $('#myDataTableOutstanding').DataTable().destroy();
            var table = $('#myDataTableOutstanding').dataTable({
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                "ajax": {
                            "url": "debit-status-agoda-receive/"+status+"/"+startDate+"/"+endDate+"",
                            "dataSrc": function(json) {
                                // Access the total field from the response
                                var total = json.total_amount;

                                $('#txt_total_outstanding').text(currencyFormat(total)); // เช่น แสดง total ใน element ที่มี id เป็น total-amount
                                $('#total_outstanding').val(total);

                                return json.data; // ส่งข้อมูล data กลับไปที่ DataTables
                            }
                        },
                // order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'date' },
                    { data: 'batch' },
                    { data: 'check_in' },
                    { data: 'check_out' },
                    { data: 'agoda_outstanding' },
                    { data: 'status' },
                ],
                    
            });  
        }

        $(document).on('click', '.applyBtn', function () {
            status_receive();
        });

        $(document).on('click', '#date-all', function () {
            if ($(this).is(':checked')) {
                $('#search-date').prop('disabled', true);
            } else {
                $('#search-date').prop('disabled', false);
            }
        });

        function convertDateFormat(date) {
            const [day, month, year] = date.split("/");
            return `${year}-${month}-${day}`;
        }

        // Number Format
        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }
    </script>
@endsection

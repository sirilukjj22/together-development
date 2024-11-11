@extends('layouts.masterLayout')

@section('pretitle')
<div class="container">
    <div class="row align-items-center">
        <div class="col">
            <small class="text-muted ">Welcome to Elexa.</small>
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
                    <h6 class="mb-3" style="font-weight: bold;">Elexa Revenue</h6>
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
                            @foreach ($elexa_revenue as $key => $item)
                            @php
                                $date = Carbon\Carbon::parse($item->date);
                            @endphp
                                <tr style="font-weight: bold; color: black;">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{$thaiMonths[$date->format('n')]}} {{$date->format('Y') + 543}}
                                    <td>{{ number_format($item->total_sum, 2) }}</td>
                                    <td>
                                        <a href="{{ route('debit-elexa-revenue', [$date->format("m"), $date->format("Y")]) }}" title="Select" class="btn btn-primary rounded-pill text-white lift">
                                            <i class="fa fa-plus"></i>
                                            Select
                                        </a>
                                    </td>
                                </tr>
                                <?php $total += $item->total_sum; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">Total</td>
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
                                                class="fa fa-circle me-2 text-success"></i>Elexa Paid</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($elexa_debit_outstanding, 2) }}</span>
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
                                                class="fa fa-circle me-2 text-danger"></i>Credit Elexa Revenue Outstanding
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
                                                class="fa fa-circle me-2 text-info"></i>Elexa Revenue</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i
                                                class="fa fa-circle me-2 text-warning"></i>Balance</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format($total_outstanding_all - $total, 2) }}</span>
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
                        <h6 class="fw-bold m-0">Elexa Outstanding Revenue</h6>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                สถานะการรับชำระ
                            </button>
                            <ul class="dropdown-menu border-0 shadow p-3">
                                <li><a class="dropdown-item py-2 rounded" href="#"
                                        onclick="status_receive(1)">Paid</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="#"
                                        onclick="status_receive(0)">Unpaid</a></li>
                            </ul>
                        </div>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif    
                                    </td>
                                </tr>
                                <?php $total += $item->ev_revenue; ?>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">Total</td>
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
            <div class="col-md-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 mb-2">
                        <h6 class="fw-bold m-0">Debit Elexa Outstanding</h6>
                    </div>
                    <table id="myDataTableAll" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_debit = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 1)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                                    <td>
                                        @if ($item->receive_payment == 0)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif    
                                    </td>
                                </tr>
                                <?php $total_debit += $item->ev_revenue; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" style="text-align: right;">Total</td>
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
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif


    <script>

    $(document).ready(function() {
        // Initialize DataTable for Outstanding
        $('#myDataTableOutstanding').dataTable({
            responsive: true,
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

    </script>
@endsection

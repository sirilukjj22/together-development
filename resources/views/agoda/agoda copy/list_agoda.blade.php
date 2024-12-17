{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('debit-agoda-revenue', [$month, $year]) }}">Agoda</a></li>
                    <li class="breadcrumb-item active">Agoda Revenue</li>
                </ol>
                <h1 class="h4 mt-1">Agoda Revsenue</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('debit-agoda-revenue', [$month, $year]) }}" title="ย้อนกลับ" class="btn btn-outline-dark lift">
                    ย้อนกลับ
                </a>
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
                                            <span class="badge bg-danger">Unpaid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
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
                                                        <a href="{{ route('debit-agoda-update-receive', [$item->id, $month, $year]) }}" type="button" class="dropdown-item py-2 rounded">
                                                            เลือกรายการ
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ route('debit-agoda-update-receive', [$item->id, $month, $year]) }}" type="button" class="dropdown-item py-2 rounded">
                                                            แก้ไข
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('debit-agoda-detail', [$item->id, $month, $year]) }}" type="button" class="dropdown-item py-2 rounded">
                                                            รายละเอียด
                                                        </a>
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
@endsection

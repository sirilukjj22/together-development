@extends('layouts.masterLayout')

@section('pretitle')
<div class="container">
    <div class="row align-items-center">
        <div class="col">
            <small class="text-muted ">Welcome to Agoda.</small>
            <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
        </div>

        <div class="col-auto">
            {{-- <a href="{{ route('debit-agoda-update', [$month, $year]) }}" title="ทำรายการ" class="btn btn-info text-white lift">
                <i class="fa fa-plus"></i>
                ทำรายการ
            </a> --}}
            <a href="{{ route('debit-agoda') }}" title="ย้อนกลับ" class="btn btn-outline-dark lift">
                ย้อนกลับ
            </a>
            <a href="#" title="พิมพ์เอกสาร" class="btn btn-outline-dark lift">
                <i class="fa fa-print"></i>
                พิมพ์เอกสาร
            </a>
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
                                            <button class="btn btn-primary rounded-pill text-white dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                @php
                                                    $checkReceiveDate = App\Models\Revenue_credit::getAgodaReceiveDate($item->id);
                                                @endphp
                                                    @if ($checkReceiveDate == 0 || $checkReceiveDate == date('Y-m-d') || Auth::user()->permission == 1 || Auth::user()->permission == 2)
                                                        <li>
                                                            <a href="{{ route('debit-agoda-update-receive', [$item->id, $month, $year]) }}" type="button" class="dropdown-item py-2 rounded">
                                                                แก้ไข
                                                            </a>
                                                        </li>
                                                    @endif
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


    <script>

    </script>
@endsection

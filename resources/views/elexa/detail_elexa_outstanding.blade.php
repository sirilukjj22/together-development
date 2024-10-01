@extends('layouts.masterLayout')

@section('pretitle')
<div class="container">
    <div class="row align-items-center">
        <div class="col">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('debit-elexa-update', [$month, $year]) }}">Elexa Revenue</a></li>
                <li class="breadcrumb-item active">Debit Elexa Revenue</li>
            </ol>
            <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('debit-elexa-update', [$month, $year]) }}" title="Back" class="btn btn-outline-dark lift">
                Back
            </a>
            <a href="#" title="Print" class="btn btn-outline-dark lift">
                <i class="fa fa-print"></i>
                Print
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="container">
        <div class="row clearfix">
            <div class="row g-2 mb-5">
                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="text-muted text-uppercase"><i class="fa fa-circle me-2 text-info"></i>Elexa Revenue</div>
                                        <div class="mt-1">
                                            <span class="fw-bold h4 mb-0" id="">{{ number_format(isset($elexa_revenue) ? $elexa_revenue->amount : 0, 2) }}</span>
                                            {{-- <span class="ms-1">5% <i class="fa fa-caret-up"></i></span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-success"></i> Debit Elexa Outstanding</h6>
                        {{-- <div>
                            <h6 class="fw-bold text-danger m-0">0.00</h6>
                        </div> --}}
                    </div>
                    <table id="myDataTableAll" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_debit = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 1 && $item->sms_revenue == $elexa_revenue->id)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        -
                                    </td>
                                </tr>
                                <?php $total_debit += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td style="text-align: right;">Total</td>
                                <td>
                                    <span id="txt_total_received">{{ number_format($total_debit, 2) }}</span>
                                    <input type="hidden" id="total_received" value="{{ $total_debit }}">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> <!-- .card end -->
            </div>
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <div
                        class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                        <h6 class="fw-bold m-0"><i class="fa fa-circle me-2 text-danger"></i> Elexa Outstanding Revenue</h6>
                    </div>
                    <table id="myDataTableOutstanding" class="exampleTable table display dataTable table-hover fw-bold">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach ($elexa_outstanding as $key => $item)
                                @if ($item->receive_payment == 0)
                                <tr id="tr_row_{{ $item->id }}">
                                    <td>{{ $item->batch }}</td>
                                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                                    <td>
                                        -
                                    </td>
                                </tr>
                                <?php $total += $item->ev_charge; ?>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td style="text-align: right;">Total</td>
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
        </div> <!-- .row end -->
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif
@endsection

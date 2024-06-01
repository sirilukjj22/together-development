{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.masterLayout')



@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="javascript:history.back(1)">Revenue</a></li>
                    <li class="breadcrumb-item active">รายละเอียด</li>
                </ol>
                <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between">
                    <h6 class="card-title mb-0">{{ $title ?? '' }} Revenue</h6>
                    {{-- <a href="#" type="button" class="btn btn-sm btn-primary" title=""><i class="fa fa-plus"></i> เพิ่มข้อมูล</a> --}}
                </div>

                <div class="card-body">
                    <ul class="list-unstyled list mb-0">
                        <li class="d-flex align-items-center py-2">
                            <div class="avatar rounded no-thumbnail chart-text-color3"><i class="fa fa-cc-mastercard"></i></div>
                            <div class="flex-fill ms-3">
                                <div class="h6 mb-0">Cash</div>
                                <small class="text-muted">เงินสด</small>
                            </div>
                            <div class="flex-end">
                                <strong class="text-success">{{ number_format(isset($total_revenue) ? $total_revenue->cash : 0, 2) }}</strong>
                            </div>
                        </li>
                        <li class="d-flex align-items-center py-2">
                            <div class="avatar rounded no-thumbnail chart-text-color3"><i class="fa fa-bank"></i></div>
                            <div class="flex-fill ms-3">
                                <div class="h6 mb-0">Bank Transfer</div>
                                <small class="text-muted">เงินโอนเข้าบัญชี</small>
                            </div>
                            <div class="flex-end">
                                <strong class="text-success">{{ number_format(isset($total_revenue) ? $total_revenue->transfer : 0, 2) }}</strong>
                            </div>
                        </li>
                        <li class="d-flex align-items-center py-2">
                            <div class="avatar rounded no-thumbnail chart-text-color3"><i class="fa fa-credit-card"></i></div>
                            <div class="flex-fill ms-3">
                                <div class="h6 mb-0">Credit Card {{ $title }} Charge</div>
                                <small class="text-muted"></small>
                            </div>
                            <div class="flex-end">
                                <strong class="text-success">{{ number_format(isset($charge) ? $charge[0]['revenue_credit_date'] : 0, 2) }}</strong>
                            </div>
                        </li>
                        <li class="d-flex align-items-center py-2">
                            <div class="avatar rounded no-thumbnail chart-text-color3"><i class="fa fa-credit-card"></i></div>
                            <div class="flex-fill ms-3">
                                <div class="h6 mb-0">Credit Card {{ $title }} Fee</div>
                                <small class="text-muted">ค่าธรรมเนียม</small>
                            </div>
                            <div class="flex-end">
                                <strong class="text-success">{{ number_format(isset($charge) ? $charge[0]['fee_date'] : 0, 2) }}</strong>
                            </div>
                        </li>
                        <li class="d-flex align-items-center py-2">
                            <div class="avatar rounded no-thumbnail chart-text-color3"><i class="fa fa-credit-card"></i></div>
                            <div class="flex-fill ms-3">
                                <div class="h6 mb-0">Credit Card {{ $title }} Revenue</div>
                                <small class="text-muted">รายได้จากบัตรเครดิต</small>
                            </div>
                            <div class="flex-end">
                                <strong class="text-success">{{ number_format(isset($charge) ? $charge[0]['total'] : 0, 2) }}</strong>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- .row end -->
</div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="../assets/bundles/jquerycounterup.bundle.js"></script>
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="../assets/bundles/jquerycounterup.bundle.js"></script>
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @endif

@endsection
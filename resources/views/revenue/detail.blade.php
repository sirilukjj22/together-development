{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

@extends('layouts.test')



@section('pretitle')
@endsection

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .tile {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .tile i {
            font-size: 2rem;
            color: #2D7F7B;
        }

        .tile-title {
            font-size: 1.25rem;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .tile-text {
            color: #6c757d;
        }

        .amount {
            font-size: 1.5rem;
            color: #28a745;
        }

        .breadcrumb {
            background-color: transparent;
        }
    </style>

    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: none;">
                <li class="breadcrumb-item"><a href="javascript:history.back(1)">Revenue</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ $title ?? '' }}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h4 class="mb-4 float-left">{{ $title ?? '' }} Revenue</h4>
            </div>
            <div class="row p-0 m-0">
                <div class="col-md-4 mb-4">
                    <div class="tile">
                        <i class="fas fa-money-bill-wave"></i>
                        <div class="tile-title">Cash</div>
                        <div class="tile-text">เงินสด</div>
                        <div class="amount">{{ number_format(isset($total_revenue) ? $total_revenue->cash : 0, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="tile">
                        <i class="fas fa-university"></i>
                        <div class="tile-title">Bank Transfer</div>
                        <div class="tile-text">เงินโอนเข้าบัญชี</div>
                        <div class="amount">
                            {{ number_format(isset($total_revenue) ? $total_revenue->transfer : 0, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="tile">
                        <i class="fas fa-credit-card"></i>
                        <div class="tile-title">Credit Card Front Desk Charge</div>
                        <div class="tile-text"></div>
                        <div class="amount">
                            {{ number_format(isset($charge) ? $charge[0]['revenue_credit_date'] : 0, 2) }}</div>
                    </div>
                </div>
                {{-- <div class="col-md-4 mb-4">
                    <div class="tile">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <div class="tile-title">Credit Card Front Desk Fee</div>
                        <div class="tile-text">ค่าธรรมเนียม</div>
                        <div class="amount">{{ number_format(isset($charge) ? $charge[0]['fee_date'] : 0, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="tile">
                        <i class="fas fa-chart-line"></i>
                        <div class="tile-title">Credit Card Front Desk Revenue</div>
                        <div class="tile-text">รายได้จากบัตรเครดิต</div>
                        <div class="amount">
                            {{ number_format(isset($charge) ? $charge[0]['total'] : 0, 2) }}</div>
                    </div>
                </div> --}}
            </div>
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

@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted ">Welcome to Revenue.</small>
                <h1 class="h4 mt-1">Revenue</h1>
            </div>

            <div class="col-auto">
                <a href="#" title="Refresh" class="btn btn-outline-dark lift btn-revenue-reload"> 
                    <i class="fa fa-refresh"></i> 
                    Refresh
                </a>

                <a href="{{ route('revenue-export') }}" title="พิมพ์เอกสาร" class="btn btn-outline-dark lift"> 
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
        <div class="row">
            <?php
                if (isset($day)) {
                    $date_current = $year."-".$month."-".$day; 
                } else {
                    $date_current = date('Y-m-d');
                }

                $day_sum = isset($day) ? date('j', strtotime(date('2024-' . $month . '-' . $day))) : date('j');
            ?>

            @if (Auth::user()->permission > 0)
                <?php 
                    $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
                    $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;

                    $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month; 

                    $total_charge_month = $credit_revenue_month->total_credit ?? 0;

                    $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;

                    $total_wp_charge_month = $wp_charge[0]['total_month'];

                    // $monthly_revenue = ($total_cash_bank_month) + ($total_wp_cash_bank_month);

                    $monthly_revenue = ($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) - $agoda_charge[0]['total'];

                ?>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                    <h4 class="fw-bold">Monthly Income</h4>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card border-0 mb-3 bg-success">
                        <div class="card-body p-5 text-light text-center">
                            <h2 class="counter">{{ number_format($monthly_revenue, 2) }}</h2>
                            <span>Monthly Revenue</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card border-0 mb-3 bg-success">
                        <div class="card-body p-5 text-light text-center">
                            <h2 class="counter">{{ number_format(($monthly_revenue) / $day_sum, 2) }}</h2>
                            <span>Daily Average Revenue</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card border-0 mb-3 bg-success">
                        <div class="btn-group position-absolute top-0 end-0">
                            <a href="{{ route('revenue-detail', ['verified', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                        </div>
                        <div class="card-body p-5 text-light text-center">
                            <h2 class="counter">{{ $total_verified ?? 0 }}</h2>
                            <span>Verified</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card border-0 mb-3 bg-warning">
                        <div class="btn-group position-absolute top-0 end-0">
                            <a href="{{ route('revenue-detail', ['unverified', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                        </div>
                        <div class="card-body p-5 text-light text-center">
                            <h2 class="counter">{{ $total_unverified ?? 0 }}</h2>
                            <span>Unverified</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 mt-3">
                <h4 class="fw-bold">Revenue</h4>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-success">
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_day + $credit_revenue->total_credit, 2) }}</h2>
                        <span>Total Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-danger">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['front', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter" id="">{{ number_format($total_revenue_today->front_amount ?? 0, 2) }}</h2>
                        <span>Front Desk Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-danger">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['room', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_revenue_today->room_amount ?? 0, 2) }}</h2>
                        <span>Guest Deposit Revenue</span>
                    </div>
                </div>

            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['fb', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_revenue_today->fb_amount ?? 0, 2) }}</h2>
                        <span>All Outlet Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-warning">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['credit_revenue', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($credit_revenue->total_credit ?? 0, 2) }}</h2>
                        <span>Credit Card Hotel Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['agoda_revenue', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format(isset($total_revenue_today) ? $total_revenue_today->total_credit_agoda : 0, 2) }}</h2>
                        <span>Agoda Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color2">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['wp', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_revenue_today->wp_amount ?? 0, 2) }}</h2>
                        <span>Water Park Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color2">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['wp_credit', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_revenue_today->wp_credit ?? 0, 2) }}</h2>
                        <span>Credit Card Water Park Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color5">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['elexa', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">0.00</h2>
                        <span>Elexa EGAT Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6"></div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6"></div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6"></div>
            <div class="col-12 mt-3 mb-3">
                <h4 class="fw-bold">Detail</h4>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['agoda_outstanding', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($agoda_charge[0]['total'], 2) }}</h2>
                        <span>Daily Credit Agoda Revenue Outstanding</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['ev_outstanding', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($ev_charge[0]['total'], 2) }}</h2>
                        <span>Daily Elexa EGAT Revenue Outstanding</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-danger">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['credit_charge', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <?php 
                           $sum_charge =  $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                        ?>

                        <h2 class="counter" id="">{{ number_format($sum_charge ?? 0, 2) }}</h2>
                        <span>Credit Card Hotel Charge</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-danger">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['credit_fee', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter" id="">{{ number_format($sum_charge == 0 || $credit_revenue->total_credit == 0 ? 0 : $sum_charge - $credit_revenue->total_credit ?? 0, 2) }}</h2>
                        <span>Credit Card Hotel Fee</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['agoda_charge', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</h2>
                        <span>Credit Card Agoda Charge</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['agoda_fee', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($agoda_charge[0]['fee_date'], 2) }}</h2>
                        <span>Credit Card Agoda Fee</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['wp_charge', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($wp_charge[0]['revenue_credit_date'], 2) }}</h2>
                        <span>Credit Card Water Park Charge</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['wp_fee', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($wp_charge[0]['fee_date'], 2) }}</h2>
                        <span>Credit Card Warter Park Fee</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['ev_charge', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</h2>
                        <span>Elexa EGAT Charge</span>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 bg-primary">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['ev_fee', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($ev_charge[0]['fee_date'], 2) }}</h2>
                        <span>Elexa Fee</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color3">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['total_agoda_outstanding', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_agoda_outstanding, 2) }}</h2>
                        <span>Total Credit Agoda Revenue Outstanding</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color3">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['total_ev_outstanding', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_ev_outstanding, 2) }}</h2>
                        <span>Total Elexa EGAT Revenue Outstanding</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6"></div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6"></div>
            <div class="col-12 mt-3 mb-3">
                <h4 class="fw-bold">Type</h4>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color4">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['transfer', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_transfer, 2) }}</h2>
                        <span>Transfer Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color4">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['split_revenue', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_split, 2) }}</h2>
                        <span>Split Credit Card Hotel Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color4">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['no_income_revenue', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_not_type_revenue, 2) }}</h2>
                        <span>No Income Revenue</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color4">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['transfer_transaction', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ $total_transfer2 }}</h2>
                        <span>Transfer Transaction</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color5">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['credit_transaction', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ $total_credit_transaction ?? 0 }}</h2>
                        <span>Credit Card Hotel Transfer Transaction</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color5">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['split_transaction', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ $total_split_transaction }}</h2>
                        <span>Split Credit Card Hotel Transaction</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color4">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['total_transaction', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>

                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ number_format($total_revenue_today->total_transaction ?? 0) }}</h2>
                        <span>Total Transaction</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 mb-3 chart-color5">
                    <div class="btn-group position-absolute top-0 end-0">
                        <a href="{{ route('revenue-detail', ['status', $date_current]) }}" type="button" class="bg-white text-black mx-2 lift m-2 small tag py-2 px-3 border rounded">รายละเอียด</a>
                    </div>
                    <div class="card-body p-5 text-light text-center">
                        <h2 class="counter">{{ $total_not_type ?? 0 }}</h2>
                        <span>No Income Type</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('revenue-search-calendar') }}" method="POST" enctype="multipart/form-data" class="basic-form" id="form-revenue">
            @csrf
            <div class="col-12">
                <div class="card p-4 mb-3">
                    <div class="row g-3">
                        <div class="col-sm-2 col-12">
                            <select class="array-select form-control form-select" aria-label="example" name="day" id="day">
                                <option value="0">ทั้งหมด</option>
                                <?php $day_num = isset($day) ? date('d', strtotime('last day of this month', strtotime(date('2024-' . $month . '-' . $day)))) : date('t'); ?>
                                @for ($i = 1; $i <= $day_num; $i++)
                                    <?php $d = str_pad($i, 2, '0', STR_PAD_LEFT); ?>

                                    @if (!isset($day) && date('d') == $d)
                                        <option value="{{$d}}" selected>{{$i}}</option>
                                    @else
                                        <option value="{{$d}}" {{ isset($day) && $day == $d ? 'selected' : date('d') }}>{{$i}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>

                        <div class="col-sm-2 col-12">
                            <select class="array-select form-control form-select" aria-label="example" name="month" id="month">
                                {{-- <option value="0">ทั้งหมด</option> --}}

                                @if (isset($month))
                                    <option value="01" {{ $month == '01' ? 'selected' : ''}}>มกราคม</option>
                                    <option value="02" {{ $month == '02' ? 'selected' : ''}}>กุมภาพันธ์</option>
                                    <option value="03" {{ $month == '03' ? 'selected' : ''}}>มีนาคม</option>
                                    <option value="04" {{ $month == '04' ? 'selected' : ''}}>เมษายน</option>
                                    <option value="05" {{ $month == '05' ? 'selected' : ''}}>พฤษภาคม</option>
                                    <option value="06" {{ $month == '06' ? 'selected' : ''}}>มิถุนายน</option>
                                    <option value="07" {{ $month == '07' ? 'selected' : ''}}>กรกฎาคม</option>
                                    <option value="08" {{ $month == '08' ? 'selected' : ''}}>สิงหาคม</option>
                                    <option value="09" {{ $month == '09' ? 'selected' : ''}}>กันยายน</option>
                                    <option value="10" {{ $month == '10' ? 'selected' : ''}}>ตุลาคม</option>
                                    <option value="11" {{ $month == '11' ? 'selected' : ''}}>พฤศจิกายน</option>
                                    <option value="12" {{ $month == '12' ? 'selected' : ''}}>ธันวาคม</option>
                                @else

                                    <option value="01" {{ date('m') == '01' ? 'selected' : ''}}>มกราคม</option>
                                    <option value="02" {{ date('m') == '02' ? 'selected' : ''}}>กุมภาพันธ์</option>
                                    <option value="03" {{ date('m') == '03' ? 'selected' : ''}}>มีนาคม</option>
                                    <option value="04" {{ date('m') == '04' ? 'selected' : ''}}>เมษายน</option>
                                    <option value="05" {{ date('m') == '05' ? 'selected' : ''}}>พฤษภาคม</option>
                                    <option value="06" {{ date('m') == '06' ? 'selected' : ''}}>มิถุนายน</option>
                                    <option value="07" {{ date('m') == '07' ? 'selected' : ''}}>กรกฎาคม</option>
                                    <option value="08" {{ date('m') == '08' ? 'selected' : ''}}>สิงหาคม</option>
                                    <option value="09" {{ date('m') == '09' ? 'selected' : ''}}>กันยายน</option>
                                    <option value="10" {{ date('m') == '10' ? 'selected' : ''}}>ตุลาคม</option>
                                    <option value="11" {{ date('m') == '11' ? 'selected' : ''}}>พฤศจิกายน</option>
                                    <option value="12" {{ date('m') == '12' ? 'selected' : ''}}>ธันวาคม</option>
                                @endif

                            </select>

                        </div>

                        <div class="col-sm-1 col-12">
                            <select class="array-select form-control form-select" aria-label="example" name="year" id="year">
                                @if (isset($year))
                                    <option value="2024" {{ $year == '2024' ? 'selected' : ''}}>2024</option>
                                @else
                                    <option value="2024" {{ date('Y') == '2024' ? 'selected' : ''}}>2024</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-1 col-12 text-lg-end" style="float: left;">
                            <button type="button" class="btn btn-md btn-primary btn-submit-search">Search</button>
                        </div>
                    </div> <!-- Row end  -->
                </div>
            </div>
        </form>

        <div class="col-md-12">
            @if (session("success"))
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                <hr>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
            @endif
            <div class="card p-4 mb-4">
                <div class="card-header py-3 d-flex justify-content-between bg-transparent border-bottom-0">
                    <div>
                        @if ($total_revenue_today->status == 1)

                            <span class="fw-bold">สถานะ : <span class="text-danger">ตรวจสอบเรียบร้อยแล้ว</span></span>

                        @endif
                    </div>

                    <div>
                        <?php $date = date('Y-m-d'); ?>
                        <div class="text-lg-end" style="float: left;">
                            <button type="button" class="btn btn-md btn-primary" onclick="Add_data('{{$date}}')" 
                            data-bs-toggle="modal" data-bs-target="#AddDataModalCenter" <?php echo $total_revenue_today->status == 1 ? 'disabled' : '' ?>><i class="fa fa-plus"></i> เพิ่มข้อมูล</button>

                            @if (Auth::user()->permission > 0)
                                @if ($total_revenue_today->status == 0)
                                    <button type="button" class="btn btn-md btn-warning btn-close-daily" value="1"><i class="icon-lock"></i> Lock</button>
                                @else
                                    <button type="button" class="btn btn-md btn-warning btn-open-daily" value="0"><i class="fa fa-unlock"></i> Unlock</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <table class="table align-middle table-bordered table-hover table_wrapper" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center" style="width: 450px;">Description</th>
                            <th class="text-center" style="width: 300px;">Today</th>
                            <th class="text-center" style="width: 300px;">M-T-D</th>
                            <th class="text-center" style="width: 300px;">Y-T-D</th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">Hotel</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>Front Desk Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Cash</td>
                            <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</td>
                            <td>{{ number_format(isset($total_front_revenue) ? $total_front_month->front_cash : 0, 2 ) }}</td>
                            <td>{{ number_format(isset($total_front_revenue) ? $total_front_year->front_cash : 0, 2 ) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Bank Transfer</td>
                            <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</td>
                            <td>{{ number_format(isset($total_front_month) ? $total_front_month->front_transfer : 0, 2  ) }}</td>
                            <td>{{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2  ) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Credit Card Front Desk Charge</td>
                            <td>{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
                            <td>{{ number_format($front_charge[0]['revenue_credit_month'], 2) }}</td>
                            <td>{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>Guest Deposit Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Cash</td>
                            <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</td>
                            <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_cash : 0, 2 ) }}</td>
                            <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2 ) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Bank Transfer</td>
                            <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</td>
                            <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_transfer : 0, 2  ) }}</td>
                            <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2  ) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Credit Card Guest Deposit Charge</td>
                            <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
                            <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_month'], 2) }}</td>
                            <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>All Outlet Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Cash</td>
                            <td>{{ number_format($total_fb_revenue->fb_cash, 2) }}</td>
                            <td>{{ number_format($total_fb_month->fb_cash, 2) }}</td>
                            <td>{{ number_format($total_fb_year->fb_cash, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Bank Transfer</td>
                            <td>{{ number_format($total_fb_revenue->fb_transfer, 2) }}</td>
                            <td>{{ number_format($total_fb_month->fb_transfer, 2) }}</td>
                            <td>{{ number_format($total_fb_year->fb_transfer, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Credit card All Outlet Charge</td>
                            <td>{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</td>
                            <td>{{ number_format($fb_charge[0]['revenue_credit_month'], 2) }}</td>
                            <td>{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>

                        {{-- ////////////////////////////////////////////////////////////// --}}

                        <?php 

                            $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
                            $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
                            $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;

                            $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer;
                            $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;
                            $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer;

                            $total_cash_bank = $total_cash + $total_bank_transfer;
                            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
                            $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;
                        ?>

                        <tr>
                            <td style="text-align: right" colspan="2"><b>Total Cash</b></td>
                            <td>{{ number_format($total_cash, 2) }}</td>
                            <td>{{ number_format($total_cash_month, 2) }}</td>
                            <td>{{ number_format($total_cash_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right" colspan="2"><b>Total Bank Transfer</b></td>
                            <td>{{ number_format($total_bank_transfer, 2) }}</td>
                            <td>{{ number_format($total_bank_transfer_month, 2) }}</td>
                            <td>{{ number_format($total_bank_transfer_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right" colspan="2"><b>Cash And Bank Transfer Hotel Revenue</b></td>
                            <td>{{ number_format($total_cash_bank, 2) }}</td>
                            <td>{{ number_format($total_cash_bank_month, 2) }}</td>
                            <td>{{ number_format($total_cash_bank_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="background-color: rgb(206, 234, 235);" colspan="5"></td>
                        </tr>
                        <tr>
                            <?php
                                $total_credit_card_revenue = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                                $total_credit_card_revenue_month = $front_charge[0]['revenue_credit_month'] + $guest_deposit_charge[0]['revenue_credit_month'] + $fb_charge[0]['revenue_credit_month'];
                                $total_credit_card_revenue_year = $front_charge[0]['revenue_credit_year'] + $guest_deposit_charge[0]['revenue_credit_year'] + $fb_charge[0]['revenue_credit_year'];
                            ?>

                            <td style="text-align: right" colspan="2"><b>Total Credit Card Charge</b></td>
                            <td>{{ number_format($total_credit_card_revenue, 2) }}</td>
                            <td>{{ number_format($total_credit_card_revenue_month, 2) }}</td>
                            <td>{{ number_format($total_credit_card_revenue_year, 2) }}</td>
                        </tr>

                        <tr>
                            <td style="text-align: right" colspan="2"><b>Credit Card Fee</b></td>
                            <td>{{ number_format($total_credit_card_revenue == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue->total_credit ?? 0, 2) }}</td>
                            <td>{{ number_format($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0, 2) }}</td>
                            <td>{{ number_format($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <?php
                               $total_charge = $credit_revenue->total_credit ?? 0;
                               $total_charge_month = $credit_revenue_month->total_credit ?? 0;
                               $total_charge_year = $credit_revenue_year->total_credit ?? 0;
                            ?>

                            <td style="text-align: right" colspan="2"><b>Credit Card Hotel Revenue</b></td>
                            <td>{{ number_format($credit_revenue->total_credit ?? 0, 2) }}</td>
                            <td>{{ number_format($credit_revenue_month->total_credit ?? 0, 2) }}</td>
                            <td>{{ number_format($credit_revenue_year->total_credit ?? 0, 2) }}</td>
                        </tr>

                        {{-- ////////////////////////////////////////////////////////////// --}}

                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>Agoda Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Credit Card Agoda Charge</td>
                            <td>{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['revenue_credit_month'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Agoda Fee</td>
                            <td>{{ number_format($agoda_charge[0]['fee_date'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['fee_month'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Credit Agoda Revenue Outstanding</td>
                            <td>{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['total_month'], 2) }}</td>
                            <td>{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(250, 211, 178);" colspan="2"><b>Total Hotel Revenue</b></td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_cash_bank + $total_charge + $agoda_charge[0]['total'], 2) }}</td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_cash_bank_month + $total_charge_month + $agoda_charge[0]['total_month'], 2) }}</td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_cash_bank_year + $total_charge_year + $agoda_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>Water Park Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Cash</td>
                            <td>{{ number_format($total_wp_revenue->wp_cash, 2) }}</td>
                            <td>{{ number_format($total_wp_month->wp_cash, 2) }}</td>
                            <td>{{ number_format($total_wp_year->wp_cash, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Bank Transfer</td>
                            <td>{{ number_format($total_wp_revenue->wp_transfer, 2) }}</td>
                            <td>{{ number_format($total_wp_month->wp_transfer, 2) }}</td>
                            <td>{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
                        </tr>
                        <tr>
                            <?php 
                                $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
                                $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
                                $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;    
                            ?>

                            <td style="text-align: right" colspan="2"><b>Cash + Bank Transfer Water Park Revenue</b></td>
                            <td>{{ number_format($total_wp_cash_bank, 2) }}</td>
                            <td>{{ number_format($total_wp_cash_bank_month, 2) }}</td>
                            <td>{{ number_format($total_wp_cash_bank_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="background-color: rgb(206, 234, 235);" colspan="5"></td>
                        </tr>
                        <tr>
                            <?php
                                $total_wp_credit_card_revenue = $wp_charge[0]['revenue_credit_date'];
                                $total_wp_credit_card_revenue_month = $wp_charge[0]['revenue_credit_month'];
                                $total_wp_credit_card_revenue_year = $wp_charge[0]['revenue_credit_year'];
                            ?>

                            <td style="text-align: right" colspan="2"><b>Credit Card Water Park Charge</b></td>
                            <td>{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
                            <td>{{ number_format($total_wp_credit_card_revenue_month, 2) }}</td>
                            <td>{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right" colspan="2"><b>Credit Card Fee</b></td>
                            <td>{{ number_format($wp_charge[0]['fee_date'], 2) }}</td>
                            <td>{{ number_format($wp_charge[0]['fee_month'], 2) }}</td>
                            <td>{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <?php 
                               $total_wp_charge = $wp_charge[0]['total'];
                               $total_wp_charge_month = $wp_charge[0]['total_month'];
                               $total_wp_charge_year = $wp_charge[0]['total_year'];
                            ?>

                            <td style="text-align: right" colspan="2"><b>Credit Card Water Park Revenue</b></td>
                            <td>{{ number_format($total_wp_charge, 2) }}</td>
                            <td>{{ number_format($total_wp_charge_month, 2) }}</td>
                            <td>{{ number_format($total_wp_charge_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="background-color: rgb(206, 234, 235);" colspan="5"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(250, 211, 178);" colspan="2"><b>Total Water Park Revenue</b></td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_wp_cash_bank + $total_wp_charge, 2) }}</td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_wp_cash_bank_month + $total_wp_charge_month, 2) }}</td>
                            <td style="background-color: rgb(250, 211, 178);">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="background-color: rgb(206, 234, 235);"><b>Elexa EGAT Revenue</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">EV Charging Charge</td>
                            <td>{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['revenue_credit_month'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Elexa Fee</td>
                            <td>{{ number_format($ev_charge[0]['fee_date'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['fee_month'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Elexa EGAT Revenue Outstanding</td>
                            <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;" colspan="2" class="fw-bold">Total Elexa EGAT Revenue</td>
                            <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                            <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td style="background-color: rgb(206, 234, 235);" colspan="5"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Total Hotel, Water Park And Elexa EGAT Revenue</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $agoda_charge[0]['total'] + $ev_charge[0]['total'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $agoda_charge[0]['total_month'] + $ev_charge[0]['total_month'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $agoda_charge[0]['total_year'] + $ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Credit Agoda Revenue Outstanding</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($agoda_charge[0]['total_month'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($agoda_charge[0]['total_year'] - $total_agoda_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Elexa EGAT Revenue Outstanding</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($ev_charge[0]['total'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Agoda Revenue</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($total_agoda_revenue, 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($total_agoda_month, 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format($total_agoda_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Elexa EGAT Revenue</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(0, 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(0, 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(0, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; background-color: rgb(242, 243, 181);" colspan="2"><b>Total Revenue</b></td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $ev_charge[0]['total'] + $total_agoda_revenue, 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month + $total_agoda_month) - $agoda_charge[0]['total_month'], 2) }}</td>
                            <td style="background-color: rgb(242, 243, 181);">{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year + $total_agoda_year) - $agoda_charge[0]['total_year'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- .card end -->
        </div>
    </div> <!-- .row end -->
</div>

<!-- Add Modal Center-->
<div class="modal fade" id="AddDataModalCenter" tabindex="-1" aria-labelledby="AddDataModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddDataModalCenterTitle">เพิ่มข้อมูลเงินสด / เครดิต</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data" class="basic-form form-store">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="col-12">
                        <div class="card-body row">
                            <div class="col-sm-6 col-12 mb-3">
                                <label for="cash" class="form-label">วันที่</label>
                                <input class="form-control" type="date" id="date" name="date" value="<?php echo isset($day) ? date($year.'-'.$month.'-'.$day) : date('Y-m-d') ?>">
                            </div>
                                <div class="col-md-12 col-12">
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading1">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                                        <span class="fw-bold">Front Desk Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-6 col-12">
                                                        <label for="cash" class="form-label">Cash</label>
                                                        <input class="form-control" type="text" id="front_cash" name="front_cash" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <label for="credit" class="form-label">Bank Transfer</label>
                                                        <input class="form-control" type="text" id="front_transfer" name="front_transfer" placeholder="0.00" disabled>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="front_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="front_revenue_type">
                                                            <option value="6">Front Desk Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Credit Card Room Charge</label>
                                                        <input type="text" class="form-control" id="front_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-front-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-front-hide mt-2" onclick="toggleHide3()">ลบทั้งหมด</button>
                                                        <span class="front-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTablefrontCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Batch</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>Credit Card Room Charge</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="front-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="front_number" value="0">
                                                    <input type="hidden" id="front_list_num" name="front_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 1  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading2">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2">
                                                        <span class="fw-bold">Guest Deposit Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-6 col-12">
                                                        <label for="cash" class="form-label">Cash</label>
                                                        <input class="form-control" type="text" id="cash" name="cash" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <label for="credit" class="form-label">Bank Transfer</label>
                                                        <input class="form-control" type="text" id="room_transfer" name="room_transfer" placeholder="0.00" disabled>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="guest_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="guest_revenue_type">
                                                            <option value="1">Guest Deposit Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Credit Card Room Charge</label>
                                                        <input type="text" class="form-control" id="guest_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-guest-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-guest-hide mt-2" onclick="toggleHide4()">ลบทั้งหมด</button>
                                                        <span class="guest-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTableguestCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Batch</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>Credit Card Room Charge</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="guest-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="guest_number" value="0">
                                                    <input type="hidden" id="guest_list_num" name="guest_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 2  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading3">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="true" aria-controls="faq3">
                                                        <span class="fw-bold">All Outlet Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="true" aria-controls="faq3"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-6 col-12">
                                                        <label for="fb_cash" class="form-label">Cash</label>
                                                        <input class="form-control" type="text" id="fb_cash" name="fb_cash" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <label for="credit" class="form-label">Bank Transfer</label>
                                                        <input class="form-control" type="text" id="fb_transfer" name="fb_transfer" placeholder="0.00" disabled>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="fb_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="fb_revenue_type">
                                                            <option value="2">All Outlet Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Credit Card Room Charge</label>
                                                        <input type="text" class="form-control" id="fb_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-fb-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-fb-hide mt-2" onclick="toggleHide5()">ลบทั้งหมด</button>
                                                        <span class="fb-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTablefbCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Batch</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>Credit Card Room Charge</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="fb-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="fb_number" value="0">
                                                    <input type="hidden" id="fb_list_num" name="fb_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 3  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading4">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="true" aria-controls="faq4">
                                                        <span class="fw-bold">Agoda Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="true" aria-controls="faq4"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-12 col-12 mt-2">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">Booking Number</label>
                                                        <input type="text" class="form-control" id="agoda_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="agoda_revenue_type">
                                                            <option value="1">Guest Deposit Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">Check in date</label>
                                                        <input type="date" class="form-control" id="check_in" name="check_in">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-2">
                                                        <label class="form-label">Check out date</label>
                                                        <input type="date" class="form-control" id="check_out" name="check_out">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-2">
                                                        <label class="form-label">Credit Card Agoda Charge</label>
                                                        <input type="text" class="form-control" id="agoda_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-2">
                                                        <label class="form-label">Revenue Outstanding</label>
                                                        <input type="text" class="form-control" id="agoda_credit_outstanding" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-agoda-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-agoda-hide mt-2" onclick="toggleHide2()">ลบทั้งหมด</button>
                                                        <span class="agoda-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTableAgodaCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Booking Number</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>Check in date</th>
                                                                        <th>Check out date</th>
                                                                        <th>Credit card Agoda Charge</th>
                                                                        <th>Credit Agoda Revenue Outstanding</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="agoda-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="agoda_number" value="0">
                                                    <input type="hidden" id="agoda_list_num" name="agoda_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 4  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading5">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="true" aria-controls="faq5">
                                                        <span class="fw-bold">Water Park Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="true" aria-controls="faq5"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq5" class="collapse" aria-labelledby="heading5" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-6 col-12">
                                                        <label for="wp_cash" class="form-label">Cash</label>
                                                        <input class="form-control" type="text" id="wp_cash" name="wp_cash" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <label for="credit" class="form-label">Bank Transfer</label>
                                                        <input class="form-control" type="text" id="wp_transfer" name="wp_transfer" placeholder="0.00" disabled>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="wp_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="wp_revenue_type">
                                                            <option value="3">Water Park Revenue</option>
                                                            <option value="7">Credit Card Water Park Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Credit Card Water Park Charge</label>
                                                        <input type="text" class="form-control" id="wp_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-wp-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-wp-hide mt-2" onclick="toggleHide6()">ลบทั้งหมด</button>
                                                        <span class="wp-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTablewpCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Batch</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>Credit Card Water Park Charge</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="wp-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="wp_number" value="0">
                                                    <input type="hidden" id="wp_list_num" name="wp_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 5  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading6">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="true" aria-controls="faq6">
                                                        <span class="fw-bold">Elexa EGAT Revenue</span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="true" aria-controls="faq6"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq6" class="collapse" aria-labelledby="heading6" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    {{-- <div class="col-sm-6 col-12">
                                                        <label for="ev_cash" class="form-label">Cash</label>
                                                        <input class="form-control" type="text" id="ev_cash" name="ev_cash" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <label for="ev_transfer" class="form-label">Bank Transfer</label>
                                                        <input class="form-control" type="text" id="ev_transfer" name="ev_transfer" placeholder="0.00" disabled>
                                                    </div> --}}
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <label for="" class="form-label"><b>Credit Card</b></label>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="ev_batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="ev_revenue_type">
                                                            <option value="8">Elexa EGAT Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-3">
                                                        <label class="form-label">EV Charging Charge</label>
                                                        <input type="text" class="form-control" id="ev_credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-4 col-12 mt-2">
                                                        <label class="form-label">Elexa EGAT Revenue Outstanding</label>
                                                        <input type="text" class="form-control" id="ev_credit_outstanding" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-ev-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-ev-hide mt-2" onclick="toggleHide6()">ลบทั้งหมด</button>
                                                        <span class="ev-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTableEvCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Bacth</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>EV Charging Charge</th>
                                                                        <th>Elexa EGAT Revenue Outstanding</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="ev-todo-list">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="ev_number" value="0">
                                                    <input type="hidden" id="ev_list_num" name="ev_list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 6  -->
                                        <div class="card border-0">
                                            <div class="card-body row align-items-center" id="heading7">
                                                <div class="col">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="true" aria-controls="faq7">
                                                        <span class="fw-bold">Credit Revenue <span class="text-danger" id="credit_card"> (ยอดเครดิต 0.00)</span></span>
                                                    </h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="true" aria-controls="faq7"> ย่อ/ขยาย </a>
                                                </div>
                                            </div>
                                            <div id="faq7" class="collapse" aria-labelledby="heading7" data-parent="#accordionExample">
                                                <div class="card-body row">
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">Batch</label>
                                                        <input type="text" class="form-control" id="batch" name="">
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">ประเภทรายได้</label>
                                                        <select class="array-select form-control form-select" aria-label="example" name="" id="revenue_type">
                                                            <option value="">เลือกประเภทรายได้</option>
                                                            <option value="6">Front Desk Revenue</option>
                                                            <option value="1">Guest Deposit Revenue</option>
                                                            <option value="2">All Outlet Revenue</option>
                                                            <option value="4">Credit Card Revenue</option>
                                                            <option value="5">Credit Card Agoda Revenue</option>
                                                            <option value="3">Water Park Revenue</option>
                                                            <option value="7">Credit Card Water Park Revenue</option>
                                                            <option value="8">Elexa EGAT Revenue</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <label class="form-label">ยอดเงิน</label>
                                                        <input type="text" class="form-control" id="credit_amount" name="" placeholder="0.00">
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <button type="button" class="btn btn-sm btn-success btn-todo-add mt-2">เพิ่ม</button>
                                                        <button type="button" class="btn btn-sm btn-secondary btn-todo-hide mt-2" onclick="toggleHide()">ลบทั้งหมด</button>
                                                        <span class="todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                        <span class="stock-error text-danger small ms-3"style="display: none;">ระบบไม่สามารถทำรายการได้ เนื่องจากมีจำนวนสินค้าไม่เพียงพอ !</span>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="table-responsive">
                                                            <table id="myTableCredit" class="table align-middle table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Batch</th>
                                                                        <th>ประเภทรายได้</th>
                                                                        <th>ยอดเงิน</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="todo-list">
                        
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="number" value="0">
                                                    <input type="hidden" id="list_num" name="list_num" value="0">
                                                </div>
                                            </div>
                                            <div class="border-bottom"></div>
                                        </div> <!-- .card - FAQ 7  -->
                                </div>
                        </div>
                    </div><!-- Form Validation -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="revenue_store()">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>


<style> 
    .table_wrapper{
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
</style>

@if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../assets/bundles/jquerycounterup.bundle.js"></script>
    <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    {{-- <script src="../assets/bundles/dataTables.bundle.js"></script> --}}
@else
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../assets/bundles/jquerycounterup.bundle.js"></script>
    <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    {{-- <script src="../assets/bundles/dataTables.bundle.js"></script> --}}
@endif

<script>
    jQuery(document).ready(function($) {
        $('.counter').counterUp({ delay: 20, time: 1500 });
    });

    $('#date').on('change', function () {
        Add_data($(this).val());
    });

    function Add_data($date) {
        var date = $('#date').val();
        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('revenue-edit/"+date+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(response) {
                $('#front_cash').val(response.data.front_cash);
                $('#front_transfer').val(currencyFormat(response.data.front_transfer));
                $('#front_credit').val(response.data.front_credit);
                $('#cash').val(response.data.room_cash);
                $('#room_transfer').val(currencyFormat(response.data.room_transfer));
                $('#credit').val(response.data.room_credit);
                $('#fb_cash').val(response.data.fb_cash);
                $('#fb_transfer').val(currencyFormat(response.data.fb_transfer));
                $('#fb_credit').val(response.data.fb_credit);
                $('#wp_cash').val(response.data.wp_cash);
                $('#wp_transfer').val(currencyFormat(response.data.wp_transfer));
                $('#wp_credit').val(response.data.wp_credit);
                // $('#ev_cash').val(response.data.ev_cash);
                // $('#ev_transfer').val(currencyFormat(response.data.ev_transfer));
                // $('#ev_credit').val(response.data.ev_credit);

                $('#credit_card').text("(ยอดเครดิต "+currencyFormat(response.data.total_credit)+")");
                $('.todo-list tr').remove();
                $('.guest-todo-list tr').remove();
                $('.fb-todo-list tr').remove();
                $('.wp-todo-list tr').remove();
                $('.agoda-todo-list tr').remove();
                $('.front-todo-list tr').remove();
                $('.ev-todo-list tr').remove();

                jQuery.each(response.data_credit, function(key, value) {
                    var type_name = "";
                    switch (value.revenue_type) {
                        case 1: type_name = "Guest Deposit Revenue"; break;
                        case 2: type_name = "All Outlet Revenue"; break;
                        case 3: type_name = "Water Park Revenue"; break;
                        case 4: type_name = "Credit Card Revenue"; break;
                        case 5: type_name = "Credit Card Agoda Revenue"; break;
                        case 6: type_name = "Front Desk Revenue"; break;
                        case 7: type_name = "Credit Card Water Park Revenue"; break;
                        case 8: type_name = "Elexa EGAT Revenue"; break;
                    }

                    var date_check_in = "";
                    var date_check_out = "";

                    if (value.agoda_check_in) {
                        var agoda_check_in = new Date(value.agoda_check_in);
                        var year = agoda_check_in.getFullYear();
                        var month = (1 + agoda_check_in.getMonth()).toString().padStart(2, '0');
                        var day = agoda_check_in.getDate().toString().padStart(2, '0');

                        date_check_in = day+'/'+month+'/'+year;

                        var agoda_check_out = new Date(value.agoda_check_out);
                        var year_out = agoda_check_out.getFullYear();
                        var month_out = (1 + agoda_check_out.getMonth()).toString().padStart(2, '0');
                        var day_out = agoda_check_out.getDate().toString().padStart(2, '0');

                        date_check_out = day_out+'/'+month_out+'/'+year_out;

                    }

                    if (value.status == 1) {
                        $('.guest-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose4(this)"></i></td>' +
                                '<input type="hidden" name="guest_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="guest_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="guest_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 2) {
                        $('.fb-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose5(this)"></i></td>' +
                                '<input type="hidden" name="fb_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="fb_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="fb_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 3) {
                        $('.wp-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose6(this)"></i></td>' +
                                '<input type="hidden" name="wp_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="wp_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="wp_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 4) {
                        $('.todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose(this)"></i></td>' +
                                '<input type="hidden" name="batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 5) {
                        $('.agoda-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + date_check_in + '</td>' +
                                '<td style="text-align: right;">' + date_check_out + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.agoda_charge)) + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.agoda_outstanding)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose2(this)"></i></td>' +
                                '<input type="hidden" name="agoda_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="agoda_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="agoda_check_in[]" value="' + value.agoda_check_in + '">' +
                                '<input type="hidden" name="agoda_check_out[]" value="' + value.agoda_check_out + '">' +
                                '<input type="hidden" name="agoda_credit_amount[]" value="' + value.agoda_charge + '">' +
                                '<input type="hidden" name="agoda_credit_outstanding[]" value="' + value.agoda_outstanding + '">' +
                            '</tr>'
                        );

                    } if (value.status == 6) {
                        $('.front-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose3(this)"></i></td>' +
                                '<input type="hidden" name="front_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="front_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="front_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 8) {
                        $('.ev-todo-list').append(
                            '<tr>' +
                                '<td>' + value.batch +'</td>' +
                                '<td>' + type_name + '</td>' +
                                // '<td style="text-align: right;">' + date_check_in + '</td>' +
                                // '<td style="text-align: right;">' + date_check_out + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.ev_charge)) + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.ev_outstanding)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose8(this)"></i></td>' +
                                '<input type="hidden" name="ev_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="ev_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="ev_check_in[]" value="' + value.ev_check_in + '">' +
                                '<input type="hidden" name="ev_check_out[]" value="' + value.ev_check_out + '">' +
                                '<input type="hidden" name="ev_credit_amount[]" value="' + value.ev_charge + '">' +
                                '<input type="hidden" name="ev_credit_outstanding[]" value="' + value.ev_outstanding + '">' +
                            '</tr>'
                        );
                    }
                });
            },

        });
    }

    // Number Format
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

    $('.btn-todo-add').on('click', function() {
        var batch = $('#batch').val();
        var type = $('#revenue_type').val();
        var amount = $('#credit_amount').val();
        var list = parseInt($('#list_num').val());
        var number = parseInt($('#number').val()) + 1;
        $('#number').val(number);

        if (batch && type && amount) {
            var type_name = "";
            switch (type) {
                case "1": type_name = "Guest Deposit Revenue"; break;
                case "2": type_name = "All Outlet Revenue"; break;
                case "3": type_name = "Water Park Revenue"; break;
                case "4": type_name = "Credit Card Revenue"; break;
                case "5": type_name = "Credit Card Agoda Revenue"; break;
                case "6": type_name = "Front Desk Revenue"; break;
                case "7": type_name = "Credit Card Water Park Revenue"; break;
                case "8": type_name = "Elexa EGAT Revenue"; break;
            }

            $('.todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose(this)"></i></td>' +
                    '<input type="hidden" name="batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="credit_amount[]" value="' + amount + '">' +
                '</tr>'
            );

            var batch = $('#batch').val('');
            var type = $('#revenue_type').val('');
            var amount = $('#credit_amount').val('');

            $('.todo-error').hide();

        } else {
            $('.todo-error').show();
        }
    });

    $('.btn-agoda-add').on('click', function() {
        var batch = $('#agoda_batch').val();
        var type = $('#agoda_revenue_type').val();
        var check_in = $('#check_in').val();
        var check_out = $('#check_out').val();
        var amount = $('#agoda_credit_amount').val();
        var outstanding = $('#agoda_credit_outstanding').val();
        var list = parseInt($('#agoda_list_num').val());
        var number = parseInt($('#agoda_number').val()) + 1;
        $('#agoda_number').val(number);

        if (batch && type && amount && outstanding && check_in && check_out) {

            var type_name = "";

            switch (type) {
                case "1": type_name = "Guest Deposit Revenue"; break;
            }

            var date_check_in = "";
            var date_check_out = "";

            if (check_in) {
                var agoda_check_in = new Date(check_in);
                var year = agoda_check_in.getFullYear();
                var month = (1 + agoda_check_in.getMonth()).toString().padStart(2, '0');
                var day = agoda_check_in.getDate().toString().padStart(2, '0');
                date_check_in = day+'/'+month+'/'+year;

                var agoda_check_out = new Date(check_out);
                var year_out = agoda_check_out.getFullYear();
                var month_out = (1 + agoda_check_out.getMonth()).toString().padStart(2, '0');
                var day_out = agoda_check_out.getDate().toString().padStart(2, '0');
                date_check_out = day_out+'/'+month_out+'/'+year_out;
            }

            $('.agoda-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + date_check_in + '</td>' +
                    '<td style="text-align: right;">' + date_check_out + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(outstanding)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose2(this)"></i></td>' +
                    '<input type="hidden" name="agoda_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="agoda_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="agoda_check_in[]" value="' + check_in + '">' +
                    '<input type="hidden" name="agoda_check_out[]" value="' + check_out + '">' +
                    '<input type="hidden" name="agoda_credit_amount[]" value="' + amount + '">' +
                    '<input type="hidden" name="agoda_credit_outstanding[]" value="' + outstanding + '">' +
                '</tr>'
            );

            var batch = $('#agoda_batch').val('');
            var type = $('#agoda_revenue_type').val('');
            var check_in = $('#check_in').val('');
            var check_out = $('#check_out').val('');
            var amount = $('#agoda_credit_amount').val('');
            var outstanding = $('#agoda_credit_outstanding').val('');

            $('.agoda-todo-error').hide();

        } else {
            $('.agoda-todo-error').show();
        }
    });

    // ##################################################################### //

    $('.btn-front-add').on('click', function() {
        var batch = $('#front_batch').val();
        var type = $('#front_revenue_type').val();
        var amount = $('#front_credit_amount').val();
        var list = parseInt($('#front_list_num').val());
        var number = parseInt($('#front_number').val()) + 1;
        $('#front_number').val(number);

        if (batch && type && amount) {

            var type_name = "";
            switch (type) {
                case "6": type_name = "Front Desk Revenue"; break;
            }

            $('.front-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose3(this)"></i></td>' +
                    '<input type="hidden" name="front_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="front_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="front_credit_amount[]" value="' + amount + '">' +
                '</tr>'
            );

            var batch = $('#front_batch').val('');
            var type = $('#front_revenue_type').val('');
            var amount = $('#front_credit_amount').val('');

            $('.front-todo-error').hide();
        } else {
            $('.front-todo-error').show();
        }
    });

    // ##################################################################### //

    $('.btn-guest-add').on('click', function() {
        var batch = $('#guest_batch').val();
        var type = $('#guest_revenue_type').val();
        var amount = $('#guest_credit_amount').val();
        var list = parseInt($('#guest_list_num').val());
        var number = parseInt($('#guest_number').val()) + 1;
        $('#guest_number').val(number);

        if (batch && type && amount) {

            var type_name = "";
            switch (type) {
                case "1": type_name = "Guest Deposit Revenue"; break;
            }

            $('.guest-todo-list').append(
                '<tr>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose4(this)"></i></td>' +
                    '<input type="hidden" name="guest_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="guest_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="guest_credit_amount[]" value="' + amount + '">' +
                '</tr>'
            );

            var batch = $('#guest_batch').val('');
            var type = $('#guest_revenue_type').val(1);
            var amount = $('#guest_credit_amount').val('');

            $('.guest-todo-error').hide();
        } else {
            $('.guest-todo-error').show();
        }

    });

    // ##################################################################### //

    $('.btn-fb-add').on('click', function() {
        var batch = $('#fb_batch').val();
        var type = $('#fb_revenue_type').val();
        var amount = $('#fb_credit_amount').val();
        var list = parseInt($('#fb_list_num').val());
        var number = parseInt($('#fb_number').val()) + 1;
        $('#fb_number').val(number);

        if (batch && type && amount) {

            var type_name = "";
            switch (type) {
                case "2": type_name = "All Outlet Revenue"; break;
            }

            $('.fb-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose5(this)"></i></td>' +
                    '<input type="hidden" name="fb_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="fb_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="fb_credit_amount[]" value="' + amount + '">' +
                '</tr>'
            );

            var batch = $('#fb_batch').val('');
            var type = $('#fb_revenue_type').val('');
            var amount = $('#fb_credit_amount').val('');

            $('.fb-todo-error').hide();
        } else {
            $('.fb-todo-error').show();
        }
    });

    // ##################################################################### //

    $('.btn-wp-add').on('click', function() {
        var batch = $('#wp_batch').val();
        var type = $('#wp_revenue_type').val();
        var amount = $('#wp_credit_amount').val();
        var list = parseInt($('#wp_list_num').val());
        var number = parseInt($('#wp_number').val()) + 1;
        $('#wp_number').val(number);

        if (batch && type && amount) {

            var type_name = "";
            switch (type) {
                case "3": type_name = "Water Park Revenue"; break;
                case "7": type_name = "Credit Card Water Park Revenue"; break;
            }

            $('.wp-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose6(this)"></i></td>' +
                    '<input type="hidden" name="wp_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="wp_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="wp_credit_amount[]" value="' + amount + '">' +
                '</tr>'
            );

            var batch = $('#wp_batch').val('');
            var type = $('#wp_revenue_type').val('');
            var amount = $('#wp_credit_amount').val('');
            $('.wp-todo-error').hide();
        } else {
            $('.wp-todo-error').show();
        }
    });

    // ##################################################################### //

    $('.btn-ev-add').on('click', function() {
        var batch = $('#ev_batch').val();
        var type = $('#ev_revenue_type').val();
        // var check_in = $('#check_in').val();
        // var check_out = $('#check_out').val();
        var amount = $('#ev_credit_amount').val();
        var outstanding = $('#ev_credit_outstanding').val();
        var list = parseInt($('#ev_list_num').val());
        var number = parseInt($('#ev_number').val()) + 1;
        $('#ev_number').val(number);

        if (batch && type && amount && outstanding) {

            var type_name = "";
            switch (type) {
                case "8": type_name = "Elexa EGAT Revenue"; break;
            }

            $('.ev-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    // '<td style="text-align: right;">' + date_check_in + '</td>' +
                    // '<td style="text-align: right;">' + date_check_out + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(outstanding)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose8(this)"></i></td>' +
                    '<input type="hidden" name="ev_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="ev_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="ev_credit_amount[]" value="' + amount + '">' +
                    '<input type="hidden" name="ev_credit_outstanding[]" value="' + outstanding + '">' +
                '</tr>'
            );

            var batch = $('#ev_batch').val('');
            var type = $('#ev_revenue_type').val('');
            var amount = $('#ev_credit_amount').val('');
            var outstanding = $('#ev_credit_outstanding').val('');
            $('.ev-todo-error').hide();
        } else {
            $('.ev-todo-error').show();
        }
    });

    $('.todo-list .close').on('click', function() { toggleClose(this); });

    $('.agoda-todo-list .close').on('click', function() { toggleClose2(this); });

    $('.front-todo-list .close').on('click', function() { toggleClose3(this); });

    $('.guest-todo-list .close').on('click', function() { toggleClose4(this); });

    $('.fb-todo-list .close').on('click', function() { toggleClose5(this); });

    $('.wp-todo-list .close').on('click', function() { toggleClose6(this); });

    $('.ev-todo-list .close').on('click', function() { toggleClose8(this); });

    function toggleClose(ele) {
        // $(ele).parent().parent().toggle();
        $(ele).parent().parent().remove();
    }

    function toggleClose2(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleClose3(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleClose4(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleClose5(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleClose6(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleClose8(ele) {
        $(ele).parent().parent().remove();
    }

    function toggleHide() {
        $('.todo-list tr').remove();
    }

    function toggleHide2() {
        $('.agoda-todo-list tr').remove();
    }

    function toggleHide3() {
        $('.front-todo-list tr').remove();
    }

    function toggleHide4() {
        $('.guest-todo-list tr').remove();
    }

    function toggleHide5() {
        $('.fb-todo-list tr').remove();
    }

    function toggleHide6() {
        $('.wp-todo-list tr').remove();
    }

    function toggleHide8() {
        $('.wp-todo-list tr').remove();
    }

    $('.btn-submit-search').on('click', function () {
        $('#form-revenue').submit();
    });

    // Sweetalert2
    $('.btn-close-daily').on('click', function () {
        Swal.fire({
        icon: "info",
        title: 'คุณต้องการปิดยอดใช่หรือไม่?',
        text: 'หากปิดยอดแล้ว ไม่สามารถเพิ่มข้อมูลได้ !',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) { 
            var date = $('#year').val()+"-"+$('#month').val()+"-"+$('#day').val();
            $.ajax({
                url: "{!! url('revenue-daily-close') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {date: date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success(response) {
                    Swal.fire('เรียบร้อย!', '', 'success');
                    location.reload();
                },
                error(response) {
                    Swal.fire('ไม่สำเร็จ', '', 'info');
                    location.reload();
                }
            });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
                location.reload();
            }
        })
    });

    $('.btn-open-daily').on('click', function () {
        Swal.fire({
        icon: "info",
        title: 'คุณต้องการแก้ไขยอดใช่หรือไม่?',
        // text: 'หากปิดยอดแล้ว ไม่สามารถเพิ่มข้อมูลได้ !',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var date = $('#year').val()+"-"+$('#month').val()+"-"+$('#day').val();
            var token = $('#token_csrf').val();

            $.ajax({
                url: "{!! url('revenue-daily-open') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {date: date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success(response) {
                    Swal.fire('เรียบร้อย!', '', 'success');
                    location.reload();
                },
                error(response) {
                    Swal.fire('ไม่สำเร็จ', '', 'info');
                    location.reload();
                }
            });

            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
                location.reload();
            }
        })
    });

    function revenue_store() {
        jQuery.ajax({
            url:    "{!! route('revenue-store') !!}",
            type: 'POST',
            dataType: "json",
            cache: false,
            data: $('.form-store').serialize(),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                location.reload();
            },
        });
    }

    $('.btn-revenue-reload').on('click', function () {
        location.reload();
    });
</script>
@endsection
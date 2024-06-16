@extends('layouts.test')

@section('content')

    <div class="container-fluid pt-3 pb-3 mb-3 rounded bg-light" style="width: 98%;">

        <style>
            .logo img {
              height: auto;
            }
    
            img {
              display: block;
              margin: auto;
              width: 40px;
              height: 40px;
              object-fit: cover;
            }
    
            .row {
              margin-bottom: 10px;
            }
    
            #myChart {
              width: 260px !important;
              height: 260px !important;
              display: block;
              margin: auto;
            }
    
            .select2 {
              width: 100% !important;
              margin: 0 !important;
            }
    
            .select2-container .select2-selection--single {
              height: 40px !important;
              margin-top: 0 !important;
            }
    
            .select2-selection__arrow {
              height: 0px !important;
            }
    
            .select2-selection__rendered {
              line-height: 20px !important;
            }

            .tr-color-orange td {
                background-color: rgb(255, 224, 194);
            }

            .tr-color-orange th {
                background-color: rgb(255, 224, 194);
            }

            .tr-color-blue td {
                background-color: rgb(186, 229, 255);
            }

            .tr-color-blue th {
                background-color: rgb(186, 229, 255);
            }
</style>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

        <?php
            if (isset($day)) {
                $date_current = $year."-".$month."-".$day; 
            } else {
                $date_current = date('Y-m-d');
            }

            $day_sum = isset($day) ? date('j', strtotime(date('2024-' . $month . '-' . $day))) : date('j');
        ?>

        <?php 
            $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
            $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;

            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month; 

            $total_charge_month = $credit_revenue_month->total_credit ?? 0;

            $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;

            $total_wp_charge_month = $wp_charge[0]['total_month'];

            $monthly_revenue = ($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) - $agoda_charge[0]['total'];

            $sum_charge =  $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
        ?>

        <?php 
            $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
            $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
            $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;

            $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer;
            $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;
            $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer;

            $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
            $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
            $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;    

            $total_cash_bank = $total_cash + $total_bank_transfer;
            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
            $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;

            $total_today_revenue_graph = $total_day + ($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_amount ?? 0);
        ?>

        <div class="row g-2">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div style="background-color: white; height:auto; border-radius: 8px !important;">
                    <div class="donut-graph">
                        <canvas id="myChart"></canvas>
                        <div class="percent" style="text-align: left; width:auto; display: block; margin-left: 30px;">
                            <h6 style=" float: left; width: 60%;"><i style="color: deepskyblue; margin-right: 10px;"
                                    class="fa-solid fa-square"></i>CASH</h6>
                            <h6>: {{ number_format($total_today_revenue_graph == 0 ? 0 : (($total_cash + $total_wp_revenue->wp_cash) / $total_today_revenue_graph * 100), 2) }}%</h6>
                            <h6 style="float: left;width: 60%;"><i style="color: hotpink; margin-right: 10px;"
                                    class="fa-solid fa-square"></i>Bank Transfer</h6>
                            <h6>: {{ number_format($total_today_revenue_graph == 0 ? 0 : (($total_bank_transfer + $total_wp_revenue->wp_transfer) / $total_today_revenue_graph * 100), 2) }}%</h6>
                            <h6 style="float: left;width: 60%;"><i style="color: orange; margin-right: 10px;"
                                    class="fa-solid fa-square"></i>Credit Card</h6>
                            <h6>: {{ number_format($total_today_revenue_graph == 0 ? 0 : ((($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_amount ?? 0)) / $total_today_revenue_graph * 100), 2) }}%</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!-- CASH -->

                <input type="hidden" id="total_revenue_dashboard" value="{{ number_format($total_today_revenue_graph, 2) }}">

                <div class="title-box">
                    <h2>Cash</h2>
                    <h1>{{ number_format($total_cash + $total_wp_revenue->wp_cash, 2) }}</h1>
                    <input type="hidden" id="total_cash_dashboard" value="{{ $total_cash + $total_wp_revenue->wp_cash }}">
                </div>

                <div class="d-flex align-content-stratch flex-wrap cash"
                    style=" height:330px; border-radius: 8px !important;">
                    <a href="{{ route('revenue-detail', ['front', $date_current]) }}" class="list-box">
                        <img src="../assets2/../assets2/images/front.png" alt="">
                        <h2>Front Desk</h2>
                        <h3>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['guest', $date_current]) }}" class="list-box">
                        <img src="../assets2/../assets2/images/guest.png" alt="">
                        <h2>Guest Deposit</h2>
                        <h3>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['all_outlet', $date_current]) }}" class="list-box">
                        <img src="../assets2/../assets2/images/F&B.png" alt="">
                        <h2>All Outlet </h2>
                        <h3>{{ number_format($total_fb_revenue->fb_cash, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['wp', $date_current]) }}" class="list-box">
                        <img src="../assets2/../assets2/images/water-park.png" alt="">
                        <h2>Water Park</h2>
                        <h3>{{ number_format($total_wp_revenue->wp_cash, 2) }}</h3>
                    </a>
                </div>
            </div>


            <div class="col-lg-3 col-md-6 col-sm-12 ">
                <!-- BANK TRANSFER -->
                <div class="title-box">
                    <h2>Bank Transfer</h2>
                    <h1>{{ number_format($total_bank_transfer + $total_wp_revenue->wp_transfer, 2) }}</h1>
                    <input type="hidden" id="total_bank_dashboard" value="{{ ($total_bank_transfer + $total_wp_revenue->wp_transfer) }}">
                </div>
                <div class="d-flex align-content-stretch flex-wrap bank"
                    style=" height: 330px; border-radius: 8px !important;">
                    <a href="{{ route('revenue-detail', ['front', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/front.png" alt="">
                        <h2>Front Desk</h2>
                        <h3>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['guest', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/guest.png" alt="">
                        <h2>Guest Deposit</h2>
                        <h3>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['all_outlet', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/F&B.png" alt="">
                        <h2>All Outlet</h2>
                        <h3>{{ number_format($total_fb_revenue->fb_transfer, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['wp', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/water-park.png" alt="">
                        <h2>Water Park</h2>
                        <h3>{{ number_format($total_wp_revenue->wp_transfer, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['agoda_revenue', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/agoda.png" alt="">
                        <h2>Agoda</h2>
                        <h3>{{ number_format($total_agoda_revenue, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['elexa', $date_current]) }}" class="list-box3">
                        <img src="../assets2/images/elexa.png" alt="">
                        <h2>Elexa EGAT</h2>
                        <h3>{{ number_format($total_ev_revenue, 2) }}</h3>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <!-- Credit -->
                <div class="title-box">
                    <h2>Credit Card</h2>
                    <h1>{{ number_format(($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_credit ?? 0), 2) }}</h1>
                    <input type="hidden" id="total_credit_dashboard" value="{{ ($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_credit ?? 0) }}">
                </div>

                <div class="d-flex align-content-stretch flex-wrap creditrevenue">
                    <a href="{{ route('revenue-detail', ['credit_revenue', $date_current]) }}" class="list-box2">
                        <img src="../assets2/images/hotel.png" alt="">
                        <h2>Hotel</h2>
                        <h3>{{ number_format($credit_revenue->total_credit ?? 0, 2) }}</h3>
                    </a>


                    <a href="{{ route('revenue-detail', ['wp_credit', $date_current]) }}" class="list-box2">
                        <img src="../assets2/images/water-park.png" alt="">
                        <h2>Water park</h2>
                        <h3>{{ number_format($total_revenue_today->wp_credit ?? 0, 2) }}</h3>
                    </a>

                </div>
            </div>
        </div>


        <!-- Manual Charge -->


        <div class="row g-2">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="title-box2">
                    <h1>Manual Charge</h1>
                </div>
                <div class="d-flex align-content-stretch flex-wrap manual"
                    style=" height: 292px; border-radius: 8px !important;">


                    <a href="{{ route('revenue-detail', ['front', $date_current]) }}}" class="list-box4">
                        <img src="../assets2/images/front.png" alt="">
                        <h2>Credit Card Front Desk</h2>
                        <h3>{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['room', $date_current]) }}" class="list-box4">
                        <img src="../assets2/images/guest.png" alt="">
                        <h2>Credit Card Guest Deposit</h2>
                        <h3>{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['fb', $date_current]) }}" class="list-box4">
                        <img src="../assets2/images/F&B.png" alt="">
                        <h2>Credit Card All Outlet</h2>
                        <h3>{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['wp', $date_current]) }}" class="list-box4">
                        <img src="../assets2/images/water-park.png" alt="">
                        <h2>Credit Card Water Park</h2>
                        <h3>{{ number_format($wp_charge[0]['total'], 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['agoda_charge', $date_current]) }}" class="list-box4">
                        <img src="../assets2/images/agoda.png" alt="">
                        <h2>Agoda</h2>
                        <h3>{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['elexa', $date_current]) }}" class="list-box4">
                        <img src="../assets2/images/elexa.png" alt="">
                        <h2>Elaxa EGAT</h2>
                        <h3>{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</h3>
                    </a>

                </div>
            </div>


            <!-- Fee -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="title-box2">
                    <h1>Fee</h1>
                </div>
                <div class="d-flex align-content-stretch flex-wrap fee"
                    style=" height: 292px; border-radius: 8px !important;">
                    <a href="{{ route('revenue-detail', ['credit_fee', $date_current]) }}" class="list-box5">
                        <img src="../assets2/images/hotel.png" alt="">
                        <h2>Credit Card Hotel Fee</h2>
                        <h3>{{ number_format($sum_charge == 0 || $credit_revenue->total_credit == 0 ? 0 : $sum_charge - $credit_revenue->total_credit ?? 0, 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['wp_fee', $date_current]) }}" class="list-box5">
                        <img src="../assets2/images/water-park.png" alt="">
                        <h2>Credit Card Water Park Fee</h2>
                        <h3>{{ number_format($wp_charge[0]['fee_date'], 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['agoda_fee', $date_current]) }}" class="list-box5">
                        <img src="../assets2/images/agoda.png" alt="">
                        <h2>Agoda Fee</h2>
                        <h3>{{ number_format($agoda_charge[0]['fee_date'], 2) }}</h3>
                    </a>
                    <a href="{{ route('revenue-detail', ['ev_fee', $date_current]) }}" class="list-box5">
                        <img src="../assets2/images/elexa.png" alt="">
                        <h2>Elaxa EGAT Fee</h2>
                        <h3>{{ number_format($ev_charge[0]['fee_date'], 2) }}</h3>
                    </a>
                </div>
            </div>





            <!-- Total Revenue Outstanding -->

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="title-box2">
                    <h1>Total Revenue Outstanding</h1>
                </div>
                <div class="d-flex align-content-stretch flex-wrap trorevenue">
                    <a href="{{ route('revenue-detail', ['total_agoda_outstanding', $date_current]) }}" class="list-box6">
                        <img src="../assets2/images/agoda.png" alt="">
                        <h2>Credit Card Agoda Revenue Outstanding</h2>
                        <h3>{{ number_format($total_agoda_outstanding, 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['total_ev_outstanding', $date_current]) }}" class="list-box6">
                        <img src="../assets2/images/elexa.png" alt="">
                        <h2>Elaxa EGAT Revenue Outstanding</h2>
                        <h3>{{ number_format($total_ev_outstanding, 2) }}</h3>
                    </a>

                </div>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="title-box2">
                    <h1>Type</h1>
                </div>
                <div class="d-flex align-content-stretch flex-wrap type"
                    style=" height: auto; border-radius: 8px !important;">
                    <a href="{{ route('revenue-detail', ['transfer', $date_current]) }}" class="list-box7">
                        <h2>Transfer Revenue</h2>
                        <h3>{{ number_format($total_transfer, 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['credit_transaction', $date_current]) }}" class="list-box7">
                        <h2>Credit Card Hotel <br>
                            Transfer Transaction</h2>
                        <h3>{{ $total_credit_transaction ?? 0 }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['split_revenue', $date_current]) }}" class="list-box7">
                        <h2>Split Credit Card Hotel Revenue</h2>
                        <h3>{{ number_format($total_split, 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['split_revenue', $date_current]) }}" class="list-box7">
                        <h2>Split Credit Card Hotel Transaction</h2>
                        <h3>{{ number_format($total_split) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['no_income_revenue', $date_current]) }}" class="list-box7">
                        <h2>No Income Revenue</h2>
                        <h3>{{ number_format($total_not_type_revenue, 2) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['total_transaction', $date_current]) }}" class="list-box7">
                        <h2>Total Transaction</h2>
                        <h3>{{ number_format($total_revenue_today->total_transaction ?? 0) }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['transfer_transaction', $date_current]) }}" class="list-box7">
                        <h2>Tranfer Transaction</h2>
                        <h3>{{ $total_transfer2 }}</h3>
                    </a>

                    <a href="{{ route('revenue-detail', ['status', $date_current]) }}" class="list-box7">
                        <h2>No incoming Type</h2>
                        <h3>{{ $total_not_type ?? 0 }}</h3>
                    </a>
                </div>
            </div>

            @if (Auth::user()->permission > 0)
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="title-box2">
                        <h1>Monthly Revenue</h1>
                    </div>
                    <div class="d-flex align-content-stretch flex-wrap monthly"
                        style="background-color: white; height: auto; border-radius: 8px !important;">
                        <a href="#" class="list-box8">
                            <h3>{{ number_format($monthly_revenue, 2) }} / Month</h3>
                        </a>
                    </div>
                    <div class="title-box2">
                        <h1>Daily Avg. Revenue</h1>
                    </div>
                    <div class="d-flex align-content-stretch flex-wrap daily"
                        style="background-color: white; height: auto; border-radius: 8px !important;">
                        <a href="#" class="list-box8">
                            <h3>{{ number_format(($monthly_revenue) / $day_sum, 2) }} / Day</h3>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="title-box2">
                      <h1>Verified</h1>
                    </div>
                    <div class="d-flex align-content-stretch flex-wrap monthly"
                      style="background-color: white; height: auto; border-radius: 8px !important;">
                      <a href="{{ route('revenue-detail', ['verified', $date_current]) }}" class="list-box8">
                        <h3>{{ $total_verified ?? 0 }}</h3>
                      </a>
                    </div>
                    <div class="title-box2">
                      <h1>Unverified</h1>
                    </div>
                    <div class="d-flex align-content-stretch flex-wrap daily"
                      style="background-color: white; height: auto; border-radius: 8px !important;">
                      <a href="{{ route('revenue-detail', ['unverified', $date_current]) }}" class="list-box8">
                        <h3>{{ $total_unverified ?? 0 }}</h3>
                      </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (session("success"))
        <div class="container p-0 rounded">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">บันทึกสำเร็จ</h4>
                <i class="fa-regular fa-circle-check">&nbsp;</i>{{ session('success') }}
            </div>
        </div>
    @endif

    <div class="container-fluid pt-3 pb-3 rounded bg-light" style="width: 98%;">
        <form action="{{ route('revenue-search-calendar') }}" method="POST" enctype="multipart/form-data" class="" id="form-revenue">
            @csrf
            <div class="row">
                {{-- <div class="col-lg-8 col-md-2 col-sm-2 mb-2"> --}}
                    <div class="col-lg-2 col-md-12 col-sm-12 mb-2 px-1">
                        <select class="form-select w-100 float-left" name="day" id="day">
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
                    <div class="col-lg-2 col-md-12 col-sm-12 mb-2 px-1">
                        <select style="width: 100%;" class="form-select w-100 float-left" name="month" id="month">
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
                    <div class="col-lg-2 col-md-12 col-sm-12 mb-2 px-1">
                        <select style="width: 100%;" class="form-select w-100 float-left" name="year" id="year">
                            @if (isset($year))
                                <option value="2024" {{ $year == '2024' ? 'selected' : ''}}>2024</option>
                            @else
                                <option value="2024" {{ date('Y') == '2024' ? 'selected' : ''}}>2024</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-12 col-sm-12 mb-2 px-1">
                    <button class="btn btn-success w-100 btn-submit-search w-100" style="background-color: #109699;" type="button"
                        role="button">ค้นหา</button>
                    </div>
                {{-- </div> --}}

                <div class="col-lg-5 col-md-12 col-sm-12">
                    <?php $date = date('Y-m-d'); ?>
                    @if (Auth::user()->permission > 0)
                        @if ($total_revenue_today->status == 0)
                        <button type="button" class="btn btn-warning float-end btn-close-daily ml-1" value="1">
                            <i class="fa-solid fa-lock">&nbsp;</i>LOCK
                        </button>
                    @else
                        <button type="button" class="btn btn-warning float-end btn-open-daily ml-1" value="0">
                            <i class="fa-solid fa-lock">&nbsp;</i>UNLOCK
                        </button>
                        @endif
                     @endif
                    <button type="button" class="btn btn-primary border-0 float-end" onclick="Add_data('{{$date}}')" style="background-color: #109699;"
                        data-bs-toggle="modal" data-bs-target="#AddDataModalCenter" <?php echo $total_revenue_today->status == 1 ? 'disabled' : '' ?>>
                        เพิ่มข้อมูลเงินสด / เครดิต
                    </button>
                </div>

                @if ($total_revenue_today->status == 1)
                    <div class="row mt-3 mb-0">
                        <div class="col-12">
                        <h5 class="float-start mr-1">สถานะ : </h5>
                        <h5 class="text-danger"> ตรวจสอบเรียบร้อยแล้ว</h5>
                        </div>
                    </div>
                @endif

            </div>
        </form>
        <!-- Reset to Default Table -->
        <style>
            /* CSS Reset for tables */
            table {
              border-collapse: collapse;
              border-spacing: 0;
              width: 100%;
              max-width: 100%;
              margin: 0;
              padding: 0;
              table-layout: auto;
            }
    
            th,
            td {
              padding: 0;
              margin: 0;
              border: none;
            }
    
            /* Custom styles */
            .table-responsive {
              overflow-x: auto;
            }
    
            table {
              border: 1px solid #ddd;
            }
    
            table caption {
              font-size: 16px;
            }
    
            table thead {
              border: initial;
              clip: initial;
              height: auto;
              margin: initial;
              overflow: visible;
              padding: initial;
              position: static;
              width: auto;
            }
    
            table tr {
              border-bottom: initial;
              display: table-row;
              margin-bottom: initial;
            }
    
            table th {
              font-weight: 600;
              text-transform: capitalize;
              color: white !important;
            }
    
            table th,
            table td {
              border-bottom: 1px solid #ddd;
              display: table-cell;
              font-size: 16px;
              text-align: left;
              padding: 8px;
              color: black !important;
              letter-spacing: unset;
            }
    
            table td::before {
              content: none;
            }
    
            table td:last-child {
              border-bottom: 1px solid #ddd;
            }
    
    
            .modal-body label {
              font-size: 16px;
              text-align: left;
            }
    
            .modal-body input {
              width: 100%;
              text-align: left;
              padding: 8px;
              border: 1px solid #ccc;
              margin: 0px;
              margin-bottom: 5px;
            }
    
            .accordion {
              margin-bottom: 1%;
            }
    
            @media (max-width: 768px) {
    
              table th,
              table td {
                font-size: 14px;
                padding: 6px;
              }
            }
    
            @media (max-width: 480px) {
    
              table th,
              table td {
                font-size: 12px;
                padding: 4px;
              }
            }
          </style>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-bordered">
                        <th class="text-center " colspan="2" scope="col">Description</th>
                        <th scope="col">Today</th>
                        <th scope="col">M-T-D</th>
                        <th scope="col">Y-T-D</th>
                    </tr>

                    <th class="text-center" colspan="2">
                        Hotel
                    </th>
                    <th colspan="5"></th>
                </thead>
                <tbody>
                    <tr> <!--Front Desk table-->
                        <th class="text-light" style="background-color: #109699;" colspan="8">Front Desk Revenue</th>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>
                        <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_front_month) ? $total_front_month->front_cash : 0, 2 ) }}</td>
                        <td>{{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2 ) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>
                        <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_front_month) ? $total_front_month->front_transfer : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Credit Card Front Desk Charge</td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr> <!--Guest Deposit Revenue-->
                        <th class="text-light" style="background-color: #109699; color: white;" colspan="8">
                            Guest Deposit Revenue
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>
                        <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_cash : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>
                        <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_transfer : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Credit Card Front Desk Charge</td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>

                    <tr> <!--All Outlet Revenue-->
                        <th class="text-light" style="background-color: #109699; color: white;" colspan="8">
                            All Outlet Revenue
                        </th>
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
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Cash</th>
                        <td>{{ number_format($total_cash, 2) }}</td>
                        <td>{{ number_format($total_cash_month, 2) }}</td>
                        <td>{{ number_format($total_cash_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Bank Transfer</th>
                        <td>{{ number_format($total_bank_transfer, 2) }}</td>
                        <td>{{ number_format($total_bank_transfer_month, 2) }}</td>
                        <td>{{ number_format($total_bank_transfer_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Cash And Bank Transfer Hotel Revenue</th>
                        <td>{{ number_format($total_cash_bank, 2) }}</td>
                        <td>{{ number_format($total_cash_bank_month, 2) }}</td>
                        <td>{{ number_format($total_cash_bank_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="background-color: #109699; color: white;"></td> <!-- ช่องเปล่า-->
                    </tr>
                    <tr>
                        <?php
                            $total_credit_card_revenue = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                            $total_credit_card_revenue_month = $front_charge[0]['revenue_credit_month'] + $guest_deposit_charge[0]['revenue_credit_month'] + $fb_charge[0]['revenue_credit_month'];
                            $total_credit_card_revenue_year = $front_charge[0]['revenue_credit_year'] + $guest_deposit_charge[0]['revenue_credit_year'] + $fb_charge[0]['revenue_credit_year'];
                        ?>

                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Credit Card Charge</th>
                        <td>{{ number_format($total_credit_card_revenue, 2) }}</td>
                        <td>{{ number_format($total_credit_card_revenue_month, 2) }}</td>
                        <td>{{ number_format($total_credit_card_revenue_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Credit Card Fee</th>
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

                        <th colspan="2" style="text-align: right; padding-right: 1%;">Credit Card Hotel Revenue</th>
                        <td>{{ number_format($credit_revenue->total_credit ?? 0, 2) }}</td>
                        <td>{{ number_format($credit_revenue_month->total_credit ?? 0, 2) }}</td>
                        <td>{{ number_format($credit_revenue_year->total_credit ?? 0, 2) }}</td>
                    </tr>

                    <tr> <!--Agoda-->
                        <th class="text-light" style="background-color: #109699; color: white;" colspan="8">
                            Agoda Revenue</th>
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
                        <td colspan="8" style="background-color: #109699; color: white;"></td> <!-- ช่องเปล่า-->
                    </tr>
                    <tr class="tr-color-orange">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Hotel Revenue</th>
                        <td>{{ number_format($total_cash_bank + $total_charge + $agoda_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($total_cash_bank_month + $total_charge_month + $agoda_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($total_cash_bank_year + $total_charge_year + $agoda_charge[0]['total_year'], 2) }}</td>
                    </tr>

                    <tr> <!--Water Park-->
                        <th class="text-light" style="background-color: #109699; color: white;" colspan="8">
                            Water Park Revenue
                        </th>
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
                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Cash + Bank Transfer Water Park Revenue
                        </th>
                        <td>{{ number_format($total_wp_cash_bank, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_month, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="background-color: #109699; color: white;"></td> <!-- ช่องเปล่า-->
                    </tr>
                    <tr>
                        <?php
                            $total_wp_credit_card_revenue = $wp_charge[0]['revenue_credit_date'];
                            $total_wp_credit_card_revenue_month = $wp_charge[0]['revenue_credit_month'];
                            $total_wp_credit_card_revenue_year = $wp_charge[0]['revenue_credit_year'];
                        ?>

                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Credit Card Water Park Charge
                        </th>
                        <td>{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
                        <td>{{ number_format($total_wp_credit_card_revenue_month, 2) }}</td>
                        <td>{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Credit Card Fee</th>
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
                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Credit Card Water Park Revenue
                        </th>
                        <td>{{ number_format($total_wp_charge, 2) }}</td>
                        <td>{{ number_format($total_wp_charge_month, 2) }}</td>
                        <td>{{ number_format($total_wp_charge_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="background-color: #109699; color: white;"></td> <!-- ช่องเปล่า-->
                    </tr>
                    <tr class="tr-color-orange">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Water Park Revenue</th>
                        <td>{{ number_format($total_wp_cash_bank + $total_wp_charge, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_month + $total_wp_charge_month, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                    </tr>
                    <tr> <!--Elexa EGAT Revenue-->
                        <th class="text-light" style="background-color: #109699; color: white;" colspan="8">
                            Elexa EGAT Revenue
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">EV Chargeing Charge</td>
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
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Elexa EGAT Revenue</th>
                        <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="background-color: #109699; color: white;"></td> <!-- ช่องเปล่า-->
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Total Hotel, Water Park And Elexa EGAT Revenue
                        </th>
                        <td>{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $agoda_charge[0]['total'] + $ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $agoda_charge[0]['total_month'] + $ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $agoda_charge[0]['total_year'] + $ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">
                            Credit Agoda Revenue Outstanding
                        </th>
                        <td>{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_year'] - $total_agoda_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Elexa EGAT Revenue Outstanding
                        </th>
                        <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Agoda Revenue</th>
                        <td>{{ number_format($total_agoda_revenue, 2) }}</td>
                        <td>{{ number_format($total_agoda_month, 2) }}</td>
                        <td>{{ number_format($total_agoda_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Elexa EGAT Revenue</th>
                        <td>{{ number_format($total_ev_revenue, 2) }}</td>
                        <td>{{ number_format($total_ev_month, 2) }}</td>
                        <td>{{ number_format($total_ev_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <th colspan="2" style="text-align: right; padding-right: 1%;">Total Revenue</th>
                        <td>{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $total_ev_revenue + $total_agoda_revenue, 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month + $total_agoda_month + $total_ev_month) - $agoda_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year + $total_agoda_year + $total_ev_year) - $agoda_charge[0]['total_year'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="AddDataModalCenter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-label="Close">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มข้อมูลเงินสด / เครดิต</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="POST" enctype="multipart/form-data" class="form-store">
                            @csrf

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="">วันที่</label>
                                    <input type="date" id="date" name="date" value="<?php echo isset($day) ? date($year.'-'.$month.'-'.$day) : date('Y-m-d') ?>">
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Front Desk Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Cash</label>
                                                    <input type="text" style="border: 1px solid #ccc;" id="front_cash" name="front_cash" placeholder="0.00">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Bank Transfer</label>
                                                    <input type="text" style="border: 1px solid #ccc;" id="front_transfer" name="front_transfer" placeholder="0.00" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-left">
                                                    <h5 class="m-0">Credit Card</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Batch</label>
                                                    <input type="text" style="border: 1px solid #ccc;" id="front_batch" name="">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">ประเภทรายได้<label>
                                                        <select class="form-select" id="front_revenue_type">
                                                            <option value="6">Front Desk Revenue</option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Credit Card Room Charge</label>
                                                    <input type="text" style="border: 1px solid #ccc;" id="front_credit_amount" name="" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" type="button" class="btn btn-primary border-0 btn-front-add"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-front-hide" onclick="toggleHide3()">ลบทั้งหมด</button>
                                                    <span class="front-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTablefrontCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">Credit Card Room Charge</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="front-todo-list">
                                                        
                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="front_number" value="0">
                                                <input type="hidden" id="front_list_num" name="front_list_num" value="0">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo"> <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="true"
                                            aria-controls="collapseTwo">
                                            <!--ใส่ ID ให้ตรง -->
                                            Guest Deposit Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Cash</label>
                                                    <input type="text" id="cash" name="cash" placeholder="0.00" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Bank Transfer</label>
                                                    <input type="text" id="room_transfer" name="room_transfer" placeholder="0.00" style="border: 1px solid #ccc;" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-left">
                                                    <h5 class="m-0">Credit Card</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Batch</label>
                                                    <input type="text" id="guest_batch" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">ประเภทรายได้<label>
                                                        <select class="form-select" id="guest_revenue_type">
                                                            <option value="1">Guest Deposit Revenue</option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Credit Card Room Charge</label>
                                                    <input type="text" id="guest_credit_amount" name="" placeholder="0.00" style="border: 1px solid #ccc;">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-guest-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-guest-hide">ลบทั้งหมด</button>
                                                    <span class="guest-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTableguestCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">Credit Card Room Charge</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="guest-todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="guest_number" value="0">
                                                <input type="hidden" id="guest_list_num" name="guest_list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="true"
                                            aria-controls="collapseThree">
                                            <!--ใส่ ID ให้ตรง -->
                                            All Outlet Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Cash</label>
                                                    <input type="text" id="fb_cash" name="fb_cash" placeholder="0.00" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Bank Transfer</label>
                                                    <input type="text" id="fb_transfer" name="fb_transfer" placeholder="0.00" style="border: 1px solid #ccc;" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-left">
                                                    <h5 class="m-0">Credit Card</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Batch</label>
                                                    <input type="text" id="fb_batch" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">ประเภทรายได้<label>
                                                    <select class="form-select" id="fb_revenue_type">
                                                        <option value="2">All Outlet Revenue</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label for="">Credit Card Room Charge</label>
                                                    <input type="text" id="fb_credit_amount" placeholder="0.00" style="border: 1px solid #ccc;">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-fb-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-fb-hide">ลบทั้งหมด</button>
                                                    <span class="fb-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTablefbCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">Credit Card Room Charge</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fb-todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="fb_number" value="0">
                                                <input type="hidden" id="fb_list_num" name="fb_list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="true"
                                            aria-controls="collapseFour">
                                            <!--ใส่ ID ให้ตรง -->
                                            Agoda Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label for="">Booking Number</label>
                                                    <input type="text" id="agoda_batch" style="border: 1px solid #ccc;">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="">ประเภทรายได้</label>
                                                    <select class="form-select" id="agoda_revenue_type">
                                                        <option value="1">Guest Deposit Revenue</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label class="">Check in date</label>
                                                    <input type="date" class="" id="check_in" name="check_in">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label class="">Check out date</label>
                                                    <input type="date" class="" id="check_out" name="check_out">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label class="">Credit Card Agoda Charge</label>
                                                    <input type="text" class="" id="agoda_credit_amount" name="" placeholder="0.00">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label class="">Revenue Outstanding</label>
                                                    <input type="text" class="" id="agoda_credit_outstanding" name="" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-agoda-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-agoda-hide">ลบทั้งหมด</button>
                                                    <span class="agoda-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTableAgodaCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Booking Number</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">Check in date</th>
                                                            <th scope="col">Check out date</th>
                                                            <th scope="col">Credit card Agoda Charge</th>
                                                            <th scope="col">Credit Agoda Revenue Outstanding</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="agoda-todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="agoda_number" value="0">
                                                <input type="hidden" id="agoda_list_num" name="agoda_list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFive">
                                        <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="true"
                                            aria-controls="collapseFive">
                                            <!--ใส่ ID ให้ตรง -->
                                            Water Park Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse"
                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label>Cash</label>
                                                    <input type="text" id="wp_cash" name="wp_cash" placeholder="0.00">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label>Bank Transfer</label>
                                                    <input type="text" id="wp_transfer" name="wp_transfer" placeholder="0.00" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-left">
                                                    <h5 class="m-0">Credit Card</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Batch</label>
                                                    <input type="text" id="wp_batch" name="">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>ประเภทรายได้</label>
                                                    <select class="form-control form-select" id="wp_revenue_type">
                                                        <option value="3">Water Park Revenue</option>
                                                        <option value="7">Credit Card Water Park Revenue</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Credit Card Water Park Charge</label>
                                                    <input type="text" id="wp_credit_amount" name="" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-wp-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-wp-hide">ลบทั้งหมด</button>
                                                    <span class="wp-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTablewpCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">Credit Card Room Charge</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="wp-todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="wp_number" value="0">
                                                <input type="hidden" id="wp_list_num" name="wp_list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="true"
                                            aria-controls="collapseSix">
                                            <!--ใส่ ID ให้ตรง -->
                                            Elexa EGAT Revenue
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse"
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Order ID</label>
                                                    <input type="text" id="ev_batch" name="">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>ประเภทรายได้</label>
                                                    <select class="form-control form-select" aria-label="example" name="" id="ev_revenue_type">
                                                        <option value="8">Elexa EGAT Revenue</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>EV Charging Charge</label>
                                                    <input type="text" id="ev_credit_amount" name="" placeholder="0.00">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Transaction Fee 10%</label>
                                                    <input type="text" id="ev_transaction_fee" name="" placeholder="0.00" readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>VAT 7%</label>
                                                    <input type="text" id="ev_vat" name="" placeholder="0.00" readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Total Revenue</label>
                                                    <input type="text" id="ev_total_revenue" name="" placeholder="0.00" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-ev-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-ev-hide">ลบทั้งหมด</button>
                                                    <span class="ev-todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTableEvCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">EV Charging Charge</th>
                                                            <th scope="col">Transaction Fee</th>
                                                            <th scope="col">VAT</th>
                                                            <th scope="col">Total Revenue</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="ev-todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="ev_number" value="0">
                                                <input type="hidden" id="ev_list_num" name="ev_list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionPanelsStayOpenExample"> <!--อันนี้หน้า collapse-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <!--ใส่ ID ให้ตรงกับ aria-labelledby -->
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="true"
                                            aria-controls="collapseSix">
                                            <!--ใส่ ID ให้ตรง -->
                                            Credit Revenue <span class="text-danger" id="credit_card"> (ยอดเครดิต 0.00)</span>
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse"
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <!--ใส่ ID ให้ตรง -->
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>Batch</label>
                                                    <input type="text" class="form-control" id="batch" name="">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>ประเภทรายได้</label>
                                                    <select class="form-control form-select" id="revenue_type">
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
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <label>ยอดเงิน</label>
                                                    <input type="text" id="credit_amount" name="" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <button type="button" class="btn btn-primary btn-todo-add border-0"
                                                        style="background-color: #109699;">เพิ่ม</button>
                                                    <button class="btn btn-danger btn-todo-hide">ลบทั้งหมด</button>
                                                    <span class="todo-error text-danger small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" style="width: 100%;">
                                                <table id="myTableEvCredit" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Batch</th>
                                                            <th scope="col">ประเภทรายได้</th>
                                                            <th scope="col">ยอดเงิน</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="todo-list">

                                                    </tbody>
                                                </table>
                                                <input type="hidden" id="number" value="0">
                                                <input type="hidden" id="list_num" name="list_num" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="revenue_store()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Register the plugin to draw text in the center
            const centerTextPlugin = {
                id: 'centerTextPlugin',
                beforeDraw: function(chart) {
                    if (chart.config.type === 'doughnut') {
                        const ctx = chart.ctx;
                        const {
                            width,
                            height
                        } = chart.chartArea;

                        ctx.restore();
                        const fontSize = (height / 200).toFixed(2);
                        ctx.font = `500 ${fontSize}em 'Sarabun', sans-serif`;
                        ctx.textBaseline = "middle";

                        const text = $('#total_revenue_dashboard').val(); // ใส่ตัวเลขกลาง chart
                        const textX = Math.round((width - ctx.measureText(text).width) / 2);
                        const textY = height / 2 + 60; // ปรับขึ้นลง

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            };

            // Register the plugin with Chart.js
            Chart.register(centerTextPlugin);

            var cash = Number($('#total_cash_dashboard').val());
            var bank = Number($('#total_bank_dashboard').val());
            var credit = Number($('#total_credit_dashboard').val());

            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Bank Transfer', 'Credit Card Revenue'],
                    datasets: [{
                        data: [cash, bank, credit],
                        borderWidth: 0, // Set borderWidth to 0 to remove gaps
                    }]
                },
                options: {
                    // other options if any
                }
            });
        });
    </script>

<script>

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
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.ev_fee)) + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.ev_vat)) + '</td>' +
                                '<td style="text-align: right;">' + currencyFormat(parseFloat(value.ev_revenue)) + '</td>' +
                                '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose8(this)"></i></td>' +
                                '<input type="hidden" name="ev_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="ev_revenue_type[]" value="' + value.revenue_type + '">' +
                                // '<input type="hidden" name="ev_check_in[]" value="' + value.ev_check_in + '">' +
                                // '<input type="hidden" name="ev_check_out[]" value="' + value.ev_check_out + '">' +
                                '<input type="hidden" name="ev_credit_amount[]" value="' + value.ev_charge + '">' +
                                '<input type="hidden" name="ev_transaction_fee[]" value="' + value.ev_fee + '">' +
                                '<input type="hidden" name="ev_vat[]" value="' + value.ev_vat + '">' +
                                '<input type="hidden" name="ev_total_revenue[]" value="' + value.ev_revenue + '">' +
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

    function currencyFormat3(num) {
        return num.toFixed(3).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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
        var fee = $('#ev_transaction_fee').val();
        var vat = $('#ev_vat').val();
        var ev_revenue = $('#ev_total_revenue').val();
        var amount = $('#ev_credit_amount').val();
        // var outstanding = $('#ev_credit_outstanding').val();
        var list = parseInt($('#ev_list_num').val());
        var number = parseInt($('#ev_number').val()) + 1;
        $('#ev_number').val(number);

        if (batch && type && amount) {

            var type_name = "";
            switch (type) {
                case "8": type_name = "Elexa EGAT Revenue"; break;
            }

            $('.ev-todo-list').append(
                '<tr>' +
                    '<td>' + batch +'</td>' +
                    '<td>' + type_name + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(fee)) + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(vat)) + '</td>' +
                    '<td style="text-align: right;">' + currencyFormat(parseFloat(ev_revenue)) + '</td>' +
                    '<td style="text-align: center;"><i class="icon-trash text-danger close p-1" onClick="toggleClose8(this)">ลบ</i></td>' +
                    '<input type="hidden" name="ev_batch[]" value="' + batch + '">' +
                    '<input type="hidden" name="ev_revenue_type[]" value="' + type + '">' +
                    '<input type="hidden" name="ev_credit_amount[]" value="' + amount + '">' +
                    '<input type="hidden" name="ev_transaction_fee[]" value="' + fee + '">' +
                    '<input type="hidden" name="ev_vat[]" value="' + vat + '">' +
                    '<input type="hidden" name="ev_total_revenue[]" value="' + ev_revenue + '">' +
                '</tr>'
            );

            var batch = $('#ev_batch').val('');
            var type = $('#ev_revenue_type').val('');
            var amount = $('#ev_credit_amount').val('');
            var fee = $('#ev_transaction_fee').val('');
            var vat = $('#ev_vat').val('');
            var ev_revenue = $('#ev_total_revenue').val('');
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

    $('#ev_credit_amount').on('keyup', function () {
        var charge = Number($(this).val());
        var fee = (charge * 10) / 100;
        var vat7 = fee * 0.07;

        $('#ev_transaction_fee').val(currencyFormat3(fee));
        $('#ev_vat').val(currencyFormat3(vat7));
        $('#ev_total_revenue').val(currencyFormat3(charge - (fee + vat7)));
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

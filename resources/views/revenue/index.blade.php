@extends('layouts.masterLayout')

@section('content')
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

            $total_cash_bank = $total_cash + $total_bank_transfer + $total_other_revenue;
            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month + $total_other_month;
            $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year + $total_other_year;

            $total_today_revenue_graph = $total_day + ($credit_revenue->total_credit ?? 0);
        ?>
        
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="nav-content" style="justify-content: space-between;margin:0.8rem 0">
                <h1 class="h-daily" style=" margin:0;">Daily Revenue by Type</h1>
                <div class="nav-content">
                    <div>
                        <div class="">
                            <input type="text" id="select-date" name="" class="input-showdatepick mw-130" value="" placeholder="Pickup Time" style="">
                        </div>
                    </div>
                    <div class="" style="display: flex; gap:2px">
                        <div class="">
                            <button data-toggle="modal" data-target="#exampleModal2" class="button" type="button" style="border-top: 0px; border-left: 0px">
                                <span class="d-sm-none d-none d-md-inline-block">Search</span>
                                <i class="fa fa-search" style="font-size: 15px;"></i>
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="button dropdown-toggle" type="button" id="dropdownMenuDaily" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="border-top: 0px; border-left: 0px"> Today
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuDaily">
                                <a class="dropdown-item" href="#">This Week</a>
                                <a class="dropdown-item" href="#">This Month</a>
                                <a class="dropdown-item" href="#">This Year</a>
                                <a class="dropdown-item" href="#">Custom Date Range</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="button dropdown-toggle" type="button" id="dropdownMenuOperation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="border-top: 0px; border-left: 0px"> 
                                Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOperation">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addIncome">
                                    <i class="fa-solid fa-sack-dollar" style="font-size:15px; margin-right:5px;"></i>Add
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-info-circle fa-solid"
                                        style="font-size:15px; margin-right:6px;"></i>Details </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-print" style="font-size:15px; margin-right:6px;"></i>Print </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-lock" style="font-size:15px; margin-right:9px;"></i>Lock </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="all-section">
                <div class="section1">
                    <div class="">
                        <div class="box-chart">
                            <div>
                                <div>
                                    <canvas id="myChart" class="sm-m-40px"></canvas>
                                    <div class="percent-chart">
                                        <div>
                                            <h6 class="w-40p">
                                                <i style="color: #2C7F7A ;" class="m-right-5 fa fa-solid fa-square"></i>Cash
                                            </h6>
                                            <h6 class="w-5p">:</h6>
                                            <h6> 33.33%</h6>
                                        </div>
                                        <div>
                                            <h6 class="w-40p">
                                                <i style="color: #008996;" class="m-right-5 fa fa-solid fa-square"></i>Bank
                                                Transfer
                                            </h6>
                                            <h6 class="w-5p">:</h6>
                                            <h6> 33.33%</h6>
                                        </div>
                                        <div>
                                            <h6 class="w-40p">
                                                <i style="color: #3cc3b1;"
                                                    class="m-right-5 fa fa-solid fa-square"></i>Credit Card
                                            </h6>
                                            <h6 class="w-5p">:</h6>
                                            <h6> 33.33%</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="header">
                            <div>Cash</div>
                            <div>{{ number_format($total_cash + $total_wp_revenue->wp_cash, 2) }}</div>
                        </div>
                        <div class="sub d-grid-r1">
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                </div>
                                <div>Front Desk</div>
                                <div class="font-semibold">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</div>
                            </div>
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                </div>
                                <div>All Outlet</div>
                                <div class="font-semibold">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</div>
                            </div>
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                </div>
                                <div>Guest Deposit</div>
                                <div class="font-semibold">{{ number_format($total_fb_revenue->fb_cash, 2) }}</div>
                            </div>
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                </div>
                                <div>Water Park</div>
                                <div class="font-semibold">{{ number_format($total_wp_revenue->wp_cash, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="header">
                            <div>Bank Transfer</div>
                            <div>95,876.00</div>
                        </div>
                        <div class="sub d-grid-c">
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                </div>
                                <div>Front Desk</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                </div>
                                <div>Guest Deposit</div>
                                <div>95,720.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                </div>
                                <div>All outlet</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                </div>
                                <div>Water Park</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                </div>
                                <div>Agoda</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                </div>
                                <div>Elexa EGAT</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card1 bg-box">
                                <div class="">
                                    <img src="./image/front/salary.png" alt="" class="img" />
                                </div>
                                <div>Other</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="header">
                            <div>Credit Card</div>
                            <div>0.00</div>
                        </div>
                        <div class="sub d-grid-r4">
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/hotel.png" alt="" class="img" />
                                </div>
                                <div>Hotel</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card bg-box">
                                <div class="">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                </div>
                                <div>Water Park</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- box5 -->
                <div class="section2">
                    <div class="box-content">
                        <div class="header">
                            <div>Manual Charge</div>
                            <div>0.00</div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                    <div>Credit Card Front Desk</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                    <div>Credit Card Guest Deposit</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                    <div>Credit Card All Outlet</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                    <div>Credit Card Water Park</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                    <div>Agoda</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                    <div>Elexa EGAT</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                        </div>
                    </div>
                    <!-- box6 -->
                    <div class="box-content">
                        <div class="header">
                            <div>Fee</div>
                            <div>0.00</div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/hotel.png" alt="" class="img" />
                                    <div>Credit Card Hotel Fee</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                    <div>Credit Card Water Park Fee</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                    <div>Agoda Fee</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card2 bg-box">
                                <div class="f-ic">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                    <div>Elexa EGAT Fee</div>
                                </div>
                                <div class="t-end">0.00</div>
                            </div>
                        </div>
                    </div>
                    <!-- box7 -->
                    <div class="box-content">
                        <div class="header">
                            <div>Total Revenue Outstanding</div>
                            <div>0.00</div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card bg-box">
                                <!-- <div class="f-ic"> -->
                                <img src="./image/front/agoda.jpg" alt="" class="img" />
                                <div>Credit Card Agoda Revenue Outstanding</div>
                                <!-- </div> -->
                                <div class="t-end">0.00</div>
                            </div>
                            <div class="box-card bg-box">
                                <!-- <div class="f-ic"> -->
                                <img src="./image/front/elexa.png" alt="" class="img" />
                                <div>Elexa EGAT Revenue Outstanding</div>
                                <!-- </div> -->
                                <div class="t-end">0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- box8 -->
                <div class="section3">
                    <div class="box-content" style="grid-column: span 2">
                        <div class="header">
                            <div>Type</div>
                        </div>
                        <div class="sub d-grid-r3">
                            <div class="box-card3 bg-box">
                                <div>Transfer Revenue</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>Credit Card Hotel Transfer Transaction</div>
                                <div>0</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>Split Credit Card Hotel Revenue</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div class="t-start">Split Credit Card Hotel Transaction</div>
                                <div class="font-semibold">0</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>No Income Revenue</div>
                                <div class="font-semibold">0.00</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>Total Transaction</div>
                                <div class="font-semibold">10</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>Transfer Transaction</div>
                                <div class="font-semibold">0</div>
                            </div>
                            <div class="box-card3 bg-box">
                                <div>No Incoming Type</div>
                                <div class="font-semibold">0</div>
                            </div>
                        </div>
                    </div>
                    <div style="display: grid;gap:7px;">
                        <div class="box-content">
                            <div class="header">
                                <div>Monthly Revenue</div>
                            </div>
                            <div class="sub d-grid-r">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box"
                                        style="min-height: 92%;display: flex;justify-content: center;">
                                        <p class="t-center">2,567,987 <span> / Month</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-content">
                            <div class="header">
                                <div>Daily Avg. Revenue</div>
                            </div>
                            <div class="sub d-grid-r">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box"
                                        style="min-height: 92%;display: flex;justify-content: center;">
                                        <p>567,987 <span> / Day</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display: grid;gap:7px;">
                        <div class="box-content">
                            <div class="header">
                                <div>Verified</div>
                            </div>
                            <div class="sub d-grid-r">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box"
                                        style="min-height: 92%;display: flex;justify-content: center;"">
                                        <p>6
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" box-content">
                            <div class="header">
                                <div>Unverified</div>
                            </div>
                            <div class="sub d-grid-r">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box"
                                        style="min-height: 92%;display: flex;justify-content: center;">
                                        <p>13 </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <!-- Table -->
            <div class="table-2" style="overflow-x:auto;">
                <table class="table-3" style="border-radius: 9%">
                    <thead>
                        <tr class="table-row-bg" style="padding: 2rem;">
                            <th class=" text-center">Description</th>
                            <th class="t-end pr-2 ">Today</th>
                            <th class="t-end pr-2 ">M-T-D</th>
                            <th class="t-end pr-2 ">Y-T-D</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row-n">
                            <td class="t-center f-semi">Hotel</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">Front Desk Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Cash</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Credit Card Front Desk Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">22,934.00</td>
                            <td class="t-end padding-x-2">174,034.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">Guest Deposit Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Cash</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Credit Card Front Desk Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">22,934.00</td>
                            <td class="t-end padding-x-2">174,034.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">All Outlet Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Cash</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Credit Card All Outlet Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">22,934.00</td>
                            <td class="t-end padding-x-2">174,034.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">Other Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end padding-x-2">0.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Total Cash</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Total Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi"> Cash And Bank Transfer Hotel Revenue </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">814,876.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Total Credit Card Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">51,668.00</td>
                            <td class="t-end padding-x-2">293,444.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Credit Card Fee</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">1,334.18.00</td>
                            <td class="t-end padding-x-2">6,686.10</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Credit Card Hotel Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">5,333.00</td>
                            <td class="t-end padding-x-2">286,876.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="text-start pl-2">Agoda Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Credit Card Agoda Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Total Agoda Fee</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2"> Credit Agoda Revenue Outstanding </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-green-middle">
                            <td class="t-end f-semi">Total Hotel Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">Water Park Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Cash</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Bank Transfer</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi"> Cash + Bank Transfer Water Park Revenue </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end pl-2 f-semi"> Credit Card Water Park Charge </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end pl-2 f-semi">Credit Card Fee</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="t-end f-semi">Credit Card Water Park Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-n bg-green-middle">
                            <td class="t-end f-semi">Total Water Park Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">75,983.00</td>
                            <td class="t-end padding-x-2">765,876.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2">Elexa EGAT Revenue</td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">EV Charging Charge</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Elexa Fee</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-n">
                            <td class="padding-l-2">Elexa EGAT Revenue Outstanding</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-green-middle">
                            <td class="t-end f-semi">Total Elexa EGAT Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">4,034.00</td>
                            <td class="t-end padding-x-2">103,034.00</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td class="padding-l-2"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                            <td class="t-end"></td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi"> Total Hotel, Water Park And Elexa EGAT Revenue </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi"> Credit Agoda Revenue Outstanding </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi"> Elexa EGAT Revenue Outstanding </td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi">Agoda Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi">Elexa EGAT Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                        <tr class="table-row-n bg-sky-200/60">
                            <td class="pl-2 text-end f-semi">Total Revenue</td>
                            <td class="t-end">0.00</td>
                            <td class="t-end">21,034.00</td>
                            <td class="t-end padding-x-2">184,034.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
                        crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <script>
        const barColors = ["#2C7F7A ", "#008996", "#3cc3b1"];
        const ctx = document.getElementById("myChart").getContext("2d");
        // Plugin to add text in the center of the doughnut chart
        const centerTextPlugin = {
            id: "centerText",
            beforeDraw: function(chart) {
                if (chart.config.type === "doughnut") {
                    const width = chart.width,
                        height = chart.height,
                        ctx = chart.ctx;
                    ctx.restore();
                    const fontSize = (height / 145).toFixed(2); // Font size
                    ctx.font = fontSize + "em 'Sarabun', sans-serif";
                    ctx.textBaseline = "middle";
                    // Check if data is empty
                    const dataValues = chart.data.datasets[0].data;
                    const isEmptyData = dataValues.every(
                        (value) => value === 0);
                    const text = isEmptyData ? "00.00" : "123,456";
                    const textX = Math.round(
                        (width - ctx.measureText(text).width) / 2);
                    const textY = height / 2 + 30;
                    // Draw circle
                    const circleRadius = fontSize * 60; // Adjust the multiplier as needed
                    ctx.beginPath();
                    ctx.arc(width / 2, textY - 5, circleRadius, 0, 2 * Math
                    .PI); // Adjust the vertical offset as needed
                    ctx.strokeStyle = "transparent"; // Circle color
                    ctx.lineWidth = 2; // Circle line width
                    ctx.stroke();
                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            },
        };
        Chart.register(centerTextPlugin);
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Cash", "Bank Transfer", "Credit Card Revenue", ],
                datasets: [{
                    data: [4987.00, 68987.00, 50000.00], // Example of empty data
                    backgroundColor: barColors,
                }, ],
            },
            options: {
                cutout: "90%",
                // other options if any
            },
            plugins: [centerTextPlugin],
        });
    </script>
@endsection

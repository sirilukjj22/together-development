@extends('layouts.masterLayout')

@section('content')
    <?php
        if (isset($search_date)) {
            $date_current = $search_date;
        } else {
            $date_current = date('d/m/Y').' - '.date('d/m/Y');
        }

        $this_week = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))); // อาทิตย์ - เสาร์
        
        $formatMonth = date('Y-m', strtotime($date_current));

        $exp_date = array_map('trim', explode(' - ', $date_current));

        if ($filter_by == 'date' && count($exp_date) == 2) {
            $FormatDate = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            $diffInDay = Carbon\Carbon::create(Carbon\Carbon::parse($FormatDate->format('Y-m-d')))->diffInDays(Carbon\Carbon::parse($FormatDate2->format('Y-m-d'))) + 1;

            $day_sum = $diffInDay == 1 ? date('t') : $diffInDay;

        } elseif ($filter_by == 'week') {
            $diffInDay = 7;
            $day_sum = 7;
        } else {
            $diffInDay = isset($formatMonth) ? date('t', strtotime($formatMonth)) : date('t');
            $day_sum = $diffInDay;
        }

        $pickup_time = $date_current;

        if ($filter_by == 'date' && count($exp_date) != 2 || $filter_by == 'today' || $filter_by == 'yesterday' || $filter_by == 'tomorrow') {
            $pickup_time = date('d F Y', strtotime($search_date));
        } elseif ($filter_by == 'date' && count($exp_date) == 2) {
            $pickup_time = date('d M', strtotime($FormatDate)) . " " . substr(date('Y', strtotime($FormatDate)), -2) . " ~ ". date('d M', strtotime($FormatDate2)) . " " . substr(date('Y', strtotime($FormatDate2)), -2);
        } elseif ($filter_by == 'month') {
            $pickup_time = $search_date;
        } elseif ($filter_by == 'year') {
            $pickup_time = $search_date;
        } elseif ($filter_by == 'week') {
            $pickup_time = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d')))))." ~ ".date('d M', strtotime("+6 day", strtotime($this_week)));
        } elseif ($filter_by == 'thisMonth') {
            $pickup_time = "01 " . date('M') . " ~ " . date('t M');
        } elseif ($filter_by == 'thisYear') {
            $pickup_time = "01 " . "Jan" . " ~ ". date('d M', strtotime(date('Y-m-d')));
        }
        if ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == $exp_date[1]) {
            $pickup_time = date('d F Y', strtotime(Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0])));
        }
    ?>

    <?php
        $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
        $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer + $total_agoda_month + $total_ev_month + $total_other_month;
        
        $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
        
        $total_charge_month = $credit_revenue_month->total_credit ?? 0;
        
        $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
        
        $total_wp_charge_month = $wp_charge[0]['total_month'];
        
        $monthly_revenue = $total_cash_bank_month + $total_charge_month + ($total_wp_cash_bank_month + $total_wp_charge_month);
        
        $sum_charge = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
    ?>

    <?php
        $today_cash = $today_front_revenue->front_cash + $today_guest_deposit->room_cash + $today_fb_revenue->fb_cash;
        $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
        $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
        $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;
        
        $today_bank_transfer = $today_front_revenue->front_transfer + $today_guest_deposit->room_transfer + $today_fb_revenue->fb_transfer + $today_other_revenue;
        $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer + $total_other_revenue;
        $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer + $total_other_month;
        $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer + $total_other_year;
        
        $today_wp_cash_bank = $today_wp_revenue->wp_cash + $today_wp_revenue->wp_transfer;
        $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
        $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
        $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;
        
        $total_cash_bank = $total_cash + $total_bank_transfer;
        $today_cash_bank = $today_cash + $today_bank_transfer;
        $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
        $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;
        
        $total_today_revenue_graph = $total_day + $total_ev_revenue + ($credit_revenue->total_credit ?? 0);

    ?>

    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="nav-content">
                <div class="nav-left">
                    <h1 class="h-daily" style=" margin:0;" id="button-change">Hotel & Water Park Revenue</h1>
                </div>
                <div class="nav-right">
                    <div class="nav-right-in">
                        <input type="text" id="select-date" name="" placeholder="{{ !empty($pickup_time) ? $pickup_time : date('d F Y') }}" value="{{ $pickup_time }}" readonly>
                                <button data-toggle="modal" data-target="#ModalShowCalendar" type="button" class="ch-button" style="border-top: 0px; border-left: 0px">
                                    <span class="d-sm-none d-none d-md-inline-block">Search</span>
                                    <i class="fa fa-search" style="font-size: 15px;"></i>
                                </button>
                            <span class="dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuDaily" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                                    <span id="txt-daily">
                                        @if ($filter_by == 'today' || $filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == date('d/m/Y') && $exp_date[1] == date('d/m/Y' || !isset($filter_by)))
                                            Today
                                        @elseif (isset($filter_by) && $filter_by == 'yesterday' || date('Y-m-d', strtotime(date($date_current))) == date('Y-m-d', strtotime('-1 day')))
                                            Yesterday
                                        @elseif (isset($filter_by) && $filter_by == 'tomorrow' || date('Y-m-d', strtotime(date($date_current))) == date('Y-m-d', strtotime('+1 day')))
                                            Tomorrow
                                        @elseif (isset($filter_by) && $filter_by == 'week')
                                            This Week
                                        @elseif (isset($filter_by) && $filter_by == 'thisMonth')
                                            This Month
                                        @elseif (isset($filter_by) && $filter_by == 'thisYear')
                                            This Year
                                        @else
                                            @if ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == date('d/m/Y') && $exp_date[1] == date('d/m/Y'))
                                                Today
                                            @else
                                                Custom
                                            @endif
                                        @endif
                                    </span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuDaily">
                                    <a class="dropdown-item" href="{{ route('revenue') }}">Today</a>
                                    <a class="dropdown-item" href="#" onclick="search_daily('week')">This Week</a>
                                    <a class="dropdown-item" href="#" onclick="search_daily('thisMonth')">This Month</a>
                                    <a class="dropdown-item" href="#" onclick="search_daily('thisYear')">This Year</a>
                                </div>
                                <input type="hidden" name="" id="week-from" value="{{ date('Y-m-d', strtotime('last sunday', strtotime('next sunday', strtotime(isset($filter_by) ? date($date_current) : date('Y-m-d')))))  }}">
                            </span>
                            <span class="dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuOperation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-action" aria-labelledby="dropdownMenuOperation">
                                    @if ($total_revenue_today->status == 0)
                                        {{-- @if (isset($filter_by) && $filter_by == 'date' || isset($filter_by) && $filter_by == 'today' || isset($filter_by) && $filter_by == 'yesterday' || isset($filter_by) && $filter_by == 'tomorrow' || !isset($filter_by)) --}}
                                        @if ($diffInDay == 1 || $filter_by == 'date' && strpos($pickup_time, '-') == false && strpos($pickup_time, '~') == false)
                                            @if (@Auth::user()->roleMenuAdd('Hotel & Water Park Revenue', Auth::user()->id) == 1 )
                                                <a class="dropdown-item" href="#" onclick="Add_data('{{ date('Y-m-d', strtotime($pickup_time)) }}')" data-toggle="modal" data-target="#addIncome" <?php echo $total_revenue_today->status == 1 ? 'disabled' : '' ?>>
                                                    <i class="fa-solid fa-sack-dollar"></i>Add
                                                </a>
                                            @endif
                                        @endif
                                    @endif

                                    @if ($diffInDay == 1 || $filter_by == 'date' && strpos($pickup_time, '-') == false && strpos($pickup_time, '~') == false)
                                        <a class="dropdown-item" href="#" onclick="view_data('{{ date('Y-m-d', strtotime($pickup_time)) }}')" data-toggle="modal" data-target="#ViewDataModalCenter">
                                            <i class="fa fa-info-circle fa-solid"></i>Details 
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="#" onclick="export_data(1)"><i class="fa fa-print"></i>Print </a>
    
                                    {{-- @if (isset($filter_by) && $filter_by == 'date' || isset($filter_by) && $filter_by == 'today' || isset($filter_by) && $filter_by == 'yesterday' || isset($filter_by) && $filter_by == 'tomorrow' || !isset($filter_by)) --}}
                                    @if ($diffInDay == 1 || $filter_by == 'date' && strpos($pickup_time, '-') == false && strpos($pickup_time, '~') == false)
                                        @if (Auth::user()->permission > 0)
                                            @if ($total_revenue_today->status == 0)
                                                <a href="#" class="dropdown-item btn-close-daily" value="1"><i class="fa fa-lock"></i>Lock </a>
                                            @else
                                                <a href="#" class="dropdown-item btn-open-daily" value="0"><i class="fa fa-unlock"></i>Unlock </a>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </span>
                    </div>
                </div>
            </div>
            <div class="all-section">
                <div class="section1">
                    <div class="box-chart" {{ $total_today_revenue_graph > 0 ? '' : 'hidden' }}>
                        @php
                            $total_credit_hotel_wp = ($credit_revenue->total_credit ?? 0) + ($total_wp_revenue->wp_credit ?? 0);
                        @endphp
                        <canvas id="myChart" class="sm-m-40px"></canvas>
                        <div class="percent-chart">
                            <div>
                                <h6 class="w-40p">
                                    <i style="color: #2C7F7A ;" class="m-right-5 fa fa-solid fa-square"></i>Cash
                                </h6>
                                <h6 class="w-5p">:</h6>
                                <h6>{{ number_format($total_today_revenue_graph == 0 ? 0 : (($total_cash + $total_wp_revenue->wp_cash) / $total_today_revenue_graph * 100), 2) }}%</h6>
                            </div>
                            <div>
                                <h6 class="w-40p">
                                    <i style="color: #008996;" class="m-right-5 fa fa-solid fa-square"></i>Bank Transfer
                                </h6>
                                <h6 class="w-5p">:</h6>
                                <h6>{{ number_format($total_today_revenue_graph == 0 ? 0 : (($total_bank_transfer + $total_wp_revenue->wp_transfer) / $total_today_revenue_graph * 100), 2) }}%</h6>
                            </div>
                            <div>
                                <h6 class="w-40p">
                                    <i style="color: #3cc3b1;"
                                        class="m-right-5 fa fa-solid fa-square"></i>Credit Card
                                </h6>
                                <h6 class="w-5p">:</h6>
                                @if ($total_credit_hotel_wp == 0)
                                    <h6>: {{ number_format(0, 2) }}%</h6>
                                @else
                                    <h6>{{ number_format($total_today_revenue_graph == 0 ? 0 : (($total_credit_hotel_wp) / $total_today_revenue_graph * 100), 2) }}%</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-chart" {{ $total_today_revenue_graph == 0 ? '' : 'hidden' }}>
                        <div class="box-chart-zero">
                            <div class="circle-top">
                                <div class="circle-ani">
                                    <div class=""></div>
                                </div>
                                <div class="circle-detail">
                                    <div>
                                        <div>0.00</div>
                                    </div>
                                </div>
                            </div>
                            <div class="percent-chart">
                                <div>
                                    <h6 class="w-40p">
                                        <i style="color: #2C7F7A ;" class="m-right-5 fa fa-solid fa-square"></i>Cash
                                    </h6>
                                    <h6 class="w-5p">:</h6>
                                    <h6> 0.00%</h6>
                                </div>
                                <div>
                                    <h6 class="w-40p">
                                        <i style="color: #008996;" class="m-right-5 fa fa-solid fa-square"></i>Bank Transfer
                                    </h6>
                                    <h6 class="w-5p">:</h6>
                                    <h6> 0.00%</h6>
                                </div>
                                <div>
                                    <h6 class="w-40p">
                                        <i style="color: #3cc3b1;" class="m-right-5 fa fa-solid fa-square"></i>Credit Card
                                    </h6>
                                    <h6 class="w-5p">:</h6>
                                    <h6> 0.00%</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-content">
                        <input type="hidden" id="total_revenue_dashboard" value="{{ number_format($total_today_revenue_graph, 2) }}">
                        <div class="header">
                            <div>Cash</div>
                            <div>{{ number_format($total_cash + $total_wp_revenue->wp_cash, 2) }}</div>
                            <input type="hidden" id="total_cash_dashboard" value="{{ $total_cash + $total_wp_revenue->wp_cash }}">
                        </div>
                        <div class="sub d-grid-r1">
                            <div class="box-card bg-box" onclick="revenue_detail('cash_front')">
                                <div class="">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                </div>
                                <div>Front Desk</div>
                                <div class="font-semibold">
                                    {{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}
                                </div>
                            </div>
                            <div class="box-card bg-box" onclick="revenue_detail('cash_all_outlet')">
                                <div class="">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                </div>
                                <div>All Outlet</div>
                                <div class="font-semibold">
                                    {{ number_format($total_fb_revenue->fb_cash, 2) }}
                                </div>
                            </div>
                            <div class="box-card bg-box" onclick="revenue_detail('cash_guest')">
                                <div class="">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                </div>
                                <div>Guest Deposit</div>
                                <div class="font-semibold">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</div>
                            </div>
                            <div class="box-card bg-box" onclick="revenue_detail('cash_water_park')">
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
                            <div>{{ number_format($total_bank_transfer + $total_wp_revenue->wp_transfer + $total_agoda_revenue + $total_ev_revenue, 2) }}</div>
                            <input type="hidden" id="total_bank_dashboard" value="{{ $total_bank_transfer + $total_wp_revenue->wp_transfer + $total_agoda_revenue + $total_ev_revenue }}">
                        </div>
                        <div class="sub d-grid-c">
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_front')">
                                <div class="">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                </div>
                                <div>Front Desk</div>
                                <div class="font-semibold">
                                    {{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}
                                </div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_guest')">
                                <div class="">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                </div>
                                <div>Guest Deposit</div>
                                <div>
                                    {{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}
                                </div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_all_outlet')">
                                <div class="">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                </div>
                                <div>All outlet</div>
                                <div class="font-semibold">{{ number_format($total_fb_revenue->fb_transfer, 2) }}</div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_water_park')">
                                <div class="">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                </div>
                                <div>Water Park</div>
                                <div class="font-semibold">{{ number_format($total_wp_revenue->wp_transfer, 2) }}</div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_agoda')">
                                <div class="">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                </div>
                                <div>Agoda</div>
                                <div class="font-semibold">{{ number_format($total_agoda_revenue, 2) }}</div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_elexa')">
                                <div class="">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                </div>
                                <div>Elexa EGAT</div>
                                <div class="font-semibold">{{ number_format($total_ev_revenue, 2) }}</div>
                            </div>
                            <div class="box-card1 bg-box" onclick="revenue_detail('tf_other')">
                                <div class="">
                                    <img src="./image/front/salary.png" alt="" class="img" />
                                </div>
                                <div>Other</div>
                                <div class="font-semibold">{{ number_format($total_other_revenue, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="header">
                            <div>Credit Card</div>
                            <div>
                                {{ number_format(($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_credit ?? 0), 2) }}
                            </div>
                            <input type="hidden" id="total_credit_dashboard" value="{{ ($credit_revenue->total_credit ?? 0) + ($total_revenue_today->wp_credit ?? 0) }}">
                        </div>
                        <div class="sub d-grid-r4">
                            <div class="box-card bg-box" onclick="revenue_detail('cc_credit_hotel')">
                                <div class="">
                                    <img src="./image/front/hotel.png" alt="" class="img" />
                                </div>
                                <div>Hotel</div>
                                <div class="font-semibold">{{ number_format($credit_revenue->total_credit ?? 0, 2) }}
                                </div>
                            </div>
                            <div class="box-card bg-box" onclick="revenue_detail('cc_credit_water_park')">
                                <div class="">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                </div>
                                <div>Water Park</div>
                                <div class="font-semibold">{{ number_format($total_revenue_today->wp_credit ?? 0, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- box5 -->
                <div class="section2">
                    <div class="box-content">
                        <div class="header" style="position: relative;">
                            <div>Manual Charge 
                                <img src="./image/front/lightbulb-grey.png" alt="" class="img"  id="toggleSumHotelCharg" />
                            </div>
                            <div>
                                {{ number_format($sum_charge + $wp_charge[0]['revenue_credit_date'] + $agoda_charge[0]['revenue_credit_date'] + $ev_charge[0]['revenue_credit_date'], 2) }}
                            </div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_front_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/reception.png" alt="" class="img" />
                                    <div>Credit Card Front Desk</div>
                                </div>
                                <div class="t-end">{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_guest_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/quest-deposit.png" alt="" class="img" />
                                    <div>Credit Card Guest Deposit</div>
                                </div>
                                <div class="t-end">
                                    {{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_all_outlet_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/shop.png" alt="" class="img" />
                                    <div>Credit Card All Outlet</div>
                                </div>
                                <div class="t-end">{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box hidden" id="hotelManualCharge" >
                                <div class="f-ic">
                                    <p ><i style='font-size:15px;color:#2C7F7A' class='fas'>&#xf139;</i> Total Hotel Manual Charge </p>
                                </div>
                                <div class="t-end">
                                    {{ number_format($front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'], 2) }}
                                </div>
                               </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_water_park_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                    <div>Credit Card Water Park</div>
                                </div>
                                <div class="t-end">{{ number_format($wp_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_agoda_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                    <div>Agoda</div>
                                </div>
                                <div class="t-end">{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('mc_elexa_charge')">
                                <div class="f-ic">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                    <div>Elexa EGAT</div>
                                </div>
                                <div class="t-end">{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- box6 -->
                    <div class="box-content">
                        <div class="header">
                            <div>Fee</div>
                            <div>
                                {{ number_format(($sum_charge == 0 || $credit_revenue->total_credit == 0 ? 0 : $sum_charge - $credit_revenue->total_credit ?? 0) + $wp_charge[0]['fee_date'] + $agoda_charge[0]['fee_date'] + $ev_charge[0]['fee_date'], 2) }}
                            </div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card2 bg-box" onclick="revenue_detail('credit_hotel_fee')"> <!-- onclick="revenue_detail('fee_credit_hotel')" -->
                                <div class="f-ic">
                                    <img src="./image/front/hotel.png" alt="" class="img" />
                                    <div>Credit Card Hotel Fee</div>
                                </div>
                                <div class="t-end">
                                    {{ number_format($sum_charge - $total_hotel_fee, 2) }}
                                </div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('water_park_fee')">
                                <div class="f-ic">
                                    <img src="./image/front/water-park.png" alt="" class="img" />
                                    <div>Credit Card Water Park Fee</div>
                                </div>
                                <div class="t-end">{{ number_format($wp_charge[0]['fee_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('agoda_fee')">
                                <div class="f-ic">
                                    <img src="./image/front/agoda.jpg" alt="" class="img" />
                                    <div>Agoda Fee</div>
                                </div>
                                <div class="t-end">{{ number_format($agoda_charge[0]['fee_date'], 2) }}</div>
                            </div>
                            <div class="box-card2 bg-box" onclick="revenue_detail('elexa_fee')">
                                <div class="f-ic">
                                    <img src="./image/front/elexa.png" alt="" class="img" />
                                    <div>Elexa EGAT Fee</div>
                                </div>
                                <div class="t-end">{{ number_format($ev_charge[0]['fee_date'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- box7 -->
                    <div class="box-content">
                        @php
                            $sum_agoda_revenue_outstanding = isset($filter_by) && $filter_by == "thisYear" || isset($filter_by) && $filter_by == "year" ? $agoda_charge[0]['total'] - $total_agoda_year : $agoda_charge[0]['total'];
                            $sum_elexa_revenue_outstanding = isset($filter_by) && $filter_by == "thisYear" || isset($filter_by) && $filter_by == "year" ? $ev_charge[0]['total'] - $total_ev_year : $ev_charge[0]['total'];
                        @endphp
                        <div class="header">
                            <div>Total Revenue Outstanding</div>
                            <div>{{ number_format($sum_agoda_revenue_outstanding + $sum_elexa_revenue_outstanding, 2) }}</div>
                        </div>
                        <div class="sub d-grid-r2">
                            <div class="box-card bg-box" onclick="revenue_detail('agoda_outstanding')"> <!-- Link ไป Dashboard ใน Debtor -->
                                <!-- <div class="f-ic"> -->
                                <img src="./image/front/agoda.jpg" alt="" class="img" />
                                <div>Credit Card Agoda Revenue Outstanding</div>
                                <!-- </div> -->
                                <div class="t-end">
                                    {{ number_format($sum_agoda_revenue_outstanding, 2) }}
                                </div>
                            </div>
                            <div class="box-card bg-box" onclick="revenue_detail('elexa_outstanding')">
                                <!-- <div class="f-ic"> -->
                                <img src="./image/front/elexa.png" alt="" class="img" />
                                <div>Elexa EGAT Revenue Outstanding</div>
                                <!-- </div> -->
                                <div class="t-end">
                                    {{ number_format($sum_elexa_revenue_outstanding, 2) }}
                                </div>
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
                            <div class="box-card3 bg-box" onclick="revenue_detail('transfer_revenue')">
                                <div>Transfer Revenue</div>
                                <div class="font-semibold">{{ number_format($total_transfer, 2) }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('credit_hotel_transfer')">
                                <div>Credit Card Hotel Transfer Transaction</div>
                                <div>{{ $total_credit_transaction ?? 0 }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('split_hotel_revenue')">
                                <div>Split Credit Card Hotel Revenue</div>
                                <div class="font-semibold">{{ number_format($total_split, 2) }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('split_hotel_transaction')">
                                <div class="t-start">Split Credit Card Hotel Transaction</div>
                                <div class="font-semibold">{{ number_format($total_split_transaction) }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('no_income_revenue')">
                                <div>No Income Revenue</div>
                                <div class="font-semibold">{{ number_format($total_not_type_revenue, 2) }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('total_transaction')">
                                <div>Total Transaction</div>
                                <div class="font-semibold">
                                    {{ number_format($total_revenue_today->total_transaction ?? 0) }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('transfer_transaction')">
                                <div>Transfer Transaction</div>
                                <div class="font-semibold">{{ $total_transfer2 }}</div>
                            </div>
                            <div class="box-card3 bg-box" onclick="revenue_detail('no_income_type')">
                                <div>No Incoming Type</div>
                                <div class="font-semibold">{{ $total_not_type ?? 0 }}</div>
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
                                    <div class="box-card3 bg-box" style="min-height: 92%;display: flex;justify-content: center;">
                                        <p class="t-center">{{ number_format(isset($filter_by) && $filter_by == "year" || isset($filter_by) && $filter_by == "thisYear" ? ($monthly_revenue / Carbon\Carbon::now()->format('n')) : $monthly_revenue, 2) }} <span> / Month</span>
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
                                    <div class="box-card3 bg-box" style="min-height: 92%;display: flex;justify-content: center;">
                                        @php
                                            $daysFromJanuary = Carbon\Carbon::create(Carbon\Carbon::now()->year, 1, 1)->diffInDays(Carbon\Carbon::now());
                                        @endphp
                                        <p>{{ number_format($filter_by == "year" || $filter_by == "thisYear" ? ($monthly_revenue / $daysFromJanuary) : ($monthly_revenue / $day_sum), 2) }} <span> / Day</span>
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
                            <div class="sub d-grid-r" onclick="revenue_detail('verified')">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box" style="min-height: 92%;display: flex;justify-content: center;">
                                        <p>{{ $total_verified ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" box-content">
                            <div class="header">
                                <div>Unverified</div>
                            </div>
                            <div class="sub d-grid-r" onclick="revenue_detail('unverified')">
                                <div class="sub-content">
                                    <div class="box-card3 bg-box" style="min-height: 92%;display: flex;justify-content: center;">
                                        <p>{{ $total_unverified ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal: เลือกวันที่ Daterange modal fade -->
                    <div class="modal fade" id="modalChoseDateRange" tabindex="-1" role="dialog" aria-labelledby="modalChoseDateRange" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="max-width: 350px;">
                            <div class="modal-content rounded-xl">
                                <div class="modal-header md-header text-white">
                                    <div class="w-full">
                                    <h5 class=".modal-hd">Custom Date Range</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="wrap-chose-dateRange">
                                        <div class="input-group">
                                            <label for="">Date Start :</label>
                                            <input type="date" class="input-showdatepick mw-130" name="" id="customRang-start" style="text-align: left;">
                                        </div>
                                        <div class="input-group">
                                            <label for="">Date End : </label>
                                            <input type="date" class="input-showdatepick mw-130" name="" id="customRang-end" style="text-align: left;">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" id="btn-save-date" class="btn btn-success" style="background-color: #2C7F7A;" onclick="search_daily('customRang')">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <!-- Table -->
            
            @if (session('success'))
                <div class="container p-0 rounded">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Successfully</h4>
                        <i class="fa-regular fa-circle-check">&nbsp;</i>{{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="table-2" style="overflow-x:auto;">
                <div class="d-flex gap-3 mb-2">
                    <div class="center" >
                        <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80" class="mb-1" />
                    </div>
      
                    <div class="text-capitalize d-grid gap-0">
                      <span class="f-semi">Together Resort Kaengkrachan</span>
                      <span>Hotel and water park revenue</span>
                      <span>Date On : {{ !empty($pickup_time) ? $pickup_time : date('d F Y') }}</span>
                        @if ($filter_by == "date" || $filter_by == "today" || $filter_by == "yesterday" || $filter_by == "tomorrow" || !isset($filter_by))
                            @if ($total_revenue_today->status == 1 && $filter_by == 'date' && strpos($pickup_time, '-') == false && strpos($pickup_time, '~') == false)
                                <span>Status : <span class="text-danger">ตรวจสอบเรียบร้อยแล้ว</span></span>
                            @else 
                                <span>Status : -</span>
                            @endif
                        @endif
                    </div>
                </div>
                <table class="table-revenue">
                    <thead>
                        <tr class="table-row-bg1">
                            <th>Description</th>
                            <th class="to-day th-topic-today">
                                @if ($filter_by == 'week')
                                    This Week
                                @elseif ($filter_by == 'date' && strpos($pickup_time, '~') == true)
                                    {{ $pickup_time }}
                                @else
                                    Today
                                @endif
                            </th>
                            <th class="m-t-d th-topic-month">M-T-D</th>
                            <th class="y-t-d th-topic-year">
                                @if ($filter_by == 'year')
                                    Year {{ $pickup_time }}
                                @elseif ($filter_by == 'thisYear')
                                    This Year
                                @else
                                    Y-T-D
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Front Desk Revenue</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_front_revenue) && $filter_by == "date" ? $today_front_revenue->front_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class=" m-t-d">
                                {{ number_format(isset($total_front_month) && $filter_by != "year" || $filter_by != "thisYear" ? $total_front_month->front_cash : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_front_revenue) && $filter_by == "date" ? $today_front_revenue->front_transfer : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format(isset($total_front_month) && $filter_by != "year" || $filter_by != "thisYear" ? $total_front_month->front_transfer : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2) }}
                            </td>
                        </tr>
                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Guest Deposit Revenue</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_guest_deposit) && $filter_by == "date" ? $today_guest_deposit->room_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" || $filter_by != "thisYear" ? $total_guest_deposit_month->room_cash : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_guest_deposit) && $filter_by == "date" ? $today_guest_deposit->room_transfer : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" || $filter_by != "thisYear" ? $total_guest_deposit_month->room_transfer : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}
                            </td>
                        </tr>
                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">All Outlet Revenue</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_cash : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_fb_revenue) && $filter_by == "date" ? $today_fb_revenue->fb_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_fb_month->fb_cash : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_fb_year->fb_cash ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_transfer : 0, 2) }}
                                @else
                                    {{ number_format(isset($today_fb_revenue) && $filter_by == "date" ? $today_fb_revenue->fb_transfer : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_fb_month->fb_transfer : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_fb_year->fb_transfer ?? 0, 2) }}</td>
                        </tr>

                        @php
                            $total_cash_bank_today = ($today_front_revenue->front_cash + $today_guest_deposit->room_cash + $today_fb_revenue->fb_cash) + ($today_front_revenue->front_transfer + $today_guest_deposit->room_transfer + $today_fb_revenue->fb_transfer + $today_other_revenue);
                            $total_cash_bank_week = ($total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash) + ($total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer + $total_other_revenue);

                            $total_credit_card_revenue = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                            $total_credit_card_revenue_week = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                            $total_credit_card_revenue_month = $front_charge[0]['revenue_credit_month'] + $guest_deposit_charge[0]['revenue_credit_month'] + $fb_charge[0]['revenue_credit_month'];
                            $total_credit_card_revenue_year = $front_charge[0]['revenue_credit_year'] + $guest_deposit_charge[0]['revenue_credit_year'] + $fb_charge[0]['revenue_credit_year'];

                            $total_charge = $credit_revenue_today->total_credit ?? 0;
                            $total_charge_week = $credit_revenue->total_credit ?? 0;
                            $total_charge_month = $credit_revenue_month->total_credit ?? 0;
                            $total_charge_year = $credit_revenue_year->total_credit ?? 0;

                            $total_wp_credit_card_revenue = $wp_charge[0]['revenue_credit_date'];
                            $total_wp_credit_card_revenue_week = $wp_charge[0]['revenue_credit_date'];
                            $total_wp_credit_card_revenue_month = $wp_charge[0]['revenue_credit_month'];
                            $total_wp_credit_card_revenue_year = $wp_charge[0]['revenue_credit_year'];

                            $today_wp_charge = $wp_charge[0]['total'];
                            $total_wp_charge = $wp_charge[0]['total'];
                            $total_wp_charge_month = $wp_charge[0]['total_month'];
                            $total_wp_charge_year = $wp_charge[0]['total_year'];
                        @endphp

                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Hotel Credit Card Revenue</td>
                        </tr>
                        <tr>
                            <td>Credit Card Front Desk Charge</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($front_charge[0]['revenue_credit_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $front_charge[0]['revenue_credit_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $front_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Credit Card Guest Deposit Charge</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}
                                @else
                                {{ number_format($filter_by == "date" ? $guest_deposit_charge[0]['revenue_credit_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $guest_deposit_charge[0]['revenue_credit_month'] : 0, 2) }}
                            </td>
                            <td class="y-t-d">{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Credit Card All Outlet Charge</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $fb_charge[0]['revenue_credit_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $fb_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Credit Card Fee</td>
                            <td class="to-day">
                                @if ($filter_by == "date")
                                    {{ number_format($total_credit_card_revenue == 0 || $credit_revenue_today->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue_today->total_credit ?? 0, 2) }}
                                @elseif ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_credit_card_revenue_week == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue_week - $credit_revenue->total_credit ?? 0, 2) }}
                                @else
                                    0.00
                                @endif 
                            </td>
                            <td class="m-t-d">
                                @if ($total_credit_card_revenue_month == 0 || $credit_revenue_month->total_credit == 0)
                                    0.00
                                @else
                                    {{ number_format($filter_by != "year" || $filter_by != "thisYear" ? ($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0) : 0, 2) }}
                                @endif
                            </td>
                            <td class="y-t-d">
                                {{ number_format(($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0), 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Credit Card Revenue (Bank Transfer)</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($credit_revenue->total_credit, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $credit_revenue_today->total_credit ?? 0 : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? ($credit_revenue_month->total_credit ?? 0) : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format(($credit_revenue_year->total_credit ?? 0), 2) }}</td>
                        </tr>

                        @php
                            $agoda_revenue_outstanding_today = $agoda_charge[0]['total'];
                            $agoda_revenue_outstanding_date = $agoda_charge[0]['total']; // Week, Custom Rang
                            $agoda_revenue_outstanding_month = $agoda_charge[0]['total_month'];
                            $agoda_revenue_outstanding_year = $agoda_charge[0]['total_year'] - $total_agoda_year;
                        @endphp

                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Agoda Revenue</td>
                        </tr>
                        <tr>
                            <td>Agoda Charge</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ?  $agoda_charge[0]['revenue_credit_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agoda Fee</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_charge[0]['fee_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $agoda_charge[0]['fee_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_charge[0]['fee_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agoda Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $agoda_charge[0]['total'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_charge[0]['total_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agoda Paid (bank transfer)</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_agoda_revenue, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_agoda_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_agoda_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_agoda_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agoda Revenue Outstanding </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_revenue_outstanding_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $agoda_revenue_outstanding_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
                        </tr>
                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Other Revenue</td>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_other_revenue, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_other_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_other_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_other_year, 2) }}</td>
                        </tr>

                        <tr class="white-h-05em"></tr>
                        
                        @php
                            // Bank Transfer
                            $summary_hotel_revenue_bank_today = $today_bank_transfer + ($credit_revenue_today->total_credit ?? 0) + $total_agoda_revenue; // Week, Custom Rang
                            $summary_hotel_revenue_bank_date = $total_bank_transfer + ($credit_revenue->total_credit ?? 0) + $total_agoda_revenue;
                            $summary_hotel_revenue_bank_month = $total_bank_transfer_month + ($credit_revenue_month->total_credit ?? 0) + $total_agoda_month;
                            $summary_hotel_revenue_bank_year = $total_bank_transfer_year + ($credit_revenue_year->total_credit ?? 0) + $total_agoda_year;
                        @endphp

                        <tr class="table-row-bg">
                            <td>Summary Hotel Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($summary_hotel_revenue_bank_today + $total_cash + $agoda_revenue_outstanding_today, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $summary_hotel_revenue_bank_today + $today_cash + $agoda_revenue_outstanding_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="t-end f-semi">Cash</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_cash, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_cash_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_cash_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t-end f-semi">Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($summary_hotel_revenue_bank_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $summary_hotel_revenue_bank_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $summary_hotel_revenue_bank_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($summary_hotel_revenue_bank_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t-end f-semi">Agoda Revenue Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_revenue_outstanding_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $agoda_revenue_outstanding_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
                        </tr>
                        
                        <tr class="white-h-05em"></tr>

                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Water Park Revenue</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_cash : 0, 2) }}
                                @else
                                    {{ number_format(isset($total_wp_revenue) && $filter_by == "date" ? $today_wp_revenue->wp_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_wp_month->wp_cash ?? 0 : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_year->wp_cash ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_transfer : 0, 2) }}
                                @else
                                    {{ number_format(isset($total_wp_revenue) && $filter_by == "date" ? $today_wp_revenue->wp_transfer : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_wp_month->wp_transfer : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
                        </tr>
                        
                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Water Park Credit Card Revenue</td>
                        </tr>
                        <tr>
                            <td> Credit Card Water Park Charge </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_wp_credit_card_revenue_week, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $total_wp_credit_card_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_wp_credit_card_revenue_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Credit Card Fee</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($wp_charge[0]['fee_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $wp_charge[0]['fee_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $wp_charge[0]['fee_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_wp_charge, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_wp_charge : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_wp_charge_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_charge_year, 2) }}</td>
                        </tr>

                        <tr class="table-row-bg">
                            <td>Summary Water Park Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? ($today_wp_revenue->wp_cash + $today_wp_revenue->wp_transfer) + $today_wp_charge : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                        </tr>

                        <tr class="white-h-05em"></tr>

                        <tr class="table-row-bg">
                            <td colspan="100%" class="td-topic">Elexa EGAT Revenue</td>
                        </tr>
                        <tr>
                            <td>EV Charging Charge</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['revenue_credit_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Elexa Fee</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['fee_date'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['fee_date'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['fee_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Elexa EGAT Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['total'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Elexa EGAT Paid (Bank Transfer)</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_ev_revenue, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_ev_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_ev_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Elexa EGAT Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['total'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                        </tr>

                        <tr class="white-h-05em"></tr>

                        <tr class="table-row-bg">
                            <td>Summary Elexa EGAT Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($today_ev_revenue + $ev_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? ($today_ev_revenue + $ev_charge[0]['total']) : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? ($total_ev_month + $ev_charge[0]['total_month']) : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t-end f-semi">Bank Transfer</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_ev_revenue, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_ev_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_ev_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t-end f-semi">Elexa EGAT Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['total'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                        </tr>

                        <tr class="white-h-05em"></tr>

                        <tr class="table-row-bg">
                            <td colspan="100%">Summary Revenue</td>
                        </tr>
                        <tr>
                            <td class="text-end f-semi"> All Revenue </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date) + ($total_wp_cash_bank + $total_wp_charge) + ($total_ev_revenue + $ev_charge[0]['total']), 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? ($summary_hotel_revenue_bank_today + $today_cash + $agoda_revenue_outstanding_today) + ($today_wp_cash_bank + $today_wp_charge) + ($today_ev_revenue + $ev_charge[0]['total']) : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format($filter_by != "year" || $filter_by != "thisYear" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($total_ev_month + $ev_charge[0]['total_month'])) : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format(($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + ($ev_charge[0]['total_year']), 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end f-semi">Outstanding Balance From Last Year</td>
                            <td class="to-day">0.00</td>
                            <td class="m-t-d">0.00</td>
                            <td class="y-t-d">{{ number_format($agoda_outstanding_last_year + $elexa_outstanding_last_year, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date) + ($total_wp_cash_bank + $total_wp_charge) + ($total_ev_revenue + $ev_charge[0]['total']), 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? ($summary_hotel_revenue_bank_today + $today_cash + $agoda_revenue_outstanding_today) + ($today_wp_cash_bank + $today_wp_charge) + ($today_ev_revenue + $ev_charge[0]['total']) : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format($filter_by != "year" || $filter_by != "thisYear" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($total_ev_month + $ev_charge[0]['total_month'])) : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format((($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year'] + ($agoda_outstanding_last_year + $elexa_outstanding_last_year)), 2) }}
                            </td>
                        </tr>
          
                          <tr class="white-h-05em"></tr>

                          <tr class="table-row-bg">
                            <td colspan="100%">Payment Summary Details Report</td>
                          </tr>
                          <tr>
                            <td class="text-end f-semi">
                                <i class="fa fa-info-circle" data-tooltip-target="tooltip-default"></i> Hotel Revenue
                                <div id="tooltip-default" role="tooltip" class="absolute tooltip-2"> 
                                    Front Desk Revenue <br> Guest Deposit Revenue <br> All Outlet Revenue
                                </div>
                            </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($summary_hotel_revenue_bank_date + $total_cash, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $summary_hotel_revenue_bank_today + $today_cash : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">
                                {{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $summary_hotel_revenue_bank_month + $total_cash_month : 0, 2) }}
                            </td>
                            <td class="y-t-d">
                                {{ number_format($summary_hotel_revenue_bank_year + $total_cash_year, 2) }}
                            </td>
                          </tr>
                          <tr>
                            <td class="text-end f-semi"> Water Park Revenue </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? ($today_wp_revenue->wp_cash + $today_wp_revenue->wp_transfer) + $today_wp_charge : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                          </tr>
                          <tr>
                            <td class="text-end f-semi">Elexa EGAT revenue </td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($total_ev_revenue, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $today_ev_revenue : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $total_ev_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                          </tr>

                          @php
                                $summary_details_report_today = ($summary_hotel_revenue_bank_today + $today_cash) + ($today_wp_revenue->wp_cash + $today_wp_revenue->wp_transfer + $today_wp_charge) + $today_ev_revenue;
                                $summary_details_report_date = ($summary_hotel_revenue_bank_date + $total_cash) + ($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer + $total_wp_charge) + $total_ev_revenue;
                                $summary_details_report_month = ($summary_hotel_revenue_bank_month + $total_cash_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $total_ev_month;
                                $summary_details_report_year = ($summary_hotel_revenue_bank_year + $total_cash_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $total_ev_year;
                          @endphp

                          <tr>
                            <td class="text-end f-semi">Total Revenue</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($summary_details_report_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $summary_details_report_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $summary_details_report_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($summary_details_report_year, 2) }}</td>
                          </tr>
          
                          <tr class="white-h-05em"></tr>
          
                          <tr class="table-row-bg">
                            <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
                          </tr>
                          <tr>
                            <td class="text-end f-semi">Agoda Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($agoda_revenue_outstanding_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $agoda_revenue_outstanding_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
                          </tr>
                          <tr>
                            <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($ev_charge[0]['total'], 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $ev_charge[0]['total'] : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                          </tr>

                          @php
                                $revenue_outstanding_report_today = $agoda_revenue_outstanding_today + $ev_charge[0]['total'];
                                $revenue_outstanding_report_date = $agoda_revenue_outstanding_date + $ev_charge[0]['total'];
                                $revenue_outstanding_report_month = $agoda_revenue_outstanding_month + $ev_charge[0]['total_month'];
                                $revenue_outstanding_report_year = $agoda_revenue_outstanding_year + ($ev_charge[0]['total_year'] - $total_ev_year);
                          @endphp
          
                          <tr>
                            <td class="text-end f-semi">Total Outstanding Balance</td>
                            <td class="to-day">
                                @if ($filter_by == "week" || $filter_by == "customRang")
                                    {{ number_format($revenue_outstanding_report_date, 2) }}
                                @else
                                    {{ number_format($filter_by == "date" ? $revenue_outstanding_report_today : 0, 2) }}
                                @endif
                            </td>
                            <td class="m-t-d">{{ number_format($filter_by != "year" || $filter_by != "thisYear" ? $revenue_outstanding_report_month : 0, 2) }}</td>
                            <td class="y-t-d">{{ number_format($revenue_outstanding_report_year, 2) }}</td>
                          </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: เลือกวันที่ modal fade -->
    <div class="modal fade" id="ModalShowCalendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-bottom md-330px" role="document">
            <div class="modal-content">
                <div class="modal-header md-header text-white">
                    <h5>ค้นหารายการ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('revenue-search-calendar') }}" method="POST" enctype="multipart/form-data" class="" id="form-revenue">
                    @csrf
                    <div class="modal-body" style="padding-top: 0; padding-bottom: 0px">
                        <div style="place-items: center">
                            <div class="center py-2" style="gap: 0.3rem; width: 100%">
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-date">Filter by Date</button>
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-month">Filter by Month</button>
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-year">Filter by Year</button>
                            </div>
                            <input type="text" id="combined-selected-box" name="date" value="{{ $date_current }}" class="selected-value-box t-alight-center" style="width: 300px" />
                            <!-- box แสดงวันที่ เดือน ปี -->
                            <div class="calendars-container" id="calendars-container">
                                <div class="calendar-wrapper flex-grow-1" id="date-picker-wrapper" style="transform: translateY(-10px); height: 250px">
                                    <div style="text-align: center">
                                        <div style="transform: scale(1.09)" id="calendarContainer"></div> <!-- เพิ่ม div สำหรับแสดงปฏิทิน -->
                                    </div>
                                </div>
                                <!-- Calendar for Picking a Month Range -->
                                <div class="calendar-wrapper flex-grow-1" id="month-picker-wrapper">
                                    <div class="calendar" id="month-range-picker">
                                        <header>
                                            <button id="prev-year"><i class="arrow fa fa-angle-left" style="font-weight: 900; font-size: 20px"></i></button>
                                            <h2 id="year"></h2>
                                            <button id="next-year"><i class="arrow fa fa-angle-right" style="font-weight: 900; font-size: 20px"></i></button>
                                        </header>
                                        <div class="months-grid"></div>
                                    </div>
                                </div>
                                <!-- Calendar for Picking a Year -->
                                <div class="calendar-wrapper flex-grow-1" id="year-picker-wrapper">
                                    <div class="calendar" id="year-picker">
                                        <div class="years-grid"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input ส่งค่าไป Controller -->
                    <input type="hidden" id="filter-by" name="filter_by" value="{{ isset($filter_by) ? $filter_by : 'date' }}">

                    <!-- ประเภทรายได้ -->
                    <input type="hidden" id="revenue-type" name="revenue_type" value="">

                    <input type="hidden" name="daily_page" id="daily_page">
                    <input type="hidden" name="export_pdf" id="export_pdf" value="0">
                </form>

                <!-- ล่าง modal -->
                <div class="modal-footer border-top d-flex justify-content-between mt-2" style="padding: 0 0.7rem">
                    <div id="btn-select-today">
                        <button type="button" class="bt-tg-normal bg-tg-light sm" id="today-btn">Today</button>
                    </div>
                    <div>
                        <button type="button" class="bt-tg-normal sm bt-grey" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-search-date" class="bt-tg-normal bg-tg-light sm btn-submit-search" style="background-color: #2c7f7a">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add ข้อมูลเงินสด modal fade -->
    <div class="modal fade bd-example-modal-lg" id="addIncome" tabindex="-1" role="dialog"
        aria-labelledby="addIncomeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-lg">
                <div class="modal-header bg-teal-green">
                    <h5 class="modal-title text-white" id="addIncomeLabel">Add</h5>
                    <button type="button" class="close text-white text-2xl" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="#" method="POST" enctype="multipart/form-data" class="form-store">
                @csrf
                <div class="modal-body bg-green500">
                    <div class="df-jc-ic">
                        <label for="" class="text2xl">Date : &nbsp;&nbsp;</label>
                        <input type="date" class="input-date" id="date_add" name="date" value="{{ date('Y-m-d', strtotime($pickup_time)) }}" readonly>
                    </div>
                    <br />
                    <div class="box-accordion">
                        <button type="button" class="accordion">
                            <div>front desk revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input active:bg-gradient-to-r active:from-blue-500/20 active:to-green-500/40 focus:outline-none" id="front_cash" name="front_cash" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="front_transfer" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <!--ครอบ column 2-->
                                <div class="credit-card">credit card</div>
                                <div class="dg-gc3-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Stand <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="front_batch" name="">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type <sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" id="front_revenue_type">
                                            <option value="6" selected>Front Desk Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Credit Card Front Desk Charge <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="front_credit_amount" name="" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-front-add"> Add </button>
                                <button type="button" class="delete-all-button btn-front-hide" onclick="toggleHide3()"> Delete All </button>
                                <span class="front-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTablefrontCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Front Desk Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="front-todo-list">

                                        </tbody>
                                        <input type="hidden" id="front_number" value="0">
                                        <input type="hidden" id="front_list_num" name="front_list_num" value="0">
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Guest Deposit Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="cash" name="cash" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="room_transfer" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <!--ครอบ column 2-->
                                <div class="credit-card">credit card</div>
                                <div class="dg-gc3-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Stand <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="guest_batch">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type <sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" id="guest_revenue_type">
                                            <option value="1" selected>Guest Deposit Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Credit Card Room Charge <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="guest_credit_amount" name="" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-guest-add"> Add </button>
                                <button type="button" class="delete-all-button btn-guest-hide" onclick="toggleHide4()"> Delete All </button>
                                <span class="guest-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTableguestCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Room Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
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
                        <button type="button" class="accordion">
                            <div>All Outlet Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="fb_cash" name="fb_cash" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="fb_transfer" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <!--ครอบ column 2-->
                                <div class="credit-card">credit card</div>
                                <div class="dg-gc3-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Stand <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="fb_batch"/>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type <sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" id="fb_revenue_type">
                                            <option value="2" selected>All Outlet Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Credit Card All Outlet Charge <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="fb_credit_amount" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-fb-add"> Add </button>
                                <button type="button" class="delete-all-button btn-fb-hide" onclick="toggleHide5()"> Delete All </button>
                                <span class="fb-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTablefbCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card All Outlet Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
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
                        <button type="button" class="accordion">
                            <div>Agoda Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Booking Number <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="agoda_batch">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type <sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" id="agoda_revenue_type">
                                            <option value="1" selected>Guest Deposit Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Check in date <sup class="text-danger">*</sup></label>
                                        <input type="date" class="accordion-input" id="check_in">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Check out date<sup class="text-danger">*</sup></label>
                                        <input type="date" class="accordion-input" id="check_out">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Credit Card Agoda Charge<sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="agoda_credit_amount" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Revenue Outstanding<sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="agoda_credit_outstanding" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-agoda-add"> Add </button>
                                <button type="button" class="delete-all-button btn-agoda-hide" onclick="toggleHide2()"> Delete All </button>
                                <span class="agoda-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTableAgodaCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:20%;">Booking No</th>
                                                <th class="t-center padding-l-2em" style="width:25%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Check in date</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Check out date</th>
                                                <th style="width:15%;border-left:white 1px solid">Credit Card Agoda Charge</th>
                                                <th style="width:15%;border-left:white 1px solid">Credit Agoda Revenue Outstanding</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Action</th>
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
                        <button type="button" class="accordion">
                            <div>Water Park Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="wp_cash" name="wp_cash" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer <sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="wp_transfer" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <!--ครอบ column 2-->
                                <div class="credit-card">credit card</div>
                                <div class="dg-gc3-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Stand <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="wp_batch"/>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type <sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" id="wp_revenue_type">
                                            <option value="3" selected>Water Park Revenue</option>
                                            <option value="7">Credit Card Water Park Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Credit Card Water Park Charge<sup class="text-danger">*</sup></label>
                                        <input type="text" class="accordion-input" id="wp_credit_amount" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-wp-add"> Add </button>
                                <button type="button" class="delete-all-button btn-wp-hide" onclick="toggleHide6()"> Delete All </button>
                                <span class="wp-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTablewpCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Water Park Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
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
                        <button type="button" class="accordion">
                            <div>Elexa EGAT Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Order ID <sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="ev_batch">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type<sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" aria-label="example" name="" id="ev_revenue_type">
                                            <option value="8" selected>Elexa EGAT Revenue</option>
                                        </select>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">EV Charging Charge<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="ev_credit_amount" name="" placeholder="0.00">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Transaction Fee 10%<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="ev_transaction_fee" name="" placeholder="0.00" readonly>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">VAT 7%<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="ev_vat" name="" placeholder="0.00" readonly>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Total Revenue<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="ev_total_revenue" name="" placeholder="0.00" readonly>
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-ev-add"> Add </button>
                                <button type="button" class="delete-all-button btn-ev-hide" onclick="toggleHide8()"> Delete All </button>
                                <span class="ev-todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTableEvCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center">Stan</th>
                                                <th class="t-center" style="border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="border-left:white 1px solid">EV Charging Charge</th>
                                                <th class="t-center" style="border-left:white 1px solid">Transaction Fee</th>
                                                <th class="t-center" style="border-left:white 1px solid">VAT</th>
                                                <th class="t-center" style="border-left:white 1px solid">Total Revenue</th>
                                                <th class="t-center" style="border-left:white 1px solid">Action</th>
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
                        <button type="button" class="accordion">
                            <div>Credit Revenue <span class="text-white" id="credit_card">&nbsp;(ยอดเครดิต 0.00)</span></div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Stan<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="batch">
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Income type<sup class="text-danger">*</sup></label>
                                        <select class="accordion-input" aria-label="example" name="" id="revenue_type">
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
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Amount<sup class="text-red-600">*</sup></label>
                                        <input type="text" class="accordion-input" id="credit_amount" name="" placeholder="0.00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="add-button btn-todo-add"> Add </button>
                                <button type="button" class="delete-all-button btn-todo-hide" onclick="toggleHide8()"> Delete All </button>
                                <span class="todo-error small ms-3"style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                                <br />
                                <br />
                                <div style="overflow-x:auto;">
                                    <table id="myTableCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center">Stan</th>
                                                <th class="t-center" style="border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="border-left:white 1px solid">Amount</th>
                                                <th class="t-center" style="border-left:white 1px solid">Action</th>
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
                    <div class="modal-footer">
                        <button type="button" class="btn button text-white" data-dismiss="modal" style="background-color: rgb(104, 100, 100)"> Close </button>
                        <button type="button" class="btn button text-white" onclick="revenue_store()" style="background-color: rgb(5, 122, 108)"> Save changes </button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal ดูรายละเอียดข้อมูลเงินสด modal fade -->
    <div class="modal fade bd-example-modal-lg" id="ViewDataModalCenter" tabindex="-1" role="dialog" aria-labelledby="ViewDataModalCenterLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-lg">
                <div class="modal-header bg-teal-green">
                    <h5 class="modal-title text-white" id="ViewDataModalCenterLabel">Detail</h5>
                    <button type="button" class="close text-white text-2xl" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-green500">
                    <div class="df-jc-ic">
                        <label for="" class="text2xl">Date : &nbsp;&nbsp;</label>
                        <input type="date" class="input-date" id="date_view_detail" value="{{ date('Y-m-d', strtotime($pickup_time)) }}">
                    </div>
                    <br />
                    <div class="box-accordion">
                        <button type="button" class="accordion">
                            <div>front desk revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash</label>
                                        <input type="text" class="accordion-input active:bg-gradient-to-r active:from-blue-500/20 active:to-green-500/40 focus:outline-none" id="front_cash2" placeholder="0.00" disabled>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer</label>
                                        <input type="text" class="accordion-input" id="front_transfer2" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <div class="credit-card mt-3 mb-3">credit card</div>
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Front Desk Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="front-todo-list">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Guest Deposit Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash</label>
                                        <input type="text" class="accordion-input active:bg-gradient-to-r active:from-blue-500/20 active:to-green-500/40 focus:outline-none" id="cash2" placeholder="0.00" disabled>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer</label>
                                        <input type="text" class="accordion-input" id="room_transfer2" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <div class="credit-card mt-3 mb-3">credit card</div>
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Room Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="guest-todo-list">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>All Outlet Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash</label>
                                        <input type="text" class="accordion-input active:bg-gradient-to-r active:from-blue-500/20 active:to-green-500/40 focus:outline-none" id="fb_cash2" placeholder="0.00" disabled>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer</label>
                                        <input type="text" class="accordion-input" id="fb_transfer2" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <div class="credit-card mt-3 mb-3">credit card</div>
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card All Outlet Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fb-todo-list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Agoda Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:20%;">Booking No</th>
                                                <th class="t-center padding-l-2em" style="width:25%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Check in date</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Check out date</th>
                                                <th style="width:15%;border-left:white 1px solid">Credit Card Agoda Charge</th>
                                                <th style="width:15%;border-left:white 1px solid">Credit Agoda Revenue Outstanding</th>
                                                <th class="t-center" style="width:10%;border-left:white 1px solid">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="agoda-todo-list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Water Park Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div class="dg-gc2-g2">
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Cash</label>
                                        <input type="text" class="accordion-input active:bg-gradient-to-r active:from-blue-500/20 active:to-green-500/40 focus:outline-none" id="wp_cash2" placeholder="0.00" disabled>
                                    </div>
                                    <div class="accordion-card">
                                        <label for="" class="max-sm:text-sm">Bank Transfer</label>
                                        <input type="text" class="accordion-input" id="wp_transfer2" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <div class="credit-card mt-3 mb-3">credit card</div>
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center" style="width:15%;">Stan</th>
                                                <th class="t-center padding-l-2em" style="width:35%;border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="width:30%;border-left:white 1px solid">Credit Card Water Park Charge</th>
                                                <th class="t-center" style="width:20%;border-left:white 1px solid"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="wp-todo-list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Elexa EGAT Revenue</div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div style="overflow-x:auto;">
                                    <table id="" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center">Stan</th>
                                                <th class="t-center" style="border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="border-left:white 1px solid">EV Charging Charge</th>
                                                <th class="t-center" style="border-left:white 1px solid">Transaction Fee</th>
                                                <th class="t-center" style="border-left:white 1px solid">VAT</th>
                                                <th class="t-center" style="border-left:white 1px solid">Total Revenue</th>
                                                <th class="t-center" style="border-left:white 1px solid">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="ev-todo-list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="accordion">
                            <div>Credit Revenue <span class="text-white" id="credit_card2">&nbsp;(ยอดเครดิต 0.00)</span></div>
                        </button>
                        <div class="panel">
                            <div id="front-desk-revenue">
                                <div style="overflow-x:auto;">
                                    <table id="myTableCredit" class="add-income-table">
                                        <thead>
                                            <tr class="" style="background-color: #2C7F7A;color: white; ">
                                                <th class="t-center">Stan</th>
                                                <th class="t-center" style="border-left:white 1px solid">Income type</th>
                                                <th class="t-center" style="border-left:white 1px solid">Amount</th>
                                                <th class="t-center" style="border-left:white 1px solid">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="todo-list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn button text-white" data-dismiss="modal" style="background-color: rgb(104, 100, 100)"> Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <!-- Sweet Alert 2 -->
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <!-- Calendar -->
    <link rel="stylesheet" href="{{ asset('assets/src/calendar-draft-litePicker.css') }}?v={{ time() }}">
    <script src="{{ asset('assets/js/calendar-draft-noDate.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

    <style>
        .current-month,
        .current-year {
          /* background-color: rgba(16, 152, 100, 0.188) !important; */
          color: #1d3d2e !important;
          /* box-shadow: inset 0 0 0 1px #388e99; */
          background-color: rgb(63, 2, 23) !important;
        }
      </style>

<script type="text/javascript">
    const monthName = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ]; // ชื่อเดือน

    $(document).ready(function() { 

        // เก็บอินสแตนซ์ Litepicker ในตัวแปร
        let picker;
        const datepickerElement = document.getElementById("combined-selected-box");

        var filter_by = $('#filter-by').val();
        var dateRange = $('#select-date').val(); 

        if (filter_by != "month" && filter_by != "year") {
            localStorage.removeItem("selectedYear");
            localStorage.removeItem("selectedMonthRange");

            // สร้าง Litepicker
            picker = new Litepicker({
                element: datepickerElement,
                inlineMode: true,
                singleMode: false,
                parentEl: document.getElementById("calendarContainer"),
                allowRepick: true,
                numberOfMonths: 1,
                numberOfColumns: 1,
                format: "DD/MM/YYYY", // ใช้ 'DD' เพื่อให้วันที่แสดงเป็นสองหลัก
                dropdowns: {
                    minYear: 2024,
                    maxYear: 2030,
                    months: true,
                    years: true,
                },
            });
        }

        if (filter_by == "month") {
            localStorage.removeItem("selectedYear");
            $('#filter-month').click();
        }

        if (filter_by == "year") {
            localStorage.removeItem("selectedMonthRange");
            $('#filter-year').click();
        }

        // Select Button Today
        document.getElementById('today-btn').addEventListener('click', function() {
            const startday = new Date(); // วันที่เริ่มต้น (วันนี้)
            const endday = new Date();   // วันที่สิ้นสุด (วันนี้)
            
            if (picker) picker.destroy();

            // สร้าง Litepicker
            picker = new Litepicker({
                element: datepickerElement,
                inlineMode: true,
                singleMode: false,
                parentEl: document.getElementById("calendarContainer"),
                allowRepick: true,
                numberOfMonths: 1,
                numberOfColumns: 1,
                format: "DD/MM/YYYY",
                startDate: startday, // วันที่เริ่มต้น
                endDate: endday,     // วันที่สิ้นสุด
                dropdowns: {
                    minYear: 2024,
                    maxYear: 2030,
                    months: true,
                    years: true,
                },
            });
        });

        // ทำลาย Litepicker เมื่อคลิกปุ่ม filter
        $(document).on("click", ".filter", function () {
            var ID = $(this).attr("id");
            if (ID == "filter-month" || ID == "filter-year") {
                $('#today-btn').addClass('hidden');
                if (picker) {
                    picker.destroy(); // ทำลายอินสแตนซ์
                }

                picker = null; // รีเซ็ตตัวแปร

            } else {
                $('#today-btn').removeClass('hidden');
                if (picker) {
                    picker.destroy(); // ทำลายอินสแตนซ์
                }

                picker = null; // รีเซ็ตตัวแปร

                // สร้าง Litepicker
                picker = new Litepicker({
                    element: datepickerElement,
                    inlineMode: true,
                    singleMode: false,
                    parentEl: document.getElementById("calendarContainer"),
                    allowRepick: true,
                    numberOfMonths: 1,
                    numberOfColumns: 1,
                    format: "DD/MM/YYYY",
                    dropdowns: {
                        minYear: 2024,
                        maxYear: 2030,
                        months: true,
                        years: true,
                    },
                });
            }
        });

        // Calendar
        if (filter_by == "date" || filter_by == "today" || filter_by == "tomorrow" || filter_by == "yesterday") {
            var dates = dateRange.split(" ~ ");

            // เก็บวันที่เริ่มต้นและวันที่สิ้นสุดลงในตัวแปร
            var startDate = dates[0].replaceAll("/", "-");
            var endDate = dates[1].replaceAll("/", "-");
            
            if (startDate != endDate) {
                $('.m-t-d').prop('hidden', true);
                $('.y-t-d').prop('hidden', true);
            }
        } 

        if (filter_by == "week") {
            $('.m-t-d').prop('hidden', true);
            $('.y-t-d').prop('hidden', true);
        }

        if (filter_by == "month" || filter_by == "thisMonth") {
            $('.to-day').prop('hidden', true);
        }

        if (filter_by == "year" || filter_by == "thisYear") {
            $('.to-day').prop('hidden', true);
            $('.m-t-d').prop('hidden', true);
        }

    });

    $('.ch-button').on('click', function () {
        $('#filter-by').val("date");
    });

    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }

    function revenue_detail(revenue_name) {
        $('#revenue-type').val(revenue_name);
        $('#form-revenue').submit();
    }
</script>

<script>
    var cash = Number($('#total_cash_dashboard').val());
    var bank = Number($('#total_bank_dashboard').val());
    var credit = Number($('#total_credit_dashboard').val());

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
                const text = isEmptyData ? "00.00" : $('#total_revenue_dashboard').val();
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
                data: [cash, bank, credit], // Example of empty data
                backgroundColor: barColors,
            }, ],
        },
        options: {
            cutout: "90%",
            // other options if any
        },
        plugins: [centerTextPlugin],
    });

    // Number Format
    function currencyFormat(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

    function currencyFormat3(num) {
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

    // เลือกวันที่ใน Modal เพิ่มเงินสด/เครดิต
    $('#date_add').on('change', function () {
        Add_data($(this).val());
    });

    // เลือกวันที่ใน Modal รายละเอียดเงินสด/เครดิต
    $('#date_view_detail').on('change', function () {
        view_data($(this).val());
    });

    // Add ข้อมูลเงินสด/เครดิต
    function Add_data($date) {
        var date = $('#date_add').val();
        
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

                $('#credit_card').text(" (ยอดเครดิต "+currencyFormat(response.data.total_credit)+")");
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
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose4(this)"></i></td>' +
                                '<input type="hidden" name="guest_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="guest_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="guest_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 2) {
                        $('.fb-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose5(this)"></i></td>' +
                                '<input type="hidden" name="fb_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="fb_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="fb_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 3) {
                        $('.wp-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose6(this)"></i></td>' +
                                '<input type="hidden" name="wp_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="wp_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="wp_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 4) {
                        $('.todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose(this)"></i></td>' +
                                '<input type="hidden" name="batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 5) {
                        $('.agoda-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-center">' + date_check_in + '</td>' +
                                '<td class="t-center">' + date_check_out + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.agoda_charge)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.agoda_outstanding)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose2(this)"></i></td>' +
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
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose3(this)"></i></td>' +
                                '<input type="hidden" name="front_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="front_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="front_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 8) {
                        $('.ev-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="capitalize padding-r-2em t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_charge)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_fee)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_vat)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_revenue)) + '</td>' +
                                '<td class="t-center pr-4"><i class="fa fa-trash-o ml-2 t-red close p-1" onClick="toggleClose8(this)"></i></td>' +
                                '<input type="hidden" name="ev_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="ev_revenue_type[]" value="' + value.revenue_type + '">' +
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

    function view_data($date) {
        var date = $('#date_view_detail').val();
        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('revenue-edit/"+date+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(response) {
                $('#front_cash2').val(response.data.front_cash);
                $('#front_transfer2').val(currencyFormat(response.data.front_transfer));
                $('#front_credit2').val(response.data.front_credit);
                $('#cash2').val(response.data.room_cash);
                $('#room_transfer2').val(currencyFormat(response.data.room_transfer));
                $('#credit2').val(response.data.room_credit);
                $('#fb_cash2').val(response.data.fb_cash);
                $('#fb_transfer2').val(currencyFormat(response.data.fb_transfer));
                $('#fb_credit2').val(response.data.fb_credit);
                $('#wp_cash2').val(response.data.wp_cash);
                $('#wp_transfer2').val(currencyFormat(response.data.wp_transfer));
                $('#wp_credit2').val(response.data.wp_credit);
                // $('#ev_cash').val(response.data.ev_cash);
                // $('#ev_transfer').val(currencyFormat(response.data.ev_transfer));
                // $('#ev_credit').val(response.data.ev_credit);

                $('#credit_card2').text(" (ยอดเครดิต "+currencyFormat(response.data.total_credit)+")");
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
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="guest_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="guest_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="guest_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 2) {
                        $('.fb-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="fb_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="fb_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="fb_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 3) {
                        $('.wp-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="wp_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="wp_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="wp_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 4) {
                        $('.todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 5) {
                        $('.agoda-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-center">' + date_check_in + '</td>' +
                                '<td class="t-center">' + date_check_out + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.agoda_charge)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.agoda_outstanding)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
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
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.credit_amount)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="front_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="front_revenue_type[]" value="' + value.revenue_type + '">' +
                                '<input type="hidden" name="front_credit_amount[]" value="' + value.credit_amount + '">' +
                            '</tr>'
                        );

                    } if (value.status == 8) {
                        $('.ev-todo-list').append(
                            '<tr class="border-1">' +
                                '<td class="t-center">' + value.batch +'</td>' +
                                '<td class="t-center">' + type_name + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_charge)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_fee)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_vat)) + '</td>' +
                                '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(value.ev_revenue)) + '</td>' +
                                '<td style="text-align: center;"></td>' +
                                '<input type="hidden" name="ev_batch[]" value="' + value.batch + '">' +
                                '<input type="hidden" name="ev_revenue_type[]" value="' + value.revenue_type + '">' +
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

    // ปุ่มAddข้อมูลแต่ละหมวด
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align:center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose3(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose4(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose5(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-center">' + date_check_in + '</td>' +
                    '<td class="t-center">' + date_check_out + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(outstanding)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose2(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose6(this)"></i></td>' +
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
                '<tr class="border-1">' +
                    '<td class="t-center">' + batch +'</td>' +
                    '<td class="t-center">' + type_name + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(amount)) + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(fee)) + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(vat)) + '</td>' +
                    '<td class="t-end padding-r-2em">' + currencyFormat(parseFloat(ev_revenue)) + '</td>' +
                    '<td style="text-align: center;"><i class="fa fa-trash-o t-red close p-1" onClick="toggleClose8(this)"></i></td>' +
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

    // คำนวณ Elexa
    $('#ev_credit_amount').on('keyup', function () {
        var charge = Number($(this).val());
        var fee = (charge * 10) / 100;
        var vat7 = fee * 0.07;

        var revenue = (charge - (fee + vat7)).toFixed(3);

        $('#ev_transaction_fee').val(fee.toFixed(3));
        $('#ev_vat').val(vat7.toFixed(3));
        $('#ev_total_revenue').val(revenue);
    });

    // ปุ่มลบข้อมูลเงิน/บัตรเครดิต
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
        $('.ev-todo-list tr').remove();
    }

    $('.btn-submit-search').on('click', function () {
        document.getElementById("form-revenue").removeAttribute('target');
        $('#export_pdf').val(0);
        $('#revenue-type').val('');
        $('#form-revenue').submit();
    });

    // Search Daily (Today, Yesterday, Tomorrow)
    function search_daily($search) {

        if ($search == 'today') {
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("Today");
        } 

        if ($search == 'yesterday') {
            var date = AddOrSubractDays(new Date(), 1, false);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("Yesterday");
        } 

        if ($search == 'tomorrow') {
            var date = AddOrSubractDays(new Date(), 1, true);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("Tomorrow");
        } 

        if ($search == 'week') {
            var date = new Date($('#week-from').val());
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("This Week");
        }

        if ($search == 'thisMonth') {
            var date = new Date($('#input-search-year').val()+"-"+$('#input-search-month').val()+"-"+$('#input-search-day').val());
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("This Month");
        }

        if ($search == 'thisYear') {
            var date = new Date($('#input-search-year').val()+"-"+$('#input-search-month').val()+"-"+$('#input-search-day').val());
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            $('#txt-daily').text("This Year");
        }

        $('#filter-by').val($search);
        $('#input-search-day').val(day);
        $('#input-search-month').val(month);
        $('#input-search-year').val(year);
        $('#form-revenue').submit();
    }

    function export_data(params) {
        document.getElementById("form-revenue").setAttribute("target", "_blank");
        $('#export_pdf').val(params);
        $('#form-revenue').submit();
    }

    $('.btn-close-daily').on('click', function () {
        var filter_by = $('#filter-by').val();
        var date = $('#select-date').val();
        var dates = date.split(" - ");
        var startDate = moment(dates[0].replaceAll("/", "-")).format('YYYY-MM-DD');
        var format_date = moment(dates[0].replaceAll("/", "-")).format('DD/MM/YYYY');

        Swal.fire({
        icon: "info",
        title: 'คุณต้องการปิดยอดวันที่ '+ format_date +' ใช่หรือไม่?',
        text: 'หากปิดยอดแล้ว ไม่สามารถเพิ่มข้อมูลได้ !',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) { 
            $.ajax({
                url: "{!! url('revenue-daily-close') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    filter_by: filter_by,
                    date: startDate
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success(response) {
                    Swal.fire('เรียบร้อย!', '', 'success');
                    location.reload();
                },
                error(response) {
                    Swal.fire('ไม่สำเร็จ', '', 'info');
                    // location.reload();
                }
            });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
                location.reload();
            }
        })
    });

    $('.btn-open-daily').on('click', function () {
        var filter_by = $('#filter-by').val();
        var date = $('#select-date').val();
        var dates = date.split(" - ");
        var startDate = moment(dates[0].replaceAll("/", "-")).format('YYYY-MM-DD');
        var format_date = moment(dates[0].replaceAll("/", "-")).format('DD/MM/YYYY');

        Swal.fire({
        icon: "info",
        title: 'คุณต้องการแก้ไขยอดวันที่ '+ format_date +' ใช่หรือไม่?',
        // text: 'หากปิดยอดแล้ว ไม่สามารถเพิ่มข้อมูลได้ !',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {

            $.ajax({
                url: "{!! url('revenue-daily-open') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    filter_by: filter_by,
                    date: startDate
                },
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

    $(function() {
    var currentClass = "bg-box";
    var currentChartClass = "box-chart";
    
    $("#button-change").on("click", function() {
            // สลับคลาสของ bg-box
            if (currentClass === "bg-box") {
                $(".bg-box").removeClass("bg-box").addClass("bg-box2");
                currentClass = "bg-box2";
            } else if (currentClass === "bg-box2") {
                $(".bg-box2").removeClass("bg-box2").addClass("bg-box3");
                currentClass = "bg-box3";
            } else if (currentClass === "bg-box3") {
                $(".bg-box3").removeClass("bg-box3").addClass("bg-box4");
                currentClass = "bg-box4";
            } else if (currentClass === "bg-box4") {
                $(".bg-box4").removeClass("bg-box4").addClass("bg-box5");
                currentClass = "bg-box5";
            } else {
                $(".bg-box5").removeClass("bg-box5").addClass("bg-box");
                currentClass = "bg-box";
            }

            // สลับคลาสของ box-chart
            if (currentChartClass === "box-chart") {
                $(".box-chart").removeClass("box-chart").addClass("box-chart2");
                currentChartClass = "box-chart2";
            } else if (currentChartClass === "box-chart2") {
                $(".box-chart2").removeClass("box-chart2").addClass("box-chart3");
                currentChartClass = "box-chart3";
            } else if (currentChartClass === "box-chart3") {
                $(".box-chart3").removeClass("box-chart3").addClass("box-chart4");
                currentChartClass = "box-chart4";
            } else if (currentChartClass === "box-chart4") {
                $(".box-chart4").removeClass("box-chart4").addClass("box-chart5");
                currentChartClass = "box-chart5";
            } else {
                $(".box-chart5").removeClass("box-chart5").addClass("box-chart");
                currentChartClass = "box-chart";
            }
        });
    });

    document.getElementById('toggleSumHotelCharg').addEventListener('click', function() {
      var hotelManualCharge = document.getElementById('hotelManualCharge');
      var toggleIcon = document.getElementById('toggleSumHotelCharg');

      hotelManualCharge.classList.toggle('hidden');

        if (hotelManualCharge.classList.contains('hidden')) {
          toggleIcon.src = './image/front/lightbulb-grey.png'; // เปลี่ยนรูปเป็นไฟปิด
          toggleIcon.style.filter = 'none';
        } else {
        
            toggleIcon.src = './image/front/lightbulb.png'; // เปลี่ยนรูปเป็นไฟเปิด
          toggleIcon.style.filter = 'drop-shadow(0 0 10px rgb(43, 240, 191))';
        }
    });
</script>
@endsection

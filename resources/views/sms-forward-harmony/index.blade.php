@extends('layouts.masterLayoutHarmony')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            @php
                $this_week = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))); // อาทิตย์ - เสาร์
                $date_current = isset($search_date) ? $search_date : date('d/m/Y').' - '.date('d/m/Y'); // วันปัจจุบัน

                $exp_date = array_map('trim', explode(' - ', $date_current));

                if ($filter_by == 'date' && count($exp_date) == 2) {
                    $FormatDate = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0]);
                    $FormatDate2 = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[1]);
                } elseif ($filter_by == 'yesterday') {
                    $FormatDate = Carbon\Carbon::now()->subDays(1);
                    $FormatDate2 = Carbon\Carbon::now()->subDays(1);
                } elseif ($filter_by == 'tomorrow') {
                    $FormatDate = Carbon\Carbon::now()->addDays(1);
                    $FormatDate2 = Carbon\Carbon::now()->addDays(1);
                }

                $pickup_time = $date_current;

                if ($filter_by == 'date' && count($exp_date) != 2 || $filter_by == 'today') {
                    $pickup_time = date('d F Y', strtotime($search_date));
                } elseif ($filter_by == 'date' && count($exp_date) == 2) {
                    $pickup_time = date('d M', strtotime($FormatDate)) . " " . substr(date('Y', strtotime($FormatDate)), -2) . " ~ ". date('d M', strtotime($FormatDate2)) . " " . substr(date('Y', strtotime($FormatDate2)), -2);
                } elseif ($filter_by == 'yesterday') {
                    $pickup_time = date('d F Y', strtotime('-1 day'));
                } elseif ($filter_by == 'tomorrow') {
                    $pickup_time = date('d F Y', strtotime('+1 day'));
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

                ## Check Close Day
                if ($filter_by == 'date'  && count($exp_date) == 2 && $exp_date[0] == $exp_date[1] || $filter_by == 'today' || $filter_by == 'yesterday' || $filter_by == 'tomorrow') {
                    $close_day = App\Models\SMS_alerts::checkCloseDay($FormatDate);
                } 
                // elseif ($filter_by == 'week' || $filter_by == 'month' || $filter_by == 'thisMonth' || $filter_by == 'year' || $filter_by == 'thisYear') {
                //     $close_day = 1;
                // } 
                else {
                    $close_day = 0;
                }
            @endphp
            <!-- กล่อง เมนูข้างบน -->
            <div>
                <!-- กล่อง conternt ทั้งหมด -->
                <div id="content-index">
                    <!-- หัวข้างบน -->
                    <div class="f-jb-p2-mb3">
                        <div>
                            <h1 class="h-daily" style="white-space: nowrap;">Bank Transaction Revenue</h1>
                        </div>
                        <!-- Button เพิ่มข้อมูล -->
                        <div class="searh-box-bg">
                            <div>
                                <input type="text" id="select-date" class="showdate-button" style="width: 100%;" placeholder="{{ !empty($pickup_time) ? $pickup_time : date('d F Y') }}" readonly>
                            </div>
                            <button type="button" class="ch-button" data-toggle="modal" data-target="#ModalShowCalendar" style="white-space: nowrap;">
                                <span class="d-sm-none d-none d-md-inline-block">Search</span>
                                <i class="fa fa-search" style="font-size: 15px;"></i>
                            </button>
                            <button class="ch-button dropdown-toggle" type="button" id="dropdownMenuDaily" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-top: 0px; border-left: 0px">
                                <span id="txt-daily">
                                    @if ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == date('d/m/Y') && $exp_date[1] == date('d/m/Y') || $filter_by == 'today')
                                        Today
                                    @elseif ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == date('d/m/Y', strtotime('-1 day')) && $exp_date[1] == date('d/m/Y', strtotime('-1 day')) || $filter_by == 'yesterday')
                                        Yesterday
                                    @elseif ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == date('d/m/Y', strtotime('+1 day')) && $exp_date[1] == date('d/m/Y', strtotime('+1 day')) || $filter_by == 'tomorrow')
                                        Tomorrow
                                    @elseif (isset($filter_by) && $filter_by == 'week')
                                        This Week
                                    @elseif (isset($filter_by) && $filter_by == 'thisMonth')
                                        This Month
                                    @elseif (isset($filter_by) && $filter_by == 'thisYear')
                                        This Year
                                    @else
                                        Custom
                                    @endif
                                </span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuDaily">
                                <a class="dropdown-item" href="{{ route('harmony-sms-alert') }}">Today</a>
                                {{-- <a class="dropdown-item" href="#" onclick="search_daily('today')">Today</a> --}}
                                <a class="dropdown-item" href="#" onclick="search_daily('yesterday')">Yesterday</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('tomorrow')">Tomorrow</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('week')">This Week</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('thisMonth')">This Month</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('thisYear')">This Year</a>

                                <input type="hidden" name="" id="week-from" value="{{ date('Y-m-d', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))) }}">
                                <input type="hidden" name="" id="week-to" value="{{ date('d M', strtotime("+6 day", strtotime($this_week))) }}">
                            </div>
                            @if ($close_day == 0 || Auth::user()->edit_close_day == 1)
                                @if (@Auth::user()->roleMenuAdd('Bank Transaction Revenue', Auth::user()->id) == 1)
                                    <button type="button" class="ch-button" id="add-data" style="white-space: nowrap;">Add</button>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <!-- กล่องสรุปรายได้ card ที่ 1 -->
                    <div class="box-revenue-card">
                        <div class="box-revenue-card-bg">
                            <!-- รายได้ที่แสดงในวงกลม -->
                            <div class="box-revenue-card-inside" >
                                <div class="box-revenue-card-shownum">
                                    <h1>{{ number_format($total_day, 2) }}</h1>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                            <!-- card ที่แสดงที่มารายได้ -->
                            <div class="box-revenue-card-sub">
                                <!-- ข้อความรายการ ลำดับที่ 1-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('front')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Front Desk Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_front, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 2-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('room')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Guest Deposit Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_room, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 3-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('all_outlet')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>All Outlet Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_fb, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 4-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('water')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Water Park Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_wp, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 5-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('credit')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Credit Card Hotel</div>
                                                    <p class="f-sm ">Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_credit, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 6-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('credit_water')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Credit Card Water Park</div>
                                                    <p>Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_wp_credit, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 7-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('agoda_detail')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Agoda Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_agoda, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 8-->
                                <div class="box-sub-revenue">
                                    <a href="#" onclick="sms_detail('other_revenue')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Other Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_other, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- ข้อความรายการ ลำดับที่ 9-->
                                <div class="box-sub-revenue sp-2">
                                    <a href="#" onclick="sms_detail('elexa_revenue')">
                                        <div>
                                            <div class="box-sub-revenue-content text-white">
                                                <div>
                                                    <div>Elexa EGAT Bank</div>
                                                    <p>Transfer Revenue</p>
                                                </div>
                                                <div>
                                                    <div class="fz-15px">{{ number_format($total_ev, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- card แสดง Detail -->
                        <div class="box-detail">Detail &nbsp;</div>
                        <!-- card ที่แสดงที่มารายได้ -->
                        <div class="box-detail-g-4">
                            <!-- ข้อความรายการ ลำดับที่ 1-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('transfer_revenue')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Transfer Revenue</div>
                                            </div>
                                            <div>
                                                <div class="t-end">{{ number_format($total_transfer, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 2-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('split_revenue')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Split Credit Card Hotel</div>
                                                <p>Revenue</p>
                                            </div>
                                            <div>
                                                <div>{{ number_format($total_split, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 3-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('transfer_transaction')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Transfer Transaction</div>
                                                <div></div>
                                            </div>
                                            <div>
                                                <div>{{ $total_transfer2 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 4-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('credit_transaction')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Credit Card Hotel</div>
                                                <p>Transfer Transaction</p>
                                            </div>
                                            <div>
                                                <div>{{ $total_credit_transaction }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 5-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('split_transaction')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Split Credit Card</div>
                                                <p>Hotel Transaction</p>
                                            </div>
                                            <div>
                                                <div>{{ $total_split_transaction->transfer_transaction ?? 0 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 6-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('total_transaction')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>Total Transaction</div>
                                                <div></div>
                                            </div>
                                            <div>
                                                <div>{{ number_format(count($total_transaction)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 7-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('status')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>No Income Type</div>
                                                <div></div>
                                            </div>
                                            <div>
                                                <div>{{ $total_not_type, 2 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- ข้อความรายการ ลำดับที่ 8-->
                            <div class="box-sub-revenue bg-box-sub-revenue">
                                <a href="#" onclick="sms_detail('no_income_revenue')">
                                    <div>
                                        <div class="box-sub-revenue-content text-white">
                                            <div class="w-60p">
                                                <div>No Income Revenue</div>
                                                <div></div>
                                            </div>
                                            <div>
                                                <div>{{ number_format($total_not_type_revenue, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <br />
                    
                    <!-- card ครอบกราฟทั้งหมด -->
                    <div class="box-a-graph gy-5">
                        <!-- กราฟที่ 1 -->
                        <div class="box-graph">
                                <div class="container-graph" id="graphChartByMonthOrYear" style="grid-column: 1/3;">
                                    <canvas id="revenueChartByMonthOrYear" style="max-height: 40vh;"></canvas>

                                    @php
                                        if ($filter_by == 'date' && count($exp_date) == 2) {
                                            // ใช้ Carbon เพื่อแปลงวันที่
                                            $start_date = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0]); // แปลงวันที่เริ่มต้น
                                            $end_date = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[1]);   // แปลงวันที่สิ้นสุด
                                            
                                            // คำนวณจำนวนวันระหว่างสองวันที่
                                            $days_difference = $end_date->diffInDays($start_date); // คืนค่าจำนวนวัน

                                            // เพิ่ม 1 วันถ้าต้องการนับรวมวันที่เริ่มต้น
                                            $days_difference += 1;
                                        } else {
                                            $days_difference = 0;
                                        }
                                    @endphp

                                    @if ($filter_by == 'date' && count($exp_date) == 2 && $days_difference > 31)
                                        <a href="{{ route('harmony-sms-daterang-detail', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d'), $into_account ?? 0, $status ?? 0]) }}" type="button" class="ac-style">More</a>
                                    @endif

                                </div>

                                <div class="container-graph graph-date" hidden>
                                    <canvas id="revenueChart" hidden></canvas>
                                    <canvas id="revenueChartThisMonth" ></canvas>
                                    <canvas id="revenueChartCustom" hidden></canvas>
                                    <canvas id="revenueChartDateRang" hidden></canvas>
                                    <div class="menu-graph">
                                        <button type="button" class="ac-style" id="button-graph-revenue" data-toggle="modal" data-target="#myModalGraph" style="min-width: 25%;cursor: pointer;"> 7 Days</button>
                                    </div>
                                    <div>
                                        <!-- The Modal modal-->
                                        <div class="modal" id="myModalGraph">
                                            <div class="modal-dialog modal-bottom modal-sm">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" style="width: 100%;color: #2C7F7A ;">Filter Date</h4>
                                                        <button type="button" id="btn-close-myModalGraph" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="sub-ch-graph"
                                                            style="position:relative;top:0; flex-direction:column; justify-content: start; background-color:white">
                                                            <button type="button" value="7" onclick="updateChart(this.value)" class="modal-graph">
                                                                <div>
                                                                    <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 7 days ({{ date('d M', strtotime("-7 days", strtotime($date_current))) }} ~ {{ date('d M', strtotime($date_current)) }})
                                                                </div>
                                                                <div class="d-flex" style="font-size: 12px;"></div>
                                                            </button>
                                                            <button type="button" value="15" onclick="updateChart(this.value)" class="modal-graph">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 15 days ({{ date('d M', strtotime("-14 day", strtotime($date_current))) }} ~ {{ date('d M', strtotime($date_current)) }})
                                                            </button>
                                                            <button type="button" value="30" onclick="updateChart(this.value)" class="modal-graph">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 30 days ({{ date('d M', strtotime("-29 day", strtotime($date_current))) }} ~  {{ date('d M', strtotime($date_current)) }})
                                                            </button>
                                                            <button type="button" value="week" onclick="updateChartThisWeek(this.value)" class="modal-graph">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>This Week ({{ date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))) }} ~ {{ date('d M', strtotime("+6 day", strtotime($this_week))) }})
                                                            </button>
                                                            <button type="button" value="month" onclick="updateChartThisMonth(this.value)" class="modal-graph">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>This Month 
                                                            </button>
                                                            <button type="button" value="monthByDay" onclick="updateChartThisMonth(this.value)" class="modal-graph">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Monthly Average By Days
                                                            </button>
                                                            <label for="" class="label-grath-inside" style="padding: 0 0.5rem; text-align:left;">
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Custom Year Range
                                                            </label>
                                                            <div class="select-graph">
                                                                <select onchange="updateChartYearRange(this.value)">
                                                                    <option value="0">Select</option>
                                                                    @for ($i = 2024; $i <= date('Y') + 1; $i++)
                                                                        <option value="{{ $i }}" {{ isset($year) && $year == $i ? 'selected' : '' }}><i class="fa fa-square"></i>{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- กราฟที่ 2 -->
                                <div class="container-graph graph-date" hidden>
                                    <canvas id="combinedChart" style="width: 100%"></canvas>
                                    <div>
                                        <button class="graph-select " style="width: 200px;" id="toggleButton" value="to_time" onclick="toggleGraph()">
                                            Switch to Time <i class="icon-repeat"></i>
                                        </button>
                                    </div>
                                </div>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <a name="sms"></a>
                    <!-- ตารางที่ 1-->
                    @php
                        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
                    @endphp
                    <div class="container-xl mt-4">
                        <div class="row clearfix">
                            <div class="col-md-12 col-12">
                                <div class="table-d p-4 mb-4">
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label sm-500px-hidden">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-sms" onchange="getPage(1, this.value, 'sms')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                            </select>
                                            <input class="search-button search-data" id="sms" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <style>
                                        .example td:nth-child(4) {
                                            text-align: left !important;
                                            vertical-align: center !important;
                                        }
                                        </style>
                                    <div style="min-height: 70vh;">
                                        <table id="smsTable" class="example ui striped table nowrap unstackable hover" >
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;" data-priority="1">#</th>
                                                    <th style="text-align: center;" data-priority="1">Date</th>
                                                    <th style="text-align: center;">Time</th>
                                                    <th style="text-align: center;">From Bank Account</th>
                                                    <th style="text-align: center;">To Bank Account</th>
                                                    <th style="text-align: center;" data-priority="1">Amount</th>
                                                    <th style="text-align: center;">Creatd By</th>
                                                    <th style="text-align: center;">Income Type</th>
                                                    <th style="text-align: center;">Transfer Date</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_sms as $key => $item)
                                                    @if ($item->split_status == 3)
                                                        <tr style="text-align: center;" class="table-secondary">
                                                        @else
                                                        <tr style="text-align: center;">
                                                    @endif
    
                                                    <td class="td-content-center">{{ $key + 1 }}</td>
                                                    <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                    <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                                    <td class="td-content-center">
                                                        <?php
                                                            $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                                            $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                                        ?>
                                                        <div>
                                                            @if (file_exists($filename))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                            @elseif (file_exists($filename2))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                            @else
                                                                <img class="img-bank" src="../assets/images/harmony/bank_transfer.png">
                                                                @if ($item->transfer_form_account == '' || $item->transfer_form_account == '-')
                                                                    Bank Transfer
                                                                @endif
                                                            @endif
                                                            {{ @$item->transfer_bank->name_en.' '.@$item->transfer_form_account }}
                                                        </div>
                                                    </td>
                                                    <td class="td-content-center">
                                                        <div class="flex-jc p-left-4 center">
                                                            @if ($item->into_account == "871-0-11991-1")
                                                                <img class="img-bank" src="../image/bank/BBL.png"> {{ 'BBL ' . $item->into_account }}
                                                            @elseif ($item->into_account == "436-0-75511-1" || $item->into_account == "156-2-77492-1")
                                                                <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
                                                            @elseif ($item->into_account == "978-2-18099-9")
                                                                <img class="img-bank" src="../image/bank/KBNK.jpg"> {{ 'KBNK ' . $item->into_account }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="td-content-center">
                                                        {{ number_format($item->amount_before_split > 0 ? $item->amount_before_split : $item->amount, 2) }}
                                                    </td>
                                                    <td class="td-content-center">{{ $item->remark ?? 'Auto' }}</td>
                                                    <td class="td-content-center">
                                                        @if ($item->status == 0)
                                                            -
                                                        @elseif ($item->status == 1)
                                                            Guest Deposit Revenue
                                                        @elseif($item->status == 2)
                                                            All Outlet Revenue
                                                        @elseif($item->status == 3)
                                                            Water Park Revenue
                                                        @elseif($item->status == 4)
                                                            Credit Card Revenue
                                                        @elseif($item->status == 5)
                                                            Agoda Bank Transfer Revenue
                                                        @elseif($item->status == 6)
                                                            Front Desk Revenue
                                                        @elseif($item->status == 7)
                                                            Credit Card Water Park Revenue
                                                        @elseif($item->status == 8)
                                                            Elexa EGAT Revenue
                                                        @elseif($item->status == 9)
                                                            Other Revenue Bank Transfer
                                                        @endif
    
                                                        @if ($item->split_status == 1)
                                                            <br>
                                                            <span class="text-danger">(Split Credit Card From {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                                                        @endif
    
                                                    </td>
                                                    <td class="td-content-center">
                                                        {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td class="td-content-center" style="text-align: center;">
                                                        @if ($item->close_day == 0 || Auth::user()->edit_close_day == 1)
                                                            <div class="dropdown">
                                                                <button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                                                    Select <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    @if (@$role_revenue->front_desk == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                                            Front Desk Bank <br>Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->guest_deposit == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                                            Guest Deposit Bank <br> Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->all_outlet == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                                            All Outlet Bank <br> Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->agoda == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                                            Agoda Bank <br>Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->credit_card_hotel == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                                            Credit Card Hotel <br> Revenue 
                                                                        </li>
                                                                    @endif
                                                                    {{-- @if (@$role_revenue->elexa == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                            Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                        </li>
                                                                    @endif --}}
                                                                    @if (@$role_revenue->no_category == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                            No Category
                                                                        </li>
                                                                    @endif
                                                                    {{-- @if (@$role_revenue->water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                            Water Park Bank <br> Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->credit_water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                            Credit Card Water <br>Park Revenue 
                                                                        </li>
                                                                    @endif --}}
                                                                    @if (@@$role_revenue->other_revenue == 1)
                                                                        <li class="button-li" onclick="other_revenue_data({{ $item->id }})">
                                                                            Other Revenue <br> Bank Transfer
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->transfer == 1)
                                                                        <li class="button-li" onclick="transfer_data({{ $item->id }})">
                                                                            Transfer
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->time == 1)
                                                                        <li class="button-li" onclick="update_time_data({{ $item->id }})">
                                                                            Update Time
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->split == 1)
                                                                        <li class="button-li" onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                                            Split Revenue
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->edit == 1)
                                                                        <li class="button-li" onclick="edit({{ $item->id }})">Edit</li>
                                                                        <li class="button-li" onclick="deleted({{ $item->id }})">Delete</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <div class="py2" id="sms-showingEntries">{{ showingEntriesTable($data_sms, 'sms') }}</div>
                                            <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_sms_amount) ? $total_sms_amount->amount : 0 , 2) }} บาท</div>
                                                <div id="sms-paginate">
                                                    {!! paginateTable($data_sms, 'sms') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                        </div>
                                    </caption>
                                </div>
                                <!-- .card end -->
                            </div>
                        </div>
                        <!-- .row end -->
                    </div>

                    <a name="transfer"></a>
                    <!-- ตารางที่ 2-->
                    <div class="container-xl">
                        <div class="row clearfix">
                            <div class="col-md-12 col-12">
                                <div class="table-d p-4 mb-4">
                                    <h1 class="table-label">Transfer Revenue</h1>
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label sm-500px-hidden">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-transfer" onchange="getPage(1, this.value, 'transfer')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                            </select>
                                            <input class="search-button search-data" id="transfer" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <div style="min-height: 70vh;">
                                        <table id="transferTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;" data-priority="1">#</th>
                                                    <th style="text-align: center;" data-priority="1">Date</th>
                                                    <th style="text-align: center;">Time</th>
                                                    <th style="text-align: center;">From Bank Account</th>
                                                    <th style="text-align: center;">To Bank Account</th>
                                                    <th style="text-align: center;" data-priority="1">Amount</th>
                                                    <th style="text-align: center;">Creatd By</th>
                                                    <th style="text-align: center;">Income Type</th>
                                                    <th style="text-align: center;">Transfer Date</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_sms_transfer as $key => $item)
                                                        @if ($item->split_status == 3)
                                                            <tr style="text-align: center;" class="table-secondary">
                                                            @else
                                                            <tr style="text-align: center;" class="test">
                                                        @endif
        
                                                        <td class="td-content-center">{{ $key + 1 }}</td>
                                                        <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                        <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                                        <td class="td-content-center">
                                                            <?php
                                                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                                            ?>
                                                            <div>
                                                                @if (file_exists($filename))
                                                                    <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                                @elseif (file_exists($filename2))
                                                                    <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                                @else
                                                                    <img class="img-bank" src="../assets/images/harmony/bank_transfer.png">
                                                                    @if ($item->transfer_form_account == '' || $item->transfer_form_account == '-')
                                                                        Bank Transfer
                                                                    @endif
                                                                @endif
                                                                {{ @$item->transfer_bank->name_en.' '.@$item->transfer_form_account }}
                                                            </div>
                                                        </td>
                                                        <td class="td-content-center">
                                                            <div class="flex-jc p-left-4 center">
                                                                @if ($item->into_account == "871-0-11991-1")
                                                                    <img class="img-bank" src="../image/bank/BBL.png"> {{ 'BBL ' . $item->into_account }}
                                                                @elseif ($item->into_account == "436-0-75511-1" || $item->into_account == "156-2-77492-1")
                                                                    <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
                                                                @elseif ($item->into_account == "978-2-18099-9")
                                                                    <img class="img-bank" src="../image/bank/KBNK.jpg"> {{ 'KBNK ' . $item->into_account }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="td-content-center">
                                                            {{ number_format($item->amount_before_split > 0 ? $item->amount_before_split : $item->amount, 2) }}
                                                        </td>
                                                        <td class="td-content-center">{{ $item->remark ?? 'Auto' }}</td>
                                                        <td class="td-content-center">
                                                            @if ($item->status == 0)
                                                                -
                                                            @elseif ($item->status == 1)
                                                                Guest Deposit Revenue
                                                            @elseif($item->status == 2)
                                                                All Outlet Revenue
                                                            @elseif($item->status == 3)
                                                                Water Park Revenue
                                                            @elseif($item->status == 4)
                                                                Credit Card Revenue
                                                            @elseif($item->status == 5)
                                                                Agoda Bank Transfer Revenue
                                                            @elseif($item->status == 6)
                                                                Front Desk Revenue
                                                            @elseif($item->status == 7)
                                                                Credit Card Water Park Revenue
                                                            @elseif($item->status == 8)
                                                                Elexa EGAT Revenue
                                                            @elseif($item->status == 9)
                                                                Other Revenue Bank Transfer
                                                            @endif
        
                                                            @if ($item->split_status == 1)
                                                                <br>
                                                                <span class="text-danger">(Split Credit Card From
                                                                    {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                                                            @endif
        
                                                        </td>
                                                        <td class="td-content-center">
                                                            {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                                                        </td>
                                                        <td class="td-content-center" style="text-align: center;">
                                                            @if ($item->close_day == 0 || Auth::user()->edit_close_day == 1)
                                                                @if (($item->status != 4 && $item->remark == 'Auto') || Auth::user()->permission > 0)
                                                                    <div class="dropdown">
                                                                        <button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                                                            Select <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            @if (@$role_revenue->front_desk == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                                                    Front Desk Bank <br>Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->guest_deposit == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                                                    Guest Deposit Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->all_outlet == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                                                    All Outlet Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->agoda == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                                                    Agoda Bank <br>Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->credit_card_hotel == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                                                    Credit Card Hotel <br> Revenue 
                                                                                </li>
                                                                            @endif
                                                                            {{-- @if (@$role_revenue->elexa == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                                    Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                                </li>
                                                                            @endif --}}
                                                                            @if (@$role_revenue->no_category == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                                    No Category
                                                                                </li>
                                                                            @endif
                                                                            {{-- @if (@$role_revenue->water_park == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                                    Water Park Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->credit_water_park == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                                    Credit Card Water <br>Park Revenue 
                                                                                </li>
                                                                            @endif --}}
                                                                            @if (@@$role_revenue->other_revenue == 1)
                                                                                <li class="button-li" onclick="other_revenue_data({{ $item->id }})">
                                                                                    Other Revenue <br> Bank Transfer
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->transfer == 1)
                                                                                <li class="button-li" onclick="transfer_data({{ $item->id }})">
                                                                                    Transfer
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->time == 1)
                                                                                <li class="button-li" onclick="update_time_data({{ $item->id }})">
                                                                                    Update Time
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->split == 1)
                                                                                <li class="button-li" onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                                                    Split Revenue
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->edit == 1)
                                                                                <li class="button-li" onclick="edit({{ $item->id }})">Edit</li>
                                                                                <li class="button-li" onclick="deleted({{ $item->id }})">Delete</li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="transfer-showingEntries">{{ showingEntriesTable($data_sms_transfer, 'transfer') }}</p>
                                            <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_transfer_amount) ? $total_transfer_amount->amount : 0 , 2) }} บาท</div>
                                                <div id="transfer-paginate">
                                                    {!! paginateTable($data_sms_transfer, 'transfer') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                        </div>
                                    </caption>
                                </div>
                                <!-- .card end -->
                            </div>
                        </div>
                        <!-- .row end -->
                    </div>
                    <a name="split"></a>
                    <!-- ตารางที่ 3-->
                    <div class="container-xl">
                        <div class="row clearfix">
                            <div class="col-md-12 col-12">
                                <div class="table-d p-4 mb-4">
                                    <h1 class="table-label">Split Credit Card Hotel Revenue</h1>
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label sm-500px-hidden">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-split" onchange="getPage(1, this.value, 'split')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                            </select>
                                            <input class="search-button search-data" id="split" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <div style="min-height: 70vh;">
                                        <table id="splitTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;" data-priority="1">#</th>
                                                    <th style="text-align: center;" data-priority="1">Date</th>
                                                    <th style="text-align: center;">Time</th>
                                                    <th style="text-align: center;">From Bank Account</th>
                                                    <th style="text-align: center;">To Bank Account</th>
                                                    <th style="text-align: center;" data-priority="1">Amount</th>
                                                    <th style="text-align: center;">Creatd By</th>
                                                    <th style="text-align: center;">Income Type</th>
                                                    <th style="text-align: center;">Transfer Date</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_sms_split as $key => $item)
                                                    <tr style="text-align: center;" class="test">
                                                        <td class="td-content-center">{{ $key + 1 }}</td>
                                                        <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                        <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                                        <td class="td-content-center">
                                                            <?php
                                                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                                            ?>
                                                            <div>
                                                                @if (file_exists($filename))
                                                                    <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                                @elseif (file_exists($filename2))
                                                                    <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                                @else
                                                                    <img class="img-bank" src="../assets/images/harmony/bank_transfer.png">
                                                                    @if ($item->transfer_form_account == '' || $item->transfer_form_account == '-')
                                                                        Bank Transfer
                                                                    @endif
                                                                @endif
                                                                {{ @$item->transfer_bank->name_en.' '.@$item->transfer_form_account }}
                                                            </div>
                                                        </td>
                                                        <td class="td-content-center">
                                                            <div class="flex-jc p-left-4 center">
                                                                @if ($item->into_account == "871-0-11991-1")
                                                                    <img class="img-bank" src="../image/bank/BBL.png"> {{ 'BBL ' . $item->into_account }}
                                                                @elseif ($item->into_account == "436-0-75511-1" || $item->into_account == "156-2-77492-1")
                                                                    <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
                                                                @elseif ($item->into_account == "978-2-18099-9")
                                                                    <img class="img-bank" src="../image/bank/KBNK.jpg"> {{ 'KBNK ' . $item->into_account }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="td-content-center">
                                                            {{ number_format($item->amount_before_split > 0 ? $item->amount_before_split : $item->amount, 2) }}
                                                        </td>
                                                        <td class="td-content-center">{{ $item->remark ?? 'Auto' }}</td>
                                                        <td class="td-content-center">
                                                            @if ($item->status == 0)
                                                                -
                                                            @elseif ($item->status == 1)
                                                                Guest Deposit Revenue
                                                            @elseif($item->status == 2)
                                                                All Outlet Revenue
                                                            @elseif($item->status == 3)
                                                                Water Park Revenue
                                                            @elseif($item->status == 4)
                                                                Credit Card Revenue
                                                            @elseif($item->status == 5)
                                                                Agoda Bank Transfer Revenue
                                                            @elseif($item->status == 6)
                                                                Front Desk Revenue
                                                            @elseif($item->status == 7)
                                                                Credit Card Water Park Revenue
                                                            @elseif($item->status == 8)
                                                                Elexa EGAT Revenue
                                                            @elseif($item->status == 9)
                                                                Other Revenue Bank Transfer
                                                            @endif
        
                                                            @if ($item->split_status == 1)
                                                                <br>
                                                                <span class="text-danger">(Split Credit Card From {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                                                            @endif
        
                                                        </td>
                                                        <td class="td-content-center">
                                                            {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                                                        </td>
                                                        <td class="td-content-center" style="text-align: center;">
                                                            @if ($item->close_day == 0 || Auth::user()->edit_close_day == 1)
                                                                @if (($item->status != 4 && $item->remark == 'Auto') || Auth::user()->permission > 0)
                                                                    <div class="dropdown">
                                                                        <button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                                                            Select <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            @if (@$role_revenue->front_desk == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                                                    Front Desk Bank <br>Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->guest_deposit == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                                                    Guest Deposit Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->all_outlet == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                                                    All Outlet Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->agoda == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                                                    Agoda Bank <br>Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->credit_card_hotel == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                                                    Credit Card Hotel <br> Revenue 
                                                                                </li>
                                                                            @endif
                                                                            {{-- @if (@$role_revenue->elexa == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                                    Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                                </li>
                                                                            @endif --}}
                                                                            @if (@$role_revenue->no_category == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                                    No Category
                                                                                </li>
                                                                            @endif
                                                                            {{-- @if (@$role_revenue->water_park == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                                    Water Park Bank <br> Transfer Revenue 
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->credit_water_park == 1)
                                                                                <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                                    Credit Card Water <br>Park Revenue 
                                                                                </li>
                                                                            @endif --}}
                                                                            @if (@$role_revenue->other_revenue == 1)
                                                                                <li class="button-li" onclick="other_revenue_data({{ $item->id }})">
                                                                                    Other Revenue <br> Bank Transfer
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->transfer == 1)
                                                                                <li class="button-li" onclick="transfer_data({{ $item->id }})">
                                                                                    Transfer
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->time == 1)
                                                                                <li class="button-li" onclick="update_time_data({{ $item->id }})">
                                                                                    Update Time
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->split == 1)
                                                                                <li class="button-li" onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                                                    Split Revenue
                                                                                </li>
                                                                            @endif
                                                                            @if (@$role_revenue->edit == 1)
                                                                                <li class="button-li" onclick="edit({{ $item->id }})">Edit</li>
                                                                                <li class="button-li" onclick="deleted({{ $item->id }})">Delete</li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="split-showingEntries">{{ showingEntriesTable($data_sms_split, 'split') }}</p>
                                            <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_split_amount) ? $total_split_amount->amount : 0 , 2) }} บาท</div>
                                                <div id="split-paginate">
                                                    {!! paginateTable($data_sms_split, 'split') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                        </div>
                                    </caption>
                                </div>
                                <!-- .card end -->
                            </div>
                        </div>
                        <!-- .row end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .flex-container {
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: column;
            flex-direction: column;
            -webkit-justify-content: flex-start;
            justify-content: flex-start;
            -webkit-align-items: stretch; /* Changed from flex-start to stretch */
            align-items: stretch; /* Allows children to stretch to full width */
            width: 100%; /* Ensure the container spans full width */
        }

        .flex-item {
            -webkit-flex-grow: 1;
            flex-grow: 1;
            width: 100%; /* Ensures it spans full width */
            min-width: 0; /* Critical for flex items to shrink or grow properly */
        }

        input[type="time"] {
            padding-left: 15px; /* Adjust to force text towards the left */
            text-align: left;
            width: 100%;
            box-sizing: border-box;
            -webkit-appearance: none;
            appearance: none;
        }

        input[type="date"] {
            padding-left: 15px; /* Adjust to force text towards the left */
            text-align: left;
            width: 100%;
            box-sizing: border-box;
            -webkit-appearance: none;
            appearance: none;
        }

        input::-webkit-date-and-time-value { 
            text-align:left;
        }
    </style>

    <!-- Modal: Other Revenue -->
    <div class="modal fade" id="modalOtherRevenue" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="modalOtherRevenueLabel">Other Revenue Bank Transfer</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" class="basic-form" id="form-other">
                    @csrf
                    <div class="modal-body">
                        <label>หมายเหตุ</label>
                        <textarea class="form-control" name="other_revenue_remark" id="other_revenue_remark" rows="7" cols="50" placeholder="กรุณาระบุหมายเหตุ..." required></textarea>
                        <input type="hidden" name="dataID" id="otherDataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift" id="btn-save-other-revenue">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Transfer -->
    <div class="modal fade" id="exampleModalCenter2" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalCenter2Label">โอนย้าย</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" id="form-transfer" class="basic-form">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-12 col-12">
                            <label>วันที่โอนย้ายไป</label>
                            <div class="flex-container">    
                                <div class="flex-item">
                                    <input type="date" class="form-control" name="date_transfer" id="date_transfer">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mt-3">
                            <label>หมายเหตุ</label>
                            <textarea class="form-control" name="transfer_remark" id="transfer_remark" rows="5" cols="10" required>ปิดยอดช้ากว่ากำหนด</textarea>
                        </div>
                        <input type="hidden" name="dataID" id="dataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift" id="btn-save-transfer">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Time -->
    <div class="modal fade" id="exampleModalCenter1" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalCenter1Label">แก้ไขเวลาการโอน</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12">
                        <label>เวลา</label>
                        <div class="flex-container">                        
                            <div class="flex-item">
                                <input type="time" class="form-control" name="update_time" id="update_time" value="<?php echo date('H:i:s'); ?>" step="any">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="timeID" id="timeID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-color-green lift" onclick="change_time()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Split -->
    <div class="modal fade" id="SplitModalCenter" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="SplitModalCenterLabel">Split Revenue</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" class="form-split">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <label>วันที่</label>
                            <div class="flex-container">    
                                <div class="flex-item">
                                    <input type="date" class="form-control" name="date-split" id="date-split">
                                    <span class="text-danger fw-bold" id="text-split-alert"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>จำนวนเงิน <span class="text-danger fw-bold" id="text-split-amount"></span></label>
                            &nbsp;<label>คงเหลือ <span class="text-danger fw-bold" id="text-split-balance"></span></label>
                            <input type="hidden" name="balance_amount" id="balance_amount">
                            <input type="text" class="form-control" name="split-amount" id="split-amount" placeholder="0.00">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="button" class="btn btn-color-green lift btn-split-add">Add</button>
                            <button type="button" class="btn btn-secondary lift btn-split-add" onclick="toggleHide()">Delete All</button>

                            <span class="split-todo-error text-danger" style="display: none;">กรุณาระบุข้อมูลให้ครบ !</span>
                            <span class="split-error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="print_invoice">
                                <table class="items">
                                    <thead>
                                        <tr>
                                            <th>วันที่</th>
                                            <th>จำนวนเงิน</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="split-todo-list">
    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="split_total_number" value="0">
                        <input type="hidden" id="split_number" value="0">
                        <input type="hidden" id="split_list_num" name="split_list_num" value="0">
                        <input type="hidden" name="splitID" id="splitID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift btn-save-split" onclick="change_split()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่มข้อมูล modal fade -->
    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter5Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-lg">
                <div class="modal-header md-header">
                    <h5 class="modal-title text-white" id="exampleModalCenter5Label">Add</h5>
                    <button type="button" class="close text-white text-2xl" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('harmony-sms-store') }}" method="POST" class="" id="form-id">
                    @csrf
                    <div class="modal-body">
                        <label for="">ประเภทรายได้</label> 
                        <br>
                        <select class="form-control form-select" id="status" name="status" onchange="select_type()">
                            <option value="0">เลือกข้อมูล</option>
                            <option value="6">Front Desk Bank Transfer Revenue</option>
                            <option value="1">Guest Deposit Bank Transfer Revenue</option>
                            <option value="2">All Outlet Bank Transfer Revenue</option>
                            <option value="4">Credit Card Revenue</option>
                            <option value="5">Credit Card Agoda Revenue</option>
                            {{-- <option value="3">Water Park Bank Transfer Revenue</option> --}}
                            {{-- <option value="7">Credit Card Water Park Revenue</option> --}}
                            {{-- <option value="8">Elexa EGAT Bank Transfer Revenue</option> --}}
                            <option value="9">Other Bank Transfer Revenue</option>
                        </select>
                        <div class="dg-gc2-g2">
                            <div class="wf-py2 ">
                                <label for="">วันที่โอน <sup class="t-red600">*</sup></label>
                                <br>
                                <div class="flex-container">    
                                    <div class="flex-item">
                                        <input class="form-control" type="date" name="date" id="sms-date" onkeydown="return false;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เวลาที่โอน <sup class="text-danger">*</sup></label>
                                <br>
                                <div class="flex-container">    
                                    <div class="flex-item">
                                        <input class="form-control" type="time" name="time" id="sms-time" onkeydown="return false;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="wf-py2 Amount agoda" hidden>
                                <label for="">Booking ID <sup class="text-danger">*</sup></label>
                                <br>
                                <input type="text" class="form-control" name="booking_id" id="booking_id" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">โอนจากบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="transfer_from" name="transfer_from">
                                    <option value="0">เลือกข้อมูล</option>
                                    @foreach ($data_bank as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}
                                            ({{ $item->name_en }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="wf-py2">
                                <label for="">โอนจากเลขที่บัญชี<sup class="text-danger">*</sup></label>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-text">xxx-x-x</span>
                                    <input type="number" class="form-control" name="transfer_account" id="transfer-account" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" required>
                                    <span class="input-group-text">-x</span>
                                </div>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เข้าบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="add_into_account" name="into_account" data-placeholder="Select">
                                    <option value="">เลือกข้อมูล</option>
                                    <option value="436-0-75511-1">ธนาคารไทยพาณิชย์ (SCB) 436-0-75511-1</option>
                                    <option value="156-2-77492-1">ธนาคารไทยพาณิชย์ (SCB) 156-2-77492-1</option>
                                    <option value="871-0-11991-1">ธนาคารกรุงเทพ (BBL) 871-0-11991-1</option>
                                    <option value="978-2-18099-9">ธนาคารกสิกรไทย (KBNK) 978-2-18099-9</option>
                                </select>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup></label>
                                <br>
                                <input class="form-control" type="text" id="amount" name="amount" placeholder="0.00" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">วันที่โอนย้าย</label>
                                <br>
                                <div class="flex-container">    
                                    <div class="flex-item">
                                        <input class="form-control" type="date" name="date_transfer" id="sms-date-transfer" onkeydown="return false;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 15px;">Close</button>
                        <button type="button" class="btn btn-color-green sa-button-submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: เลือกวันที่ modal fade -->
    <div class="modal fade" id="ModalShowCalendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-bottom md-330px" role="document" >
            <div class="modal-content rounded-xl">
                <div class="modal-header md-header text-white">
                    <div class="w-full">
                        <h5 class=".modal-hd">ค้นหารายการ</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('harmony-sms-search-calendar') }}" method="POST" enctype="multipart/form-data" id="form-calendar">
                    @csrf
                    <div class="modal-body">
                        <!-- Modal: เลือกวันที่ modal fade -->
                        <div style="place-items: center;">
                            <div class="center py-2" style="gap: 0.3rem; width: 100%">
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-date">Filter by Date</button>
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-month">Filter by Month</button>
                                <button type="button" class="bt-tg-normal bg-tg-light sm flex-grow-1 filter" id="filter-year">Filter by Year</button>
                            </div>
                            <div class="center w-100" style="gap:0.3rem;">
                                <select class="form-control select2" id="add_into_account" name="into_account" data-placeholder="Select">
                                    <option value="" {{ isset($into_account) && $into_account == '' ? 'selected' : '' }}>เลขที่บัญชีทั้งหมด</option>
                                    <option value="436-0-75511-1" {{ isset($into_account) && $into_account == '436-0-75511-1' ? 'selected' : '' }}>ธนาคารไทยพาณิชย์ (SCB) 436-0-75511-1</option>
                                    <option value="156-2-77492-1" {{ isset($into_account) && $into_account == '156-2-77492-1' ? 'selected' : '' }}>ธนาคารไทยพาณิชย์ (SCB) 156-2-77492-1</option>
                                    <option value="871-0-11991-1" {{ isset($into_account) && $into_account == '871-0-11991-1' ? 'selected' : '' }}>ธนาคารกรุงเทพ (BBL) 871-0-11991-1</option>
                                    <option value="978-2-18099-9" {{ isset($into_account) && $into_account == '978-2-18099-9' ? 'selected' : '' }}>ธนาคารกสิกรไทย (KBNK) 978-2-18099-9</option>
                                </select>

                                <!-- tooltip -->
                                <div data-tooltip-target="tooltip-default" class="relative tooltip-1">
                                    <span class="fa fa-info-circle"></span>
                                </div>
                                <div id="tooltip-default" role="tooltip" class="absolute tooltip-2"> 
                                    <div id="bank-note">
                                        @if (isset($bank_note) && $bank_note != '')
                                            {!! $bank_note !!}
                                        @else
                                            Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue <br>
                                            Credit Card Hotel Revenue <br>
                                            Warter Park & Credit Card Warter Park Revenue <br>
                                        @endif
                                    </div>
                                    <div class="tooltip-arrow text-black" data-popper-arrow></div>
                                </div>
                            </div>

                            <select class="selected-value-box" name="status" id="search-status">
                                <option value="" {{ isset($status) && $status == '' ? 'selected' : '' }}>ประเภทรายได้ทั้งหมด</option>
                                <option value="6" {{ isset($status) && $status == 6 ? 'selected' : '' }}>Front Desk Bank Transfer Revenue</option>
                                <option value="1" {{ isset($status) && $status == 1 ? 'selected' : '' }}>Guest Deposit Bank Transfer Revenue</option>
                                <option value="2" {{ isset($status) && $status == 2 ? 'selected' : '' }}>All Outlet Bank Transfer Revenue</option>
                                <option value="4" {{ isset($status) && $status == 4 ? 'selected' : '' }}>Credit Card Revenue</option>
                                <option value="5" {{ isset($status) && $status == 5 ? 'selected' : '' }}>Credit Card Agoda Revenue</option>
                                {{-- <option value="3" {{ isset($status) && $status == 3 ? 'selected' : '' }}>Water Park Bank Transfer Revenue</option> --}}
                                {{-- <option value="7" {{ isset($status) && $status == 7 ? 'selected' : '' }}>Credit Card Water Park Revenue</option> --}}
                                {{-- <option value="8" {{ isset($status) && $status == 8 ? 'selected' : '' }}>Elexa EGAT Bank Transfer Revenue</option> --}}
                                <option value="9" {{ isset($status) && $status == 9 ? 'selected' : '' }}>Other Bank Transfer Revenue</option>
                            </select>

                            <input type="text" id="combined-selected-box" name="date" value="{{ $date_current }}" class="selected-value-box t-alight-center w-100"/>
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

                        <!-- Input ส่งค่าไป Controller -->
                        <input type="hidden" id="filter-by" name="filter_by" value="{{ isset($filter_by) ? $filter_by : 'date' }}">
                        {{-- <input type="hidden" id="date" name="date" value="{{ $date_current }}"> --}}
                        <!-- ประเภทรายได้ -->
                        <input type="hidden" id="revenue-type" name="revenue_type" value="">

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
                </form>
            </div>
        </div>
    </div>
    
    <input type="hidden" id="get-total-sms" value="{{ !empty($total_sms_amount) ? $total_sms_amount->total_sms : 0 }}">
    <input type="hidden" id="get-total-transfer" value="{{ !empty($total_transfer_amount) ? $total_transfer_amount->total_transfer : 0 }}">
    <input type="hidden" id="get-total-split" value="{{ !empty($total_split_amount) ? $total_split_amount->total_split : 0 }}">
    <input type="hidden" id="currentPage-sms" value="1">
    <input type="hidden" id="currentPage-transfer" value="1">
    <input type="hidden" id="currentPage-split" value="1">

    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <!-- style สำหรับเปิดปิด custom date -->

    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Calendar -->
    <link rel="stylesheet" href="{{ asset('assets/src/calendar-draft-litePicker.css') }}?v={{ time() }}">
    <script src="{{ asset('assets/js/calendar-draft-noDate.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableHarmony.js')}}"></script>

    <!-- Sweet Alert 2 -->
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <!-- card graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="{{ asset('assets/graph/harmonyGraphUpdateDay.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/graph/harmonyGraphCondition.js')}}"></script>

    <style>
        .content-col {
            display: none;
        }

        .active-customdate {
            display: block;
        }

        .select-graph select {
            appearance: none;
            outline: 10px red;
            border: 0;
            box-shadow: none;
            background-color: #2C7F7A;
            color:white;
            flex: 1;
            padding: 0 1em;
            background-image: none;
            cursor: pointer;
            text-align: center;
        }

        .select-graph select option {
            background-color: aliceblue;
            color:#2c7f7a;
        }

        .select-graph {
            position: relative;
            display: flex;
            height: 3em;
            border-radius: .25em;
            overflow: hidden;
        }
        .select-graph:after {
            content: '\25BC';
            position: absolute;
            top: 0;
            right: 0;
            padding: 1em;
            transition: .25s all ease;
            pointer-events: none;
        }

        .select-graph:hover:after {
            color: #f39c12;
        }

        .label-grath-inside {
            text-align:left;
            color: black;
        }

        .swal-title-custom {
            font-size: 19px;
            font-weight: bold;
            color: #202020; 
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
            new DataTable('.example', {
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                    className: 'dtr-control',
                    orderable: true,
                    target: null,
                }],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });

            // Calendar
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
            // END Calendar

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

            // Graph
            if (filter_by == "month") {
                var dateString = $('#combined-selected-box').val();
                var dateSplit = dateString.split('-');

                if (dateSplit.length == 1) {
                    var fDate_start = moment(dateSplit[0]).format('MM');
                    var fDate_end = moment(dateSplit[0]).format('MM');
                    var fYear = moment(dateSplit[0]).format('YYYY');
                } else {
                    var fDate_start = moment(dateSplit[0], "MMMM").format('MM');
                    var fDate_end = moment(dateSplit[1]).format('MM');
                    var fYear = moment(dateSplit[1]).format('YYYY');
                }

                var start_month = fDate_start;
                var end_month = fDate_end;
                var year = fYear;

                chartMonthToMonth(start_month, end_month, year);
                $('.graph-date').prop('hidden', true);
                $('#graphChartByMonthOrYear').prop('hidden', false);

            } if (filter_by == "thisMonth") {
                var dateString = new Date();
                var start_month = dateString.getMonth() + 1;
                var end_month = dateString.getMonth() + 1;
                var year = dateString.getFullYear();

                chartThisMonth2(start_month, end_month, year);
                $('.graph-date').prop('hidden', true);
                $('#graphChartByMonthOrYear').prop('hidden', false);

            } if (filter_by == "year") { 
                var year = $('#combined-selected-box').val();
                
                chartFilterByYear(year);
                $('.graph-date').prop('hidden', true);
                $('#graphChartByMonthOrYear').prop('hidden', false);

            } if (filter_by == "thisYear") { 
                var dateString = new Date();
                var year = dateString.getFullYear();
                
                chartFilterByYear(year);
                $('.graph-date').prop('hidden', true);
                $('#graphChartByMonthOrYear').prop('hidden', false);

            } if (filter_by == "date" || filter_by == "today" || filter_by == "tomorrow" || filter_by == "yesterday") {
                updateChart(7);
                $('.graph-date').prop('hidden', false);
                $('#graphChartByMonthOrYear').prop('hidden', true);

            } if (filter_by == "week") {
                chartWeek(7);
                $('.graph-date').prop('hidden', true);
                $('#graphChartByMonthOrYear').prop('hidden', false);
            }

            // Calendar
            if (filter_by == "date" || filter_by == "today" || filter_by == "tomorrow" || filter_by == "yesterday") {
                var dateString = $('#combined-selected-box').val();
                var dateSplit = dateString.split(' - ');

                if (dateSplit[0] != dateSplit[1]) {
                    var start_date = moment(dateSplit[0], "DD/MM/YYYY").format('YYYY-MM-DD');
                    var end_date = moment(dateSplit[1], "DD/MM/YYYY").format('YYYY-MM-DD');
                    var interval_days = moment(start_date).daysInMonth(); // จำนวนวันที่ต้องการเช็ค
                    var first_date = null; // เก็บวันที่แรกที่ครบ 30 วัน

                    // เปลี่ยนวันที่เริ่มต้นให้อยู่ในรูปแบบ Moment.js
                    var current_date = moment(start_date);

                    // วนลูปเพื่อหา "วันที่แรกที่ครบ 31 วัน"
                    while (current_date.isBefore(end_date)) {
                        var next_date = current_date.clone().add(interval_days, 'days'); // บวก 30 วัน
                        if (next_date.isSameOrBefore(end_date)) {
                            first_date = next_date.subtract(1, 'days').format('YYYY-MM-DD'); // แปลงวันที่เป็นรูปแบบที่ต้องการ
                            break; // หยุดลูปทันทีเมื่อเจอวันที่แรก
                        }
                        current_date = next_date; // อัปเดตวันที่ปัจจุบัน
                    }

                    if (first_date) { // มีค่าวันที่แรกที่ครบ 31 วัน
                        end_date = first_date;
                    }
                    
                    chartDateRang(start_date, end_date);
                    $('.graph-date').prop('hidden', true);
                    $('#graphChartByMonthOrYear').prop('hidden', false);
                } else {
                    var dateString = $('#date').val();
                    var date = new Date(dateString);
                    var date_now = date.getDate() + ' ' + (date.getMonth() + 1) + ' ' + date.getFullYear();

                    document.getElementById("myDay").innerHTML = date_now;
                }
            }
        });

        $('.ch-button').on('click', function () {
            $('#filter-by').val("date");
        });

        //เปิดปิด coustome date range
        $(document).on("click", function (event) {
            if ($(event.target).closest(".button-row").length) {
                return;
            }
            // Add internal reference
            $(".target-2").addClass("gStarter");
            $(".content-col").addClass("gColumn");
            if ($(event.target).hasClass("target-2") && $(event.target).prop("tagName") == "BUTTON") {
                if (!$(".target-2").parent().next().children().hasClass("active-customdate")) {
                    $(".target-2").parent().next().children().addClass("active-customdate");
                } else {
                    $(".target-2").parent().next().children().removeClass("active-customdate");
                    $(".target-2").removeAttr("data-starter");
                }
            } else {
                if (!$(".target-2").closest().parent().next().children().is(event.target)) {
                    if ($(".target-2").parent().next().children().is(":visible")) {
                        $(".target-2").parent().next().children().removeClass("active-customdate");
                    }
                }
            }
        });

        // Search 
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var total = parseInt($('#get-total-'+id).val());
            var table_name = id+'Table';

            var filter_by = $('#filter-by').val();
            var dateString = $('#combined-selected-box').val();
            var type = $('#status').val();
            var account = $('#into_account').val();
            var count_total = 0;
            var getUrl = window.location.pathname;

            $('#'+table_name).DataTable().destroy();
            var table = $('#'+table_name).dataTable({
                searching: false,
                paging: false,
                info: false,
                // "ajax": "sms-search-table/"+search_value+"/"+table_name+"",
                ajax: {
                    url: 'harmony-sms-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        date: dateString,
                        status: type,
                        into_account: account
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings, json) {

                    if ($('#'+id+'Table .dataTables_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    } else {
                        var count = 0;
                        $('.dataTables_empty').addClass('dt-center');
                    }

                    if (search_value == '') {
                        count_total = total;
                    } else {
                        count_total = count;
                    }
                    
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1, count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                columnDefs: [
                            { targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], className: 'dt-center td-content-center' },
                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: 'date' },
                    { data: 'time' },
                    { data: 'transfer_bank' },
                    { data: 'into_account' },
                    { data: 'amount' },
                    { data: 'remark' },
                    { data: 'revenue_name' },
                    { data: 'date_into' },
                    { data: 'btn_action' },
                ],
                    
            });  

            document.getElementById(id).focus();
        });

        function sms_detail(revenue_name) 
        {
            $('#revenue-type').val(revenue_name);
            $('#form-calendar').submit();
        }

        function AddOrSubractDays(startingDate, number, add) 
        {
            if (add) {
                return new Date(new Date().setDate(startingDate.getDate() + number));
            } else {
                return new Date(new Date().setDate(startingDate.getDate() - number));
            }
        }

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
                var startDate = moment().subtract(1, 'days').format('DD/MM/YYYY');
                var endDate = moment().subtract(1, 'days').format('DD/MM/YYYY');
                $('#txt-daily').text("Yesterday");
            } 

            if ($search == 'tomorrow') {
                var startDate = moment().add(1, 'days').format('DD/MM/YYYY');
                var endDate = moment().add(1, 'days').format('DD/MM/YYYY');
                $('#txt-daily').text("Tomorrow");
            } 

            if ($search == 'week') {
                var startDate = moment().format('DD/MM/YYYY');
                var endDate = moment().format('DD/MM/YYYY');
                $('#txt-daily').text("This Week");
            }
            
            if ($search == 'thisMonth') {
                var startDate = moment().format('DD/MM/YYYY');
                var endDate = moment().format('DD/MM/YYYY');
                $('#txt-daily').text("This Month");
            }

            if ($search == 'thisYear') {
                var startDate = moment().format('DD/MM/YYYY');
                var endDate = moment().format('DD/MM/YYYY');
                $('#txt-daily').text("This Year");
            }

            $('#filter-by').val($search);
            $('#combined-selected-box').val(startDate+" - "+endDate);
            $('#revenue-type').val('');
            $('#form-calendar').submit();
        }

        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        // ประเภทรายได้
        function change_status($id, $status) {
            
            jQuery.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-change-status/"+$id+"/"+$status+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        }

        // ประเภทรายได้ Other Revenue
        function other_revenue_data(id) {
            $('#otherDataID').val(id);
            $('#modalOtherRevenue').modal('show');

            jQuery.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-get-remark-other-revenue/"+id+"') !!}",
                datatype: "JSON",
                cache: false,
                async: false,
                success: function(response) {
                    if (response.data !== null) {
                        $('#other_revenue_remark').val(response.data.other_remark);
                    } else {
                        $('#other_revenue_remark').val('');
                    }
                },
            });
        }

        function transfer_data(id) {
            $('#dataID').val(id);
            $('#exampleModalCenter2').modal('show');
        }

        function update_time_data(id) {
            $('#timeID').val(id);
            $('#exampleModalCenter1').modal('show');
        }

        function change_time() {
            var time = $('#update_time').val();
            var id = $('#timeID').val();

            $.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-update-time/"+id+"/"+time+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        }

        function split_data($id, $amount) {
            $('#splitID').val($id);
            $('#text-split-amount').text("(" + currencyFormat($amount) + ")");
            $('#text-split-balance').text("(" + currencyFormat($amount) + ")");
            $('#balance_amount').val($amount);
            $('#SplitModalCenter').modal('show');
        }

        function change_split() {
            jQuery.ajax({
                url: "{!! url('harmony-sms-update-split') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: $('.form-split').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        }

        $(document).on('click', '#btn-save-other-revenue', function () {
            var id = $('#otherDataID').val();
            var remark = $('#other_revenue_remark').val();

            jQuery.ajax({
                type: "POST",
                url: "{!! url('harmony-sms-other-revenue') !!}",
                datatype: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {dataID: id, other_revenue_remark: remark},
                cache: false,
                async: false,
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        });

        $(document).on('click', '#btn-save-transfer', function () {

            jQuery.ajax({
                type: "POST",
                url: "{!! url('harmony-sms-transfer') !!}",
                datatype: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#form-transfer').serialize(),
                cache: false,
                async: false,
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                    }
                },
            });
        });

        $('.btn-split-add').on('click', function() {
            var date_split = $('#date-split').val();
            var amount = $('#split-amount').val();
            var list = parseInt($('#split_list_num').val());
            var number = parseInt($('#split_number').val()) + 1;
            var total_amount = $('#split_total_number').val();
            var balance = $('#balance_amount').val();
            $('#split_number').val(number);

            if (date_split && amount) {
                var sum_split_revenue = parseFloat(total_amount) + parseFloat(amount);
                var total_split_revenue = Math.round(sum_split_revenue * 100) / 100;

                if (total_split_revenue <= balance) {
                    var date = "";
                    var date_fm = new Date(date_split);
                    var year = date_fm.getFullYear();
                    var month = (1 + date_fm.getMonth()).toString().padStart(2, '0');
                    var day = date_fm.getDate().toString().padStart(2, '0');
                    date = day + '/' + month + '/' + year;

                    $('.split-todo-list').append(
                        '<tr>' +
                        '<td>' + date + '</td>' +
                        '<td>' + currencyFormat(parseFloat(amount)) + '</td>' +
                        '<td style="text-align: center;"><i class="fa fa-trash text-danger close" onClick="toggleClose(this, ' +
                        amount + ')"></i></td>' +
                        '<input type="hidden" name="date_split[]" value="' + date_split + '">' +
                        '<input type="hidden" name="amount_split[]" value="' + amount + '">' +
                        '</tr>'
                    );

                    $('#split_total_number').val(total_split_revenue);
                    $('#date-split').val('');
                    $('#split-amount').val('');
                    $('.split-todo-error').hide();
                    $('.split-error').text("");
                

                    if ($('#split_total_number').val() == balance) {
                        $('.btn-save-split').prop('disabled', false);
                    } else {
                        $('.btn-save-split').prop('disabled', true);
                    }
                } else {
                    $('.split-error').text("จำนวนเงินมากกว่ายอดคงเหลือ");
                }

            } else {
                $('.split-todo-error').show();
            }

            $('#text-split-balance').text("("+currencyFormat(balance - Number($('#split_total_number').val()))+")");
        });

        $('.split-todo-list .close').on('click', function() {
            toggleClose(this);
        });

        function toggleClose(ele, amount) {
            var total_amount = $('#split_total_number').val();
            $('#split_total_number').val(parseFloat(total_amount) - parseFloat(amount));
            $(ele).parent().parent().remove();

            if ($('#split_total_number').val() == $('#balance_amount').val()) {
                $('.btn-save-split').prop('disabled', false);
            } else {
                $('.btn-save-split').prop('disabled', true);
            }

            $('#text-split-balance').text("("+currencyFormat(Number($('#balance_amount').val()) - Number($('#split_total_number').val()))+")");
        }

        function toggleHide() {
            $('#split_total_number').val(0);
            $('.split-todo-list tr').remove();
        }

        $('#add-data').on('click', function() {
            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#transfer-account').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');

            $('#id').val('');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#transfer-account').val('');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            $('#exampleModalCenter5').modal('show');

            $('#transfer_from').select2({
                dropdownParent: $('#exampleModalCenter5')
            });

            $('#add_into_account').select2({
                dropdownParent: $('#exampleModalCenter5')
            });

            $('#transfer-account').prop('disabled', false);
        });

        function select_type() {
            var type = $('#status').val();

            if (type == 5) {
                $('.agoda').prop('hidden', false);
            } else {
                $('.agoda').prop('hidden', true);
            }

            if (type == 4) {
                $('#transfer_from').val(15).trigger('change');
                $('#add_into_account').val("708-2-26792-1").trigger('change');
            } else {
                $('#transfer_from').val(0).trigger('change');
                $('#add_into_account').val(0).trigger('change');
            }

            if (type == 7) {
                $('#transfer_from').val(15).trigger('change');
            } else {
                $('#transfer_from').val(0).trigger('change');
                $('#add_into_account').val(0).trigger('change');
            }
        }

        function select_account() {
            var account = $('#into_account').val();
            $('#bank-note').html("");

            if (account == "708-2-26791-3") {
                $('#bank-note').append('Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue">');
            }

            if (account == "708-2-26792-1") {
                $('#bank-note').append('Credit Card Hotel Revenue');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Credit Card Hotel Revenue">');
            }

            if (account == "708-2-27357-4") {
                $('#bank-note').append('Warter Park & Credit Card Water Park Revenue');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Warter Park & Credit Card Water Park Revenue">');
            }

            if (account == "") {
                $('#bank-note').append('Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue <br>' +
                    'Credit Card Hotel Revenue <br>' +
                    'Warter Park & Credit Card Warter Park Revenue <br>');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue <br>' +
                    'Credit Card Hotel Revenue <br>' +
                    'Warter Park & Credit Card Warter Park Revenue <br>">');
            }
        }

        function edit($id) {
            $('#exampleModalCenter5').modal('show');
            $('#id').val($id);
            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#transfer-account').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#transfer-account').val('');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            $('#transfer-account').prop('disabled', false);
 
            jQuery.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-edit/"+$id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var myArray = response.data.date.split(" ");

                        if (response.data.date_into != null) {
                            var myArray2 = response.data.date_into.split(" ");
                        }

                        if (response.data.transfer_form_account != null) {
                            var transfer_account = response.data.transfer_form_account.replace(/\D/g, '');
                            if (transfer_account.length > 4) {
                                $('#transfer-account').prop('disabled', true);
                            }
                        } else {
                            var transfer_account = '';
                        }

                        $('#status').val(response.data.status).trigger('change');
                        $('#sms-date').val(myArray[0]);
                        $('#sms-time').val(myArray[1]);
                        $('#booking_id').val(response.data.booking_id);
                        $('#transfer_from').val(response.data.transfer_from).trigger('change');
                        $('#transfer-account').val(transfer_account);
                        $('#add_into_account').val(response.data.into_account).trigger('change');
                        $('#amount').val(response.data.amount);
                        if (response.data.date_into != null) {
                            $('#sms-date-transfer').val(myArray2[0]);
                        }
                    }
                },
            });
        }

        function deleted($id) {
            Swal.fire({
                icon: "info",
                title: 'คุณต้องการลบใช่หรือไม่?',
                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',
                showCancelButton: true,
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('harmony-sms-delete/"+$id+"') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(response) {
                            if (response.status == 200) {
                                Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                                location.reload();
                            } else {
                                Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ response.message +' แล้ว', 'error');
                            }
                        },
                    });

                } else if (result.isDenied) {
                    Swal.fire('ลบข้อมูลไม่สำเร็จ!', '', 'info');
                }
            });
        }

        $('#btn-search-date').on('click', function () {
            $('#form-calendar').submit();

        });

        // Sweetalert2 #กรอกข้อมูลไม่ครบ
        document.querySelector(".sa-button-submit").addEventListener('click', function() {
            var date = $('#sms-date').val();
            var time = $('#sms-time').val();
            var transfer = $('#error-transfer').val();
            var into = $('#error-into').val();
            var amount = $('#amount').val();
            var type = $('#status').val();

            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');

            if (date == '') {
                $('#sms-date').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (type != 5 && time == '') {
                $('#sms-time').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (transfer == 0) {
                $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (into == 0) {
                $('#error-transfer').css('border', '1px solid red').css("border-radius", 5);

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });
            }

            if (amount == '') {
                $('#amount').css('border-color', 'red');

                return Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                    text: 'กรุณาระบุข้อมูลให้ครบ!',
                });

            } else {

                var adate = new Date(date);
                var month = adate.getMonth() + 1;
                date = adate.getDate() +'/'+ month.toString().padStart(2, '0') +'/'+ adate.getFullYear();

                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('harmony-sms-store') !!}",
                    datatype: "JSON",
                    data: $('#form-id').serialize(),
                    async: false,
                    success: function(response) {
                        if (response.status == 200) {
                            Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        } else {
                            Swal.fire('ไม่สามารถทำรายการได้!', 'ระบบได้ทำการปิดยอดวันที่ '+ date +' แล้ว', 'error');
                        }
                    },
                });
            }
        });

        // for modal เพิ่มข้อมูล
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        })

        //เปิดปิด coustome date range
        $(document).on("click", function(event) {
            if ($(event.target).closest(".button-row").length) {
                return;
            }
            // Add internal reference
            $(".target-2").addClass("gStarter");
            $(".content-col").addClass("gColumn");
            // Check if the elem been clicked has target-2 as class
            // and if the tag name matches a button tag
            if ($(event.target).hasClass("target-2") && $(event.target).prop("tagName") == "BUTTON") {
                // Add active class if it doesn't have it
                if (!$(".target-2").parent().next().children().hasClass("active-customdate")) {
                    // Make sure there won't be any content activated
                    //$( ".gColumn" ).removeClass( "active" );
                    //$( ".gStarter" ).removeAttr( "data-starter" );
                    // Add active class
                    $(".target-2").parent().next().children().addClass("active-customdate");
                } else {
                    /*
                     * Already have active class
                     */
                    // Hide content by removing the class
                    $(".target-2").parent().next().children().removeClass("active-customdate");
                    // Remove data-starter attribute
                    $(".target-2").removeAttr("data-starter");
                }
            } else {
                // Find and get the first class by targeting its data-starter attribute
                //var find = $('[data-starter="starter-active"]');
                // var getClass = $(find).attr('class').toString().split(' ')[0];
                if (!$(".target-2").closest().parent().next().children().is(event.target)) {
                    if ($(".target-2").parent().next().children().is(":visible")) {
                        $(".target-2").parent().next().children().removeClass("active-customdate");
                    }
                }
            }
        });
    </script>

<!-- กราฟ -->
<script>
    function updateChartThisWeek(params) {
        chartThisWeek();
        $('#button-graph-revenue').text('This Week');
    }

    function updateChartThisMonth(params) {
        
        if (params == "month") {
            chartThisMonth();
            $('#button-graph-revenue').text('This Month');
        } else {
            chartThisMonthByDay();
            $('#button-graph-revenue').text('Monthly Average By Days');
        }
    }

    function updateChartYearRange(params) {
        chartYearRange(params);
        $('#button-graph-revenue').text('Custom Year Range ('+ params +')');
    }
</script>

    {{-- กราฟ 2 --}}
    <script>
        var dateString = $('#combined-selected-box').val();
        var dateRange = dateString.split(" - ");
        if (dateRange[1]) {
            var date = moment(dateRange[0].replaceAll("/", "-"), 'DD/MM/YYYY').format("YYYY-MM-DD");
        } else {
            var date = moment().format("YYYY-MM-DD");
        }
        var date_now = date;
        var type = $('#status').val();
        var account = $('#into_account').val();

        function get_graphForecast() {
            var result_amount = "";

            jQuery.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-graphForcast/"+date_now+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    result_amount = [response.yesterday, response.today, response.forcast];
                }
            });

            return result_amount;
        }

        function get_graphToday() {
            var result_amount = "";

            jQuery.ajax({
                type: "GET",
                url: "{!! url('harmony-sms-graphToday/"+date_now+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    result_amount = [response.data_1, response.data_2, response.data_3, response.data_4,
                        response.data_5, response.data_6, response.data_7, response.data_8
                    ];
                }
            });

            return result_amount;
        }

        var combinedChart = document.getElementById("combinedChart").getContext("2d");
        var data1 = {
            labels: ["Yesterday", "Today", "Average"],
            datasets: [{
                label: "Forecast Revenue",
                backgroundColor: "#2C7F7A",
                borderWidth: 1,
                barPercentage: 0.33, // Adjust bar width
                data: get_graphForecast(), // Adjusted for more realistic numbers
            }, ],
        };
        var options1 = {
            scales: {
                y: {
                    ticks: {
                    beginAtZero: true,
                    callback: function(value) {
                        return formatNumber(value);
                    },
                    },
                }, 
            },
        };
        var data2 = {
            labels: ["21:00-23:59", "00:00-02:59", "03:00-05:59", "06:00-08:59", "09:00-11:59", "12:00-14:59",
                "15:00-17:59", "18:00-20:59",
            ],
            datasets: [{
                label: "Revenue",
                data: get_graphToday(),
                backgroundColor: "#2C7F7A",
                borderWidth: 1,
                barPercentage: 0.8, // Adjust bar width
            }, ],
        };
        var options2 = {
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return formatNumber(value);
                        },
                    },
                },
            },
        };
        // Function to format numbers as 100K, 2M, etc.
        function formatNumber(value) {
            if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + "M";
            } else if (value >= 1000) {
                return (value / 1000).toFixed(1) + "K";
            }
            return value;
        }
        // Function to calculate dynamic font size based on canvas width
        function calculateFontSize(canvas) {
            var canvasWidth = canvas.width;
            if (canvasWidth < 400) {
                return Math.max(8, Math.floor(canvasWidth / 150)); // Smaller font for mobile
            } else if (canvasWidth < 800) {
                return Math.max(10, Math.floor(canvasWidth / 50)); // Medium font for tablets
            } else {
                return Math.max(10, Math.floor(canvasWidth / 100)); // Larger font for desktops
            }
        }
        var valueOnTopPlugin2 = {
            afterDatasetsDraw: function(chart) {
                var ctx = chart.ctx;
                var fontSize = calculateFontSize(chart.canvas);
                chart.data.datasets.forEach(function(dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // ctx.rotate(-Math.PI / 2);
                            ctx.fillStyle = "#000"; // Black text color
                            var fontStyle = "normal";
                            var fontFamily = "Sarabun";
                            // ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            ctx.font = "normal 11px Sarabun";
                            ctx.save();
                            var dataString = formatNumber(dataset.data[index]);
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - fontSize / 4 - padding);
                        });
                    }
                });
            },
        };
        var currentData = data1;
        var currentOptions = options1;
        var forecastChart = new Chart(combinedChart, {
            type: "bar",
            data: currentData,
            options: currentOptions,
            plugins: [valueOnTopPlugin2],
        });

        function toggleGraph() {
            var switch_name = $('#toggleButton').val();

            if (switch_name == "to_time") {
                currentData = data2;
                currentOptions = options2;

            } if (switch_name == "to_forecast") {
                currentData = data1;
                currentOptions = options1;
            }
            forecastChart.destroy(); // Destroy the current chart
            forecastChart = new Chart(combinedChart, {
                type: "bar",
                data: currentData,
                options: currentOptions,
                plugins: [valueOnTopPlugin2],
            });

            var toggleButton = document.getElementById("toggleButton");
            var icon = document.createElement("i");

            if (switch_name == "to_time") {
                toggleButton.textContent = "Switch to Forecast ";
                icon.className = "icon-repeat";
                $('#toggleButton').val("to_forecast");

            } if (switch_name == "to_forecast") {
                toggleButton.textContent = "Switch to Time ";
                icon.className = "icon-repeat";
                $('#toggleButton').val("to_time");
            }

            // Append the icon to the toggleButton content
            toggleButton.appendChild(icon);
        }
    </script>
@endsection

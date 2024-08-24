@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            @php
                $day_from = isset($day) ? $day : date('d');
                $month_from = isset($month) ? $month : date('m');
                $year_from = isset($year) ? $year : date('Y');
                $time_from = isset($time) ? $time : '21:00:00'; // เวลาเริ่มนับตั้งแต่ 21:00:00
                
                $date_from = $year_from . '-' . $month_from . '-' . $day_from . ' ' . $time_from;

                if (isset($filter_by) && $filter_by == 'date' || isset($filter_by) && $filter_by == 'today' || isset($filter_by) && $filter_by == 'yesterday' || isset($filter_by) && $filter_by == 'tomorrow') {
                    $pickup_time = $day_from . ' ' . formatMonthName($month_from) . ' ' . $year_from;
                } elseif (isset($filter_by) && $filter_by == 'month') {
                    $pickup_time = formatMonthName($month) . ' - ' . formatMonthName($month_to);
                } elseif (isset($filter_by) && $filter_by == 'year') {
                    $pickup_time = $year;
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
                            <button type="submit" class="ch-button" data-toggle="modal" data-target="#ModalShowCalendar" style="white-space: nowrap;">
                                <span class="d-sm-none d-none d-md-inline-block">Search</span>
                                <i class="fa fa-search" style="font-size: 15px;"></i>
                            </button>
                            <button class="ch-button dropdown-toggle" type="button" id="dropdownMenuDaily" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-top: 0px; border-left: 0px">
                                <span id="txt-daily">
                                    @if (isset($filter_by) && $filter_by == 'today')
                                        Today
                                    @elseif (isset($filter_by) && $filter_by == 'yesterday')
                                        Yesterday
                                    @elseif (isset($filter_by) && $filter_by == 'tomorrow')
                                        Tomorrow
                                    @else
                                        Today
                                    @endif
                                </span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuDaily">
                                <a class="dropdown-item" href="#" onclick="search_daily('today')">Today</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('yesterday')">Yesterday</a>
                                <a class="dropdown-item" href="#" onclick="search_daily('tomorrow')">Tomorrow</a>
                            </div>
                            <button type="button" class="ch-button" data-toggle="modal" data-target="#exampleModalCenter5" style="white-space: nowrap;"> 
                                Add
                            </button>
                        </div>
                    </div>
                    
                    <!-- กล่องสรุปรายได้ card ที่ 1 -->
                    <div class="card box-revenue-card">
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
                                    <a href="#" onclick="agoda_detail('agoda_detail')">
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
                                                    <div class="fz-15px">{{ number_format(0, 2) }}</div>
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="box-sub-revenue" style="background-image: linear-gradient(to right,  rgb(12, 73, 70) , rgb(4, 8, 8));">
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
                            <div class="container-graph">
                                <canvas id="revenueChart" hidden></canvas>
                                <canvas id="revenueChartThisMonth" ></canvas>
                                <canvas id="revenueChartCustom" hidden></canvas>
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
                                                                <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 7 days ({{ date('d M', strtotime("-6 day")) }} ~ {{ date('d M') }})
                                                            </div>
                                                            <div class="d-flex" style="font-size: 12px;"></div>
                                                        </button>
                                                        <button type="button" value="15" onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 15 days ({{ date('d M', strtotime("-14 day")) }} ~ {{ date('d M') }})
                                                        </button>
                                                        <button type="button" value="30" onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last 30 days ({{ date('d M', strtotime("-29 day")) }} ~  {{ date('d M') }})
                                                        </button>
                                                        <button type="button" value="week" onclick="updateChartThisWeek(this.value)" class="modal-graph">
                                                            <i class="fa fa-square" style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>This Week ({{ date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))) }} ~ {{ date('d M', strtotime("+6 day")) }})
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
                            <div>
                                <!-- กราฟที่ 2 -->
                                <div class="container-graph">
                                    <canvas id="combinedChart" style="width: 100%"></canvas>
                                    <div>
                                        <button class="graph-select " style="width: 200px;" id="toggleButton" value="to_time" onclick="toggleGraph()">
                                            Switch to Time <i class="icon-repeat"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
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
                                        <table id="smsTable" class="example ui striped table nowrap unstackable hover">
                                            <caption class="caption-top">
                                                <div>
                                                    <div class="flex-end-g2">
                                                        <label class="entriespage-label">entries per page :</label>
                                                        <select class="entriespage-button" id="search-per-page-sms" onchange="getPage(1, this.value, 'sms')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                            <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "sms" ? 'selected' : '' }}>10</option>
                                                            <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "sms" ? 'selected' : '' }}>25</option>
                                                            <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "sms" ? 'selected' : '' }}>50</option>
                                                            <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "sms" ? 'selected' : '' }}>100</option>
                                                        </select>
                                                        <input class="search-button search-data" id="sms" style="text-align:left;" placeholder="Search" />
                                                    </div>
                                            </caption>
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;" data-priority="1">#</th>
                                                    <th style="text-align: center;" data-priority="1">Date</th>
                                                    <th style="text-align: center;">Time</th>
                                                    <th style="text-align: center;">Bank</th>
                                                    <th style="text-align: center;">Bank Account</th>
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
                                                        <div class="flex-jc p-left-4 center">
                                                            @if (file_exists($filename))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                            @elseif (file_exists($filename2))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                            @endif
                                                            {{ @$item->transfer_bank->name_en }}
                                                        </div>
                                                    </td>
                                                    <td class="td-content-center">
                                                        <div class="flex-jc p-left-4 center">
                                                            <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
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
                                                                @if (@$role_revenue->elexa == 1)
                                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                        Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                    </li>
                                                                @endif
                                                                @if (@$role_revenue->no_category == 1)
                                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                        No Category
                                                                    </li>
                                                                @endif
                                                                @if (@$role_revenue->water_park == 1)
                                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                        Water Park Bank <br> Transfer Revenue 
                                                                    </li>
                                                                @endif
                                                                @if (@$role_revenue->credit_water_park == 1)
                                                                    <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                        Credit Card Water <br>Park Revenue 
                                                                    </li>
                                                                @endif
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
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <caption class="caption-bottom">
                                                <div class="md-flex-bt-i-c">
                                                    <p class="py2" id="sms-showingEntries">{{ showingEntriesTable($data_sms, 'sms') }}</p>
                                                    <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_sms_amount) ? $total_sms_amount->amount : 0 , 2) }} บาท</div>
                                                        <div id="sms-paginate">
                                                            {!! paginateTable($data_sms, 'sms') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                        </div>
                                                </div>
                                            </caption>
                                        </table>
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
                                    <table id="transferTable" class="example ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-transfer" onchange="getPage(1, this.value, 'transfer')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "transfer" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "transfer" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "transfer" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "transfer" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data" id="transfer" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" data-priority="1">#</th>
                                                <th style="text-align: center;" data-priority="1">Date</th>
                                                <th style="text-align: center;">Time</th>
                                                <th style="text-align: center;">Bank</th>
                                                <th style="text-align: center;">Bank Account</th>
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
                                                        <div class="flex-jc p-left-4 center">
                                                            @if (file_exists($filename))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                            @elseif (file_exists($filename2))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                            @endif
                                                            {{ @$item->transfer_bank->name_en }}
                                                        </div>
                                                    </td>
                                                    <td class="td-content-center">
                                                        <div class="flex-jc p-left-4 center">
                                                            <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
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
                                                                    @if (@$role_revenue->elexa == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                            Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->no_category == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                            No Category
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                            Water Park Bank <br> Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->credit_water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                            Credit Card Water <br>Park Revenue 
                                                                        </li>
                                                                    @endif
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
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="transfer-showingEntries">{{ showingEntriesTable($data_sms_transfer, 'transfer') }}</p>
                                                <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_transfer_amount) ? $total_transfer_amount->amount : 0 , 2) }} บาท</div>
                                                    <div id="transfer-paginate">
                                                        {!! paginateTable($data_sms_transfer, 'transfer') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
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
                                    <table id="" class="example ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-split" onchange="getPage(1, this.value, 'split')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "split" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "split" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "split" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "split" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data" id="split" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" data-priority="1">#</th>
                                                <th style="text-align: center;" data-priority="1">Date</th>
                                                <th style="text-align: center;">Time</th>
                                                <th style="text-align: center;">Bank</th>
                                                <th style="text-align: center;">Bank Account</th>
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
                                                        <div class="flex-jc p-left-4 center">
                                                            @if (file_exists($filename))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg">
                                                            @elseif (file_exists($filename2))
                                                                <img class="img-bank" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png">
                                                            @endif
                                                            {{ @$item->transfer_bank->name_en }}
                                                        </div>
                                                    </td>
                                                    <td class="td-content-center">
                                                        <div class="flex-jc p-left-4 center">
                                                            <img class="img-bank" src="../image/bank/SCB.jpg"> {{ 'SCB ' . $item->into_account }}
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
                                                                    @if (@$role_revenue->elexa == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                                            Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->no_category == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'No Category')">
                                                                            No Category
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                                            Water Park Bank <br> Transfer Revenue 
                                                                        </li>
                                                                    @endif
                                                                    @if (@$role_revenue->credit_water_park == 1)
                                                                        <li class="button-li" onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                                            Credit Card Water <br>Park Revenue 
                                                                        </li>
                                                                    @endif
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
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="split-showingEntries">{{ showingEntriesTable($data_sms_split, 'split') }}</p>
                                                <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format(!empty($total_split_amount) ? $total_split_amount->amount : 0 , 2) }} บาท</div>
                                                    <div id="split-paginate">
                                                        {!! paginateTable($data_sms_split, 'split') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
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
                <form action="{{ route('sms-transfer') }}" method="POST" enctype="multipart/form-data" class="basic-form">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-12 col-12">
                            <label>วันที่โอนย้ายไป</label>
                            <input type="date" class="form-control" name="date_transfer" id="date_transfer">
                        </div>
                        <div class="col-md-12 col-12 mt-3">
                            <label>หมายเหตุ</label>
                            <textarea class="form-control" name="transfer_remark" id="transfer_remark" rows="5" cols="10" required>ปิดยอดช้ากว่ากำหนด</textarea>
                        </div>
                        <input type="hidden" name="dataID" id="dataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-color-green lift">Save changes</button>
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
                    <div class="col-md-12 col-12">
                        <label>เวลา</label>
                        <input type="time" class="form-control" name="update_time" id="update_time" value="<?php echo date('H:i:s'); ?>" step="any">
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
                            <input type="date" class="form-control" name="date-split" id="date-split">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="col-md-6">
                            <label>จำนวนเงิน <span class="text-danger fw-bold" id="text-split-amount"></span></label>
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
                    <h5 class="modal-title text-white" id="exampleModalCenter5Label">เพิ่มข้อมูล
                    </h5>
                    <button type="button" class="close text-white text-2xl" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sms-store') }}" method="POST" class="" id="form-id">
                    @csrf
                    <div class="modal-body">
                        <label for="">ประเภทรายได้</label>
                        <br>
                        <select class="form-control form-select" id="status" name="status" onchange="select_type()">
                            <option value="0">เลือกข้อมูล</option>
                            <option value="1">Room Revenue</option>
                            <option value="2">All Outlet Revenue</option>
                            <option value="3">Water Park Revenue</option>
                            <option value="4">Credit Revenue</option>
                            <option value="5">Agoda Revenue</option>
                            <option value="6">Front Desk Revenue</option>
                            <option value="8">Elexa EGAT Revenue</option>
                            <option value="9">Other Revenue Bank Transfer</option>
                        </select>
                        <div class="dg-gc2-g2">
                            <div class="wf-py2 ">
                                <label for="">วันที่โอน <sup class="t-red600">*</sup></label>
                                <br>
                                <input class="form-control" type="date" name="date" id="sms-date" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เวลาที่โอน <sup class="text-danger">*</sup></label>
                                <br>
                                <input class="form-control" type="time" name="time" id="sms-time">
                            </div>
                            <div class="wf-py2 Amount agoda" hidden>
                                <label for="">Booking ID <sup class="text-danger">*</sup></label>
                                <br>
                                <input type="text" class="form-control" name="booking_id" id="booking_id" required>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">โอนจากบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="transfer_from" name="transfer_from" data-placeholder="Select">
                                    <option value="0">เลือกข้อมูล</option>
                                    @foreach ($data_bank as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}
                                            ({{ $item->name_en }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">เข้าบัญชี <sup class="text-danger">*</sup></label>
                                <br>
                                <select class="form-control select2" id="add_into_account" name="into_account" data-placeholder="Select">
                                    <option value="0">เลือกข้อมูล</option>
                                    <option value="708-226791-3">ธนาคารไทยพาณิชย์ (SCB) 708-226791-3</option>
                                    <option value="708-226792-1">ธนาคารไทยพาณิชย์ (SCB) 708-226792-1</option>
                                    <option value="708-227357-4">ธนาคารไทยพาณิชย์ (SCB) 708-227357-4</option>
                                    <option value="076355900016902">ชำระผ่าน QR 076355900016902</option>
                                </select>
                            </div>
                            <div class="wf-py2 ">
                                <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup></label>
                                <br>
                                <input class="form-control" type="text" id="amount" name="amount" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 15px;">Close</button>
                        <button type="button" class="btn btn-color-green sa-button-submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: เลือกวันที่ modal fade -->
    <div class="modal fade" id="ModalShowCalendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog mw-350" role="document">
            <div class="modal-content rounded-xl">
                <div class="modal-header md-header text-white">
                    <div class="w-full">
                        <h5 class=".modal-hd">ค้นหารายการ</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sms-search-calendar') }}" method="POST" enctype="multipart/form-data" id="form-calendar">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <div class="box-ch-button">
                                <button type="button" id="showD" onclick="Choice(this);" class="ch-pick"> filter by date</button>
                                <button type="button" id="showM" onclick="Choice(this);" class="ch-pick"> filter by month</button>
                                <button type="button" id="showY" onclick="Choice(this);" class="ch-pick"> filter by year</button>
                                <input type="hidden" id="choice-date">
                            </div>
                            <div style="width: 100%; display: flex; justify-content: center;">
                                <div style="width: 95%; align-self:center;align-items: center;">
                                    <div class="g-g2">
                                        <div class="fic">
                                            <select class="box-input-full" id="into_account" name="into_account" onchange="select_account()">
                                                <option value="" {{ isset($into_account) && $into_account == '' ? 'selected' : '' }}>เลขที่บัญชีทั้งหมด</option>
                                                <option value="708-226791-3" {{ isset($into_account) && $into_account == '708-226791-3' ? 'selected' : '' }}>SCB 708-226791-3</option>
                                                <option value="708-226792-1" {{ isset($into_account) && $into_account == '708-226792-1' ? 'selected' : '' }}>SCB 708-226792-1</option>
                                                <option value="708-227357-4" {{ isset($into_account) && $into_account == '708-227357-4' ? 'selected' : '' }}>SCB 708-227357-4</option>
                                            </select>
                                            <!-- tooltip -->
                                            <div data-tooltip-target="tooltip-default"
                                                class="relative tooltip-1">
                                                <span class="fa fa-info-circle"></span>
                                                </span>
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
                                        <select class="box-input-full" name="status">
                                            <option value="" {{ isset($status) && $status == '' ? 'selected' : '' }}>ประเภทรายได้ทั้งหมด</option>
                                            <option value="6" {{ isset($status) && $status == 6 ? 'selected' : '' }}>Front Desk Bank Transfer Revenue</option>
                                            <option value="1" {{ isset($status) && $status == 1 ? 'selected' : '' }}>Guest Deposit Bank Transfer Revenue</option>
                                            <option value="2" {{ isset($status) && $status == 2 ? 'selected' : '' }}>All Outlet Bank Transfer Revenue</option>
                                            <option value="4" {{ isset($status) && $status == 4 ? 'selected' : '' }}>Credit Card Revenue</option>
                                            <option value="5" {{ isset($status) && $status == 5 ? 'selected' : '' }}>Credit Card Agoda Revenue</option>
                                            <option value="3" {{ isset($status) && $status == 3 ? 'selected' : '' }}>Water Park Bank Transfer Revenue</option>
                                            <option value="7" {{ isset($status) && $status == 7 ? 'selected' : '' }}>Credit Card Water Park Revenue</option>
                                            <option value="7" {{ isset($status) && $status == 8 ? 'selected' : '' }}>Elexa EGAT Bank Transfer Revenue</option>
                                        </select>
                                    </div>
                                    <br>
                                    <!-- box แสดงวันที่ เดือน ปี -->
                                    <div id="box"></div>
                                    <!-- วันเดือนปีซ่อนไว้  display: none-->
                                    <div id="calendar-day">
                                        <div class="ch-day" style=" border: none;" style="display: none;">
                                            <!-- เลือกจากวันที่ -->
                                            <div id="ch-day">
                                                <p class="t-month"> filter by date</p>
                                                <div class="calendar">
                                                    <div class="month month-top">
                                                        <i class="fa fa-angle-left prev"></i>
                                                        <div class="date">
                                                            <h1 id="mymonth" class="thisMont"></h1>
                                                            <p id="myDay" class="dateShose"> วันที่เลือก
                                                            </p>
                                                            <!-- <p id="" class="date-current border-2"> วันที่เลือก</p> -->
                                                        </div>
                                                        <i class="fa fa-angle-right next"></i>
                                                    </div>
                                                    <div>
                                                        <div class="weekdays">
                                                            <div>Sun</div>
                                                            <div>Mon</div>
                                                            <div>Tue</div>
                                                            <div>Wed</div>
                                                            <div>Thu</div>
                                                            <div>Fri</div>
                                                            <div>Sat</div>
                                                        </div>
                                                        <div class="days"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                            <!-- เลือกจากวันเดือน -->
                                            <div id="ch-month" style="display: none;">
                                                <p class="t-month"> filter by month</p>
                                                <div class="calendar">
                                                    <div class="month month-top">
                                                        <div
                                                            style="display: flex; flex-direction:column;width: 100%;">
                                                            <div class="month-date">
                                                                <p id="myMonth1" class="thisMont">
                                                                    เดือนเริ่มต้น</p>
                                                                <p>&nbsp; - &nbsp;</p>
                                                                <p id="myMonth2"> สิ้นสุดเดือน
                                                                </p>
                                                                <!-- <p id="" class="date-current border-2"> วันที่เลือก</p> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="allMonth" class="show-all-month"></div>
                                                </div>
                                            </div>
                                            <!-- เลือกจากปี -->
                                            <div id="ch-year" style="display: none;">
                                                <p class="t-month"> filter by Year</p>
                                                <div class="calendar">
                                                    <div class="month month-top">
                                                        <div
                                                            style="display: flex; gap:20px;justify-content: center; width: 100%;">
                                                            <p id="myYear" style="font-size: 20px;"> 2024
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="show-all-years">
                                                        <div class="ch-years" onclick="getYearValue(2024)" value="">2024</div>
                                                        <div class="ch-years" onclick="getYearValue(2025)" value="">2025</div>
                                                        <div class="ch-years" onclick="getYearValue(2026)" value="">2026</div>
                                                        <div class="ch-years" onclick="getYearValue(2027)" value="">2027</div>
                                                        <div class="ch-years" onclick="getYearValue(2028)" value="">2028</div>
                                                        <div class="ch-years" onclick="getYearValue(2029)" value="">2029</div>
                                                        <div class="ch-years" onclick="getYearValue(2030)" value="">2030</div>
                                                        <div class="ch-years" onclick="getYearValue(2031)" value="">2032</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="month-click-num" value="0">
                                <input type="hidden" id="month-number1" value="0">
                                <input type="hidden" id="month-number2" value="0">
                                <input type="hidden" id="by-month-year">

                                <!-- Input ส่งค่าไป Controller -->
                                <input type="hidden" id="filter-by" name="filter_by" value="{{ isset($filter_by) ? $filter_by : 'date' }}">
                                <input type="hidden" id="input-search-day" name="day" value="{{ isset($day) ? $day : date('d') }}">
                                <input type="hidden" id="input-search-month" name="month" value="{{ isset($month) ? $month : date('m') }}">
                                <input type="hidden" id="input-search-month-to" name="month_to" value="{{ isset($month_to) ? $month_to : date('m') }}">
                                <input type="hidden" id="input-search-year" name="year" value="{{ isset($year) ? $year : date('Y') }}">
                                <input type="time" id="time" name="time" value="<?php echo isset($time) && $time != $time ?: date('20:59:59'); ?>" hidden>
                            </div>
                        </div>
                        <!-- ล่าง modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="btn-search-date" class="btn btn-success" style="background-color: #2C7F7A;">Search</button>
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
    <script src="{{ asset('assets/js/searh-calendar.js') }}"></script>
    <!-- style สำหรับเปิดปิด custom date -->

    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTable.js')}}"></script>

    <!-- Sweet Alert 2 -->
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <!-- card graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="{{ asset('assets/graph/graphUpdateDay.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/graph/graphCondition.js')}}"></script>

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
    </style>

    <script type="text/javascript">
        function dayPickup() {
            let x1 = document.getElementById("myDate").value;
            let x2 = document.getElementById("myDate2").value;
            document.getElementById("demo-date").innerHTML = x1 + " - " + x2;
        }
    </script>

    <script type="text/javascript">
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
        });

        // Search 
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var total = parseInt($('#get-total-'+id).val());
            var table_name = id+'Table';

            var filter_by = $('#filter-by').val();
            var day = $('#input-search-day').val();
            var month = $('#input-search-month').val();
            var year = $('#input-search-year').val();
            var month_to = $('#input-search-month-to').val();
            var type = $('#status').val();
            var account = $('#into_account').val();
            var getUrl = window.location.pathname;

            if (search_value != '') {
                
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    // "ajax": "sms-search-table/"+search_value+"/"+table_name+"",
                    ajax: {
                    url: 'sms-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        day: day,
                        month: month,
                        year: year,
                        month_to: month_to,
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
                        
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearch(count, id));
                        $('#'+id+'-paginate').append(paginateSearch(count, id, 'sms-alert'));
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

            } else {
                $('#'+id+'-paginate').children().remove().end();
                $('#'+id+'-showingEntries').text(showingEntriesSearch(total, id));
                $('#'+id+'-paginate').append(paginateSearch(total, id, getUrl));
            }

            document.getElementById(id).focus();
        });

        function sms_detail(revenue_name) 
        {
            var filter_by = $('#filter-by').val();
            var day = $('#input-search-day').val();
            var month = $('#input-search-month').val();
            var year = $('#input-search-year').val();
            var month_to = $('#input-search-month-to').val();
            var type = $('#status').val();
            var account = $('#into_account').val();

            if (account == '') {
                account = 0;
            }

            window.location.href = "{!! url('sms-detail/"+revenue_name+"?filterBy="+filter_by+"&day="+day+"&month="+month+"&year="+year+"&monthTo="+month_to+"&type="+type+"&account="+account+"') !!}";
        }

        function agoda_detail(revenue_name) 
        {
            var filter_by = $('#filter-by').val();
            var day = $('#input-search-day').val();
            var month = $('#input-search-month').val();
            var year = $('#input-search-year').val();
            var month_to = $('#input-search-month-to').val();
            var type = $('#status').val();
            var account = $('#into_account').val();

            if (account == '') {
                account = 0;
            }

            window.location.href = "{!! url('sms-agoda_detail/"+revenue_name+"?filterBy="+filter_by+"&day="+day+"&month="+month+"&year="+year+"&monthTo="+month_to+"&type="+type+"&account="+account+"') !!}";
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

            $('#filter-by').val($search);
            $('#input-search-day').val(day);
            $('#input-search-month').val(month);
            $('#input-search-year').val(year);
            $('#form-calendar').submit();
        }

        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        // ประเภทรายได้
        function change_status($id, $status) {
            console.log($status);
            
            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-change-status/"+$id+"/"+$status+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    location.reload();
                },
            });
        }

        // ประเภทรายได้ Other Revenue
        function other_revenue_data(id) {
            $('#otherDataID').val(id);
            $('#modalOtherRevenue').modal('show');

            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-get-remark-other-revenue/"+id+"') !!}",
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
                url: "{!! url('sms-update-time/"+id+"/"+time+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    location.reload();
                },
            });
        }

        function split_data($id, $amount) {
            $('#splitID').val($id);
            $('#text-split-amount').text("(" + currencyFormat($amount) + ")");
            $('#balance_amount').val($amount);
            $('#SplitModalCenter').modal('show');
        }

        $(document).on('click', '#btn-save-other-revenue', function () {
            var id = $('#otherDataID').val();
            var remark = $('#other_revenue_remark').val();

            jQuery.ajax({
                type: "POST",
                url: "{!! url('sms-other-revenue') !!}",
                datatype: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {dataID: id, other_revenue_remark: remark},
                cache: false,
                async: false,
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire('เรียบร้อย!', '', 'success');
                        location.reload();
                    } else {
                        Swal.fire('ไม่สำเร็จ', '', 'info');
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
                if (parseFloat(total_amount) + parseFloat(amount) <= balance) {
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

                    $('#split_total_number').val(parseFloat(total_amount) + parseFloat(amount));
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
        }

        function toggleHide() {
            $('#split_total_number').val(0);
            $('.split-todo-list tr').remove();
        }

        $('#add-data').on('click', function() {
            $('#sms-date').css('border-color', '#f0f0f0');
            $('#sms-time').css('border-color', '#f0f0f0');
            $('#error-transfer').css('border-color', '#f0f0f0');
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');

            $('#id').val('');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            $('#exampleModalCenter5').modal('show');

        });

        function select_type() {
            var type = $('#status').val();

            if (type == 5) {
                $('.agoda').prop('hidden', false);
            } else {
                $('.agoda').prop('hidden', true);
            }
        }

        function select_account() {
            var account = $('#into_account').val();
            $('#bank-note').html("");

            if (account == "708-226791-3") {
                $('#bank-note').append('Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue">');
            }

            if (account == "708-226792-1") {
                $('#bank-note').append('Credit Card Hotel Revenue');
                $('#bank-note').append('<input type="hidden" name="bank_note" value="Credit Card Hotel Revenue">');
            }

            if (account == "708-227357-4") {
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
            $('#error-into').css('border-color', '#f0f0f0');
            $('#amount').css('border-color', '#f0f0f0');
            $('#status').val(0).trigger('change');
            $('#sms-date').val('');
            $('#sms-time').val('');
            $('#booking_id').val('');
            $('#transfer_from').val(0).trigger('change');
            $('#add_into_account').val(0).trigger('change');
            $('#amount').val('');

            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-edit/"+$id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    if (response.data) {
                        var myArray = response.data.date.split(" ");
                        $('#status').val(response.data.status).trigger('change');
                        $('#sms-date').val(myArray[0]);
                        $('#sms-time').val(myArray[1]);
                        $('#booking_id').val(response.data.booking_id);
                        $('#transfer_from').val(response.data.transfer_from).trigger('change');
                        $('#add_into_account').val(response.data.into_account).trigger('change');
                        $('#amount').val(response.data.amount);
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
                        url: "{!! url('sms-delete/"+$id+"') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(result) {
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                    });

                } else if (result.isDenied) {
                    Swal.fire('ลบข้อมูลไม่สำเร็จ!', '', 'info');
                    location.reload();
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

                jQuery.ajax({
                    type: "POST",
                    url: "{!! route('sms-store') !!}",
                    datatype: "JSON",
                    data: $('#form-id').serialize(),
                    async: false,
                    success: function(result) {
                        Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                        location.reload();
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
    updateChart(7);

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
        var date_now = $('#input-search-year').val() + '-' + $('#input-search-month').val() + '-' + $('#input-search-day').val();
        var type = $('#status').val();
        var account = $('#into_account').val();

        function get_graphForecast() {
            var result_amount = "";

            jQuery.ajax({
                type: "GET",
                url: "{!! url('sms-graphForcast/"+date_now+"') !!}",
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
                url: "{!! url('sms-graphToday/"+date_now+"') !!}",
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
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return formatNumber(value);
                        },
                    },
                }, ],
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
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return formatNumber(value);
                        },
                    },
                }, ],
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

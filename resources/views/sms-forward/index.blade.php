@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <!-- กล่อง เมนูข้างบน -->
            <div>
                <!-- กล่อง conternt ทั้งหมด -->
                <div id="content-index">
                    <!-- หัวข้างบน -->
                    <div class="f-jb-p2-mb3">
                        <div>
                            <h1 class="h-daily" style="white-space: nowrap;">Daily Bank Transaction Revenue</h1>
                        </div>
                        <!-- Button เพิ่มข้อมูล -->
                        <div class="searh-box-bg">
                            <div>
                                <input type="text" id="select-date" name="" class="showdate-button"
                                    style="width: 100%;" value="" placeholder="Pickup Time">
                            </div>
                            <button type="submit" class="ch-button" data-toggle="modal" data-target="#ModalShowCalendar"
                                style="white-space: nowrap;">
                                <span class="d-sm-none d-none d-md-inline-block">Search</span>
                                <i class="fa fa-search" style="font-size: 15px;"></i>
                            </button>
                            <button class="ch-button dropdown-toggle" type="button" id="dropdownMenuDaily"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="border-top: 0px; border-left: 0px">
                                Today
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuDaily">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Tomorrow</a>
                            </div>
                            <button type="button" class="ch-button" data-toggle="modal" data-target="#exampleModal"
                                style="white-space: nowrap;"> Add </i>
                            </button>
                        </div>
                    </div>
                    <!-- Modal: เลือกวันที่ modal fade -->
                    <div class="modal fade" id="ModalShowCalendar" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="modal-body">
                                    <div>
                                        <div class="box-ch-button">
                                            <button id="showD" onclick="Choice(this);" class="ch-pick"> filter by date</button>
                                            <button id="showM" onclick="Choice(this);" class="ch-pick"> filter by month</button>
                                            <button id="showY" onclick="Choice(this);" class="ch-pick"> filter by year</button>
                                            <input type="hidden" id="choice-date">
                                        </div>
                                        <div style="width: 100%; display: flex; justify-content: center;">
                                            <div style="width: 95%; align-self:center;align-items: center;">
                                                <div class="g-g2">
                                                    <div class="fic">
                                                        <select class="box-input-full" onchange="select_account()">
                                                            <option value="">เลขที่บัญชีทั้งหมด
                                                            </option>
                                                            <option value="">SCB 708-226791-3
                                                            </option>
                                                            <option value="">SCB 708-226792-1
                                                            </option>
                                                            <option value="">SCB 708-227357-4
                                                            </option>
                                                        </select>
                                                        <!-- tooltip -->
                                                        <div data-tooltip-target="tooltip-default"
                                                            class="relative tooltip-1">
                                                            <span class="fa fa-info-circle"></span>
                                                            </span>
                                                        </div>
                                                        <div id="tooltip-default" role="tooltip" class="absolute tooltip-2">
                                                            Tooltip content
                                                            Tooltip content Tooltip content <div
                                                                class="tooltip-arrow text-black" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    <select class="box-input-full" name="status">
                                                        <option value=""> ประเภทรายได้ทั้งหมด
                                                        </option>
                                                        <option value="">Front Desk Bank Transfer
                                                            Revenue</option>
                                                        <option value="">Guest Deposit Bank Transfer
                                                            Revenue</option>
                                                        <option value="">All Outlet BankTrans fer
                                                            Revenue </option>
                                                        <option value="">Credit Card Revenue</option>
                                                        <option value="">Credit Card Agoda Revenue
                                                        </option>
                                                        <option value="">Water Park Bank <br>
                                                            Transfer Revenue </option>
                                                        <option value="">Credit Card Water Park
                                                            Revenue</option>
                                                        <option value="">Elexa EGAT Bank Transfer
                                                            Revenue </option>
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
                                                                        <div
                                                                            style="display: flex; gap:20px;justify-content: center;align-items: center; width: 100%;max-height: 18px;">
                                                                            <i class="fa fa-angle-left t5-month"></i>
                                                                            <p id="select-month-year">2024
                                                                            </p>
                                                                            <i class="fa fa-angle-right t6-month"></i>
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
                                                                    <div class="ch-years" onclick="getYearValue(2020)"
                                                                        value="">2020</div>
                                                                    <div class="ch-years" onclick="getYearValue(2021)"
                                                                        value="">2021</div>
                                                                    <div class="ch-years" onclick="getYearValue(2022)"
                                                                        value="">2022</div>
                                                                    <div class="ch-years" onclick="getYearValue(2023)"
                                                                        value="">2023</div>
                                                                    <div class="ch-years" onclick="getYearValue(2024)"
                                                                        value="">2024</div>
                                                                    <div class="ch-years" onclick="getYearValue(2025)"
                                                                        value="">2025</div>
                                                                    <div class="ch-years" onclick="getYearValue(2026)"
                                                                        value="">2026</div>
                                                                    <div class="ch-years" onclick="getYearValue(2027)"
                                                                        value="">2027</div>
                                                                    <div class="ch-years" onclick="getYearValue(2028)"
                                                                        value="">2028</div>
                                                                    <div class="ch-years" onclick="getYearValue(2029)"
                                                                        value="">2029</div>
                                                                    <div class="ch-years" onclick="getYearValue(2030)"
                                                                        value="">2030</div>
                                                                    <div class="ch-years" onclick="getYearValue(2031)"
                                                                        value="">2032</div>
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
                                        </div>
                                    </div>
                                    <!-- ล่าง modal -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" id="btn-save-date" class="btn btn-success"
                                            style="background-color: #2C7F7A;" onclick="btn_date_confirm()">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal เพิ่มข้อมูล modal fade -->
                    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content rounded-lg">
                                <div class="modal-header md-header">
                                    <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มข้อมูล
                                    </h5>
                                    <button type="button" class="close text-white text-2xl" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label for="">ประเภทรายได้</label>
                                    <br>
                                    <select class="box-input-full" id="status" name="status"
                                        onChange="select_type()">
                                        <option value="0">เลือกข้อมูล</option>
                                        <option value="1">Room Revenue</option>
                                        <option value="2">All Outlet Revenue</option>
                                        <option value="3">Water Park Revenue</option>
                                        <option value="4">Credit Revenue</option>
                                        <option value="5">Agoda Revenue</option>
                                        <option value="6">Front Desk Revenue</option>
                                        <option value="8">Elexa EGAT Revenue</option>
                                    </select>
                                    <div class="dg-gc2-g2">
                                        <div class="wf-py2 ">
                                            <label for="">วันที่โอน <sup class="t-red600">*</sup>
                                            </label>
                                            <br>
                                            <input class="box-input-full" type="date" name="date" id="sms-date"
                                                required>
                                        </div>
                                        <div class="wf-py2 ">
                                            <label for="">เวลาที่โอน <sup class="text-danger">*</sup>
                                            </label>
                                            <br>
                                            <input class=" box-input-full" type="time" name="time" id="sms-time">
                                        </div>
                                        <div class="Amount agoda" hidden>
                                            <label for="">Booking ID <sup class="text-danger">*</sup>
                                            </label>
                                            <br>
                                            <input class="box-input-full" type="text" name="booking_id"
                                                id="booking_id" required>
                                        </div>
                                        <div class="wf-py2 ">
                                            <label for="">โอนจากบัญชี <sup class="text-danger">*</sup>
                                            </label>
                                            <br>
                                            <select class="box-input-full" id="transfer_from" name="transfer_from"
                                                data-placeholder="Select">
                                                <option value="0">เลือกข้อมูล</option>
                                                @foreach ($data_bank as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name_th }} ({{ $item->name_en }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="wf-py2  ">
                                            <label for="">เข้าบัญชี <sup class="text-danger">*</sup>
                                            </label>
                                            <br>
                                            <select class="box-input-full" id="add_into_account" name="into_account"
                                                data-placeholder="Select">
                                                <option value="0">เลือกข้อมูล</option>
                                                <option value="708-226791-3">ธนาคารไทยพาณิชย์ (SCB)
                                                    708-226791-3</option>
                                                <option value="708-226792-1">ธนาคารไทยพาณิชย์ (SCB)
                                                    708-226792-1</option>
                                                <option value="708-227357-4">ธนาคารไทยพาณิชย์ (SCB)
                                                    708-227357-4</option>
                                                <option value="076355900016902">ชำระผ่าน QR 076355900016902
                                                </option>
                                            </select>
                                        </div>
                                        <div class="wf-py2 ">
                                            <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup>
                                            </label>
                                            <br>
                                            <input class="box-input-full" type="text" id="amount" name="amount"
                                                placeholder="0.00" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- <input type="hidden" name="id" id="id"> -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        style="font-size: 15px;">Close</button>
                                    <button type="button" class="btn btn-success"
                                        style="background-color: #2C7F7A;">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $day_from = isset($day) ? $day : date('d');
                        $month_from = isset($month) ? $month : date('m');
                        $year_from = isset($year) ? $year : date('Y');
                        $time_from = isset($time) ? $time : '21:00:00';
                        
                        $date_from = $year_from . '-' . $month_from . '-' . $day_from . ' ' . $time_from;
                    @endphp
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
                                    <a href="{{ route('sms-detail', ['front', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['room', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['all_outlet', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['water', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['credit_transaction', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['credit_water', $date_from]) }}">
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
                                    <a href="{{ route('sms-agoda_detail', [$date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['other', $date_from]) }}">
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
                                    <a href="{{ route('sms-detail', ['elexa_revenue', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['transfer_revenue', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['split_revenue', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['transfer_transaction', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['credit_transaction', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['split_transaction', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['total_transaction', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['status', $date_from]) }}">
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
                                <a href="{{ route('sms-detail', ['no_income_revenue', $date_from]) }}">
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
                    <!-- card graph -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <!-- card ครอบกราฟทั้งหมด -->
                    <div class="box-a-graph gy-5">
                        <!-- กราฟที่ 1 -->
                        <div class="box-graph">
                            <div class="container-graph">
                                <canvas id="revenueChart"></canvas>
                                <div class="menu-graph">
                                    <button type="button" class="ac-style" data-toggle="modal"
                                        data-target="#myModalGraph" style="min-width: 25%;cursor: pointer;"> 7
                                        Days</button>
                                </div>
                                <div>
                                    <!-- The Modal modal-->
                                    <div class="modal" id="myModalGraph">
                                        <div class="modal-dialog modal-bottom modal-sm">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title" style="width: 100%;color: #2C7F7A ;">Filter
                                                        Date
                                                    </h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <div class="sub-ch-graph"
                                                        style="position:relative;top:0; flex-direction:column; justify-content: start; background-color:white">
                                                        <button type="button" value="7"
                                                            onclick="updateChart(this.value)" class="modal-graph">
                                                            <div>
                                                                <i class="fa fa-square"
                                                                    style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last
                                                                7 days
                                                            </div>
                                                            <div class="d-flex" style="font-size: 12px;">
                                                            </div>
                                                        </button>
                                                        <button type="button" value="15"
                                                            onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square"
                                                                style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last
                                                            15 days </button>
                                                        <button type="button" value="30"
                                                            onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square"
                                                                style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Last
                                                            30 days </button>
                                                        <button type="button" value="30"
                                                            onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square"
                                                                style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>This
                                                            Week </button>
                                                        <button type="button" value="30"
                                                            onclick="updateChart(this.value)" class="modal-graph">
                                                            <i class="fa fa-square"
                                                                style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>This
                                                            Month </button>
                                                        <div>
                                                            <button type="button" data-starter="starter-active"
                                                                class="target-2 target modal-graph" style="width: 100%;">
                                                                <i class="fa fa-square"
                                                                    style="font-size: 10px;color: #2c7f7a;margin-right: 8px;"></i>Custom
                                                                date range
                                                            </button>
                                                        </div>
                                                        <div class="button-row row" style="margin:0px;">
                                                            <div class="button-col content-col">
                                                                <div class="custom-date-graph">
                                                                    <div style="display: block;">
                                                                        <label for="">Date start :
                                                                        </label>
                                                                        <input type="date" id="myDate"
                                                                            value="">
                                                                    </div>
                                                                    <div style="display: block;">
                                                                        <label for="">Date end
                                                                            :</label>
                                                                        <input type="date" id="myDate2"
                                                                            value="">
                                                                    </div>
                                                                    <p id="demo-date">แสดงวันที่เลือก</p>
                                                                    <div>
                                                                        <button onclick="dayPickup()">Done</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        function dayPickup() {
                                            let x1 = document.getElementById("myDate").value;
                                            let x2 = document.getElementById("myDate2").value;
                                            document.getElementById("demo-date").innerHTML = x1 + " - " + x2;
                                        }
                                    </script>
                                </div>
                            </div>
                            <div>
                                <!-- กราฟที่ 2 -->
                                <div class="container-graph">
                                    <canvas id="combinedChart" style="width: 100%"></canvas>
                                    <div>
                                        <button class="graph-select " style="width: 200px;" id="toggleButton"
                                            onclick="toggleGraph()">
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
                        $page = !empty(@$_GET['page']) ? $_GET['page'] : 1;
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
                                                        <select class="entriespage-button" id="search-per-page" onchange="search_per_page({{$page}}, 'sms', 'sms-alert')"> <!-- เลขที่หน้า, ชือนำหน้าตาราง, ชื่อ Route -->
                                                            <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 ? 'selected' : '' }}>10</option>
                                                            <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 ? 'selected' : '' }}>25</option>
                                                            <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 ? 'selected' : '' }}>50</option>
                                                            <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 ? 'selected' : '' }}>100</option>
                                                        </select>
                                                        <input class="search-button" id="search-sms" style="text-align:left;" placeholder="Search" />
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
                                                        <div class="dropdown">
                                                            <button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                                                ทำรายการ <span class="caret"></span>
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
                                                    <p class="py2" id="sms-showingEntries">{{ showingEntriesTable($data_sms) }}</p>
                                                    <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format($total_sms_amount, 2) }} บาท</div>
                                                    @if ($total_sms_amount > 0)
                                                        <div id="sms-paginate">
                                                            {!! paginateTable($data_sms) !!}
                                                        </div>
                                                    @endif
                                                </div>
                                            </caption>
                                        </table>
                                </div>
                                <!-- .card end -->
                            </div>
                        </div>
                        <!-- .row end -->
                    </div>

                    <!-- ตารางที่ 2-->
                    <div class="container-xl">
                        <div class="row clearfix">
                            <div class="col-md-12 col-12">
                                <div class="table-d p-4 mb-4">
                                    <h1 class="table-label">Transfer Revenue</h1>
                                    <table id="" class="example ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :
                                                    </label>
                                                    <select class="entriespage-button">
                                                        <option value="7" class="bg-[#f7fffc] text-[#2C7F7A]">10
                                                        </option>
                                                        <option value="15" class="bg-[#f7fffc] text-[#2C7F7A]">25
                                                        </option>
                                                        <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">50
                                                        </option>
                                                        <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">100
                                                        </option>
                                                    </select>
                                                    <input class="search-button" style="text-align:left;" placeholder="Search" />
                                                    </i>
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" data-priority="1">#</th>
                                                <th style="text-align: center;" data-priority="1">วันที่</th>
                                                <th style="text-align: center;" data-priority="1">เวลา</th>
                                                <th style="text-align: center;">โอนจากบัญชี</th>
                                                <th style="text-align: center;">เข้าบัญชี</th>
                                                <th style="text-align: center;" data-priority="1">จำนวนเงิน</th>
                                                <th style="text-align: center;">ผู้ทำรายการ</th>
                                                <th style="text-align: center;">ประเภทรายได้</th>
                                                <th style="text-align: center;">วันที่โอนย้าย</th>
                                                <th style="text-align: center;" data-priority="1">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- row 1 -->
                                            <tr>
                                                <td>data</td>
                                                <td>
                                                    <div class="flex-jc p-left-4">
                                                        <img src="./image/bank/KBNK.jpg" alt=""
                                                            class="img-bank" /> KBNK
                                                    </div>
                                                </td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td style="text-align: center;">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary" type="button"
                                                            data-toggle="dropdown" type="button"
                                                            data-toggle="dropdown">ทำรายการ <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">
                                                                Front
                                                                Desk Bank <br>Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Guest
                                                                Deposit Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">All
                                                                Outlet Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">
                                                                Agoda
                                                                Bank <br>Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Credit
                                                                Card Hotel <br> Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">
                                                                Elexa
                                                                EGAT Bank Transfer <br> Transfer Revenue
                                                            </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">No
                                                                Category</li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Water
                                                                Park Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">
                                                                Credit
                                                                Card Water <br>Park Revenue </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2">Showing 1 to 7 of 7 entries</p>
                                                <div class="font-bold">ยอดรวมทั้งหมด 00.00 บาท</div>
                                                <div>
                                                    <div class="pagination">
                                                        <a href="#" class="r-l-md">&laquo;</a>
                                                        <a href="#">1</a>
                                                        <a href="#" class="active">2</a>
                                                        <a href="#">3</a>
                                                        <a href="#" class="r-r-md">&raquo;</a>
                                                    </div>
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
                                                    <label class="entriespage-label">entries per page :
                                                    </label>
                                                    <select class="entriespage-button">
                                                        <option value="7" class="bg-[#f7fffc] text-[#2C7F7A]">10
                                                        </option>
                                                        <option value="15" class="bg-[#f7fffc] text-[#2C7F7A]">25
                                                        </option>
                                                        <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">50
                                                        </option>
                                                        <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">100
                                                        </option>
                                                    </select>
                                                    <input class="search-button" style="text-align:left;" placeholder="Search" />
                                                    </i>
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" data-priority="1">#</th>
                                                <th style="text-align: center;" data-priority="1">วันที่</th>
                                                <th style="text-align: center;" data-priority="1">เวลา</th>
                                                <th style="text-align: center;">โอนจากบัญชี</th>
                                                <th style="text-align: center;">เข้าบัญชี</th>
                                                <th style="text-align: center;" data-priority="1">จำนวนเงิน</th>
                                                <th style="text-align: center;">ผู้ทำรายการ</th>
                                                <th style="text-align: center;">ประเภทรายได้</th>
                                                <th style="text-align: center;">วันที่โอนย้าย</th>
                                                <th style="text-align: center;" data-priority="1">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- row 1 -->
                                            <tr>
                                                <td>data</td>
                                                <td>
                                                    <div class="flex-jc p-left-4">
                                                        <img src="./image/bank/KBNK.jpg" alt=""
                                                            class="img-bank" /> KBNK
                                                    </div>
                                                </td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td>data</td>
                                                <td style="text-align: center;">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary" type="button"
                                                            data-toggle="dropdown" type="button"
                                                            data-toggle="dropdown">ทำรายการ <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">
                                                                Front
                                                                Desk Bank <br>Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Guest
                                                                Deposit Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">All
                                                                Outlet Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">
                                                                Agoda
                                                                Bank <br>Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Credit
                                                                Card Hotel <br> Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">
                                                                Elexa
                                                                EGAT Bank Transfer <br> Transfer Revenue
                                                            </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter1">No
                                                                Category</li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter2">
                                                                Water
                                                                Park Bank <br> Transfer Revenue </li>
                                                            <li class="button-li" data-toggle="modal"
                                                                data-target="#exampleModalCenter4">
                                                                Credit
                                                                Card Water <br>Park Revenue </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <caption class="caption-bottom ">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2 ">Showing 1 to 7 of 7 entries</p>
                                                <div class="font-bold ">ยอดรวมทั้งหมด 00.00 บาท</div>
                                                <div>
                                                    <div class="pagination">
                                                        <a href="#" class="r-l-md">&laquo;</a>
                                                        <a href="#">1</a>
                                                        <a href="#" class="active">2</a>
                                                        <a href="#">3</a>
                                                        <a href="#" class="r-r-md">&raquo;</a>
                                                    </div>
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
                <!-- Modal: Logout -->
                <div class="modal fade" id="exampleModalLogout" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-color-green">
                                <h5 class="modal-title text-white" id="exampleModalLogoutLabel">Logout
                                </h5>
                                <button type="button" class="btn-close lift" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are You Sure to Logout!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary lift"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-color-green lift"
                                    onclick="location.href='logout'">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal: Logout -->
        <div class="modal fade" id="exampleModalLogout" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-color-green">
                        <h5 class="modal-title text-white" id="exampleModalLogoutLabel">Logout</h5>
                        <button type="button" class="btn-close lift" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are You Sure to Logout!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-color-green lift"
                            onclick="location.href='{{ route('logout') }}'">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="get-page" value="{{ !empty(@$_GET['page']) ? @$_GET['page'] : 1 }}">
    <input type="hidden" id="get-perPage" value="{{ !empty(@$_GET['perPage']) ? @$_GET['perPage'] : 10 }}">

    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="./assets/js/searh-calendar.js"></script>
    <!-- style สำหรับเปิดปิด custom date -->

    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/search.js')}}"></script>

    <style>
        .content-col {
            display: none;
        }

        .active-customdate {
            display: block;
        }
    </style>
    <script>
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
        $(document).on('keyup', '#search-sms', function () {
            var search_value = $(this).val();

            $('#sms-paginate').children().remove().end();

            if (search_value != '') {
                $('#smsTable').DataTable().destroy();
                var table = $('#smsTable').dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    "ajax": "sms-search-table/"+search_value+"",
                    "initComplete": function (settings, json) {//here is the tricky part 
                        console.log($('#smsTable tr').length - 1);
                        
                        var count = $('#smsTable tr').length - 1;
                        $("#sms-showingEntries").text(showingEntriesSearch(count));
                        $('#sms-paginate').append(paginateSearch(count, 'sms-alert'));
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
                $('#sms-paginate').append(paginateSearch(20, 'sms-alert'));
            }

            document.getElementById('search-sms').focus();
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

<script>
    Chart.defaults.font.family = "Sarabun";
    const revenueData = [
        201600, 201600, 147700, 172900, 99800, 211400, 98200, 136200, 166200,
        121000, 101300, 126600, 128600, 99300, 111220, 130500, 76300, 122900,
        170800, 73600, 93300, 92000, 60500, 69600, 118000, 110700, 107500, 47100,
        86400, 4000,
    ];
    const today = new Date();
    const labels = [];
    for (let i = 29; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const month = date.getMonth() + 1;
        const day = date.getDate();
        labels.push(`${month}/${day}`);
    }

    function formatNumber(num) {
        if (num >= 1e6) {
            return (num / 1e6).toFixed(1) + "M";
        } else if (num >= 1e3) {
            return (num / 1e3).toFixed(1) + "K";
        }
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatFullNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function drawRotatedText(ctx, bar, displayData, fontSize) {
        ctx.font = "normal " + (fontSize - 4) + "px Sarabun"; // Adjust font size for longer labels
        ctx.save();
        // Check media width and adjust translation offset accordingly
        var translateOffset = window.innerWidth < 768 ? -10 : -20;
        ctx.translate(bar.x, bar.y + translateOffset);
    }
    const valueOnTopPlugin = {
        afterDatasetsDraw: function(chart) {
            const ctx = chart.ctx;
            const fontSize = Math.min(16, Math.max(10, Math.round(chart.width / 50)));
            ctx.font = "normal " + fontSize + "px Sarabun"; // Set font size dynamically
            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, index) => {
                    const data = dataset.data[index];
                    let displayData = formatNumber(data);
                    if (chart.data.labels.length === 7) {
                        displayData = formatNumber(data);
                        ctx.save();
                        ctx.translate(bar.x, bar.y - 10);
                        ctx.fillStyle = "#000";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText(displayData, 0, 0);
                        ctx.restore();
                    } else if (chart.data.labels.length === 15) {
                        ctx.font = "normal " + (fontSize - 4) +
                            "px Sarabun"; // Adjust font size for longer labels
                        ctx.fillStyle = "#000";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.save();
                        ctx.fillText(displayData, bar.x, bar.y - 10);
                        ctx.restore();
                    } else if (chart.data.labels.length === 30) {
                        ctx.font = "normal " + (fontSize - 4) +
                            "px Sarabun"; // Adjust font size for longer labels
                        ctx.save();
                        drawRotatedText(ctx, bar, displayData);
                        ctx.rotate(-Math.PI / 2);
                        ctx.fillStyle = "#000";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText(displayData, 0, 0);
                        ctx.restore();
                        return;
                    }
                });
            });
        },
    };
    const ctx = document.getElementById("revenueChart").getContext("2d");
    const maxRevenueValue = Math.max(...revenueData);
    const buffer = 20000; // Adding a buffer value
    let yAxisMax = maxRevenueValue + buffer;
    const roundingFactor = 20000;
    yAxisMax = Math.ceil(yAxisMax / roundingFactor) * roundingFactor;
    const revenueChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Last 30 Days Revenue",
                data: revenueData,
                backgroundColor: "#2C7F7A",
                borderWidth: 0,
                barPercentage: 0.7,
            }, ],
        },
        options: {
            scales: {
                x: {},
                y: {
                    beginAtZero: true,
                    max: yAxisMax,
                    ticks: {
                        stepSize: 20000,
                        callback: function(value) {
                            return formatNumber(value);
                        },
                    },
                },
            },
        },
        plugins: [valueOnTopPlugin],
    });

    function updateChart(days) { 
        const newData = [];
        const newLabels = [];
        const today = new Date();
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(today.getDate() - i);
            const month = date.getMonth() + 1;
            const day = date.getDate();
            newLabels.push(`${month}/${day}`);
        }
        const startIndex = revenueData.length - days;
        for (let i = startIndex; i < revenueData.length; i++) {
            newData.push(revenueData[i]);
        }
        revenueChart.data.labels = newLabels;
        revenueChart.data.datasets[0].data = newData;
        revenueChart.data.datasets[0].label = `Last ${days} Days Revenue`;
        revenueChart.update();
    }
    updateChart(7);
</script>

    {{-- กราฟ 2 --}}
    <script>
        var combinedChart = document.getElementById("combinedChart").getContext("2d");
        var graph1Visible = true;
        var data1 = {
            labels: ["Yesterday", "Today", "Average"],
            datasets: [{
                label: "Forecast Revenue",
                backgroundColor: "#2C7F7A",
                borderWidth: 1,
                barPercentage: 0.33, // Adjust bar width
                data: [2000000, 2200000, 2100000], // Adjusted for more realistic numbers
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
                data: [
                    1000000, 1500000, 2000000, 1800000, 2200000, 1300000, 1100000,
                    900000,
                ],
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
        var data3 = {
            labels: ["จันทร์", "อังคาร", "พุธ", "พฤกหัส", "ศุกร์", "เสาร์", "อาทิตย์", ],
            datasets: [{
                label: "Revenue",
                data: [2000000, 2200000, 4100000, 3000000, 3200000, 2100000, 4100000],
                backgroundColor: "#2C7F7A",
                borderWidth: 1,
                barPercentage: 0.8, // Adjust bar width
            }, ],
        };
        var options3 = {
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
                return Math.max(12, Math.floor(canvasWidth / 100)); // Larger font for desktops
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
                            ctx.fillStyle = "#000"; // Black text color
                            var fontStyle = "normal";
                            var fontFamily = "Sarabun";
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            var dataString = formatNumber(dataset.data[index]);
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";
                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - fontSize / 4 -
                                padding);
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
            if (graph1Visible) {
                currentData = data2;
                currentOptions = options2;
            } else {
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
            graph1Visible = !graph1Visible;
            var toggleButton = document.getElementById("toggleButton");
            var icon = document.createElement("i");
            if (graph1Visible) {
                toggleButton.textContent = "Switch to Time ";
                icon.className = "icon-repeat";
            } else {
                toggleButton.textContent = "Switch to Forecast ";
                icon.className = "icon-repeat2";
            }
            // Append the icon to the toggleButton content
            toggleButton.appendChild(icon);
        }
    </script>
@endsection

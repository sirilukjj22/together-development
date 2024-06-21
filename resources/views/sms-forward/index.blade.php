@extends('layouts.test')

@section('content')
<style>
    /* อันนี้ style ของ table นะ */
    .dtr-details {
        width: 100%;
    }

    .dtr-title {
        float: left;
        text-align: left;
        margin-right: 10px;
    }

    .dtr-data {
        display: block;
        text-align: right !important;
    }

    .dt-container .dt-paging .dt-paging-button {
        padding: 0 !important;
    }
</style>
    <div class="add">
        <button type="button" class="button-17 button-18" id="add-data">เพิ่มข้อมูล</button>
    </div>

    <div class="topic">
        <h3>Daily Bank Transaction Revenue</h3>
    </div>

    <!-- Begin Dashboard -->
    <?php
    // $month_from_current = date('d') == '01' ? date('m', strtotime('-1 month')) : date('m');
    
    $day_from = isset($day) ? $day : date('d');
    $month_from = isset($month) ? $month : date('m');
    $year_from = isset($year) ? $year : date('Y');
    $time_from = isset($time) ? $time : '21:00:00';
    
    // $day_to = isset($day2) ? $day2 : date('d');
    // $month_to = isset($month2) ? $month2 : date('m');
    // $year_to = isset($year2) ? $year2 : date('Y');
    // $time_to = isset($time2) ? $time2 : '21:00:00';
    
    $date_from = $year_from . '-' . $month_from . '-' . $day_from . ' ' . $time_from;
    // $date_to = $year_to . '-' . $month_to . '-' . $day_to . ' ' . $time_to;
    
    // echo $date_to;
    ?>

    <div class="dashboard">
        <div class="control">
            <div class="circle">
                <h3>{{ number_format($total_day, 2) }}</h3>
                <h4>Total Revenue</h4>
            </div>
        </div>

        <div class="total-number-mobile">{{ number_format($total_day, 2) }}</div>
        <div class="total-revenue-mobile">Total Revenue</div>
        <a href="{{ route('sms-detail', ['front', $date_from]) }}">
            <div class="rectangle-FDB">
                <div class="front-desk-bank-transfer-revenue">
                    <span>Front Desk Bank
                        <br>
                        Transfer Revenue</span>
                </div>
                <div class="front-desk-number">{{ number_format($total_front, 2) }}</div>
            </div>
        </a>
        <div>
            <div>
                <a href="{{ route('sms-detail', ['credit_water', $date_from]) }}">
                    <div class="rectangle-CCWP">
                        <div class="credit-card-water-park-revenue ">
                            Credit Card Water <br> Park Revenue
                        </div>
                        <div class="credit-card-water-park-number">{{ number_format($total_wp_credit, 2) }}</div>
                    </div>
                </a>
            </div>
        </div>
        <div>
            <a href="{{ route('sms-detail', ['room', $date_from]) }}">
                <div class="rectangle-GDB">
                    <div class="guest-deposit-bank-revenue">
                        Guest Deposit Bank
                        <br />
                        Transfer Revenue
                    </div>
                    <div class="guest-deposit-number">{{ number_format($total_room, 2) }}</div>
                </div>
            </a>
        </div>
        <div>
            <a href="{{ route('sms-detail', ['elexa_revenue', $date_from]) }}">
                <div class="rectangle-WPB">
                    <div class="transfer-revenue">Elexa EGAT Bank <br> Transfer Revenue</div>
                    <div class="transfer-revenue-number">{{ number_format($total_ev, 2) }}</div>
                </div>
            </a>
        </div>
        <div>
            <a href="{{ route('sms-detail', ['all_outlet', $date_from]) }}">
                <div class="rectangle-FnB">
                    <div class="f-b-bank-transfer-revenue">
                        All Outlet Bank
                        <br />
                        Transfer Revenue
                    </div>
                    <div class="f-b-bank-number">{{ number_format($total_fb, 2) }}</div>
                </div>
            </a>
        </div>
        <div>
            <a href="{{ route('sms-detail', ['credit', $date_from]) }}">
                <div class="rectangle-CCR">
                    <div class="credit-card-revenue">Credit Card Hotel Revenue</div>
                    <div class="credit-card-number">{{ number_format($total_credit, 2) }}</div>
                </div>
            </a>
            <div>
                <a href="{{ route('sms-agoda_detail', [$date_from]) }}">
                    <div class="rectangle-CCA">
                        <div class="credit-card-agoda-revenue">
                            Agoda bank
                            <br>
                            Transfer Revenue
                        </div>
                        <div class="credit-card-agoda-number">{{ number_format($total_agoda, 2) }}</div>
                    </div>
                </a>
            </div>
        </div>
        <div>
            <a href="{{ route('sms-detail', ['water', $date_from]) }}">
                <div class="rectangle-TP">
                    <div class="water-park-bank-transfer-revenue">
                        Water Park Bank
                        <br />
                        Transfer Revenue
                    </div>
                    <div class="water-park-bank-number">{{ number_format($total_wp, 2) }}</div>
                </div>
            </a>
        </div>
    </div>

    <!-- END Dashboard -->

    <label class="topic" style="margin-top: 10px; width: 100%;" for="">Detail</label>
    <div class="group-53">
        <a href="{{ route('sms-detail', ['transfer_revenue', $date_from]) }}">
            <div class="frame-8 frame-9">
                <div class="detail_group53">
                    Transfer Revenue
                </div>
                <div class="_4">{{ number_format($total_transfer, 2) }}</div>
            </div>
        </a>



        <a href="{{ route('sms-detail', ['split_revenue', $date_from]) }}">
            <div class="frame-9">
                <div class="detail2_group53">
                    Split Credit Card Hotel
                    <br />
                    Revenue
                </div>
                <div class="_42">{{ number_format($total_split, 2) }}</div>
            </div>
        </a>

        <a href="{{ route('sms-detail', ['transfer_transaction', $date_from]) }}">
            <div class="frame-11">
                <div class="no2 detail2_group53">Transfer Transaction</div>
                <div class="_42">{{ $total_transfer2 }}</div>
            </div>
        </a>

        <a href="{{ route('sms-detail', ['credit_transaction', $date_from]) }}">
            <div class="frame-12">
                <div class="detail2_group53">
                    Credit Card Hotel
                    <br />
                    Transfer Transaction
                </div>
                <div class="_42">{{ $total_credit_transaction }}</div>
            </div>
        </a>

        <a href="{{ route('sms-detail', ['split_transaction', $date_from]) }}">
            <div class="frame-7 frame-11">
                <div class="detail2_group53">Split Credit Card <br> Hotel Transaction</div>
                <div class="_42">{{ $total_split_transaction->transfer_transaction ?? 0 }}</div>
            </div>
        </a>

        <a href="{{ route('sms-detail', ['total_transaction', $date_from]) }}">
            <div class="frame-6 frame-12">
                <div class="no2 detail2_group53">Total Transaction <br>&nbsp;</div>
                <div class="_42">{{ number_format(count($total_transaction)) }}</div>
            </div>
        </a>



        <a href="{{ route('sms-detail', ['status', $date_from]) }}">
            <div class="frame-6 frame-12">
                <div class="no2 detail2_group53">No income Type <br>&nbsp;</div>
                <div class="_42">{{ $total_not_type, 2 }}</div>
            </div>
        </a>



        <a href="{{ route('sms-detail', ['no_income_revenue', $date_from]) }}">
            <div class="frame-6 frame-12">
                <div class="no2 detail2_group53">No income Revenue <br>&nbsp;</div>
                <div class="_42">{{ number_format($total_not_type_revenue, 2) }}</div>
            </div>
        </a>
    </div>



    <div class="Graph1">
        <canvas id="revenueChart" width="800" height="400"></canvas>
        <div class="btngraph">
            <div class="custom-select-wrapper">
                <select class="custom-select" onchange="updateChart(this.value)">
                    <option value="7">7 Days</option>
                    <option value="15">15 Days</option>
                    <option value="30">30 Days</option>
                </select>
                <span class="custom-arrow"></span>
            </div>
        </div>
    </div>

    <div class="Graph2">
        <canvas id="combinedChart" width="800" height="400"></canvas>

        <div>
            <button class="iphone-button" id="toggleButton" onclick="toggleGraph()">Switch to Time <i
                    class="fa-solid fa-repeat"></i></button>
        </div>
    </div>

    <div class="search">
        @php
            $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
        @endphp
        <form action="{{ route('sms-search-calendar') }}" method="POST" enctype="multipart/form-data" id="form-calendar">
            @csrf
            <div class="date_from">
                <div class="day">
                    <select name="day" id="day">
                        <option value="0">ทั้งหมด</option>
                        <?php $day_num = isset($day) ? date('d', strtotime('last day of this month', strtotime(date('2024-' . $month . '-' . $day)))) : date('t'); ?>

                        @for ($i = 1; $i <= $day_num; $i++)
                            <?php $d = str_pad($i, 2, '0', STR_PAD_LEFT); ?>

                            @if (!isset($day) && date('d') == $d)
                                <option value="{{ $d }}" selected>{{ $i }}</option>
                            @else
                                <option value="{{ $d }}"
                                    {{ isset($day) && $day == $d ? 'selected' : date('d') }}>{{ $i }}
                                </option>
                            @endif

                            @if (date('t') == date('d') && $d == '01')
                                <option value="{{ $d }}" selected>{{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                </div>

                <div class="month">
                    <select name="month" id="month">
                        <option value="0">ทั้งหมด</option>
                        @if (isset($month))
                            <option value="01" {{ $month == '01' ? 'selected' : '' }}>มกราคม</option>
                            <option value="02" {{ $month == '02' ? 'selected' : '' }}>กุมภาพันธ์</option>
                            <option value="03" {{ $month == '03' ? 'selected' : '' }}>มีนาคม</option>
                            <option value="04" {{ $month == '04' ? 'selected' : '' }}>เมษายน</option>
                            <option value="05" {{ $month == '05' ? 'selected' : '' }}>พฤษภาคม</option>
                            <option value="06" {{ $month == '06' ? 'selected' : '' }}>มิถุนายน</option>
                            <option value="07" {{ $month == '07' ? 'selected' : '' }}>กรกฎาคม</option>
                            <option value="08" {{ $month == '08' ? 'selected' : '' }}>สิงหาคม</option>
                            <option value="09" {{ $month == '09' ? 'selected' : '' }}>กันยายน</option>
                            <option value="10" {{ $month == '10' ? 'selected' : '' }}>ตุลาคม</option>
                            <option value="11" {{ $month == '11' ? 'selected' : '' }}>พฤศจิกายน</option>
                            <option value="12" {{ $month == '12' ? 'selected' : '' }}>ธันวาคม</option>
                        @else
                            <?php
                            $month_current = date('d') == '01' ? date('m', strtotime('-1 month')) : date('m');
                            ?>
                            <option value="01" {{ $month_current == '01' ? 'selected' : '' }}>มกราคม</option>
                            <option value="02" {{ $month_current == '02' ? 'selected' : '' }}>กุมภาพันธ์</option>
                            <option value="03" {{ $month_current == '03' ? 'selected' : '' }}>มีนาคม</option>
                            <option value="04" {{ $month_current == '04' ? 'selected' : '' }}>เมษายน</option>
                            <option value="05" {{ $month_current == '05' ? 'selected' : '' }}>พฤษภาคม</option>
                            <option value="06" {{ $month_current == '06' ? 'selected' : '' }}>มิถุนายน</option>
                            <option value="07" {{ $month_current == '07' ? 'selected' : '' }}>กรกฎาคม</option>
                            <option value="08" {{ $month_current == '08' ? 'selected' : '' }}>สิงหาคม</option>
                            <option value="09" {{ $month_current == '09' ? 'selected' : '' }}>กันยายน</option>
                            <option value="10" {{ $month_current == '10' ? 'selected' : '' }}>ตุลาคม</option>
                            <option value="11" {{ $month_current == '11' ? 'selected' : '' }}>พฤศจิกายน</option>
                            <option value="12" {{ $month_current == '12' ? 'selected' : '' }}>ธันวาคม</option>
                        @endif
                    </select>
                </div>

                <div class="year">
                    <select name="year" id="year">
                        <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                    </select>
                </div>

                <div class="time">
                    <input type="time" id="time" name="time" value="<?php echo isset($time) && $time != $time ?: date('20:59:59'); ?>" hidden>
                </div>

                <div class="revenue_type">
                    <select class="select2" name="status">
                        <option value="" {{ isset($status) && $status == '' ? 'selected' : '' }}>
                            ประเภทรายได้ทั้งหมด
                        </option>
                        <option value="6" {{ isset($status) && $status == 6 ? 'selected' : '' }}>Front Desk Bank
                            Transfer Revenue
                            Transfer Revenue</option>
                        <option value="1" {{ isset($status) && $status == 1 ? 'selected' : '' }}>Guest Deposit
                            Bank
                            Transfer Revenue
                            Transfer Revenue</option>
                        <option value="2" {{ isset($status) && $status == 2 ? 'selected' : '' }}>All Outlet Bank
                            Transfer Revenue
                        </option>
                        <option value="4" {{ isset($status) && $status == 4 ? 'selected' : '' }}>Credit Card
                            Revenue
                        </option>
                        <option value="5" {{ isset($status) && $status == 5 ? 'selected' : '' }}>Credit Card
                            Agoda Revenue
                        </option>
                        <option value="3" {{ isset($status) && $status == 3 ? 'selected' : '' }}>Water Park Bank
                            Transfer Revenue</option>
                        <option value="7" {{ isset($status) && $status == 7 ? 'selected' : '' }}>Credit Card
                            Water Park Revenue</option>
                        <option value="7" {{ isset($status) && $status == 8 ? 'selected' : '' }}>Elexa EGAT Bank
                            Transfer Revenue
                        </option>
                    </select>

                </div>

                <div class="acc_number">
                    <select class="select2-1" name="into_account" id="into_account" onchange="select_account()">
                        <option value="" {{ isset($into_account) && $into_account == '' ? 'selected' : '' }}>
                            เลขที่บัญชีทั้งหมด</option>
                        <option value="708-226791-3"
                            {{ isset($into_account) && $into_account == '708-226791-3' ? 'selected' : '' }}>SCB
                            708-226791-3</option>
                        <option value="708-226792-1"
                            {{ isset($into_account) && $into_account == '708-226792-1' ? 'selected' : '' }}>SCB
                            708-226792-1</option>
                        <option value="708-227357-4"
                            {{ isset($into_account) && $into_account == '708-227357-4' ? 'selected' : '' }}>SCB
                            708-227357-4</option>
                    </select>
                </div>
                <div class="info-container">
                    <div class="icon facebook">
                        <div class="tooltip" id="div-note">
                            Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue <br>
                            Credit Card Hotel Revenue <br>
                            Warter Park & Credit Card Warter Park Revenue <br>

                        </div>
                        <span><i class="fa-solid fa-circle-info"></i></span>
                    </div>
                    <div class="icon twitter">
                        <div class="tooltip">
                            Twitter
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="button-6">
                <button type="button" role="" id="btn-search-date">ค้นหา</button>
            </div> --}}
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                        <button class="button-6 btn btn-custom" type="button" role="" id="btn-search-date">ค้นหา</button>
                </div>
            </div>
        </form>

        {{-- <div class="dataTables_wrapper"></div> --}}
        <table id="example" class="table-hover nowarp" style="width:100%">
            <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="1">วันที่</th>
                    <th data-priority="1">เวลา</th>
                    <th>โอนจากบัญชี</th>
                    <th>เข้าบัญชี</th>
                    <th data-priority="1">จำนวนเงิน</th>
                    <th>ผู้ทำรายการ</th>
                    <th>ประเภทรายได้</th>
                    <th>วันที่โอนย้าย</th>
                    <th data-priority="1">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                @foreach ($data_sms as $key => $item)
                    @if ($item->split_status == 3)
                        <tr class="my-row table-secondary">
                        @else
                        <tr class="my-row">
                    @endif

                    <td>{{ $key + 1 }}</td>
                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                    <td>{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                    <td style="text-align: left;">
                        <?php
                            $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                            $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                        ?>

                        @if (file_exists($filename))
                            <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg"
                                alt="avatar" title="">
                        @elseif (file_exists($filename2))
                            <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png"
                                alt="avatar" title="">
                        @endif
                        {{ @$item->transfer_bank->name_en }}
                    </td>
                    <td>
                        <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/SCB.jpg" alt="avatar" title="">
                        {{ 'SCB ' . $item->into_account }}
                    </td>
                    <td data-label="จำนวนเงิน">
                        {{ number_format($item->amount_before_split > 0 ? $item->amount_before_split : $item->amount, 2) }}
                    </td>
                    <td>{{ $item->remark ?? 'Auto' }}</td>
                    <td>
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
                        @endif

                        @if ($item->split_status == 1)
                            <br>
                            <span class="text-danger">(Split Credit Card From
                                {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                        @endif

                    </td>
                    <td>
                        {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-custom" type="button" data-toggle="dropdown">
                                ทำรายการ<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if ($role_revenue->front_desk == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                        Front Desk Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->guest_deposit == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                        Guest Deposit Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->all_outlet == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                        All Outlet Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->agoda == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                        Agoda Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->credit_card_hotel == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                        Credit Card Hotel Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->elexa == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                        Elexa EGAT Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->no_category == 1)
                                    <li class="licolor" onclick="change_status({{ $item->id }}, 'No Category')">
                                        No Category
                                    </li>
                                @endif

                                @if ($role_revenue->water_park == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                        Water Park Bank Transfer Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->credit_water_park == 1)
                                    <li class="licolor"
                                        onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                        Credit Card Water Park Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->transfer == 1)
                                    <li class="licolor" onclick="transfer_data({{ $item->id }})">
                                        Transfer
                                    </li>
                                @endif
                                @if ($role_revenue->time == 1)
                                    <li class="licolor" onclick="update_time_data({{ $item->id }})">
                                        Update Time
                                    </li>
                                @endif
                                @if ($role_revenue->split == 1)
                                    <li class="licolor"
                                        onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                        Split Revenue
                                    </li>
                                @endif
                                @if ($role_revenue->edit == 1)
                                    <li class="licolor" onclick="edit({{ $item->id }})">Edit</li>
                                    <li class="licolor" onclick="deleted({{ $item->id }})">Delete</li>
                                @endif
                            </ul>
                        </div>
                    </td>
                    </tr>
                    <?php $total += $item->amount; ?>
                @endforeach
            </tbody>
            <div class="all_revenue">
                <h1>ยอดรวมทั้งหมด {{ number_format($total, 2) }} บาท</h1>
            </div>
        </table>
    </div>

    <div class="search" style="margin-top: 10px;">
        {{-- <div class="dataTables_wrapper2"></div> --}}

        <h4>Transfer Revenue</h4>
        <table id="example2" class="table-hover nowarp" style="width:100%">
            <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="1">วันที่</th>
                    <th data-priority="1">เวลา</th>
                    <th>โอนจากบัญชี</th>
                    <th>เข้าบัญชี</th>
                    <th data-priority="1">จำนวนเงิน</th>
                    <th>ผู้ทำรายการ</th>
                    <th>ประเภทรายได้</th>
                    <th>วันที่โอนย้าย</th>
                    <th data-priority="1">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_transfer_revenue = 0; ?>

                @foreach ($data_sms_transfer as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                        <td style="text-align: left;">
                            <?php
                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                            ?>

                            @if (file_exists($filename))
                                <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg"
                                    alt="avatar" title="">
                            @elseif (file_exists($filename2))
                                <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png"
                                    alt="avatar" title="">
                            @endif
                            {{ @$item->transfer_bank->name_en }}
                        </td>
                        <td>
                            <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/SCB.jpg" alt="avatar" title="">
                            <span>{{ 'SCB ' . $item->into_account }}</span>
                        </td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->remark ?? 'Auto' }}</td>
                        <td>
                            @if ($item->status == 1)
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
                            @endif

                            @if ($item->split_status == 1)
                                <br>
                                <span class="text-danger">(Split Credit Card From
                                    {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                            @endif
                        </td>
                        <td>
                            {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '' }}
                        </td>
                        <td>
                            @if (($item->status != 4 && $item->remark == 'Auto') || Auth::user()->permission > 0)
                                <div class="dropdown">
                                    <button class="btn btn-custom" type="button" data-toggle="dropdown">
                                        ทำรายการ<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if ($role_revenue->front_desk == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                Front Desk Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->guest_deposit == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                Guest Deposit Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->all_outlet == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                All Outlet Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->agoda == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                Agoda Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->credit_card_hotel == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                Credit Card Hotel Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->elexa == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                Elexa EGAT Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->no_category == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'No Category')">
                                                No Category
                                            </li>
                                        @endif

                                        @if ($role_revenue->water_park == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                Water Park Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->credit_water_park == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                Credit Card Water Park Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->transfer == 1)
                                            <li class="licolor" onclick="transfer_data({{ $item->id }})">
                                                Transfer
                                            </li>
                                        @endif
                                        @if ($role_revenue->time == 1)
                                            <li class="licolor" onclick="update_time_data({{ $item->id }})">
                                                Update Time
                                            </li>
                                        @endif
                                        @if ($role_revenue->split == 1)
                                            <li class="licolor"
                                                onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                Split Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->edit == 1)
                                            <li class="licolor" onclick="edit({{ $item->id }})">Edit</li>
                                            <li class="licolor" onclick="deleted({{ $item->id }})">Delete</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <?php $total_transfer_revenue += $item->amount; ?>
                @endforeach
            </tbody>
            <div class="all_revenue">
                <h1>ยอดรวมทั้งหมด {{ number_format($total_transfer_revenue, 2) }} บาท </h1>
            </div>
        </table>
    </div>

    <div class="search" style="margin-top: 10px;">
        {{-- <div class="dataTables_wrapper4"></div> --}}
        <h4>Split Credit Card Hotel Revenue</h4>
        <table id="example3" class="table-hover nowarp" style="width:100%">
            <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="1">วันที่</th>
                    <th data-priority="1">เวลา</th>
                    <th>โอนจากบัญชี</th>
                    <th>เข้าบัญชี</th>
                    <th data-priority="1">จำนวนเงิน</th>
                    <th>ผู้ทำรายการ</th>
                    <th>ประเภทรายได้</th>
                    <th>วันที่โอนย้าย</th>
                    <th data-priority="1">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_split_revenue = 0; ?>

                @foreach ($data_sms_split as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                        <td style="text-align: left;">
                            <?php
                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                            ?>

                            @if (file_exists($filename))
                                <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg"
                                    alt="avatar" title="">
                            @elseif (file_exists($filename2))
                                <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/{{ @$item->transfer_bank->name_en }}.png"
                                    alt="avatar" title="">
                            @endif
                            {{ @$item->transfer_bank->name_en }}
                        </td>
                        <td>
                            <img class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" src="../image/bank/SCB.jpg" alt="avatar" title="">
                            <span>{{ 'SCB ' . $item->into_account }}</span>
                        </td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->remark ?? 'Auto' }}</td>
                        <td>
                            @if ($item->status == 1)
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
                            @endif

                            @if ($item->split_status == 1)
                                <br>
                                <span class="text-danger">(Split Credit Card From
                                    {{ number_format(@$item->fullAmount->amount_before_split, 2) }})</span>
                            @endif
                        </td>
                        <td>
                            {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '' }}
                        </td>
                        <td>
                            @if (($item->status != 4 && $item->remark == 'Auto') || Auth::user()->permission > 0)
                                <div class="dropdown">
                                    <button class="btn btn-custom" type="button" data-toggle="dropdown">
                                        ทำรายการ<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if ($role_revenue->front_desk == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Front Desk Revenue')">
                                                Front Desk Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->guest_deposit == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Guest Deposit Revenue')">
                                                Guest Deposit Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->all_outlet == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'All Outlet Revenue')">
                                                All Outlet Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->agoda == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Agoda Revenue')">
                                                Agoda Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->credit_card_hotel == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Card Revenue')">
                                                Credit Card Hotel Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->elexa == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Elexa EGAT Revenue')">
                                                Elexa EGAT Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->no_category == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'No Category')">
                                                No Category
                                            </li>
                                        @endif

                                        @if ($role_revenue->water_park == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Water Park Revenue')">
                                                Water Park Bank Transfer Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->credit_water_park == 1)
                                            <li class="licolor"
                                                onclick="change_status({{ $item->id }}, 'Credit Water Park Revenue')">
                                                Credit Card Water Park Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->transfer == 1)
                                            <li class="licolor" onclick="transfer_data({{ $item->id }})">
                                                Transfer
                                            </li>
                                        @endif
                                        @if ($role_revenue->time == 1)
                                            <li class="licolor" onclick="update_time_data({{ $item->id }})">
                                                Update Time
                                            </li>
                                        @endif
                                        @if ($role_revenue->split == 1)
                                            <li class="licolor"
                                                onclick="split_data({{ $item->id }}, {{ $item->amount }})">
                                                Split Revenue
                                            </li>
                                        @endif
                                        @if ($role_revenue->edit == 1)
                                            <li class="licolor" onclick="edit({{ $item->id }})">Edit</li>
                                            <li class="licolor" onclick="deleted({{ $item->id }})">Delete</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <?php $total_split_revenue += $item->amount; ?>
                @endforeach
            </tbody>
            <div class="all_revenue">
                <h1>ยอดรวมทั้งหมด {{ number_format($total_split_revenue, 2) }} บาท </h1>
            </div>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter1Title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">แก้ไขเวลาการโอน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">เวลา</label>
                    <input type="time" class="form-control" name="update_time" id="update_time" value="<?php echo date('H:i:s'); ?>"
                        step="any">
                </div>
                <input type="hidden" name="timeID" id="timeID">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black;"
                    data-bs-dismiss="modal">Close</button>
                    <button type="button" class="button-10" style="background-color: #109699;"
                        onclick="change_time()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter2Title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">โอนย้าย</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sms-transfer') }}" method="POST" enctype="multipart/form-data"
                    class="basic-form">
                    @csrf
                    <div class="modal-body">
                        <div class="revenue_type_modal">
                            <label for="">วันที่โอนย้ายไป</label>
                            <input type="date" class="form-control" name="date_transfer" id="date_transfer">
                        </div>
                        <div class="box_modal">
                            <label>หมายเหตุ</label>
                            <textarea class="form-control" name="transfer_remark" id="transfer_remark" rows="7" cols="50" required>ปิดยอดช้ากว่ากำหนด</textarea>
                        </div>
                        <input type="hidden" name="dataID" id="dataID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="color: black;"
                        data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="button-10" style="background-color: #109699;">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter5" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter2Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sms-store') }}" method="POST" class="" id="form-id">
                    @csrf
                    <div class="modal-body-split">
                        <h2>เพิ่มข้อมูล</h2>
                        <label for="">ประเภทรายได้</label><br>
                        <select class="select2-2" id="status" name="status" onChange="select_type()">
                            <option value="0">เลือกข้อมูล</option>
                            <option value="1">Room Revenue</option>
                            <option value="2">All Outlet Revenue</option>
                            <option value="3">Water Park Revenue</option>
                            <option value="4">Credit Revenue</option>
                            <option value="5">Agoda Revenue</option>
                            <option value="6">Front Desk Revenue</option>
                            <option value="8">Elexa EGAT Revenue</option>
                        </select>

                        <div class="transfer_date">
                            <label for="">วันที่โอน <sup class="text-danger">*</sup></label><br>
                            <input class="form-control" type="date" name="date" id="sms-date" required>
                        </div>

                        <div class="transfer_time">
                            <label for="">เวลาที่โอน <sup class="text-danger">*</sup></label><br>
                            <input class="form-control" type="time" name="time" id="sms-time">
                        </div>

                        <div class="Amount agoda" hidden>
                            <label for="">Booking ID <sup class="text-danger">*</sup></label><br>
                            <input type="text" name="booking_id" id="booking_id" required>
                        </div>

                        <div class="transfer_from">
                            <label for="">โอนจากบัญชี <sup class="text-danger">*</sup></label><br>
                            <select class="select2-2" id="transfer_from" name="transfer_from" data-placeholder="Select">
                                <option value="0">เลือกข้อมูล</option>
                                @foreach ($data_bank as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}
                                        ({{ $item->name_en }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="transfer_to">
                            <label for="">เข้าบัญชี <sup class="text-danger">*</sup></label><br>
                            <select class="select2-2" id="add_into_account" name="into_account"
                                data-placeholder="Select">
                                <option value="0">เลือกข้อมูล</option>
                                <option value="708-226791-3">ธนาคารไทยพาณิชย์ (SCB) 708-226791-3</option>
                                <option value="708-226792-1">ธนาคารไทยพาณิชย์ (SCB) 708-226792-1</option>
                                <option value="708-227357-4">ธนาคารไทยพาณิชย์ (SCB) 708-227357-4</option>
                                <option value="076355900016902">ชำระผ่าน QR 076355900016902</option>
                            </select>

                        </div>
                        <label for="">จำนวนเงิน (บาท) <sup class="text-danger">*</sup></label><br>
                        <div class="Amount">
                            <input class="form-control" type="text" id="amount" name="amount" placeholder="0.00" required>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="color: black;" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="button-10 sa-button-submit" style="background-color: #109699;">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="SplitModalCenter" tabindex="-1" role="dialog" aria-labelledby="SplitModalCenter"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SplitModalCenter">Split Revenue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" class="form-split">
                    @csrf
                    <div class="modal-body-split">
                        <div class="split_date">
                            <label for="">วันที่</label>
                            <input type="date" class="form-control" name="date-split" id="date-split">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>

                        <div class="split_price">
                            <label for="">จำนวนเงิน <span class="text-danger fw-bold"
                                    id="text-split-amount"></span></label>
                            <input type="hidden" class="" name="balance_amount" id="balance_amount">
                            <input type="text" class="form-control" name="split-amount" id="split-amount"
                                placeholder="0.00">
                            <span class="text-danger fw-bold" id="text-split-alert"></span>
                        </div>
                        <div class="button-7">
                            <button type="button" class="btn-split-add" style="margin-top: 10px;">เพิ่ม</button>
                        </div>

                        <button type="button" class="button-10" style="background-color: #555555!important; "
                            onmouseover="this.style.backgroundColor='#555555';"
                            onmouseout="this.style.backgroundColor='#555555'" onclick="toggleHide()">ลบทั้งหมด</button>

                        <span class="split-todo-error text-danger" style="display: none;">กรุณาระบุข้อมูลให้ครบ
                            !</span>
                        <span class="split-error text-danger" style=""></span>

                        <table id="example" class="display3">
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

                    <input type="hidden" id="split_total_number" value="0">
                    <input type="hidden" id="split_number" value="0">
                    <input type="hidden" id="split_list_num" name="split_list_num" value="0">
                    <input type="hidden" name="splitID" id="splitID">
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black;"
                    data-bs-dismiss="modal">Close</button>

                    <button type="button" class="button-10 btn-save-split" onclick="change_split()"
                        style="background-color: #109699;" disabled>Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

    <style>
        /* Add this CSS to specify the font */
        #myChart {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <script>
        window.addEventListener('scroll', function() {
            var scrollPosition = window.scrollY;
            var windowHeight = window.innerHeight;
            var element = document.getElementById('element-to-transition');
            var elementOffset = element.offsetTop;

            // Calculate the distance of the element from the top of the viewport
            var distanceFromTop = elementOffset - scrollPosition;

            // Calculate the opacity based on the scroll position
            var opacity = 1 - (distanceFromTop / windowHeight);

            // Apply the opacity to the element
            element.style.opacity = opacity;
        });

        $(document).ready(function() { 

            new DataTable('#example', {
                columnDefs: [
                    {
                        className: 'dtr-control',
                        orderable: true,
                        target: null
                    },
                    { width: '6%', targets: 0 },
                    { width: '10%', targets: 3 },
                    { width: '16%', targets: 4 },
                    { width: '10%', targets: 6 },
                    { width: '11%', targets: 7 },
                    { width: '10%', targets: 8 },
                    { width: '10%', targets: 9 }

                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
            new DataTable('#example2', {
                columnDefs: [
                {
                    className: 'dtr-control',
                    orderable: true,
                    target: null
                },
                { width: '6%', targets: 0 },
                { width: '10%', targets: 3 },
                { width: '16%', targets: 4 },
                { width: '10%', targets: 6 },
                { width: '11%', targets: 7 },
                { width: '10%', targets: 8 },
                { width: '10%', targets: 9 }
        
                ],
                order: [0, 'asc'],
                responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
                }
            });
            new DataTable('#example3', {
                columnDefs: [
                {
                    className: 'dtr-control',
                    orderable: true,
                    target: null
                },
                { width: '6%', targets: 0 },
                { width: '10%', targets: 3 },
                { width: '16%', targets: 4 },
                { width: '10%', targets: 6 },
                { width: '11%', targets: 7 },
                { width: '10%', targets: 8 },
                { width: '10%', targets: 9 }
        
                ],
                order: [0, 'asc'],
                responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
                }
            });

            $('.select2').select2();    //ประเภทรายได้ทั้งหมด in Search
            $('.select2-1').select2();  //หมายเลขบัญชีทั้งหมด in Search
            $('.select2-2').select2({
                dropdownParent: $('#exampleModalCenter5') // Ensure the dropdown is appended to the modal
            });
            $('.select2-3').select2({
                dropdownParent: $('#exampleModalCenter2') // Ensure the dropdown is appended to the modal
            });
        });

        $('#btn-search-date').on('click', function () {
            $('#form-calendar').submit();

        });

        function currencyFormat(num) {
            return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }

        $('#month').on('change', function() {
            var month = Number($(this).val());
            var year = $('#year').val();
            var d = new Date(year, month, 0);

            jQuery('#day').children().remove().end();
            $('#day').append(new Option('ทั้งหมด', '0'));

            for (var i = 1; i <= d.getDate().toString(); i++) {
                var day = i.toString().padStart(2, '0');
                var newOption = new Option(i, day);
                $('#day').append(newOption);

                if (i == 1) {
                    $('#day').val('01').trigger('change');
                }
            }
        });

        function transfer_data(id) {
            $('#dataID').val(id);
            $('#exampleModalCenter2').modal('show');
        }

        function update_time_data(id) {
            $('#timeID').val(id);
            $('#exampleModalCenter1').modal('show');
        }

        function split_data($id, $amount) {
            $('#splitID').val($id);
            $('#text-split-amount').text("(" + currencyFormat($amount) + ")");
            $('#balance_amount').val($amount);
            $('#SplitModalCenter').modal('show');
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
            $('#div-note').html("");

            if (account == "708-226791-3") {
                $('#div-note').append('Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue');
            }

            if (account == "708-226792-1") {
                $('#div-note').append('Credit Card Hotel Revenue');
            }

            if (account == "708-227357-4") {
                $('#div-note').append('Warter Park & Credit Card Water Park Revenue');
            }

            if (account == "") {
                $('#div-note').append('Front Desk, Guest Deposit, All Outlet, Agoda And Elexa EGAT Revenue <br>' +
                    'Credit Card Hotel Revenue <br>' +
                    'Warter Park & Credit Card Warter Park Revenue <br>');
            }
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

        function change_split() {
            jQuery.ajax({
                url: "{!! url('sms-update-split') !!}",
                type: 'POST',
                dataType: "json",
                cache: false,
                data: $('.form-split').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
            });
        }

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

        function change_status($id, $status) {
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

        $('.btn-sms-reload').on('click', function() {
            location.reload();
        });
    </script>

    {{-- กราฟ --}}
    <script>
        let date_now = $('#year').val() + '-' + $('#month').val() + '-' + $('#day').val() + ' ' + $('#time').val();
        let type = $('#status').val();
        let account = $('#into_account').val();

        function get_graph() {
            if (type == '') {
                type = 0;
            }

            if (account == '') {
                account = 0;
            }

            var revenueData = "";

            $.ajax({
                type: "GET",
                url: "{!! url('sms-graph30days/"+date_now+"/"+type+"/"+account+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    // Sample data for watch revenue over 30 days
                    revenueData = response.amount;
                }
            });
            return revenueData;
        }

        Chart.defaults.font.family = "Sarabun";

        const revenueData = get_graph();

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
                return (num / 1e6).toFixed(1) + 'M';
            } else if (num >= 1e3) {
                return (num / 1e3).toFixed(1) + 'K';
            }
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function formatFullNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function drawRotatedText(ctx, bar, displayData, fontSize) {
            ctx.font = 'normal ' + (fontSize - 4) + 'px Sarabun'; // Adjust font size for longer labels
            ctx.save();

            // Check media width and adjust translation offset accordingly
            var translateOffset = window.innerWidth < 768 ? -10 : -20;

            ctx.translate(bar.x, bar.y + translateOffset);
        }

        const valueOnTopPlugin = {
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                const fontSize = Math.min(16, Math.max(10, Math.round(chart.width / 50)));
                ctx.font = 'normal ' + fontSize + 'px Sarabun'; // Set font size dynamically
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((bar, index) => {
                        const data = dataset.data[index];
                        let displayData = formatNumber(data);
                        if (chart.data.labels.length === 7) {
                            displayData = formatNumber(data);
                            ctx.save();
                            ctx.translate(bar.x, bar.y - 10);
                            ctx.fillStyle = '#000';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(displayData, 0, 0);
                            ctx.restore();
                        } else if (chart.data.labels.length === 15) {
                            ctx.font = 'normal ' + (fontSize - 4) +
                                'px Sarabun'; // Adjust font size for longer labels
                            ctx.fillStyle = '#000';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.save();
                            ctx.fillText(displayData, bar.x, bar.y - 10);
                            ctx.restore();
                        } else if (chart.data.labels.length === 30) {
                            ctx.font = 'normal ' + (fontSize - 4) +
                                'px Sarabun'; // Adjust font size for longer labels
                            ctx.save();
                            drawRotatedText(ctx, bar, displayData);
                            ctx.rotate(-Math.PI / 2);
                            ctx.fillStyle = '#000';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(displayData, 0, 0);
                            ctx.restore();
                            return;
                        }
                    });
                });
            }
        };

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const maxRevenueValue = Math.max(...revenueData);
        const buffer = 20000; // Adding a buffer value
        let yAxisMax = maxRevenueValue + buffer;
        const roundingFactor = 20000;
        yAxisMax = Math.ceil(yAxisMax / roundingFactor) * roundingFactor;
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Last 30 Days Revenue',
                    data: revenueData,
                    backgroundColor: '#109699',
                    borderWidth: 0,
                    barPercentage: 0.7
                }]
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
                            }
                        }
                    }
                }
            },
            plugins: [valueOnTopPlugin]
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

    <script>
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

        var combinedChart = document.getElementById('combinedChart').getContext('2d');
        var graph1Visible = true;
        var data1 = {
            labels: ['Yesterday', 'Today', 'Average'],
            datasets: [{
                label: 'Forecast Revenue',
                backgroundColor: '#109699',
                borderWidth: 1,
                barPercentage: 0.33, // Adjust bar width
                data: get_graphForecast(), // Adjusted for more realistic numbers
            }]
        };

        var options1 = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                }]
            },
        };

        var data2 = {
            labels: [
                '21:00-23:59', '00:00-02:59', '03:00-05:59', '06:00-08:59',
                '09:00-11:59', '12:00-14:59', '15:00-17:59', '18:00-20:59'
            ],
            datasets: [{
                label: 'Revenue',
                data: get_graphToday(),
                backgroundColor: '#109699',
                borderWidth: 1,
                barPercentage: 0.8, // Adjust bar width
            }]
        };

        var options2 = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                }]
            }
        };

        // Function to format numbers as 100K, 2M, etc.
        function formatNumber(value) {
            if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                return (value / 1000).toFixed(1) + 'K';
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
                            ctx.fillStyle = '#000'; // Black text color
                            var fontStyle = 'normal';
                            var fontFamily = 'Sarabun';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            var dataString = formatNumber(dataset.data[index]);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 4) -
                                padding);
                        });
                    }
                });
            }
        };

        var currentData = data1;
        var currentOptions = options1;

        var forecastChart = new Chart(combinedChart, {
            type: 'bar',
            data: currentData,
            options: currentOptions,
            plugins: [valueOnTopPlugin2]
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
                type: 'bar',
                data: currentData,
                options: currentOptions,
                plugins: [valueOnTopPlugin2]
            });

            graph1Visible = !graph1Visible;

            var toggleButton = document.getElementById('toggleButton');
            var icon = document.createElement('i');
            icon.className = 'fa-solid fa-repeat';

            if (graph1Visible) {
                toggleButton.textContent = "Switch to Time ";
            } else {
                toggleButton.textContent = "Switch to Forecast ";
            }

            // Append the icon to the toggleButton content
            toggleButton.appendChild(icon);
        }
    </script>
@endsection

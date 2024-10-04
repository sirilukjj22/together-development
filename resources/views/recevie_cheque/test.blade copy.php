@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to test.</small>
                    <div class=""><span class="span1">test</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-lg-4"></div>
                                <div class="PROPOSALfirst col-lg-7" style="background-color: #ffffff;">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Issue Date:</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12" id="reportrange1Issue">
                                                <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;"readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Expiration Date:</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;"readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4"></div>
                                <div class="PROPOSALfirst col-lg-7" style="background-color: #ffffff;">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Check In Date</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12" id="reportrange2">
                                                <input type="text" id="Checkin" class="form-control readonly-input" name="Checkin" style="text-align: left;"readonly>
                                                <input type="hidden" id="inputmonth" name="inputmonth" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Check Out Date</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <input type="text" id="Checkout" class="form-control readonly-input" name="Checkout" style="text-align: left;"readonly>
                                                <input type="hidden" id="checkmonth" name="checkmonth" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" > No Check In Date</label>
                            </div>
                            <div class="row mt-2">

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <span for="">จำนวน</span>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน" readonly>
                                        <span class="input-group-text">Day</span>
                                        <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน" readonly>
                                        <span class="input-group-text">Night</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <span class="star-red" style="display:none" id="Adultred" for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก) </span>
                                    <span  style="display:block" id="Adultblack" for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก) </span>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Adult" id="Adult" placeholder="จำนวนผู้ใหญ่" @required(true)>
                                        <span class="input-group-text">ผู้ใหญ่</span>
                                        <input type="text" class="form-control" name="Children"id="Children" placeholder="จำนวนเด็ก"@required(true)>
                                        <span class="input-group-text">เด็ก</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Input field สำหรับแสดงวันที่ที่เลือก -->

                            <span id="calendartext">0</span>
                            <!-- ปฏิทินที่สร้างขึ้นด้วย JavaScript -->
                            <div id="calendar"></div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableReceiveCheque.js')}}"></script>
    @include('script.script')
    <script>

        $(function() {
            var start = moment();
            var end = moment();
            function cb(start, end) {
                var dayName = start.format('dddd');
                var enddayName = end.format('dddd');
                var daymonthName = start.format('MMMM'); // ชื่อเดือนเต็ม เช่น January, February
                var endmonthName = end.format('MMMM');   // ชื่อเดือนเต็ม เช่น January, February
                var month;
                if (daymonthName === endmonthName) {
                    var monthDiff = end.diff(start, 'months');
                    month = monthDiff;
                }else{
                    var monthDiff = end.diff(start, 'months');
                    month = monthDiff+1;
                }

                $('#checkmonth').val(month);
                if (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(dayName)) {
                    if (dayName === 'Thursday' && enddayName === 'Saturday') {
                        $('#calendartext').text("Weekday-Weekend");
                    }else{
                        $('#calendartext').text("Weekday");
                    }
                } else if (['Friday','Saturday','Sunday'].includes(dayName)) {
                    if (dayName === 'Saturday' && enddayName === 'Monday') {
                        $('#calendartext').text("Weekday-Weekend");
                    }else{
                        $('#calendartext').text("WeekEnd");
                    }
                }
            }
            $('#reportrange2').daterangepicker({
                startDate: start,
                endDate: end,
                autoApply: true,
                autoUpdateInput: false,
            },
            cb);
            $('#reportrange2').on('apply.daterangepicker', function(ev, picker) {
                var currentMonthIndex = picker.startDate.month(); // จะได้หมายเลขเดือน (0-11)
                $('#inputmonth').val(currentMonthIndex + 1); // บันทึกใน input โดยเพิ่ม 1 เพื่อให้เป็น 1-12 แทน

                $('#Checkin').val(picker.startDate.format('DD/MM/Y')); // อัปเดต input เอง
                $('#Checkout').val(picker.endDate.format('DD/MM/Y')); // อัปเดต input เอง
                cb(picker.startDate, picker.endDate); // เรียก callback เพื่ออัปเดตข้อมูลตามเงื่อนไข
                var checkinDate = picker.startDate.toDate();
                var checkoutDate = picker.endDate.toDate();

                if (checkoutDate > checkinDate) {
                    const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                    const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const totalDays = diffDays; // รวม Check-in เป็นวันแรก
                    const nights = diffDays-1;

                    $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                    $('#Night').val(isNaN(nights) ? '0' : nights);

                    $('#checkinpo').text(picker.startDate.format('DD/MM/YYYY'));
                    $('#checkoutpo').text(picker.endDate.format('DD/MM/YYYY'));
                    $('#daypo').text(totalDays + ' วัน');
                    $('#nightpo').text(nights + ' คืน');
                } else if (checkoutDate.getTime() === checkinDate.getTime()) {
                    const totalDays = 1;
                    $('#Day').val(totalDays);
                    $('#Night').val('0');

                    $('#checkinpo').text(picker.startDate.format('DD/MM/YYYY'));
                    $('#checkoutpo').text(picker.endDate.format('DD/MM/YYYY'));
                    $('#daypo').text(totalDays + ' วัน');
                    $('#nightpo').text('0 คืน');
                } else {
                    console.log("วัน Check-out ต้องมากกว่าวัน Check-in");
                    $('#Day').val('0');
                    $('#Night').val('0');
                }
                month();
            });

        });
    </script>
    <script>
        function month() {
            var checkmonthValue = document.getElementById('checkmonth').value; // ค่าจาก input checkmonth
            var inputmonth = document.getElementById('inputmonth').value; // ค่าจาก input inputmonth
            var start = moment(); // เริ่มที่วันที่ปัจจุบัน
            var end; // ประกาศตัวแปร end

            var currentMonthIndex = start.month();
            var monthDiff = inputmonth - currentMonthIndex;
              // ถ้าเดือนปัจจุบันมากกว่าหรือเท่ากับเป้าหมายเดือน
            if (monthDiff < 0) {
                monthDiff += 12; // เพิ่ม 12 เดือนถ้าข้ามปี
            }
            console.log(monthDiff);

            if (monthDiff <= 1) {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(7, 'days'); // สิ้นสุดอีก 7 วัน
            } else if (monthDiff >= 2 && monthDiff < 3 ) {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(15, 'days'); // สิ้นสุดอีก 15 วัน
            } else {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(30, 'days'); // สิ้นสุดอีก 30 วัน
            }

            function cb(start, end) {
                $('#datestart').val(start.format('DD/MM/Y')); // แสดงวันที่เริ่มต้น
                $('#dateex').val(end.format('DD/MM/Y')); // แสดงวันที่สิ้นสุด
            }

            // ตั้งค่า daterangepicker
            $('#reportrange1Issue').daterangepicker({
                start: start,
                end: end,
                ranges: {
                    '3 Days': [moment(), moment().add(3, 'days')],
                    '7 Days': [moment(), moment().add(7, 'days')],
                    '15 Days': [moment(), moment().add(15, 'days')],
                    '30 Days': [moment(), moment().add(30, 'days')],
                },
                autoApply: true, // ใช้เพื่อไม่ต้องกด Apply
            }, cb);

            cb(start, end); // เรียก callback ทันทีหลังจากตั้งค่าเริ่มต้น
        }

    </script>
@endsection

@extends('layouts.masterLayout')
<style>
    td.today {
        background-color: transparent !important; /* ไม่ให้มีสีพื้นหลัง */
    }
</style>
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
                                                <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" value="04/10/2024" style="text-align: left;"readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Expiration Date:</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" value="10/10/2024" style="text-align: left;"readonly>
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
                                                <input type="text" id="Checkin" class="form-control readonly-input" name="Checkin" value="02/10/2024" style="text-align: left;">
                                                <input type="hidden" id="inputmonth" name="inputmonth" value="">
                                                <input type="hidden" id="CheckinNew" name="CheckinNew" value="02/10/2024">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Check Out Date</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <input type="text" id="Checkout" class="form-control readonly-input" name="Checkout" value="08/10/2024" style="text-align: left;"readonly>
                                                <input type="hidden" id="checkmonth" name="checkmonth" value="">
                                                <input type="hidden" id="CheckoutNew" name="CheckoutNew" value="08/10/2024">
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
                            <input type="hidden" id="inputcalendartext" name="inputcalendartext" value="">
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
        $(document).ready(function() {
            const checkinDate = moment(document.getElementById('Checkin').value, 'DD/MM/YYYY');
            const checkoutDate = moment(document.getElementById('Checkout').value, 'DD/MM/YYYY');

            var dayName = checkinDate.format('dddd'); // Format to get the day name
            var enddayName = checkoutDate.format('dddd'); // Format to get the day name


            if (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(dayName)) {
                if (dayName === 'Thursday' && enddayName === 'Saturday') {
                    $('#calendartext').text("Weekday-Weekend");

                    $('#inputcalendartext').val("Weekday-Weekend");
                }else{
                    $('#calendartext').text("Weekday");
                    $('#inputcalendartext').val("Weekday");
                }
            } else if (['Friday','Saturday','Sunday'].includes(dayName)) {
                if (dayName === 'Saturday' && enddayName === 'Monday') {
                    $('#calendartext').text("Weekday-Weekend");
                    $('#inputcalendartext').val("Weekday-Weekend");
                }else{
                    $('#calendartext').text("Weekend");
                    $('#inputcalendartext').val("Weekend");
                }
            }
        });
        $(function() {
            var checkinDate = document.getElementById('inputcalendartext').value;
            const checkoutDate = moment(document.getElementById('Checkout').value, 'DD/MM/YYYY');

            var enddayName = checkoutDate.format('dddd');
            $('#Checkin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น DD/MM/YYYY
                },
                isInvalidDate: function(date) {
                    if (checkinDate == 'Weekday') {
                        if (checkinDate === 'Weekday' && ['Friday','Saturday','Sunday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวันในช่วงนี้
                        }
                    }else if (checkinDate == 'Weekend') {
                        if (checkinDate === 'Weekend' && ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวันในช่วงนี้
                        }
                    }else if (checkinDate == 'Weekday-Weekend' && enddayName == 'Saturday'|| enddayName == 'Monday') {
                        if (checkinDate === 'Weekday-Weekend' && ['Monday','Sunday', 'Tuesday', 'Wednesday', 'Friday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวัน
                        }
                    }
                }
            });
            $('#Checkin').on('apply.daterangepicker', function(ev, picker) {
                var datefirst = picker.startDate.format('DD/MM/YYYY');
                $(this).val(datefirst);
                $('#CheckinNew').val(datefirst);
                var currentMonthIndex = picker.startDate.month(); // จะได้หมายเลขเดือน (0-11)
                $('#inputmonth').val(currentMonthIndex + 1);
                CheckDate();
            });
        });
        $(function() {
            // ตรวจสอบว่า input 'Checkin' มีค่าหรือไม่
            var checkinValue = document.getElementById('Checkin').value;
            // ถ้าไม่มีค่า ใช้วันที่ปัจจุบันแทน

            $('#Checkout').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น DD/MM/YYYY
                },
                isInvalidDate: function(date) {
                    var CheckinNew = document.getElementById('CheckinNew').value;
                    var checkDate = document.getElementById('inputcalendartext').value;
                    var momentCheckinNew = moment(CheckinNew, 'DD/MM/YYYY');
                    var indayName = momentCheckinNew.format('dddd'); // รับค่าเป็นชื่อวัน
                    if (checkDate === 'Weekday') {
                        if (['Friday', 'Saturday', 'Sunday'].includes(date.format('dddd'))) {
                            return false;
                        }
                    } else if (checkDate === 'Weekend') {
                        if (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Saturday'].includes(date.format('dddd'))) {
                            return false;
                        }
                    } else {
                        if (indayName === 'Thursday') {
                            if (['Monday', 'Sunday', 'Tuesday', 'Wednesday', 'Friday', 'Thursday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        } else {
                            if (['Saturday', 'Sunday', 'Tuesday', 'Wednesday', 'Friday', 'Thursday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        }
                    }
                }
            });

            $('#Checkout').on('apply.daterangepicker', function(ev, picker) {
                var dateend = picker.startDate.format('DD/MM/YYYY');
                $(this).val(dateend);
                $('#CheckoutNew').val(dateend);

                var checkDate = document.getElementById('inputcalendartext').value;
                var CheckinNew = document.getElementById('CheckinNew').value;

                // แปลงวันที่ CheckinNew และ dateend เป็น moment object
                var datefirst = moment(CheckinNew, 'DD/MM/YYYY');
                var dateendMoment = moment(dateend, 'DD/MM/YYYY');

                // ตรวจสอบว่า checkinDate คือ 'Weekday-Weekend'
                if (checkDate === 'Weekday-Weekend') {
                    // ตรวจสอบว่า datefirst และ dateend ถูกต้อง
                    if (datefirst.isValid() && dateendMoment.isValid()) {
                        // คำนวณความแตกต่างระหว่าง datefirst และ dateend เป็นจำนวนวัน
                        var diffDays = dateendMoment.diff(datefirst, 'days');

                        // เช็คว่าห่างกันไม่เกิน 3 วันหรือไม่
                        if (diffDays <= 3) {
                            console.log('วันห่างกันไม่เกิน 3 วัน');
                            // คุณสามารถทำสิ่งที่ต้องการได้ที่นี่ เช่น อนุญาตให้เลือกวันที่
                        } else {
                            alert('วันสิ้นสุดไม่สามารถห่างจากวันเริ่มต้นเกิน 3 วันได้');
                            // เพิ่มโค้ดสำหรับการแสดงข้อผิดพลาด หรือการแจ้งเตือน
                        }
                    } else {
                        console.error('วันที่ไม่ถูกต้อง');
                    }
                }
                var daymonthName = datefirst.format('MMMM'); // ชื่อเดือนเต็ม เช่น January, February
                var endmonthName = dateendMoment.format('MMMM');   // ชื่อเดือนเต็ม เช่น January, February
                var monthDiff = dateendMoment.diff(datefirst, 'months');
                var month;

                if (daymonthName === endmonthName) {
                    month = monthDiff; // เดือนเดียวกัน
                } else {
                    month = monthDiff + 1; // ข้ามเดือน
                }

                $('#checkmonth').val(month);
                CheckDate();
            });

        });
        function CheckDate() {
            var CheckinNew = document.getElementById('CheckinNew').value;
            var CheckoutNew = document.getElementById('CheckoutNew').value;
            var momentCheckinNew = moment(CheckinNew, 'DD/MM/YYYY');
            var momentCheckoutNew = moment(CheckoutNew, 'DD/MM/YYYY');
            const checkinDateValue = momentCheckinNew.format('YYYY-MM-DD');
            const checkoutDateValue = momentCheckoutNew.format('YYYY-MM-DD');
            const checkinDate = new Date(checkinDateValue);
            const checkoutDate = new Date(checkoutDateValue);
            if (checkoutDate > checkinDate) {
                const timeDiff = checkoutDate - checkinDate;
                const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const totalDays = diffDays + 1; // รวม Check-in เป็นวันแรก
                const nights = diffDays;

                $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                $('#Night').val(isNaN(nights) ? '0' : nights);

                $('#checkinpo').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpo').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text(nights + ' คืน');
            } else if (checkoutDate.getTime() === checkinDate.getTime()) {
                const totalDays = 1;
                $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                $('#Night').val('0');

                $('#checkinpo').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpo').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text('0 คืน');
            } else {
                // alert('วัน Check-out ต้องมากกว่าวัน Check-in');
                $('#Day').val('0');
                $('#Night').val('0');
            }

            month();
        }

        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('Checkin').setAttribute('min', today);
            document.getElementById('Checkout').setAttribute('min', today);
        }

        // เรียกใช้เมื่อโหลดหน้า
        setMinDate();


        document.addEventListener('DOMContentLoaded', setMinDate);
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

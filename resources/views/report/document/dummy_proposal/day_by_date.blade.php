@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
<style>
    .wrap-con {
      display: flex;
      height: 100%;
    }

    @media (max-width: 900px) {
      .wrap-con {
        display: flex;
        flex-direction: column-reverse;
        justify-content: start;
      }
    }

    .wrap-con .content {
      background-color: rgb(231, 232, 231);
      flex-grow: 1;
      display: flex;
      justify-content: center;

      min-height: 100vh;

    }

    .wrap-con .side {
      background-color: white;
      box-shadow: rgba(60, 64, 67, 0.3) -5px 0px 5px -5px;
      flex-basis: 300px;
      padding-left: 10px;
      padding-top: 10px;
    }
  </style>
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Document Proposal Report</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="bt-tg-normal export-pdf" id="download-pdf"> Print <img src="/image/front/pdf.png" width="30px" alt=""></button>
                    <button type="button" class="bt-tg-normal export-excel" id="export-excel"> Export <img src="/image/front/xls.png" width="30px" alt=""></button>
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
        <div class="wrap-con">
            <div class="content">e</div>
            <div class="side">ddd</div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(document).ready(function() {
            var filterBy = $('#filter-by').val();
            var startDate = document.getElementById("startDate");
            var MonthStart = document.getElementById("month");

            startYear.disabled = true;
            if (filterBy == "all") {
                startDate.type = "text";
                startDate.disabled = false;
                MonthStart.disabled = true;
                $('#box-all').prop('hidden', false);
                // $('#box-start-date').prop('hidden', false);
                $('#filter-by').val("all");

            }
            if (filterBy == "date") {
                startDate.type = "text";
                startDate.disabled = false;
                MonthStart.disabled = true;
                $('#box-month').prop('hidden', true);
                $('#box-start-date').prop('hidden', false);
                $('#filter-by').val("date");

            }

            if (filterBy == "check") {
                MonthStart.type = "month";
                MonthStart.disabled = false;
                startDate.disabled = true;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', false);
                $('#filter-by').val("month");
            }

            if (filterBy == "user") {
                startYear.disabled = false;
                startDate.disabled = true;
                MonthStart.disabled = true;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', true);
                $('#box-start-year').prop('hidden', false);
                $('#filter-by').val("year");
            }

            $('input[name="startDate"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'  // กำหนดรูปแบบวันที่เป็น 'ปี-เดือน-วัน'
                }
            });
            $('#startDate').on('apply.daterangepicker', function(ev, picker) {
                document.getElementById('statusinput').disabled = true;
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".btn-group button");
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");
            var statusinput = document.getElementById("statusinput");

            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // เพิ่ม 0 ถ้าเป็นเลขหลักเดียว
            const day = String(date.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            const formattedMonth = `${year}-${month}`;
            const formattedYear = 2025;


            // เมื่อเลือกค่าใน month (กรณีเดือน)
            MonthStart.addEventListener('input', function () {
                statusinput.disabled = true;
            });

            // เมื่อเลือกค่าใน startYear (กรณีปี)
            startYear.addEventListener('change', function () {
                statusinput.disabled = true;
            });
            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    // Remove 'selected' class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove("selected"));
                    // Add 'selected' class to the clicked button
                    this.classList.add("selected");

                    $('#box-start-year').prop('hidden', true);
                    $('#box-start-date').prop('hidden', false);
                    $('#box-month').prop('hidden', false);

                    startDate.disabled = true;
                    MonthStart.disabled = true;
                    startYear.disabled = true;

                    // Adjust the input types based on selected filter
                    if (this.id === "filter-date") {
                        startDate.type = "text";
                        startDate.disabled = false;
                        MonthStart.disabled = true;
                        $('#box-month').prop('hidden', true);
                        $('#filter-by').val("date");

                    } else if (this.id === "filter-month") {
                        MonthStart.type = "month";
                        MonthStart.disabled = false;
                        startDate.disabled = true;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', false);
                        $('#filter-by').val("month");

                    } else if (this.id === "filter-year") {
                        startYear.disabled = false;
                        startDate.disabled = true;
                        MonthStart.disabled = true;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', true);
                        $('#box-start-year').prop('hidden', false);
                        $('#filter-by').val("year");

                    }
                });
            });
        });
        $(document).ready(function() {
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");
            $('#statusinput').on('change', function() {
                // เช็คว่า radio ถูกเลือกหรือไม่

                    // ทำสิ่งที่ต้องการเมื่อ radio ถูกเลือก
                    startDate.disabled = true;
                    MonthStart.disabled = true;
                    startYear.disabled = true;

            });

        });
        // Export
        $(document).on('click', '.export-pdf', function () {
            $('#method-name').val("pdf");
            document.getElementById("form-search").setAttribute("target", "_blank");
            $('#form-search').submit();
        });

        $(document).on('click', '.export-excel', function () {
            $('#method-name').val("excel");
            document.getElementById("form-search").setAttribute("target", "_blank");
            $('#form-search').submit();
        });

        $(document).on('click', '.btn-search', function () {
            document.getElementById("form-search").removeAttribute('target');
            $('#method-name').val("search");
            $('#form-search').submit();
        });
    </script>


@endsection

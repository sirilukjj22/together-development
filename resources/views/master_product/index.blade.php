@extends('layouts.test')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="
https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js
"></script>
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



  .statusbtn1,.statusbtn2{
        border-style: solid;
        border-radius: 8px;
        border-width: 1px;
        border-color: #9a9a9a;
        margin-left: 10px;
        width: 45%;
        height: 40px;
        border-radius: 8px;
        float: right;
        color: #000000;
        margin: 0;
        margin-left: 10px;
        margin-bottom: 10px;

      }

    .dropdown-menu {
        width: 10%;
    }
    .create{
        background-color: #109699 !important;
        color: white !important;
        text-align: center;
        border-radius: 8px;
        border-color: #9a9a9a;
        border-style: solid;
        border-width: 1px;
        width: 40%;
        height: 50px;
        padding-top: 6px;
        float: right;
    }
    .logo img {
        height: auto;
    }

    img {
        display: flex;
        margin: auto;
        width: 70px;
        height: 70px;
        object-fit: cover;
    }

    .row {
        margin-bottom: 10px;
    }

    #myChart {
        margin-top: 50px;
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
    .percent{
        text-align: left;
        width:auto;
        display: block;
        margin-left: 90px;
    }
    h6{
        width: 50%;
        float: left;
    }
    @media (max-width: 768px) {
    h1{
       margin-top:32px;
    }
    .create{
        width: 100%!important;
        font-size: 14px;
        padding: 5px;
    }
    .statusbtn1,.statusbtn2{
        border-style: solid;
        border-radius: 8px;
        border-width: 1px;
        border-color: #9a9a9a;
        margin-left: 10px;
        width: 95%;
        height: 40px;
        border-radius: 8px;
        float: right;
        color: #000000;
        margin: 0;
        margin-left: 10px;
        margin-bottom: 10px;
    }
    .percent{
        text-align: left;
        width:auto;
        display: block;
        margin-left: 10px;
    }

    .title{
        width: 60%;
    }
    .totle{
        width: 40%;
    }
    .le{

        width: 100%;
    }
}

</style>
    <div  class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">

        <h1>Product Item</h1>
        <div class="col-lg-12 col-md-12 col-sm-12 " style="float: right">
            <div  class="col-lg-4 col-md-6 col-sm-12" style="float: right">
                @if (Auth::check() && in_array(Auth::user()->permission, ['3', '2', '1']))
                    <button type="button" class="create" onclick="window.location.href='{{ route('Mproduct.create') }}'">เพิ่มผู้ใช้งาน</button>
                @endif
            </div>
        </div>

        <div class="row mt-5 g-2 le">
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div style="background-color: rgb(255, 255, 255);">
                <div class="donut-graph">
                  <canvas id="myChart"></canvas>
                  <div class="percent" >
                    <h6 class="title"><i style="color: deepskyblue; margin-right: 10px;"
                        class="fa-solid fa-square"></i>Room</h6>
                    <h6 class="totle">: {{ number_format($CountRoom, 2) }} %</h6>
                    <h6 class="title"><i style="color: hotpink; margin-right: 10px;"
                        class="fa-solid fa-square"></i>Banquet</h6>
                    <h6 class="totle">: {{ number_format($CountBanquet, 2) }} %</h6>
                    <h6 class="title"><i style="color: orange; margin-right: 10px;"
                        class="fa-solid fa-square"></i>Meals</h6>
                    <h6 class="totle">: {{ number_format($CountMeals, 2) }} %</h6>
                    <h6 class="title"><i style="color: #ffda23; margin-right: 10px;"
                        class="fa-solid fa-square"></i>Entertainment</h6>
                    <h6 class="totle">: {{ number_format($CountEntertainment, 2) }} %</h6>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-5">
            <!-- CASH -->
                <div class="title-box">
                    <h2>Product</h2>
                </div>

                <div class="d-flex align-content-stratch flex-wrap cash"
                    style=" height:375px; border-radius: 8px !important;">
                    <a href="" class="list-box">
                    <img src="{{ asset('assets2/images/bedroom_452811.png') }}" alt="">
                    <h2>Room</h2>
                    <h3>{{$Room_Revenue}}</h3>
                    </a>


                    <a href="" class="list-box">
                    <img src="images/guest.png" alt="">
                    <h2>Banquet</h2>
                    <h3>{{$Banquet}}</h3>
                    </a>


                    <a href="" class="list-box">
                    <img src="images/F&B.png" alt="">
                    <h2>Meals</h2>
                    <h3>{{$Meals}}</h3>
                    </a>


                    <a href="" class="list-box">
                    <img src="images/water-park.png" alt="">
                    <h2>Entertainment</h2>
                    <h3>{{$Entertainment}}</h3>
                    </a>

                </div>
            </div>


    </div>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function (chart) {
            if (chart.config.type === 'doughnut') {
                const width = chart.width,
                    height = chart.height,
                    ctx = chart.ctx;

                ctx.restore();
                const fontSize = (height / 175).toFixed(2); // Font size
                ctx.font = fontSize + "em 'Sarabun', sans-serif";
                ctx.textBaseline = "middle";

                // Check if data is empty
                const dataValues = chart.data.datasets[0].data;
                const isEmptyData = dataValues.every(value => value === 0);
                const text = isEmptyData ? "0" : {{$productcount}};
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2 + 30;

                // Draw circle
                const circleRadius = fontSize * 60; // Adjust the multiplier as needed
                ctx.beginPath();
                ctx.arc(width / 2, textY - 5, circleRadius, 0, 2 * Math.PI); // Adjust the vertical offset as needed
                ctx.strokeStyle = 'black'; // Circle color
                ctx.lineWidth = 2; // Circle line width
                ctx.stroke();

                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }
    };
    Chart.register(centerTextPlugin);
    new Chart(ctx, {
        type: 'doughnut',
        data: {
        labels: ['Room', 'Banquet', 'Meals','Entertainment'],
        datasets: [{
        data: [{{$Room_Revenue}}, {{$Banquet}},{{$Meals}},{{$Entertainment}}], // Example of empty data
        }]
        },
        options: {
        cutout: '70%',
        // other options if any
        },
        plugins: [centerTextPlugin]
    });
</script>
<script>
         $(document).ready(function() {
            new DataTable('#example', {
                columnDefs: [
                    {
                        className: 'dtr-control',
                        orderable: true,
                        target: null
                    },
                    { width: '10%', targets: 0 },
                    { width: '10%', targets: 1 },
                    { width: '25%', targets: 2 },
                    { width: '10%', targets: 3 },
                    { width: '13%', targets: 4 },
                    { width: '13%', targets: 5 },

                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });



    // หากมีการส่งค่า alert มาจากหน้าอื่น
    var alertMessage = "{{ session('alert_') }}";
    var alerterror = "{{ session('error_') }}";
    if(alertMessage) {
        // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
        Swal.fire({
            icon: 'success',
            title: alertMessage,
            showConfirmButton: false,
            timer: 1500
        });
    }if(alerterror) {
        Swal.fire({
            icon: 'error',
            title: alerterror,
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('.status-toggle').click(function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var token = "{{ csrf_token() }}"; // รับ CSRF token จาก Laravel
        // ทำ AJAX request
        $.ajax({
            type: 'GET',
            url: "{{ url('/Mproduct/change-Status/') }}" + '/' + id + '/' + status,
            success: function(response) {
                // ปรับเปลี่ยนสถานะบนหน้าเว็บ
                console.log(response.success);
                if (status == 1) {
                    // เปลี่ยนสถานะจากเปิดเป็นปิด
                    $(this).data('status', 0);
                    $(this).removeClass('btn-success').addClass('btn-danger').html('Deactivate');
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                     location.reload();
                } else  {
                    // เปลี่ยนสถานะจากปิดเป็นเปิด
                    $(this).data('status', 1);
                    $(this).removeClass('btn-danger').addClass('btn-success').html('Activate');
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                     location.reload();
                }
            }
        });
    });
});
</script>
@endsection

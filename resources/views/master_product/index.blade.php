@extends('layouts.masterLayout')
<link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->

    <!-- project css file  -->
    <link rel="stylesheet" href="../assets/css/al.style.min.css">
    <!-- project layout css file -->
    <link rel="stylesheet" href="../assets/css/layout.a.min.css">
<style>

</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Product Item.</small>
                <h1 class="h4 mt-1">Product Item (รายการสินค้า)</h1>
            </div>
            <div class="col-auto">
                @if (Auth::check() && in_array(Auth::user()->permission, ['3', '2', '1']))
                <button type="button" class="btn btn-primary lift btn_modal" onclick="window.location.href='{{ route('Mproduct.create') }}'">
                    <i class="fa fa-plus"></i> เพิ่มรายการสินค้า</button>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container mt-3">
    <div class="row clearfix" >
        <div class="col-sm-4 col-lg-4">
            <div class=" card p-4 mb-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                            <h6 class="m-0"><b>Total Product Item </b><br><small  class="text-muted">(รายการสินค้าทั้งหมด)</small></h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="d-flex flex-row align-items-center text-center text-sm-start">
                                <div class="p-2 ms-4">
                                    <h6 class="mb-0 fw-bold">{{$Room_Revenue}}</h6>
                                    <small class="text-muted">Room</small>
                                </div>
                                <div class="p-2 ms-2">
                                    <h6 class="mb-0 fw-bold">{{$Banquet}}</h6>
                                    <small class="text-muted">Banquet</small>
                                </div>
                                <div class="p-2 ms-2">
                                    <h6 class="mb-0 fw-bold">{{$Meals}}</h6>
                                    <small class="text-muted">Meals</small>
                                </div>
                                <div class="p-2 ms-2">
                                    <h6 class="mb-0 fw-bold">{{$Entertainment}}</h6>
                                    <small class="text-muted">Entertainment</small>
                                </div>
                            </div>
                            <div class="mt-3" id="apex-wc-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-lg-8" >
            <div class=" card p-4 mb-4">
                <div class="card mb-3 overflow-hidden" >
                    <div class="p-2  d-flex  text-light align-items-center" style="background-color: #2D7F7B;">
                        <i class="fa fa-cubes fs-1"></i>
                        <div><span class="fs-2">Total Product Item </span></div>
                    </div>
                    <div class="card-body p-2">
                        <h3>Product quantity : {{$productcount}}</h3>
                    </div>
                </div>
                <div class="row col-13 mt-2" >
                    <div class="col-lg-6 col-sm-6">
                        <div class="card border-0 mb-3">
                            <div class="card-body d-flex align-items-center p-4" style="background-color: #f86f50; border-radius: 5px;">
                                <div class="avatar lg rounded no-thumbnail"style="background-color: #fff">
                                    <img src="{{ asset('assets2/images/bed.png') }}" style="width: 70%" class="logo"/>
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h5 class="mb-0  text-light"><b>Total Room</b></h5>
                                    <h5 class="mb-0 text-light">{{ number_format($Room_Revenue) }} Product</h5>
                                    <h5 class="mb-0  text-light">{{ number_format($CountRoom, 2) }} %</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 ">
                        <div class="card border-0 mb-3">
                            <div class="card-body d-flex align-items-center p-4"  style="background-color: #62e079; border-radius: 5px;">
                                <div class="avatar lg rounded no-thumbnail"style="background-color: #fff">
                                    <img src="{{ asset('assets2/images/seminar.png') }}" style="width: 70%" class="logo"/>
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h5 class="mb-0 text-light"><b>Total Banquet</b></h5>
                                    <h5 class="mb-0 text-light">{{ number_format($Banquet) }} Product</h5>
                                    <h5 class="mb-0 text-light">{{ number_format($CountBanquet, 2) }} %</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 mt-2">
                        <div class="card border-0 mb-3">
                            <div class="card-body d-flex align-items-center p-4" style="background-color: #3357FF; border-radius: 5px;">
                                <div class="avatar lg rounded no-thumbnail"style="background-color: #fff">
                                    <img src="{{ asset('assets2/images/serving-dish.png') }}" style="width: 70%" class="logo"/>
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h5 class="mb-0  text-light"><b>Total Meals</b></h5>
                                    <h5 class="mb-0 text-light">{{ number_format($Meals) }} Product</h5>
                                    <h5 class="mb-0  text-light">{{ number_format($CountMeals, 2) }} %</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 mt-2">
                        <div class="card border-0 mb-3">
                            <div class="card-body d-flex align-items-center p-4"  style="background-color: #F333FF; border-radius: 5px;">
                                <div class="avatar lg rounded no-thumbnail"style="background-color: #fff">
                                    <img src="{{ asset('assets2/images/multimedia.png') }}" style="width: 70%" class="logo"/>
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h5 class="mb-0 text-light"><b>Total Entertainment</b></h5>
                                    <h5 class="mb-0 text-light">{{ number_format($Entertainment) }} Product</h5>
                                    <h5 class="mb-0 text-light">{{ number_format($CountEntertainment, 2) }} %</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <h4><b>Room</b></h4>
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableProductItem table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>เรียงลำดับ</th>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อภาษาไทย</th>
                            <th>รายละเอียด</th>
                            <th>ขนาดห้อง</th>
                            <th>ราคาปกติ</th>
                            <th>ราคารายสัปดาห์</th>
                            <th>ราคาสัปดาห์ระยะยาว</th>
                            <th>ราคาสุดสัปดาห์</th>
                            <th>หน่วย</th>
                            <th>Create by</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th class="text-center">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($productroom))
                            @foreach ($productroom as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->Product_ID }}</td>
                                <td>{{ $item->name_th }}</td>
                                <td>{{ $item->detail_th }}</td>
                                <td>{{ $item->room_size }}</td>
                                <td>{{ $item->normal_price }}</td>
                                <td>{{ $item->weekend_price }}</td>
                                <td>{{ $item->long_weekend_price }}</td>
                                <td>{{ $item->end_weekend_price	 }}</td>
                                <td>{{ @$item->productunit->name_th}}</td>
                                <td>{{ @$item->user_create_id->name}}</td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                        <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="#">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <h4><b>Banquet</b></h4>
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableProductItem table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>เรียงลำดับ</th>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อภาษาไทย</th>
                            <th>รายละเอียด</th>
                            <th>ราคาปกติ</th>
                            <th>หน่วย</th>
                            <th>Create by</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th class="text-center">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($productBanquet))
                            @foreach ($productBanquet as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->Product_ID }}</td>
                                <td>{{ $item->name_th }}</td>
                                <td>{{ $item->detail_th }}</td>
                                <td>{{ $item->normal_price }}</td>
                                <td>{{ @$item->productunit->name_th}}</td>
                                <td>{{ @$item->user_create_id->name}}</td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                        <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="#">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
    </div>
</div>
<!-- Jquery Core Js -->
<script src="../assets/bundles/libscripts.bundle.js"></script>

<script src="../assets/bundles/apexcharts.bundle.js"></script>

<!-- Jquery Page Js -->
<script src="../assets/js/template.js"></script>
<script>
    var apexwc12 = {
        chart: {
            height: 310,
            type: 'donut',
        },
        labels: ['Room', 'Banquet', 'Meals', 'Entertainment'],
        dataLabels: {
            enabled: false,
        },
        legend: {
            position: 'bottom', // left, right, top, bottom
            horizontalAlign: 'center',  // left, right, top, bottom
        },
        colors: ['#FF5733', '#62e079', '#3357FF', '#F333FF'],
        series: [{{ number_format($CountRoom, 2) }},{{ number_format($CountBanquet, 2) }},{{ number_format($CountMeals, 2) }},{{ number_format($CountEntertainment, 2) }}],
        responsive: [{
            breakpoint: 420,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    }
    new ApexCharts(document.querySelector("#apex-wc-12"), apexwc12).render();
</script>
@endsection

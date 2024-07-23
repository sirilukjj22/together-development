@extends('layouts.masterLayout')
<style>

@media (max-width: 768px) {
    .EntertainmentImage {
        width: 1%;
    }
}
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
                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Mproduct.create') }}'">
                    <i class="fa fa-plus"></i> เพิ่มรายการสินค้า</button>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container mt-3">
    <div class="row align-items-center mb-2">
        @if (session("success"))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
            <hr>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        @endif
        @if (session("error"))
        <div class="alert alert-error" role="alert">
            <h4 class="alert-heading">เกิดข้อพิดพลาด!</h4>
            <hr>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
        @endif
    </div> <!-- Row end  -->
    <div class="row clearfix" >
        <div class="col-sm-12 col-12">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-2 col-6">
                    <button type="button" class="btn  mt-3 lift btn_modal" id="alldataproduct" onclick="alldataproduct()" style="background-color:#2D7F7B;color:#fff;">
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('assets2/images/supplies.png') }}" style="width: 100%" class="logo"/>
                            </div>
                            <div class="col-8"  >
                                <div class="row">
                                    <span style="font-size: 16px;float:left">ทั้งหมด</span>
                                    <span style="font-size: 16px;float:left">{{$productcount}} Product</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
                <div class="col-lg-2 col-6">
                    <button type="button" class="btn  mt-3 lift btn_modal" id="Room" onclick="alldataRoom()" style="background-color:#2D7F7B;color:#fff;">
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('assets2/images/bed.png') }}" style="width: 100%" class="logo"/>
                            </div>
                            <div class="col-8"  >
                                <div class="row">
                                    <span style="font-size: 16px;float:left">Room</span>
                                    <span style="font-size: 16px;float:left">{{ number_format($Room_Revenue) }} Product</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
                <div class="col-lg-2 col-6">
                    <button type="button" class="btn  mt-3 lift btn_modal" id="Banquet" onclick="alldataBanquet()" style="background-color:#2D7F7B;color:#fff;">
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('assets2/images/seminar.png') }}" style="width: 100%" class="logo"/>
                            </div>
                            <div class="col-8"  >
                                <div class="row">
                                    <span style="font-size: 16px;float:left">Banquet</span>
                                    <span style="font-size: 16px;float:left">{{ number_format($Banquet) }} Product</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
                <div class="col-lg-2 col-6">
                    <button type="button" class="btn  mt-3 lift btn_modal" id="Meals" onclick="alldataMeals()" style="background-color:#2D7F7B;color:#fff;">
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('assets2/images/serving-dish.png') }}" style="width: 100%" class="logo"/>
                            </div>
                            <div class="col-8"  >
                                <div class="row">
                                    <span style="font-size: 16px;float:left">Meals</span>
                                    <span style="font-size: 16px;float:left">{{ number_format($Meals) }} Product</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
                <div class="col-lg-2 col-6 ">
                    <button type="button" class="btn  mt-3 lift btn_modal"id="Entertainment"  onclick="alldataEntertainment()" style="background-color:#2D7F7B;color:#fff;">
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('assets2/images/multimedia.png') }}" style="width: 100%" class="logo"/>
                            </div>
                            <div class="col-8"  >
                                <div class="row">
                                    <span style="font-size: 16px;float:left">Entertainment</span>
                                    <span style="font-size: 16px;float:left">{{ number_format($Entertainment) }} Product</span>
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="card p-4 mb-4 mt-5" style="display: block;"id="alltable" >
                <h4><b>All Product Item</b></h4>
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
                        @if(!empty($product))
                            @foreach ($product as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->Product_ID }}</td>
                                <td>{{ $item->name_th }}</td>
                                <td>{{ $item->detail_th }}</td>
                                <td>
                                    @if ($item->room_size === null)
                                        -
                                    @else
                                        {{ $item->room_size }}
                                    @endif
                                </td>
                                <td>{{ $item->normal_price }}</td>
                                <td>
                                    @if ($item->weekend_price === null)
                                        -
                                    @else
                                        {{ $item->weekend_price }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->long_weekend_price === null)
                                        -
                                    @else
                                        {{ $item->long_weekend_price }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->end_weekend_price === null)
                                        -
                                    @else
                                        {{ $item->end_weekend_price }}
                                    @endif
                                </td>
                                <td>{{ @$item->productunit->name_th}}</td>
                                <td>{{ @$item->user_create_id->name}}</td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3" >
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/delete/'.$item->id) }}">ลบรายการ</a></li>
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
            <div class="card p-4 mb-4 mt-5" style="display: none;" id="Roomtable">
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
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3" >
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/delete/'.$item->id) }}">ลบรายการ</a></li>
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
            <div class="card p-4 mb-4 mt-5" style="display: none; " id="Banquettable">
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
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/delete/'.$item->id) }}">ลบรายการ</a></li>
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
            <div class="card p-4 mb-4 mt-5" style="display: none; " id="Mealstable">
                <h4><b>Meals</b></h4>
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
                        @if(!empty($productMeals))
                            @foreach ($productMeals as $key => $item)
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
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/delete/'.$item->id) }}">ลบรายการ</a></li>
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
            <div class="card p-4 mb-4 mt-5" style="display: none; " id="Entertainmenttable">
                <h4><b>Entertainment</b></h4>
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
                        @if(!empty($productEntertainment))
                            @foreach ($productEntertainment as $key => $item)
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
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/delete/'.$item->id) }}">ลบรายการ</a></li>
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


<script src="../assets/bundles/apexcharts.bundle.js"></script>

<!-- Jquery Page Js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function btnstatus(id) {
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Mproduct/change-Status/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                location.reload();
            },
        });
    }
    function alldataproduct() {
        var alltable = document.getElementById('alltable');
        var Roomtable = document.getElementById('Roomtable');
        var Banquettable = document.getElementById('Banquettable');
        var Mealstable = document.getElementById('Mealstable');
        var Entertainmenttable = document.getElementById('Entertainmenttable');
        alltable.style.display = 'block';
        Roomtable.style.display = 'none';
        Banquettable.style.display = 'none';
        Mealstable.style.display = 'none';
        Entertainmenttable.style.display = 'none';
    }
    function alldataRoom() {
        var alltable = document.getElementById('alltable');
        var Roomtable = document.getElementById('Roomtable');
        var Banquettable = document.getElementById('Banquettable');
        var Mealstable = document.getElementById('Mealstable');
        var Entertainmenttable = document.getElementById('Entertainmenttable');
        alltable.style.display = 'none';
        Roomtable.style.display = 'block';
        Banquettable.style.display = 'none';
        Mealstable.style.display = 'none';
        Entertainmenttable.style.display = 'none';
    }
    function alldataBanquet() {
        console.log('Banquet');
        var alltable = document.getElementById('alltable');
        var Roomtable = document.getElementById('Roomtable');
        var Banquettable = document.getElementById('Banquettable');
        var Mealstable = document.getElementById('Mealstable');
        var Entertainmenttable = document.getElementById('Entertainmenttable');
        alltable.style.display = 'none';
        Roomtable.style.display = 'none';
        Banquettable.style.display = 'block';
        Mealstable.style.display = 'none';
        Entertainmenttable.style.display = 'none';
    }
    function alldataMeals() {
        console.log('Meals');
        var alltable = document.getElementById('alltable');
        var Roomtable = document.getElementById('Roomtable');
        var Banquettable = document.getElementById('Banquettable');
        var Mealstable = document.getElementById('Mealstable');
        var Entertainmenttable = document.getElementById('Entertainmenttable');
        alltable.style.display = 'none';
        Roomtable.style.display = 'none';
        Banquettable.style.display = 'none';
        Mealstable.style.display = 'block';
        Entertainmenttable.style.display = 'none';
    }
    function alldataEntertainment() {
        console.log('Entertainment');
        var alltable = document.getElementById('alltable');
        var Roomtable = document.getElementById('Roomtable');
        var Banquettable = document.getElementById('Banquettable');
        var Mealstable = document.getElementById('Mealstable');
        var Entertainmenttable = document.getElementById('Entertainmenttable');
        alltable.style.display = 'none';
        Roomtable.style.display = 'none';
        Banquettable.style.display = 'none';
        Mealstable.style.display = 'none';
        Entertainmenttable.style.display = 'block';
    }
</script>
@endsection

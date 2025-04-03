@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Product Item</div>
                </div>
                <div class="col-auto">
                    @if (Auth::check() && in_array(Auth::user()->permission, ['3', '2', '1']))
                        <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Mproduct.create') }}'">
                        <i class="fa fa-plus"></i> เพิ่มรายการสินค้า</button>
                    @endif
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Mproduct.Log') }}'">
                        LOG
                    </button>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Save successful.</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Save failed!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
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

            <style>
                .d-grid2 {
                    display: grid;
                    grid-template-columns: 60px 1fr;
                    /* border:red 1px solid; */
                }
            </style>
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <button type="button" class="btn  mt-3 lift btn_modal" id="alldataproduct" onclick="alldataproduct()" style="background-color:#2D7F7B;color:#fff;width:100%">
                                <div class="d-grid2" style="width: 100%" >
                                    <div class="" >
                                        <img src="{{ asset('assets/images/supplies.png') }}" style="width: 100%" class="logo"/>
                                    </div>
                                    <div>
                                        <div class="row">
                                            <span style="font-size: 16px;float:left">ทั้งหมด</span>
                                            <span style="font-size: 16px;float:left">{{$productcount}} Product</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <button type="button" class="btn  mt-3 lift btn_modal" id="Room" onclick="alldataRoom()" style="background-color:#2D7F7B;color:#fff;width:100%">
                                <div class="d-grid2" style="width: 100%">
                                    <div>
                                        <img src="{{ asset('assets/images/bed.png') }}" style="width: 100%" class="logo"/>
                                    </div>
                                    <div >
                                        <div class="row">
                                            <span style="font-size: 16px;float:left">Room</span>
                                            <span style="font-size: 16px;float:left">{{ number_format($Room_Revenue) }} Product</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button type="button" class="btn  mt-3 lift btn_modal" id="Banquet" onclick="alldataBanquet()" style="background-color:#2D7F7B;color:#fff;width:100%">
                                <div class="d-grid2" style="width: 100%">
                                    <div>
                                        <img src="{{ asset('assets/images/seminar.png') }}" style="width: 100%" class="logo"/>
                                    </div>
                                    <div>
                                        <div class="row">
                                            <span style="font-size: 16px;float:left">Banquet</span>
                                            <span style="font-size: 16px;float:left">{{ number_format($Banquet) }} Product</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button type="button" class="btn  mt-3 lift btn_modal" id="Meals" onclick="alldataMeals()" style="background-color:#2D7F7B;color:#fff;width:100%">
                                <div class="d-grid2" style="width: 100%">
                                    <div>
                                        <img src="{{ asset('assets/images/serving-dish.png') }}" style="width: 100%" class="logo"/>
                                    </div>
                                    <div>
                                        <div class="row">
                                            <span style="font-size: 16px;float:left">Meals</span>
                                            <span style="font-size: 16px;float:left">{{ number_format($Meals) }} Product</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <button type="button" class="btn  mt-3 lift btn_modal"id="Entertainment"  onclick="alldataEntertainment()" style="background-color:#2D7F7B;color:#fff;width:100%">
                                <div class="d-grid2" style="width: 100%">
                                    <div>
                                        <img src="{{ asset('assets/images/multimedia.png') }}" style="width: 100%" class="logo"/>
                                    </div>
                                    <div>
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
                        <div style="min-height: 70vh;">
                            <table id="alltable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th class="text-center"data-priority="1">No</th>
                                        <th class="text-center">Product ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Detail</th>
                                        <th class="text-center">Room size</th>
                                        <th class="text-center">Normal price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Document Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($product))
                                        @foreach ($product as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->Product_ID}}
                                            </td>
                                            <td style="text-align: left;">{{ $item->name_th }}</td>
                                            <td style="text-align: left;">{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;" class="target-class">
                                                {{$item->normal_price }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productquantity->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productunit->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3" >
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div> <!-- .card end -->
                    <div class="card p-4 mb-4 mt-5" style="display: none;" id="Roomtable" >
                        <h4><b>Room</b></h4>
                        <div style="min-height: 70vh;">
                            <table id="Roomtable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th class="text-center"data-priority="1">No</th>
                                        <th class="text-center">Product ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Detail</th>
                                        <th class="text-center">Room size</th>
                                        <th class="text-center">Normal price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Document Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productroom))
                                        @foreach ($productroom as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->Product_ID}}
                                            </td>
                                            <td style="text-align: left;">{{ $item->name_th }}</td>
                                            <td style="text-align: left;">{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;" class="target-class">
                                                {{ $item->normal_price }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productquantity->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productunit->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3" >
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Banquettable">
                        <h4><b>Banquet</b></h4>
                        <div style="min-height: 70vh;">
                            <table id="Banquettable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th class="text-center"data-priority="1">No</th>
                                        <th class="text-center">Product ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Detail</th>
                                        <th class="text-center">Room size</th>
                                        <th class="text-center">Normal price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Document Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productBanquet))
                                        @foreach ($productBanquet as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->Product_ID}}
                                            </td>
                                            <td style="text-align: left;">{{ $item->name_th }}</td>
                                            <td style="text-align: left;">{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;" class="target-class">
                                                {{ $item->normal_price }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productquantity->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productunit->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3" >
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Mealstable">
                        <h4><b>Meals</b></h4>
                        <div style="min-height: 70vh;">
                            <table id="Mealstable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th class="text-center"data-priority="1">No</th>
                                        <th class="text-center">Product ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Detail</th>
                                        <th class="text-center">Room size</th>
                                        <th class="text-center">Normal price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Document Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productMeals))
                                        @foreach ($productMeals as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->Product_ID}}
                                            </td>
                                            <td style="text-align: left;">{{ $item->name_th }}</td>
                                            <td style="text-align: left;">{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;" class="target-class">
                                                {{ $item->normal_price }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productquantity->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productunit->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3" >
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Entertainmenttable">
                        <h4><b>Entertainment</b></h4>
                        <div style="min-height: 70vh;">
                            <table id="Entertainmenttable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th class="text-center"data-priority="1">No</th>
                                        <th class="text-center">Product ID</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="1">Detail</th>
                                        <th class="text-center">Room size</th>
                                        <th class="text-center">Normal price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Document Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($productEntertainment))
                                        @foreach ($productEntertainment as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->Product_ID}}
                                            </td>
                                            <td style="text-align: left;">{{ $item->name_th }}</td>
                                            <td style="text-align: left;">{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;" class="target-class">
                                                {{ $item->normal_price }}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productquantity->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                {{ @$item->productunit->name_th}}
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="hidden" id="status" value="{{ $item->status }}">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3" >
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/edit/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    @include('script.script')

    <script>

        function Delete(id){
            Swal.fire({
            title: "คุณต้องการลบรายการนี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Mproduct/delete/') }}/" + id;
                }
            });
        }
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
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }

        function alldataBanquet() {
            console.log('alldataRoom');
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
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
        function alldataMeals() {
            console.log('alldataRoom');
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
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
        function alldataRoom() {
            console.log('alldataRoom');
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
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
    </script>
@endsection

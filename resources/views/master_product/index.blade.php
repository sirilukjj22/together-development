@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Product Item.</small>
                    <div class=""><span class="span1">Product Item (รายการสินค้า)</span></div>
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
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
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
                                        <img src="{{ asset('assets2/images/supplies.png') }}" style="width: 100%" class="logo"/>
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
                                        <img src="{{ asset('assets2/images/bed.png') }}" style="width: 100%" class="logo"/>
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
                                        <img src="{{ asset('assets2/images/seminar.png') }}" style="width: 100%" class="logo"/>
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
                                        <img src="{{ asset('assets2/images/serving-dish.png') }}" style="width: 100%" class="logo"/>
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
                                        <img src="{{ asset('assets2/images/multimedia.png') }}" style="width: 100%" class="logo"/>
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
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-product" onchange="getPage(1, this.value, 'product')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "product" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "product" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "product" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "product" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="product" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="productTable" class="example1 ui striped table nowrap unstackable hover">
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
                                            <td>{{ $item->name_th }}</td>
                                            <td>{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->normal_price) }}
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
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
                            <input type="hidden" id="get-total-product" value="{{ $product->total() }}">
                            <input type="hidden" id="currentPage-product" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="product-showingEntries">{{ showingEntriesTable($product, 'product') }}</p>
                                        <div id="product-paginate">
                                            {!! paginateTable($product, 'product') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </div>
                    </div> <!-- .card end -->
                    <div class="card p-4 mb-4 mt-5" style="display: none;" id="Roomtable" >
                        <h4><b>Room</b></h4>
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-productroom" onchange="getPagePending(1, this.value, 'productroom')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "productroom" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "productroom" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "productroom" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "productroom" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data-productroom" id="productroom" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="productroomTable" class="example2 ui striped table nowrap unstackable hover">
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
                                            <td>{{ $item->name_th }}</td>
                                            <td>{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->normal_price) }}
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
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
                            <input type="hidden" id="get-total-productroom" value="{{ $productroom->total() }}">
                            <input type="hidden" id="currentPage-productroom" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="productroom-showingEntries">{{ showingEntriesTablePending($productroom, 'productroom') }}</p>
                                        <div id="productroom-paginate">
                                            {!! paginateTablePending($productroom, 'productroom') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Banquettable">
                        <h4><b>Banquet</b></h4>
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-productBanquet" onchange="getPageAwaiting(1, this.value, 'productBanquet')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "productBanquet" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "productBanquet" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "productBanquet" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "productBanquet" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data-productBanquet" id="productBanquet" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="productBanquetTable" class="example2 ui striped table nowrap unstackable hover">
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
                                            <td>{{ $item->name_th }}</td>
                                            <td>{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->normal_price) }}
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
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
                            <input type="hidden" id="get-total-productBanquet" value="{{ $productBanquet->total() }}">
                            <input type="hidden" id="currentPage-productBanquet" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="productBanquet-showingEntries">{{ showingEntriesTableAwaiting($productBanquet, 'productBanquet') }}</p>
                                        <div id="productBanquet-paginate">
                                            {!! paginateTableAwaiting($productBanquet, 'productBanquet') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Mealstable">
                        <h4><b>Meals</b></h4>
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-productMeals" onchange="getPageApproved(1, this.value, 'productMeals')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "productMeals" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "productMeals" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "productMeals" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "productMeals" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data-productMeals" id="productMeals" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="productMealsTable" class="example2 ui striped table nowrap unstackable hover">
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
                                            <td>{{ $item->name_th }}</td>
                                            <td>{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->normal_price) }}
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
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
                            <input type="hidden" id="get-total-productMeals" value="{{ $productMeals->total() }}">
                            <input type="hidden" id="currentPage-productMeals" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="productMeals-showingEntries">{{ showingEntriesTableApproved($productMeals, 'productMeals') }}</p>
                                        <div id="productMeals-paginate">
                                            {!! paginateTableApproved($productMeals, 'productMeals') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
                        </div>
                    </div>
                    <div class="card p-4 mb-4 mt-5" style="display: none; " id="Entertainmenttable">
                        <h4><b>Entertainment</b></h4>
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-productEntertainment" onchange="getPageReject(1, this.value, 'productEntertainment')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "productEntertainment" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "productEntertainment" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "productEntertainment" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "productEntertainment" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data-productEntertainment" id="productEntertainment" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="productEntertainmentTable" class="example2 ui striped table nowrap unstackable hover">
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
                                            <td>{{ $item->name_th }}</td>
                                            <td>{{ $item->detail_th }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->room_size === null)
                                                    -
                                                @else
                                                    {{ $item->room_size }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->normal_price) }}
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
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
                            <input type="hidden" id="get-total-productEntertainment" value="{{ $productEntertainment->total() }}">
                            <input type="hidden" id="currentPage-productEntertainment" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="productEntertainment-showingEntries">{{ showingEntriesTableReject($productEntertainment, 'productEntertainment') }}</p>
                                        <div id="productEntertainment-paginate">
                                            {!! paginateTableReject($productEntertainment, 'productEntertainment') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableMasterProduct.js')}}"></script>
    @include('script.script')
    <script>
        const table_name = ['productTable','productroomTable','productBanquetTable','productMealsTable','productEntertainmentTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                console.log();

                new DataTable('#'+table_name[index], {
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
            }
        });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
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
            }
        }
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/product-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,4,5,6,7,8,9], className: 'dt-center td-content-center' },
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
                        { data: 'Product' },
                        { data: 'Name' },
                        { data: 'Detail' },
                        { data: 'Room' },
                        { data: 'Normal' },
                        { data: 'Quantity' },
                        { data: 'Unit' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-productroom', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/productroom-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchPending(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchPending(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,4,5,6,7,8,9], className: 'dt-center td-content-center' },
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
                        { data: 'Product' },
                        { data: 'Name' },
                        { data: 'Detail' },
                        { data: 'Room' },
                        { data: 'Normal' },
                        { data: 'Quantity' },
                        { data: 'Unit' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-productBanquet', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/productBanquet-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchAwaiting(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchAwaiting(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,4,5,6,7,8,9], className: 'dt-center td-content-center' },
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
                        { data: 'Product' },
                        { data: 'Name' },
                        { data: 'Detail' },
                        { data: 'Room' },
                        { data: 'Normal' },
                        { data: 'Quantity' },
                        { data: 'Unit' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-productMeals', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/productMeals-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchApproved(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchApproved(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,4,5,6,7,8,9], className: 'dt-center td-content-center' },
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
                        { data: 'Product' },
                        { data: 'Name' },
                        { data: 'Detail' },
                        { data: 'Room' },
                        { data: 'Normal' },
                        { data: 'Quantity' },
                        { data: 'Unit' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-productEntertainment', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/productEntertainment-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchReject(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchReject(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,4,5,6,7,8,9], className: 'dt-center td-content-center' },
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
                        { data: 'Product' },
                        { data: 'Name' },
                        { data: 'Detail' },
                        { data: 'Room' },
                        { data: 'Normal' },
                        { data: 'Quantity' },
                        { data: 'Unit' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],
                });
            document.getElementById(id).focus();
        });
    </script>
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
            $.fn.dataTable
            .tables({
                visible: true,
                api: true,
            })
            .columns.adjust()
            .responsive.recalc();

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
            $.fn.dataTable
            .tables({
                visible: true,
                api: true,
            })
            .columns.adjust()
            .responsive.recalc();
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
            $.fn.dataTable
            .tables({
                visible: true,
                api: true,
            })
            .columns.adjust()
            .responsive.recalc();
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
            .tables({
                visible: true,
                api: true,
            })
            .columns.adjust()
            .responsive.recalc();
        }
    </script>
@endsection

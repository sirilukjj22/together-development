@extends('layouts.masterLayout')

    @section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('user-department') }}">Department</a></li>
                    <li class="breadcrumb-item active">Edit Department</li>
                </ol>
                <h1 class="h4 mt-1">Edit Department</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('user-department') }}" title="ย้อนกลับ" class="btn btn-outline-dark lift">
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
    @endsection
    
    @section('content')
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div class="card-header py-3 bg-transparent border-bottom-0 mb-3">
                            <h5 class="card-title mb-0">Edit Department</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user-department-update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $department->id }}">
                                <div class="row mb-3">
                                    <label for="username" class="col-sm-3 col-form-label fw-bold">Department Name<sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" value="{{ $department->department }}" maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="close-day" class="col-sm-3 col-form-label fw-bold">Close Day</label>
                                    <div class="col-sm-9">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="close_day" id="close-day" value="1" {{ $department->close_day == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="close-day">Close Day <span class="text-danger" style="font-size: 12px;">* สิทธิ์การแก้ไขยอดวันที่ Close Day แล้ว</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">Menu Permissions</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_menu_all" id="select_menu_all" value="{{ @$department->roleMenu->select_menu_all }}" {{ @$department->roleMenu->select_menu_all == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="select_menu_all">Select All</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <div class="accordion card p-0 p-lg-4" id="accordionExample">
                                            <div class="card border-0">
                                                <div class="card-body" id="heading1">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                                        <b>เมนู / Menu</b><span style="float: right; color:rgb(26, 107, 87);">ย่อ/ขยาย</span>
                                                    </h6>
                                                </div>
                                                <div id="faq1" class="collapse" aria-labelledby="heading1" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="col-12 table_wrapper print_invoice">
                                                            <table class="items">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th>ชื่อเมนู</th>
                                                                        <th>เพิ่มข้อมูล</th>
                                                                        <th>แก้ไขข้อมูล</th>
                                                                        <th>ลบข้อมูล</th>
                                                                        <th>ดูข้อมูล</th>
                                                                        <th>Discount</th>
                                                                        <th>Special Discount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($tb_menu))
                                                                        @foreach ($tb_menu as $item)
                                                                            @if ($item->category_name == 1)
                                                                            @php
                                                                                $menu = $item->id;
                                                                            @endphp
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}" id="menu_{{ $item->name2 }}" value="1" {{ @$department->roleMenuSelect($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}"><b>{{ $item->name2 }}</b></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    @if ($item->name_en == "Product Item")
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_add" id="menu_{{ $item->name2 }}_add" value="1" {{ @$department->roleMenuAdd($item->id, $department->id) == 1 ? 'checked' : '' }}> 
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_edit" id="menu_{{ $item->name2 }}_edit" value="1" {{ @$department->roleMenuEdit($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_delete" id="menu_{{ $item->name2 }}_delete" value="1" {{ @$department->roleMenuDelete($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_view" id="menu_{{ $item->name2 }}_view" value="1" {{ @$department->roleMenuView($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_discount" id="menu_{{ $item->name2 }}_discount" value="1" {{ @$department->roleMenuDiscount($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item->name2 }}_special_discount" id="menu_{{ $item->name2 }}_special_discount" value="1" {{ @$department->roleMenuSpecialDiscount($item->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->name2 }}_special_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                    @endif
                                                                                </tr>
                                                                            @endif
                                                                            @foreach ($tb_menu as $item2)
                                                                                @if ($item2->category_name == 2 && $item2->menu_main == $item->id)
                                                                                    @php
                                                                                        $menu2 = $item2->id;
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td>
                                                                                           <div>
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}" id="menu_{{ $item2->name2 }}" value="1" {{ @$department->roleMenuSelect($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}">{{ $item2->name_en }}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_add" id="menu_{{ $item2->name2 }}_add" value="1" {{ @$department->roleMenuAdd($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_edit" id="menu_{{ $item2->name2 }}_edit" value="1" {{ @$department->roleMenuEdit($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_delete" id="menu_{{ $item2->name2 }}_delete" value="1" {{ @$department->roleMenuDelete($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_view" id="menu_{{ $item2->name2 }}_view" value="1" {{ @$department->roleMenuView($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_discount" id="menu_{{ $item2->name2 }}_discount" value="1" {{ @$department->roleMenuDiscount($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->name2 }}" type="checkbox" name="menu_{{ $item2->name2 }}_special_discount" id="menu_{{ $item2->name2 }}_special_discount" value="1" {{ @$department->roleMenuSpecialDiscount($item2->id, $department->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_special_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom"></div>
                                            </div> <!-- .card - FAQ 1  -->
                                        </div>
                                    </div>
                                </div> <!-- Row end  -->
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">Revenue type permissions</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_revenue_all" id="select_revenue_all" value="{{ @$department->roleRevenues->select_revenue_all }}" {{ @$department->roleRevenues->select_revenue_all == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="select_revenue_all">Select All</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    {{-- <div class="col-lg-3 col-md-3"></div> --}}
                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <div class="accordion card p-0 p-lg-4" id="accordionExample2">
                                            <div class="card border-0">
                                                <div class="card-body" id="heading2">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2"><b>ประเภทรายได้ / Revenue Type</b> <span style="float: right; color:rgb(26, 107, 87);">ย่อ/ขยาย</span></h6>
                                                </div>
                                                <div id="faq2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample2">
                                                    <div class="card-body">
                                                        <div class="col-12 table_wrapper print_invoice">
                                                            <table class="items">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th></th>
                                                                        <th>ชื่อเมนู</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($tb_revenue_type))
                                                                        @foreach ($tb_revenue_type as $key => $item)
                                                                        @php
                                                                            $name2 = $tb_revenue_type2[$key];
                                                                        @endphp
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="text-center">
                                                                                        <input class="form-check-input select_revenue" type="checkbox" name="{{ $name2 }}" id="{{ $name2 }}" value="1" {{ @$department->roleRevenues->$name2 == 1 ? 'checked' : '' }}>
                                                                                        <label class="form-check-label" for="{{ $name2 }}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    {{ $item }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-bottom"></div>
                                            </div> <!-- .card - FAQ 2  -->
                                        </div>
                                    </div>
                                </div> <!-- Row end  -->
                                <div class="text-end col-12">
                                    <a href="{{ route('user-department') }}" type="button" class="btn btn-outline-dark lift">Cancel</a>
                                    <button type="submit" class="btn btn-color-green lift">Save</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    
    
    
    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

<script type="text/javascript">
    $(document).ready(function() {
        $('#permission-select2').select2();
        $('#permission-edit-select2').select2();
    });

    $('#select_menu_all').on('click', function() {
        var menu = $('#select_menu_all').val();

        if (menu == 0) {
            $('.select_menu').prop('checked', true);
            $('#select_menu_all').val(1);
        } else { 
            $('.select_menu').prop('checked', false);
            $('#select_menu_all').val(0);
        }
    });

    $('#select_revenue_all').on('click', function() {
        var revenue = $('#select_revenue_all').val();

        if (revenue == 0) {
            $('.select_revenue').prop('checked', true);
            $('#select_revenue_all').val(1);
        } else {
            $('.select_revenue').prop('checked', false);
            $('#select_revenue_all').val(0);
        }
    });

    $('.select_menu').on('click', function() {
        var select_menu = $(this).attr('id');

        $('#select_menu_all').val(0);
        $('#select_menu_all').prop('checked', false);

        if ($(this).is(':checked')) {
            $('.select_'+select_menu).prop('checked', true);
        } else {
            $('.select_'+select_menu).prop('checked', false);
        }
    });

    $('.select_revenue').on('click', function() {
        $('#select_revenue_all').val(0);
        $('#select_revenue_all').prop('checked', false);
    });
</script>
@endsection
    
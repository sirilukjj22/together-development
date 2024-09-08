@extends('layouts.masterLayout')

    @section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('users', 'index') }}">User</a></li>
                    <li class="breadcrumb-item active">Edit User</li>
                </ol>
                <h1 class="h4 mt-1">Edit User</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('users', 'index') }}" title="ย้อนกลับ" class="btn btn-outline-dark lift">
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
                            <h5 class="card-title mb-0">Edit User</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user-update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <div class="row mb-3">
                                    <label for="username" class="col-sm-3 col-form-label fw-bold">ชื่อผู้ใช้งาน / Username <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label fw-bold">อีเมล์ / Email <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">รหัสผ่าน / Password <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">สิทธิ์ในการเข้าถึง / Access rights <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="permission" id="permission-select2" onchange="select_department()">
                                            <option value="">Select</option>
                                            @foreach ($departments as $item)
                                                @if (Auth::user()->permission == 1 && $item->department == "Developer")
                                                    <option value="{{ $item->id }}" {{ $user->permission == $item->id ? 'selected' : '' }}>{{ $item->department }}</option>
                                                @else
                                                    <option value="{{ $item->id }}" {{ $user->permission == $item->id ? 'selected' : '' }}>{{ $item->department }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="permission-edit-select2" class="col-sm-2 col-form-label text-right fw-bold">สิทธิ์ในการใช้ข้อมูล<sup class="text-danger">*</sup></label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="permission_edit" id="permission-edit-select2">
                                            <option value="0" {{ $user->permission_edit == 0 ? 'selected' : '' }}>ดูได้อย่างเดียว</option>
                                            <option value="1" {{ $user->permission_edit == 1 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง</option>
                                            <option value="2" {{ $user->permission_edit == 2 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง และดูข้อมูลคนอื่นได้</option>
                                            <option value="3" {{ $user->permission_edit == 3 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง และแก้ไขข้อมูลคนอื่นได้</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">ส่วนลด / Discount</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" min="0" max="100" class="form-control" name="discount" value="{{ $user->discount }}">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <label for="close-day" class="col-sm-3 col-form-label fw-bold text-right">Close Day</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="close_day" id="close-day" value="1" {{ $user->edit_close_day == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="close-day">Close Day</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">สิทธิ์การใช้งานเมนู / Menu Permissions</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_menu_all" id="select_menu_all" value="{{ @$user->roleMenu->select_menu_all }}" {{ @$user->roleMenu->select_menu_all == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="select_menu_all">เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    {{-- <div class="col-lg-3 col-md-3"></div> --}}
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
                                                                                $menu = $item->name2;
                                                                            @endphp
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}" id="menu_{{ $item->id }}" value="1" {{ @$user->roleMenu->$menu == 1 ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="menu_{{ $item->id }}"><b>{{ $item->name_en }}</b></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    @if ($item->name_en == "Product Item")
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_add" id="menu_{{ $item->id }}_add" value="1" {{ @$user->roleMenuAdd($item->name_en, $user->id) == 1 ? 'checked' : '' }}> 
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_edit" id="menu_{{ $item->id }}_edit" value="1" {{ @$user->roleMenuEdit($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_delete" id="menu_{{ $item->id }}_delete" value="1" {{ @$user->roleMenuDelete($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_view" id="menu_{{ $item->id }}_view" value="1" {{ @$user->roleMenuView($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_discount" id="menu_{{ $item->id }}_discount" value="1" {{ @$user->roleMenuDiscount($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item->name2 }}_special_discount" id="menu_{{ $item->id }}_special_discount" value="1" {{ @$user->roleMenuSpecialDiscount($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item->id }}_special_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                    @endif
                                                                                </tr>
                                                                            @endif
                                                                            @foreach ($tb_menu as $item2)
                                                                                @if ($item2->category_name == 2 && $item2->menu_main == $item->id)
                                                                                    @php
                                                                                        $menu2 = $item2->name2;
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td>
                                                                                           <div>
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}" id="menu_{{ $item2->id }}" value="1" {{ @$user->roleMenu->$menu2 == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}">{{ $item2->name_en }}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_add" id="menu_{{ $item2->id }}_add" value="1" {{ @$user->roleMenuAdd($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_edit" id="menu_{{ $item2->id }}_edit" value="1" {{ @$user->roleMenuEdit($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_delete" id="menu_{{ $item2->id }}_delete" value="1" {{ @$user->roleMenuDelete($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_view" id="menu_{{ $item2->id }}_view" value="1" {{ @$user->roleMenuView($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_discount" id="menu_{{ $item2->id }}_discount" value="1" {{ @$user->roleMenuDiscount($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu select_menu_{{ $item->id }}" type="checkbox" name="menu_{{ $item2->name2 }}_special_discount" id="menu_{{ $item2->id }}_special_discount" value="1" {{ @$user->roleMenuSpecialDiscount($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                                                <label class="form-check-label" for="menu_{{ $item2->id }}_special_discount"></label>
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
                                            <input class="form-check-input" type="checkbox" name="select_revenue_all" id="select_revenue_all" value="{{ @$user->roleRevenues->select_revenue_all }}" {{ @$user->roleRevenues->select_revenue_all == 1 ? 'checked' : '' }}>
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
                                                                                        <input class="form-check-input select_revenue" type="checkbox" name="{{ $name2 }}" id="revenue_{{ $name2 }}" value="1" {{ @$user->roleRevenues->$name2 == 1 ? 'checked' : '' }}>
                                                                                        <label class="form-check-label" for="revenue_{{ $name2 }}"></label>
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
                                    <a href="{{ route('users', 'index') }}" type="button" class="btn btn-outline-dark lift">Cancel</a>
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

    function select_department() {
            var id = $('#permission-select2').val();
            $('.select_menu').prop('checked', false);
            $('.select_revenue').prop('checked', false);
            $('#close-day').prop('checked', false);

            jQuery.ajax({
                type: "GET",
                url: "{!! url('user-search-department/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    // Department 
                    if (response.data.close_day == 1) {
                        $('#close-day').prop('checked', true);
                    }

                    // Menu
                    $.each(response.data_menu, function (key, val) {
                        $('#menu_'+val.menu_id).prop('checked', true);

                        if (val.add_data == 1) {
                            $('#menu_'+val.menu_id+'_add').prop('checked', true);
                        }

                        if (val.edit_data == 1) {
                            $('#menu_'+val.menu_id+'_edit').prop('checked', true);
                        }

                        if (val.delete_data == 1) {
                            $('#menu_'+val.menu_id+'_delete').prop('checked', true);
                        }

                        if (val.view_data == 1) {
                            $('#menu_'+val.menu_id+'_view').prop('checked', true);
                        }

                        if (val.discount == 1) {
                            $('#menu_'+val.menu_id+'_discount').prop('checked', true);
                        }

                        if (val.special_discount == 1) {
                            $('#menu_'+val.menu_id+'_special_discount').prop('checked', true);
                        }
                    });
                    
                    // Revenue 
                    if (response.data_revenue.front_desk == 1) {
                        $('#revenue_front_desk').prop('checked', true);
                    }

                    if (response.data_revenue.guest_deposit == 1) {
                        $('#revenue_guest_deposit').prop('checked', true);
                    }

                    if (response.data_revenue.all_outlet == 1) {
                        $('#revenue_all_outlet').prop('checked', true);
                    }

                    if (response.data_revenue.agoda == 1) {
                        $('#revenue_agoda').prop('checked', true);
                    }

                    if (response.data_revenue.credit_card_hotel == 1) {
                        $('#revenue_credit_card_hotel').prop('checked', true);
                    }

                    if (response.data_revenue.elexa == 1) {
                        $('#revenue_elexa').prop('checked', true);
                    }

                    if (response.data_revenue.water_park == 1) {
                        $('#revenue_water_park').prop('checked', true);
                    }

                    if (response.data_revenue.credit_water_park == 1) {
                        $('#revenue_credit_water_park').prop('checked', true);
                    }

                    if (response.data_revenue.other_revenue == 1) {
                        $('#revenue_other_revenue').prop('checked', true);
                    }

                    if (response.data_revenue.no_category == 1) {
                        $('#revenue_no_category').prop('checked', true);
                    }

                    if (response.data_revenue.transfer == 1) {
                        $('#revenue_transfer').prop('checked', true);
                    }

                    if (response.data_revenue.time == 1) {
                        $('#revenue_time').prop('checked', true);
                    }

                    if (response.data_revenue.split == 1) {
                        $('#revenue_split').prop('checked', true);
                    }

                    if (response.data_revenue.edit == 1) {
                        $('#revenue_edit').prop('checked', true);
                    }
                },
            });
        }
</script>
@endsection
    
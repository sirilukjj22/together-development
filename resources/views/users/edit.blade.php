@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">User</span><span class="span2"> / Edit User</span></div>
                    <div class="span3">Edit User</div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('users', 'index') }}" type="button" class="btn btn-color-green text-white lift">Back</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    @php
        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
    @endphp
    <div class="body-header d-flex py-3">
        <div class="container-xl" style="min-height: 85vh">
            <!-- content -->
            <div>
                <main class="main-content bg-together-light">
                    <!-- section card ข้างบน -->
                    <section class="">
                        <h1 class="form-title-top">Edit User</h1>
                        <form action="{{ route('user-update') }}" method="POST" enctype="multipart/form-data" style="display: grid;gap: 1rem;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="wrap-permissoion-create-user">
                                <section>
                                    <div class="form-group">
                                        <label for="firstname" class="star-red">Firstname</label>
                                        <input type="text" class="form-control" name="firstname" value="{{ $user->firstname }}" maxlength="70" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class="star-red">Username</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" maxlength="70" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="star-red">Email</label>
                                        <input type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="signature">Signature <a href="/upload/signature/{{ $user->signature }}" target="_blank" class="text-primary"><u>แสดงรูปภาพ</u></a></label>
                                        <input type="file" class="form-control" name="signature" accept=".jpg, .jpeg, .png">
                                    </div>
                                    <div class="form-group">
                                        <label for="access-rights" class="star-red">สิทธิ์ในการใช้ข้อมูล /Data Usage Rights</label>
                                        <select class="form-control" name="permission_edit" id="access-use-rights" style="padding: 0;">
                                            <option value="0" {{ $user->permission_edit == 0 ? 'selected' : '' }}>ดูได้อย่างเดียว</option>
                                            <option value="1" {{ $user->permission_edit == 1 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง</option>
                                            <option value="2" {{ $user->permission_edit == 2 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง และดูข้อมูลคนอื่นได้</option>
                                            <option value="3" {{ $user->permission_edit == 3 ? 'selected' : '' }}>สามารถแก้ไขข้อมูลตัวเอง และแก้ไขข้อมูลคนอื่นได้</option>
                                        </select>
                                    </div>
                                </section>
                                <section>
                                    <div class="form-group">
                                        <label for="lastname" class="star-red">Lastname</label>
                                        <input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" maxlength="70" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter password">
                                    </div>
                                    <div class="form-group">
                                        <label for="telephone" class="star-red">Telephone</label>
                                        <input type="text" class="form-control phone" name="telephone" value="{{ $user->tel }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="access-rights" class="star-red">สิทธิ์ในการเข้าถึง / Access Rights</label>
                                        <select name="permission" id="access-rights" onchange="select_department()">
                                            @foreach ($departments as $item)
                                                @if (Auth::user()->permission == 1 && $item->department == "Developer")
                                                    <option value="{{ $item->id }}" {{ $user->permission == $item->id ? 'selected' : '' }}>{{ $item->department }}</option>
                                                @else
                                                    <option value="{{ $item->id }}" {{ $user->permission == $item->id ? 'selected' : '' }}>{{ $item->department }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div  class="d-grid-2column" >
                                        <div class="form-group" style="height: 4.7em;">
                                            <label for="discount">Discount (%)</label>
                                            <input type="text" min="0" max="100" class="form-control" name="discount" value="{{ $user->discount }}">
                                        </div>
                                        <div class="form-group" style="height: 4.7em;" >
                                            <label >Additional Discount (%)</label>
                                            <input type="number" name="additional_discount" value="{{ $user->additional_discount }}" min="0" max="100" />
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="permissions-select">
                                <div>
                                    <p>
                                        <label style="display: flex;gap:0.5rem" class="bg-together-card-light">
                                            <span id="open-menu-permissions" style="width: 100%;"> สิทธิ์การใช้งานเมนู / Menu Permissions</span>
                                        </label>
                                        <span>
                                            <input type="checkbox" name="select_menu_all" id="select_menu_all" class="ml-2" value="{{ @$user->roleMenu->select_menu_all }}" {{ @$user->roleMenu->select_menu_all == 1 ? 'checked' : '' }}>
                                            <span>เลือกทั้งหมด</span>
                                        </span>
                                    </p>
                                    <section class="bg-together-card-plain" id="menu-section" style="display:none;">
                                        <div class="flex-between mb-2">
                                            <span class="f-top-table">เมนู / Menu</span>
                                            <div id="close-menu-section" class="bg-together">Close</div>
                                        </div>
                                        <div class="table-permissions-container">
                                            <table id="table-permissions">
                                                <thead>
                                                    <tr>
                                                        <th>Menu</th>
                                                        <th>View</th>
                                                        <th>Edit</th>
                                                        <th>Add</th>
                                                        <th>Delete</th>
                                                        <th>Discount</th>
                                                        <th>Special Discount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($tb_menu))
                                                        @foreach ($tb_menu as $item)
                                                            @if ($item->category_name == 1) <!-- Topic Main -->
                                                                @php
                                                                    $menu = $item->name2;
                                                                @endphp
                                                                <tr class="head-sub" style="background-color: #248a8a23;">
                                                                    <td colspan="7">
                                                                        <input class="select_menu" type="checkbox" name="menu_{{ $item->name2 }}_main" id="menu_{{ $item->id }}_main" value="1" {{ @$user->roleMenu->$menu == 1 ? 'checked' : '' }}>
                                                                        <strong>{{ $item->name_en }}</strong>
                                                                    </td>
                                                                </tr>
                                                                @if ($item->name_en == "Product Item" || $item->name_en == "Report")
                                                                    <tr>
                                                                        <td>
                                                                            <input class="select-row select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}" id="menu_{{ $item->id }}" value="1" {{ @$user->roleMenuSub($user->id, $item->name_en) == 1 ? 'checked' : '' }}>
                                                                            {{ $item->name_en }}
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_view" id="menu_{{ $item->id }}_view" value="1" {{ @$user->roleMenuView($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_edit" id="menu_{{ $item->id }}_edit" value="1" {{ @$user->roleMenuEdit($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_add" id="menu_{{ $item->id }}_add" value="1" {{ @$user->roleMenuAdd($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_delete" id="menu_{{ $item->id }}_delete" value="1" {{ @$user->roleMenuDelete($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_discount" id="menu_{{ $item->id }}_discount" value="1" {{ @$user->roleMenuDiscount($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item->name2 }}_special_discount" id="menu_{{ $item->id }}_special_discount" value="1" {{ @$user->roleMenuSpecialDiscount($item->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endif

                                                            @foreach ($tb_menu as $item2) <!-- Topic 2 -->
                                                                @if ($item2->category_name == 2 && $item2->menu_main == $item->id)
                                                                    @php
                                                                        $menu2 = $item2->name2;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <input class="select-row select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}" id="menu_{{ $item2->id }}" value="1" {{ @$user->roleMenu->$menu2 == 1 ? 'checked' : '' }}>
                                                                            {{ $item2->name_en }}
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_view" id="menu_{{ $item2->id }}_view" value="1" {{ @$user->roleMenuView($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_edit" id="menu_{{ $item2->id }}_edit" value="1" {{ @$user->roleMenuEdit($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_add" id="menu_{{ $item2->id }}_add" value="1" {{ @$user->roleMenuAdd($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_delete" id="menu_{{ $item2->id }}_delete" value="1" {{ @$user->roleMenuDelete($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_discount" id="menu_{{ $item2->id }}_discount" value="1" {{ @$user->roleMenuDiscount($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <input class="select_menu select_menu_{{ $item->id }}_main" type="checkbox" name="menu_{{ $item2->name2 }}_special_discount" id="menu_{{ $item2->id }}_special_discount" value="1" {{ @$user->roleMenuSpecialDiscount($item2->name_en, $user->id) == 1 ? 'checked' : '' }}>
                                                                        </td>
                                                                    </tr>

                                                                    @if ($item2->name_en == "Bank Transaction Revenue")
                                                                        <tr style="background-color: rgb(239, 240, 240);">
                                                                            <td colspan="7">
                                                                                <input class="select-row" type="checkbox" name="close_day" id="close-day" value="1" {{ $user->edit_close_day == 1 ? 'checked' : '' }}>
                                                                                Close Day (สำหรับการแสดงค่าของ Bank Transaction Revenue)
                                                                            </td>
                                                                        <tr>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                </div>
                                <div>
                                    <div>
                                        <p>
                                            <label style="display: flex;gap:0.5rem" class="bg-together-card-light">
                                                <span id="open-revenue-section" style="width: 100%;">สิทธิ์การใช้งานประเภทรายได้ / Revenue Type</span>
                                            </label>
                                            <span>
                                                <input type="checkbox" name="select_revenue_all" id="select_revenue_all" class="ml-2" value="{{ @$user->roleRevenues->select_revenue_all }}" {{ @$user->roleRevenues->select_revenue_all == 1 ? 'checked' : '' }}>
                                                <span>เลือกทั้งหมด</span>
                                            </span>
                                        </p>
                                        <section class="bg-together-card-plain" id="revenue-section"
                                            style="display:none;">
                                            <div class="flex-between mb-2">
                                                <span class="f-top-table">ประเภทรายได้ / Revenue Type</span>
                                                <div id="close-revenue-section" class="bg-together">Close</div>
                                            </div>
                                            <div class="revenue-type-container">
                                                <table id="revenue-type-table">
                                                    <thead>
                                                        <tr>
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
                                                                        <input class="select_revenue" type="checkbox" name="{{ $name2 }}" id="revenue_{{ $name2 }}" value="1" {{ @$user->roleRevenues->$name2 == 1 ? 'checked' : '' }}>
                                                                        <span>{{ $item }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-end">
                                <button class="bg-plain grey-color  no-select">Cancle</button>
                                <button class="bg-plain no-select">Save</button>
                            </div>
                        </form>
                    </section>
                </main>
            </div>
            <!-- .row end -->
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/formatNumber.js')}}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/formatNumber.js')}}"></script>
    @endif

    <style>
        .select2 {
            width: 100%;
            padding: 0;
            border: none;
            border-radius: 7px;
            box-shadow: inset 2px 2px 8px rgba(0, 0, 0, 0.1),
                inset -2px -2px 8px rgba(255, 255, 255, 0.429);
            background: #f5f9f9 !important;
            font-size: 16px;
            transition: all 0.3s ease;
            height: 100%;
            text-align: start;
        }

        .select2-container .select2-selection {
            border-color: none;
            height: auto;
            position: relative;
            line-height: 1.5;
            padding: 0.89rem;
        }

        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border: none;
            border-radius: 4px;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('#access-rights').select2();
            $('#access-use-rights').select2();

            // ฟังก์ชันสำหรับแสดง/ซ่อนส่วนของ menu-permissions และ revenue-section
            $('#open-menu-permissions').on('click', function () {
                $('#menu-section').slideToggle(); // แสดงหรือซ่อน #menu-section
            });
            $('#open-revenue-section').on('click', function () {
                $('#revenue-section').slideToggle(); // แสดงหรือซ่อน #revenue-section
            });
            // ปุ่มปิด #menu-section
            $('#close-menu-section').on('click', function () {
                $('#menu-section').slideUp(); // ใช้ slideUp เพื่อเลื่อนขึ้นและปิด
            });
            // ปุ่มปิด #revenue-section
            $('#close-revenue-section').on('click', function () {
                $('#revenue-section').slideUp(); // ใช้ slideUp เพื่อเลื่อนขึ้นและปิด
            });

            // ฟังก์ชันสำหรับการเลือก checkbox ทั้งหมดใน menu-section
            $('#menu-permissions').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('#menu-section input[type="checkbox"]').prop('checked', isChecked);
            });

            // ฟังก์ชันสำหรับการเลือก checkbox ทั้งหมดใน revenue-section
            $('#revenue-types').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('#revenue-section input[type="checkbox"]').prop('checked', isChecked);
            });
        });


        $(document).ready(function () {
            // เมื่อมีการคลิกที่ <td> ที่มี class .select-row หรือ checkbox ภายใน td นั้น
            $('td:has(.select-row)').on('click', function (e) {
                if (e.target.type !== 'checkbox') {
                var checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
                }
                var isChecked = $(this).find('input[type="checkbox"]').is(':checked');
                $(this).closest('tr').find('input[type="checkbox"]').prop('checked', isChecked);
            });
            // $('tr input[type="checkbox"]').on('change', function () {
            //     var allChecked = true;
            //     var row = $(this).closest('tr');

            //     row.find('td:not(:has(.select-row)) input[type="checkbox"]').each(function () {
            //     if (!$(this).is(':checked')) {
            //         allChecked = false;
            //     }
            //     });
            //     row.find('.select-row').prop('checked', allChecked);
            // });
        });



        // เมื่อมีการคลิกใน <td> ที่มี checkbox ภายใน เฉพาะใน #revenue-section
        $('#revenue-section td').on('click', function (e) {
            // ตรวจสอบว่าจุดที่คลิกไม่ใช่ checkbox โดยตรง
            if (e.target.type !== 'checkbox') {
                // หา checkbox ภายใน <td> นั้น
                var checkbox = $(this).find('input[type="checkbox"]');
                // สลับสถานะ checked (ถ้าถูกเลือก ก็ยกเลิก ถ้าไม่ถูกเลือก ก็เลือก)
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });

        $('#select_menu_all').on('click', function() {
            var menu = $('#select_menu_all').val();

            if (menu == 0) {
                $('.select_menu').prop('checked', true);
                $('#close-day').prop('checked', true);
                $('#select_menu_all').val(1);
            } else {
                $('.select_menu').prop('checked', false);
                $('#close-day').prop('checked', false);
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

        // $('.select_revenue').on('click', function() {
        //     $('#select_revenue_all').val(0);
        //     $('#select_revenue_all').prop('checked', false);
        // });

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

        function select_department() {
            var id = $('#access-rights').val();
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
                    $.each(response.data_menu, function(key, val) {
                        $('#menu_' + val.menu_id + '_main').prop('checked', true); // Topic Main
                        $('#menu_' + val.menu_id).prop('checked', true);

                        if (val.add_data == 1) {
                            $('#menu_' + val.menu_id + '_add').prop('checked', true);
                        }

                        if (val.edit_data == 1) {
                            $('#menu_' + val.menu_id + '_edit').prop('checked', true);
                        }

                        if (val.delete_data == 1) {
                            $('#menu_' + val.menu_id + '_delete').prop('checked', true);
                        }

                        if (val.view_data == 1) {
                            $('#menu_' + val.menu_id + '_view').prop('checked', true);
                        }

                        if (val.discount == 1) {
                            $('#menu_' + val.menu_id + '_discount').prop('checked', true);
                        }

                        if (val.special_discount == 1) {
                            $('#menu_' + val.menu_id + '_special_discount').prop('checked', true);
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

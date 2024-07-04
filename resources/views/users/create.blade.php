@extends('layouts.masterLayout')

    @section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('users', 'index') }}">User</a></li>
                    <li class="breadcrumb-item active">Create User</li>
                </ol>
                <h1 class="h4 mt-1">Create User</h1>
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
                            <h5 class="card-title mb-0">Create User</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="username" class="col-sm-3 col-form-label fw-bold">ชื่อผู้ใช้งาน / Username <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" placeholder="กรุณาระบุชื่อผู้ใช้งาน" maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label fw-bold">อีเมล์ / Email <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email" placeholder="email@website.com" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">รหัสผ่าน / Password <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">สิทธิ์ในการเข้าถึง / Access rights <sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="permission" id="permission-select2">
                                            <option value="0">ผู้ใช้งานทั่วไป</option>
                                            <option value="1">แอดมิน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label fw-bold">ส่วนลด / Discount</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input type="text" min="0" max="100" class="form-control" name="discount" value="0">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-7"></div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">สิทธิ์การใช้งานเมนู / Menu Permissions</label>
                                    <div class="col-sm-9">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_menu_all" id="select_menu_all" value="0">
                                            <label class="form-check-label" for="select_menu_all">เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="accordion card p-0 p-lg-4" id="accordionExample">
                                            <div class="card border-0">
                                                <div class="card-body" id="heading1">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1"><span class="fw-bold"></span><b>เมนู (หัวข้อหลัก) / Menu Main</b></h6>
                                                </div>
                                                <div id="faq1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                                    <div class="card-body border-top">
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
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}" id="menu_{{ $item->name2 }}" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}"><b>{{ $item->name_en }}</b></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_add" id="menu_{{ $item->name2 }}_add" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_add"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_edit" id="menu_{{ $item->name2 }}_edit" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_edit"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_delete" id="menu_{{ $item->name2 }}_delete" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_delete"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_view" id="menu_{{ $item->name2 }}_view" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_view"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_discount" id="menu_{{ $item->name2 }}_discount" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_discount"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-center">
                                                                                            <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item->name2 }}_special_discount" id="menu_{{ $item->name2 }}_special_discount" value="1">
                                                                                            <label class="form-check-label" for="menu_{{ $item->name2 }}_special_discount"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            @foreach ($tb_menu as $item2)
                                                                                @if ($item2->category_name == 2 && $item2->menu_main == $item->id)
                                                                                    <tr>
                                                                                        <td>
                                                                                           <div>
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}" id="menu_{{ $item2->name2 }}" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}">{{ $item2->name_en }}</label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_add" id="menu_{{ $item2->name2 }}_add" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_add"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_edit" id="menu_{{ $item2->name2 }}_edit" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_edit"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_delete" id="menu_{{ $item2->name2 }}_delete" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_delete"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_view" id="menu_{{ $item2->name2 }}_view" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_view"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_discount" id="menu_{{ $item2->name2 }}_discount" value="1">
                                                                                                <label class="form-check-label" for="menu_{{ $item2->name2 }}_discount"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="text-center">
                                                                                                <input class="form-check-input select_menu" type="checkbox" name="menu_{{ $item2->name2 }}_special_discount" id="menu_{{ $item2->name2 }}_special_discount" value="1">
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
                                            </div> <!-- .card - FAQ 1  -->
                                            <div class="card border-0">
                                                <div class="card-body" id="heading2">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="true" aria-controls="faq2"><span <span class="fw-bold"></span> How does the Genesis Simple FAQ plugin?</h6>
                                                </div>
                                                <div id="faq2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                                    <div class="card-body border-top">
                                                       <p>Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.</p>
                                                       <div class="alert alert-primary" role="alert">
                                                            A simple primary alert—check it out!
                                                      </div>
                                                    </div>
                                                </div>
                                            </div> <!-- .card - FAQ 2  -->
                                            <div class="card border-0">
                                                <div class="card-body" id="heading3">
                                                    <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="true" aria-controls="faq3"><span <span class="fw-bold"></span> Can i customize the design of my FAQ section?</h6>
                                                </div>
                                                <div id="faq3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                                    <div class="card-body border-top">
                                                       <p>Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.</p>
                                                       <figure>
                                                            <blockquote class="blockquote">
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                                            </blockquote>
                                                            <figcaption class="blockquote-footer">
                                                            Someone famous in <cite title="Source Title">Source Title</cite>
                                                            </figcaption>
                                                      </figure>
                                                    </div>
                                                </div>
                                            </div> <!-- .card - FAQ 3  -->
                                        </div>
                                    </div>
                                </div> <!-- Row end  -->
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">เมนู (หัวข้อหลัก) / Menu Main</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_profile" id="menu_profile" value="1">
                                            <label class="form-check-label" for="menu_profile">Profile</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_freelancer" id="menu_freelancer" value="1">
                                            <label class="form-check-label" for="menu_freelancer">Freelancer</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_document" id="menu_document" value="1">
                                            <label class="form-check-label" for="menu_document">Document</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_product_item" id="menu_product_item" value="1">
                                            <label class="form-check-label" for="menu_product_item">Product Item</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_debtor" id="menu_debtor" value="1">
                                            <label class="form-check-label" for="menu_debtor">Debtor</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_maintenance" id="menu_maintenance" value="1">
                                            <label class="form-check-label" for="menu_maintenance">Maintenance</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_general_ledger" id="menu_general_ledger" value="1">
                                            <label class="form-check-label" for="menu_general_ledger">General Ledger</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_setting" id="menu_setting" value="1">
                                            <label class="form-check-label" for="menu_setting">Setting</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">เมนู (หัวข้อย่อย) / Submenu</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_company" id="menu_company" value="1">
                                            <label class="form-check-label" for="menu_company">Company / Agent (Profile)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_guest" id="menu_guest" value="1">
                                            <label class="form-check-label" for="menu_guest">Guest (Profile)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_membership" id="menu_membership" value="1">
                                            <label class="form-check-label" for="menu_membership">Membership (Freelancer)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_message_inbox" id="menu_message_inbox" value="1">
                                            <label class="form-check-label" for="menu_message_inbox">Message Inbox (Freelancer)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_registration_request" id="menu_registration_request" value="1">
                                            <label class="form-check-label" for="menu_registration_request">Registration Request (Freelancer)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_message_request" id="menu_message_request" value="1">
                                            <label class="form-check-label" for="menu_message_request">Message Request (Freelancer)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_proposal" id="menu_proposal" value="1">
                                            <label class="form-check-label" for="menu_proposal">Proposal (Document)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_banquet_event_order" id="menu_banquet_event_order" value="1">
                                            <label class="form-check-label" for="menu_banquet_event_order">Banquet Event Order (Document)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_hotel_contact_rate" id="menu_hotel_contact_rate" value="1">
                                            <label class="form-check-label" for="menu_hotel_contact_rate">Hotel Contact rate (Document)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_proforma_invoice" id="menu_proforma_invoice" value="1">
                                            <label class="form-check-label" for="menu_proforma_invoice">Proforma Invoice (Document)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_billing_folio" id="menu_billing_folio" value="1">
                                            <label class="form-check-label" for="menu_billing_folio">Billing Folio (Document)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_agoda" id="menu_agoda" value="1">
                                            <label class="form-check-label" for="menu_agoda">Agoda (Debtor)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_elexa" id="menu_elexa" value="1">
                                            <label class="form-check-label" for="menu_elexa">Elexa (Debtor)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_request_repair" id="menu_request_repair" value="1">
                                            <label class="form-check-label" for="menu_request_repair">Request Repair (Maintenance)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_repair_job" id="menu_repair_job" value="1">
                                            <label class="form-check-label" for="menu_repair_job">Repair Job (Maintenance)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_preventive_maintenance" id="menu_preventive_maintenance" value="1">
                                            <label class="form-check-label" for="menu_preventive_maintenance">Preventive Maintenance (Maintenance)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_sms_alert" id="menu_sms_alert" value="1">
                                            <label class="form-check-label" for="menu_sms_alert">Daily Bank Transaction Revenue (General Ledger)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_revenue" id="menu_revenue" value="1">
                                            <label class="form-check-label" for="menu_revenue">Hotel & Water Park Revenue (General Ledger)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_user" id="menu_user" value="1">
                                            <label class="form-check-label" for="menu_user">Users (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_bank" id="menu_bank" value="1">
                                            <label class="form-check-label" for="menu_bank">Bank (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_quantity" id="menu_quantity" value="1">
                                            <label class="form-check-label" for="menu_quantity">Quantity (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_unit" id="menu_unit" value="1">
                                            <label class="form-check-label" for="menu_unit">Unit (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_prefix" id="menu_prefix" value="1">
                                            <label class="form-check-label" for="menu_prefix">Prename (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_company_type" id="menu_company_type" value="1">
                                            <label class="form-check-label" for="menu_company_type">Company Type (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_company_market" id="menu_company_market" value="1">
                                            <label class="form-check-label" for="menu_company_market">Company Market (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_company_event" id="menu_company_event" value="1">
                                            <label class="form-check-label" for="menu_company_event">Company Event (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_booking" id="menu_booking" value="1">
                                            <label class="form-check-label" for="menu_booking">Booking (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input select_menu" type="checkbox" name="menu_template" id="menu_template" value="1">
                                            <label class="form-check-label" for="menu_template">Template (Setting)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row mb-3">
                                    <label for="main-menu" class="col-sm-3 col-form-label fw-bold">สิทธิ์ใช้งานประเภทรายได้ / <br> Revenue type permissions</label>
                                    <div class="col-sm-3">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="select_revenue_all" id="select_revenue_all" value="0">
                                            <label class="form-check-label" for="select_revenue_all">เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="front_desk" id="front_desk" value="1">
                                            <label class="form-check-label" for="front_desk">Front Desk Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="guest_deposit" id="guest_deposit" value="1">
                                            <label class="form-check-label" for="guest_deposit">Guest Deposit Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="all_outlet" id="all_outlet" value="1">
                                            <label class="form-check-label" for="all_outlet">All Outlet Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="agoda" id="agoda" value="1">
                                            <label class="form-check-label" for="agoda">Agoda Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="credit_card_hotel" id="credit_card_hotel" value="1">
                                            <label class="form-check-label" for="credit_card_hotel">Credit Card Hotel Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="elexa" id="elexa" value="1">
                                            <label class="form-check-label" for="elexa">Elexa EGAT Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="water_park" id="water_park" value="1">
                                            <label class="form-check-label" for="water_park">Water Park Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="credit_water_park" id="credit_water_park" value="1">
                                            <label class="form-check-label" for="credit_water_park">Credit Card Water Park Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="no_category" id="no_category" value="1">
                                            <label class="form-check-label" for="no_category">No Category</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="transfer" id="transfer" value="1">
                                            <label class="form-check-label" for="transfer">Transfer</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="time" id="time" value="1">
                                            <label class="form-check-label" for="time">Update Time</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="split" id="split" value="1">
                                            <label class="form-check-label" for="split">Split Revenue</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input select_revenue" type="checkbox" name="edit" id="edit" value="1">
                                            <label class="form-check-label" for="edit">Edit / Delete</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end col-12">
                                    <a href="{{ route('users', 'index') }}" type="button" class="btn btn-outline-dark lift">Cancle</a>
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
        $('#select_menu_all').val(0);
        $('#select_menu_all').prop('checked', false);
    });

    $('.select_revenue').on('click', function() {
        $('#select_revenue_all').val(0);
        $('#select_revenue_all').prop('checked', false);
    });
</script>
@endsection
    
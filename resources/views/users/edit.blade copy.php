@extends('layouts.test')

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            margin-bottom: 1%;
            border-radius: 8px;
        }

        .select2-container .select2-selection {
            margin: 0 !important;
        }

        input,
        textarea {
            width: 100%;
            border-radius: 8px !important;
            margin-bottom: 1%;
            resize: none;
        }

        .row {
            margin-top: 10px;
        }

        .name_box {
            font-size: 32px;
            margin-bottom: 40px;
        }

        .form-check-label {
            margin-right: 10px;
        }
    </style>

    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            User (ผู้ใช้งาน) / &nbsp; <a href="{{ route('users', 'index') }}"> Edit User</a>
        </div>
        <div class="name_box">
            <h1>Edit User</h1>
        </div>

        <form action="{{ route('user-update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="row justify-center">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="fullName">ชื่อผู้ใช้งาน <sup class="text-danger">*</sup></label>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-12">
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" maxlength="70" required>
                </div>
            </div>
            <div class="row justify-center">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="email">อีเมล์ <sup class="text-danger">*</sup></label>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-12">
                    <input type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                </div>
            </div>
            <div class="row justify-center">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="">รหัสผ่าน</label>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-12">
                    <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน">
                </div>
            </div>
            <div class="row justify-center">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for="">สิทธ์ของผู้ใช้งาน <sup class="text-danger">*</sup></label>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-12">
                    <select class="form-control" name="permission" id="testselect2">
                        <option value="0" {{ $user->permission == 0 ? 'selected' : '' }}>ผู้ใช้งานทั่วไป</option>
                        <option value="1" {{ $user->permission == 1 ? 'selected' : '' }}>แอดมิน</option>
                        @if ($user->permission == 2)
                            <option value="2" {{ $user->permission == 2 ? 'selected' : '' }}>ผู้พัฒนาระบบ</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="row justify-center">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for=""><b>สิทธิ์การใช้งานเมนู</b></label>
                </div>
                <div class="col-lg-2 col-md-8 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="select_menu_all" id="select_menu_all"
                            value="{{ @$user->roleMenu->select_menu_all }}" {{ @$user->roleMenu->select_menu_all == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="select_menu_all">
                            เลือกทั้งหมด
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-start">
                <div class="col-lg-2 col-md-10 col-sm-12">
                    <label for="">เมนู (หัวข้อหลัก)</label>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_profile" id="menu_profile"
                            value="1" {{ @$user->roleMenu->profile == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_profile">
                            Profile
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_freelancer" id="menu_freelancer" value="1" {{ @$user->roleMenu->freelancer == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_freelancer">
                            Freelancer
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_document" id="menu_document" value="1" {{ @$user->roleMenu->document == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_document">
                            Document
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-2 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_debtor" id="menu_debtor"
                            value="1" {{ @$user->roleMenu->debtor == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_debtor">
                            Debtor
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_maintenance" id="menu_maintenance" value="1" {{ @$user->roleMenu->maintenance == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_maintenance">
                            Maintenance
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_general_ledger" id="menu_general_ledger" value="1" {{ @$user->roleMenu->general_ledger == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_general_ledger">
                            General Ledger
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_setting" id="menu_setting" value="1" {{ @$user->roleMenu->setting == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_setting">
                            Setting
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-start mt-4">
                <div class="col-lg-2 col-md-10 col-sm-12">
                    <label for="">เมนู (หัวข้อย่อย)</label>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_company"
                            id="menu_company" value="1" {{ @$user->roleMenu->company == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_company">
                            Company / Agent (Profile)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_guest" id="menu_guest"
                            value="1" {{ @$user->roleMenu->guest == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_guest">
                            Guest (Profile)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_membership" id="menu_membership" value="1" {{ @$user->roleMenu->membership == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_membership">
                            Membership (Freelancer)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_message_inbox" id="menu_message_inbox" value="1" {{ @$user->roleMenu->message_inbox == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_message_inbox">
                            Message Inbox (Freelancer)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_registration_request" id="menu_registration_request" value="1" {{ @$user->roleMenu->registration_request == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_registration_request">
                            Registration Request (Freelancer)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_message_request" id="menu_message_request" value="1" {{ @$user->roleMenu->message_request == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_message_request">
                            Message Request (Freelancer)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_proposal" id="menu_proposal" value="1" {{ @$user->roleMenu->proposal == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_proposal">
                            Proposal (Document)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_banquet_event_order" id="menu_banquet_event_order" value="1" {{ @$user->roleMenu->banquet_event_order == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_banquet_event_order">
                            Banquet Event Order (Document)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_hotel_contact_rate" id="menu_hotel_contact_rate" value="1" {{ @$user->roleMenu->hotel_contact_rate == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_hotel_contact_rate">
                            Hotel Contact rate (Document)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_proforma_invoice" id="menu_proforma_invoice" value="1" {{ @$user->roleMenu->proforma_invoice == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_proforma_invoice">
                            Proforma Invoice (Document)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_billing_folio" id="menu_billing_folio" value="1" {{ @$user->roleMenu->billing_folio == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_billing_folio">
                            Billing Folio (Document)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_sms_alert"
                            id="menu_sms_alert" value="1" {{ @$user->roleMenu->sms_alert == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_sms_alert">
                            Daily Bank Transaction Revenue (General Ledger)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_revenue" id="menu_revenue"
                            value="1" {{ @$user->roleMenu->revenue == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_revenue">
                            Hotel & Water Park Revenue (General Ledger)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_agoda" id="menu_agoda"
                            value="1" {{ @$user->roleMenu->agoda == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_agoda">
                            Agoda (Debtor)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_elexa" id="menu_elexa"
                            value="1" {{ @$user->roleMenu->elexa == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_elexa">
                            Elexa (Debtor)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_request_repair" id="menu_request_repair"
                            value="1" {{ @$user->roleMenu->request_repair == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_request_repair">
                            Request Repair (Maintenance)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_repair_job" id="menu_repair_job" value="1" 
                        {{ @$user->roleMenu->repair_job == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_repair_job">
                            Repair Job (Maintenance)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_preventive_maintenance" id="menu_preventive_maintenance" value="1"
                        {{ @$user->roleMenu->preventive_maintenance == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_preventive_maintenance">
                            Preventive Maintenance (Maintenance)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_user" id="menu_user"
                            value="1" {{ @$user->roleMenu->user == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_user">
                            Users (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_bank" id="menu_bank"
                            value="1" {{ @$user->roleMenu->bank == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_bank">
                            Bank (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <!-- <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_product_item" id="menu_product_item" value="1" {{ @$user->roleMenu->product_item == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_product_item">
                            Product Item (Setting)
                        </label>
                    </div>
                </div> -->
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_quantity" id="menu_quantity" value="1" {{ @$user->roleMenu->quantity == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_quantity">
                            Quantity (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_unit" id="menu_unit" value="1" {{ @$user->roleMenu->unit == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_unit">
                            Unit (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_prefix" id="menu_prefix" value="1" {{ @$user->roleMenu->prefix == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_prefix">
                            Prefix (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_bank_company" id="menu_bank_company" value="1" {{ @$user->roleMenu->bank_company == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_bank_company">
                            Bank Company (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_company_type" id="menu_company_type" value="1" {{ @$user->roleMenu->company_type == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_company_type">
                            Company Type (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_company_market" id="menu_company_market" value="1" {{ @$user->roleMenu->company_market == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_company_market">
                            Company Market (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_company_event" id="menu_company_event" value="1" {{ @$user->roleMenu->company_event == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_company_event">
                            Company Event (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_booking" id="menu_booking" value="1" {{ @$user->roleMenu->booking == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_booking">
                            Booking (Setting)
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_menu" name="menu_template" id="menu_template" value="1" {{ @$user->roleMenu->document_template_pdf == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="menu_template">
                            Template (Setting)
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-center mt-4">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <label for=""><b>สิทธิ์การใช้งานประเภทรายได้</b></label>
                </div>
                <div class="col-lg-2 col-md-8 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="select_revenue_all"
                            id="select_revenue_all" value="{{ @$user->roleRevenues->select_revenue_all }}" {{ @$user->roleRevenues->select_revenue_all == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="select_revenue_all">
                            เลือกทั้งหมด
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-center">
                <div class="col-lg-2">
                    <label for="">ประเภทรายได้</label>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="front_desk" id="front_desk"
                            value="1" {{ @$user->roleRevenues->front_desk == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="front_desk">
                            Front Desk Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="guest_deposit"
                            id="guest_deposit" value="1" {{ @$user->roleRevenues->guest_deposit == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="guest_deposit">
                            Guest Deposit Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="all_outlet" id="all_outlet"
                            value="1" {{ @$user->roleRevenues->all_outlet == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="all_outlet">
                            All Outlet Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="agoda" id="agoda"
                            value="1" {{ @$user->roleRevenues->agoda == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="agoda">
                            Agoda Revenue
                        </label>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="credit_card_hotel"
                            id="credit_card_hotel" value="1" {{ @$user->roleRevenues->credit_card_hotel == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="credit_card_hotel">
                            Credit Card Hotel Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="elexa" id="elexa"
                            value="1" {{ @$user->roleRevenues->elexa == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="elexa">
                            Elexa EGAT Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="water_park" id="water_park"
                            value="1" {{ @$user->roleRevenues->water_park == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="water_park">
                            Water Park Revenue
                        </label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="credit_water_park"
                            id="credit_water_park" value="1" {{ @$user->roleRevenues->credit_water_park == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="credit_water_park">
                            Credit Card Warter Park Revenue
                        </label>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="no_category"
                            id="no_category" value="1" {{ @$user->roleRevenues->no_category == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="no_category">
                            No Category
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="transfer" id="transfer"
                            value="1" {{ @$user->roleRevenues->transfer == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="transfer">
                            Transfer
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="time" id="time"
                            value="1" {{ @$user->roleRevenues->time == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="time">
                            Update Time
                        </label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="split" id="split"
                            value="1" {{ @$user->roleRevenues->split == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="split">
                            Split Revenue
                        </label>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select_revenue" name="edit" id="edit"
                            value="1" {{ @$user->roleRevenues->edit == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit">
                            Edit / Delete
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('users', 'index') }}">
                    <button type="button" class="defaultbtn button-5">
                        ยกเลิก
                    </button>
                </a>
                <div class="button-2">
                    <button type="submit" class="button-2">สร้าง</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#testselect2').select2();
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

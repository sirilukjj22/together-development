<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\RevenuesController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AgodaRevenuesController;

## New
use App\Http\Controllers\master_booking;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\master_product_i;
use App\Http\Controllers\Master_bank;
use App\Http\Controllers\Master_prefix;
use App\Http\Controllers\Master_Company_type;
use App\Http\Controllers\Master_market;
use App\Http\Controllers\freelancer_register;
use App\Http\Controllers\FreelancerMemberController;
use App\Http\Controllers\MasterEventFormatController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\Master_TemplateController;
use App\Http\Controllers\Master_Vat;
use App\Http\Controllers\DummyQuotationController;
use App\Http\Controllers\proposal_request;
use App\Http\Controllers\Document_invoice;
use App\Http\Controllers\BillingFolioController;
use App\Http\Controllers\ElexaController;
use App\Http\Controllers\Masterpromotion;
use App\Http\Controllers\Master_Address_System;
use App\Http\Controllers\UserDepartmentsController;
use App\Http\Controllers\ReceiveChequeController;
use App\Http\Controllers\confirmationrequest;
use App\Http\Controllers\Additional;
use App\Http\Controllers\LinkPDFProposal;
use App\Http\Controllers\ReportAuditRevenueDateController;
use App\Http\Controllers\ReportHotelManualChangeController;
use App\Http\Controllers\ReportHotelWaterparkRevenueController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('sms-forward', [SMSController::class, 'forward'])->name('sms-forward');
Route::get('sms-api-forward', [SMSController::class, 'forward'])->name('sms-api-forward');
Route::get('/Quotation/Quotation/cover/document/PDF/{id}', [LinkPDFProposal::class, 'proposal'])->name('Proposal.link');
Route::get('/Invoice/Quotation/cover/document/PDF/{id}', [LinkPDFProposal::class, 'invoice'])->name('Invoice.link');

Route::middleware(['auth'])->group(function () {

    # SMS Alert
    Route::controller(SMSController::class)->middleware('role:sms_alert')->group(function () {
        Route::get('sms-alert', 'index')->name('sms-alert');
        Route::get('sms-alert-refresh/{day}/{month}/{year}', 'index_refresh')->name('sms-alert-refresh');
        Route::get('sms-change-status/{id}/{status}', 'change_status')->name('sms-change-status');
        Route::post('sms-search-calendar', 'search_calendar')->name('sms-search-calendar');
        Route::get('sms-create', 'create')->name('sms-create');
        Route::post('sms-store', 'store')->name('sms-store');
        Route::get('sms-edit/{id}', 'edit')->name('sms-edit');
        Route::get('sms-delete/{id}', 'delete')->name('sms-delete');
        Route::get('sms-get-remark-other-revenue/{id}', 'get_other_revenue')->name('sms-get-remark-other-revenue');
        Route::post('sms-other-revenue', 'other_revenue')->name('sms-other-revenue');
        Route::post('sms-transfer', 'transfer')->name('sms-transfer');
        Route::get('sms-detail/{name}', 'detail')->name('sms-detail');
        Route::get('sms-update-time/{id}/{time}', 'update_time')->name('sms-update-time');
        Route::post('sms-update-split', 'update_split')->name('sms-update-split');
        Route::get('sms-agoda-receive-payment/{id}', 'receive_payment')->name('sms-agoda-receive-payment');

        // Graph
        Route::get('sms-graph-thisWeek/{date}/{type}/{account}', 'graphThisWeek')->name('sms-thisWeek');
        Route::get('sms-graph-thisMonth/{date}/{type}/{account}', 'graphThisMonth')->name('sms-thisMonth');
        Route::get('sms-graph-thisMonthByDay/{date}/{type}/{account}', 'graphThisMonthByDay')->name('sms-thisMonthByDay');
        Route::get('sms-graph-yearRange/{year}/{type}/{account}', 'graphYearRange')->name('sms-yearRange');
        Route::get('sms-graph-monthRange/{month}/{to_month}/{year}/{type}/{account}', 'graphMonthRange')->name('sms-monthRange');
        Route::get('sms-graph-daterang/{startdate}/{enddate}/{type}/{account}', 'graphDateRang')->name('sms-daterang');
        Route::get('sms-graph-daterang-detail/{startdate}/{enddate}/{type}/{account}', 'graphDateRangDetail')->name('sms-daterang-detail');
        Route::get('sms-graph30days/{date}/{type}/{account}', 'graph30days')->name('sms-graph30days');
        Route::get('sms-graphToday/{to_date}', 'graphToday')->name('sms-graphToday');
        Route::get('sms-graphForcast/{to_date}', 'graphForcast')->name('sms-graphForcast');

        // Table Search / Paginate
        Route::post('sms-search-table', 'search_table')->name('sms-search-table');
        Route::post('sms-paginate-table', 'paginate_table')->name('sms-paginate-table');
    });

    # Revenue
    Route::controller(RevenuesController::class)->middleware('role:revenue')->group(function () {
        Route::get('revenue', 'index')->name('revenue'); // By Type
        Route::get('revenue-department', 'index')->name('revenue-department'); // By Department
        Route::post('revenue-search-calendar', 'search_calendar')->name('revenue-search-calendar');
        Route::post('revenue-store', 'store')->name('revenue-store');
        Route::get('revenue-edit/{id}', 'edit')->name('revenue-edit');
        Route::get('revenue-export', 'export')->name('revenue-export');
        Route::post('revenue-detail', 'detail')->name('revenue-detail');
        Route::get('revenue-input-month/{month}', 'input_month')->name('revenue-input-month');
        Route::post('revenue-daily-close', 'daily_close')->name('revenue-daily-close');
        Route::post('revenue-daily-open', 'daily_open')->name('revenue-daily-open');

        // Table Search / Paginate
        Route::post('revenue-search-table', 'search_table')->name('revenue-search-table');
        Route::post('revenue-paginate-table', 'paginate_table')->name('revenue-paginate-table');
    });

    # Debit Agoda Revenue
    Route::controller(AgodaRevenuesController::class)->middleware('role:agoda')->group(function () {
        Route::get('debit-agoda', 'index')->name('debit-agoda');
        Route::get('debit-agoda-revenue/{month}/{year}', 'index_list_days')->name('debit-agoda-revenue');
        Route::get('debit-agoda-update/{month}/{year}', 'index_update_agoda')->name('debit-agoda-update');
        Route::get('debit-agoda-update-receive/{id}/{month}/{year}', 'index_receive')->name('debit-agoda-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-agoda-detail/{id}/{month}/{year}', 'index_detail_receive')->name('debit-agoda-detail'); // แสดงรายละเอียด
        Route::post('debit-agoda-store', 'receive_payment')->name('debit-agoda-store');
        Route::get('debit-select-agoda-outstanding/{id}', 'select_agoda_outstanding')->name('debit-select-agoda-outstanding');
        Route::get('debit-select-agoda-received/{id}', 'select_agoda_received')->name('debit-select-agoda-received');
        Route::get('debit-status-agoda-receive/{status}/{startDate}/{endDate}', 'status_agoda_receive')->name('debit-status-agoda-receive');
    });

    # Debit Elexa
    Route::controller(ElexaController::class)->middleware('role:elexa')->group(function () {
        Route::get('debit-elexa', 'index')->name('debit-elexa');
        Route::get('debit-elexa-revenue/{month}/{year}', 'index_list_days')->name('debit-elexa-revenue');
        Route::get('debit-elexa-update/{month}/{year}', 'index_update_elexa')->name('debit-elexa-update');
        Route::get('debit-elexa-update-receive/{id}/{month}/{year}', 'index_receive')->name('debit-elexa-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-elexa-detail/{id}/{month}/{year}', 'index_detail_receive')->name('debit-elexa-detail'); // แสดงรายละเอียด
        Route::post('debit-elexa-store', 'receive_payment')->name('debit-elexa-store');
        Route::get('debit-select-elexa-outstanding/{id}', 'select_elexa_outstanding')->name('debit-select-elexa-outstanding');
        // Route::get('debit-select-agoda-received/{id}', 'select_agoda_received')->name('debit-select-agoda-received');
        Route::get('debit-status-elexa-receive/{status}', 'status_elexa_receive')->name('debit-status-elexa-receive');
        Route::get('debit-elexa-search/{month}', 'search_month')->name('debit-elexa-search');
    });


    ## Master Data
    Route::controller(MasterController::class)->middleware('role:setting')->group(function () {
        Route::get('master/{menu}', 'index')->name('master');
        Route::get('master/check/{category}/{field}/{datakey}', 'validate_field');
        // Route::get('master/check2/{category}/{field}/{datakey}/{type_name}', [MasterController::class, 'validate_field2']);
        Route::get('master/check-edit/{id}/{category}/{field}/{datakey}', 'validate_field_edit');
        Route::get('master/check-dupicate-name/{category}/{datakey}/{type_name}', 'validate_dupicate_name');
        Route::get('master/check-dupicate-name-edit/{id}/{category}/{datakey}/{type_name}', 'validate_dupicate_name_edit');
        Route::post('master/store', 'store')->name('master-store');
        Route::get('master/edit/{id}', 'edit');
        Route::post('master-delete', 'delete');
        Route::get('master/change-status/{id}', 'change_status');
        Route::get('master/search-list/{category}/{name_th}/{type_name}', 'search_list');
        Route::get('master/search-list2/{category}/{name_th}/{type_name}', 'search_list2');
        Route::get('master/search-type/{category}/{name_th}/{type_name}', 'search_type');

        // Table Search / Paginate
        Route::post('master-search-table', 'search_table')->name('master-search-table');
        Route::post('master-paginate-table', 'paginate_table')->name('master-paginate-table');
    });


    ## Users
    Route::controller(UsersController::class)->middleware('role:user')->group(function () {
        Route::get('users/{menu}', 'index')->name('users');
        Route::get('user-create', 'create')->name('user-create');
        Route::get('user-edit/{id}', 'edit')->name('user-edit');
        Route::get('user-detail/{id}', 'detail')->name('user-detail');
        Route::get('user-search-department/{id}', 'search_department')->name('user-search-department');
        Route::post('user-update', 'update')->name('user-update');
        Route::post('user-delete', 'delete')->name('user-delete');
        Route::get('user/change-status/{id}', 'change_status');


        // Table Search / Paginate
        Route::post('user-search-table', 'search_table')->name('user-search-table');
        Route::post('user-paginate-table', 'paginate_table')->name('user-paginate-table');
    });
    // Add User
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post')->middleware('role:user');


    ## User Department
    Route::controller(UserDepartmentsController::class)->middleware('role:department')->group(function () {
        Route::get('user-department', 'index')->name('user-department');
        Route::get('user-department-create', 'create')->name('user-department-create');
        Route::get('user-department-edit/{id}', 'edit')->name('user-department-edit');
        Route::get('user-department-detail/{id}', 'detail')->name('user-department-detail');
        Route::post('user-department-store', 'store')->name('user-department-store');
        Route::post('user-department-update', 'update')->name('user-department-update');

        // Table Search / Paginate
        Route::post('user-department-search-table', 'search_table')->name('user-department-search-table');
        Route::post('user-department-paginate-table', 'paginate_table')->name('user-department-paginate-table');
    });

    ## Report
    Route::controller(ReportAuditRevenueDateController::class)->middleware('role:report')->group(function () {
        Route::get('report-audit-revenue-date', 'index')->name('report-audit-revenue-date');
        Route::post('report-audit-revenue-date-search', 'search')->name('report-audit-revenue-date-search');
        Route::post('report-audit-paginate-table', 'paginate_table')->name('report-audit-paginate-table');
        Route::post('report-audit-search-table', 'search_table')->name('report-audit-search-table');
    });

    Route::controller(ReportHotelWaterparkRevenueController::class)->middleware('role:report')->group(function () {
        Route::get('report-hotel-water-park-revenue', 'index')->name('report-hotel-water-park-revenue');
        Route::post('report-hotel-water-park-revenue-search', 'search')->name('report-hotel-water-park-revenue-search');
    });

    Route::controller(ReportHotelManualChangeController::class)->middleware('role:report')->group(function () {
        Route::get('report-hotel-manual-charge', 'index')->name('report-hotel-manual-charge');
        Route::post('report-hotel-manual-charge-search', 'search')->name('report-hotel-manual-charge-search');
    });

    ####################################################

    ## Master Booking Channal
    Route::controller(master_booking::class)->middleware('role:setting')->group(function () {
        Route::get('/Mbooking/{menu}', 'index')->name('Mbooking');
        Route::post('/Mbooking/master_booking/save', 'Mbookingsave')->name('Mbooking.save');
        Route::get('/Mbooking/update/{id}/{datakey}/{dataEN}/{code}', 'update')->name('Master.Mbooking_update');
        Route::get('/Mbooking/change-Status/{id}', 'changeStatus')->name('Master.changeStatus');
        Route::get('/Mbooking/edit/{id}','edit')->name('Mproduct.Mbooking.unit');
        Route::get('/Mbooking/search-list2/{datakey}','search')->name('Mproduct.Mbooking.search');
        Route::get('/Mbooking/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mbooking.dupicate');

        Route::get('/Mbooking/log/detail', 'log')->name('Mbooking.Log');

        Route::post('book-search-table', 'book_search_table')->name('book-search-table');
        Route::post('book-paginate-table', 'book_paginate_table')->name('book-paginate-table');

        Route::post('book-Log-search-table', 'book_search_table_paginate_log');
        Route::post('book-Log-paginate-table', 'book_paginate_log_table');
    });

    ## Company
    Route::controller(CompanyController::class)->middleware('role:company')->group(function () {
        Route::get('/Company/{menu}', 'index')->name('Company');
        Route::get('/Company-create', 'create')->name('Company.create');
        Route::get('/Company/amphures/{id}', 'amphures')->name('Company.amphures');
        Route::get('/Company/districts/{id}', 'district')->name('Company.districts');
        Route::get('/Company/Tambon/{id}', 'Tambon')->name('Company.Tambon');
        Route::get('/Company/edit/{id}', 'edit')->name('Company.edit');
        Route::post('/Company/save', 'save')->name('Company.save');
        Route::get('/Company/change-status/{id}', 'changeStatus')->name('Company.changeStatus');
        Route::get('/Company/view/{id}', 'view')->name('Company.view');
        Route::post('/Company/Company_edit/Company_update/{id}', 'update')->name('Company_update');
        Route::get('/Company/provinces/{id}', 'provinces')->name('Company.provinces');
        Route::get('/Company/amphuresA/{id}', 'amphuresA')->name('Company.amphuresAgent');
        Route::get('/Company/TambonA/{id}', 'TambonA')->name('Company.TambonAgent');
        Route::get('/Company/districtsA/{id}', 'districtA')->name('Company.districtAgent');
        Route::get('/Company/amphuresT/{id}', 'amphuresT')->name('Company.amphuresT');
        Route::get('/Company/TambonT/{id}', 'TambonT')->name('Company.TambonT');
        Route::get('/Company/districtT/{id}', 'districtT')->name('Company.districtT');

        Route::get('/Company/amphures/Contact/{id}', 'amphuresContact')->name('Company.amphuresContact');
        Route::get('/Company/districts/Contact/{id}', 'districtContact')->name('Company.districtsContact');
        Route::get('/Company/Tambon/Contact/{id}', 'TambonContact')->name('Company.TambonContact');
        Route::post('/Company/check/company', 'SearchContact');
        Route::get('/Company/edit/contact/{id}', 'contactedit')->name('Company.contact.edit');
        Route::post('/Company/edit/contact/editcontact/update/{id}', 'contactupdate')->name('Company.contact.update');


        Route::post('/Company/save/Tax/{id}', 'Tax')->name('Company.Tax');
        Route::get('/company/change-status/tax/{id}', 'changeStatustax')->name('Company.tax.changeStatus');
        Route::get('/Company/viewTax/{id}', 'viewTax')->name('Company.viewTax');
        Route::get('/Company/editTax/{id}', 'editTax')->name('Company.viewTax');
        Route::post('/Company/editTax/update/{Comid}/{id}', 'updatetax')->name('Company.updatetax');

        Route::get('/Company/index/contact/{id}', 'contact')->name('Company.contact.index');
        Route::post('/Company/edit/contact/create/{id}', 'contactcreate')->name('contact.update');
        Route::get('/company/change-status/Contact/{id}', 'changeStatuscontact')->name('Company.contact.changeStatus');
        Route::get('/Company/view/contact/{id}', 'contactview')->name('Company.contact.view');

        Route::post('company-search-table', 'company_search_table');
        Route::post('company-paginate-table', 'company_paginate_table');

        Route::post('tax-company-search-table', 'search_table_company');
        Route::post('tax-company-paginate-table', 'paginate_table_company');

        Route::post('Visit-company-search-table', 'search_table_company_Visit');
        Route::post('Visit-company-paginate-table', 'paginate_table_company_Visit');

        Route::post('Contact-company-search-table', 'search_table_company_Contact');
        Route::post('Contact-company-paginate-table', 'paginate_table_company_Contact');

        Route::post('Log-company-search-table', 'search_table_company_Log');
        Route::post('Log-company-paginate-table', 'paginate_table_company_Log');
    });

    Route::controller(GuestController::class)->middleware('role:guest')->group(function () {
        Route::get('/guest/{menu}', 'index')->name('guest');
        Route::get('/guest-create', 'create')->name('guestcreate');
        Route::get('/guest/amphures/{id}', 'amphures')->name('guest.amphures');
        Route::get('/guest/districts/{id}', 'district')->name('guest.districts');
        Route::get('/guest/Tambon/{id}', 'Tambon')->name('guest.Tambon');
        Route::get('/guest/amphuresT/{id}', 'amphuresT')->name('guest.amphuresT');
        Route::get('/guest/TambonT/{id}', 'TambonT')->name('guest.TambonT');
        Route::get('/guest/districtT/{id}', 'districtT')->name('guest.districtT');
        Route::get('/guest/edit/{id}', 'guest_edit')->name('guest_edit');
        Route::post('/guest/save', 'guestsave')->name('saveguest');
        Route::get('/guest/change-status/{id}', 'guestStatus')->name('guestStatus');
        Route::post('/guest/edit/update/{id}', 'guest_update')->name('guest_edit_update');

        Route::post('guest-search-table', 'search_table')->name('guest-search-table');
        Route::post('guest-paginate-table', 'paginate_table')->name('guest-paginate-table');

        Route::post('logguest-search-table', 'search_table_log')->name('guest-search-table_log');
        Route::post('logguest-paginate-table', 'paginate_table_log')->name('guest-paginate-table_log');
        //--------------------------------เพิ่ม ซับ ------------------------
        Route::post('/guest/save/cover/{id}', 'guest_cover')->name('guest_cover');
        Route::get('/guest/change-status/tax/{id}', 'guestStatustax')->name('guestStatustax');

        Route::post('tax-guest-search-table', 'search_table_guest')->name('guest-search-table_guest');
        Route::post('tax-guest-paginate-table', 'paginate_table_guest')->name('guest-paginate-table_guest');

        Route::get('/guest/view/{id}', 'view')->name('guest_view');
        Route::get('/guest/Tax/edit/{id}', 'guest_edit_tax')->name('guest_edit_tax');
        Route::post('/guest/tax/edit/update/{id}', 'guest_update_tax')->name('guest_update_tax');
        Route::get('/guest/Tax/view/{id}', 'guest_view_tax')->name('guest_view_tax');

        Route::post('Visit-guest-search-table', 'search_table_guest_Visit');
        Route::post('Visit-guest-paginate-table', 'paginate_table_guest_Visit');
    });
});
    #master product
    Route::controller(master_product_i::class)->middleware('role:product_item')->group(function() {
        Route::get('/Mproduct/index','index')->name('Mproduct.index');
        Route::get('/Mproduct/create','create')->name('Mproduct.create');
        Route::get('/Mproduct/ac','ac')->name('Mproduct.ac');
        Route::get('/Mproduct/no','no')->name('Mproduct.no');
        Route::get('/Mproduct/Room_Type','Room_Type')->name('Mproduct.Room_Type');
        Route::get('/Mproduct/Banquet','Banquet')->name('Mproduct.Banquet');
        Route::get('/Mproduct/Meals','Meals')->name('Mproduct.Meals');
        Route::get('/Mproduct/Entertainment','Entertainment')->name('Mproduct.Entertainment');
        Route::get('/Mproduct/edit/{id}','edit')->name('Mproduct.edit');
        Route::get('/Mproduct/view/{id}','view')->name('Mproduct.view');
        Route::post('/Mproduct/Save','save')->name('Mproduct.save');
        Route::get('/Mproduct/change-Status/{id}','changeStatus')->name('Mproduct.changeStatus');
        Route::post('/Mproduct/master_Mproduct/Mproduct_update/{id}','update')->name('Mproduct.update');
        Route::post('/Mproduct/check/Category','Category')->name('Mproduct.Category');
        Route::get('/Mproduct/delete/{id}','delete')->name('Mproduct.delete');
        Route::get('/Mproduct/log/detail', 'product_log')->name('Mproduct.Log');
        //----------------------------
        Route::post('product-search-table', 'search_table_product');
        Route::post('product-paginate-table', 'paginate_table_product');
        //----------------------------
        Route::post('productroom-search-table', 'search_table_productroom');
        Route::post('productroom-paginate-table', 'paginate_table_productroom');
        //----------------------------
        Route::post('productBanquet-search-table', 'search_table_productBanquet');
        Route::post('productBanquet-paginate-table', 'paginate_table_productBanquet');
        //----------------------------
        Route::post('productMeals-search-table', 'search_table_productMeals');
        Route::post('productMeals-paginate-table', 'paginate_table_productMeals');
        //----------------------------
        Route::post('productEntertainment-search-table', 'search_table_productEntertainment');
        Route::post('productEntertainment-paginate-table', 'paginate_table_productEntertainment');

        Route::post('product-Log-search-table', 'product_search_table_paginate_log');
        Route::post('product-Log-paginate-table', 'product_paginate_log_table');
        // ----------------------------------Quantity-----------------------------------------------
        Route::get('/Mproduct/Quantity/{menu}','index_quantity')->name('Quantity');
        Route::post('/Mproduct/Quantity/Save','save_quantity')->name('Mproduct.save.quantity');
        Route::get('/Mproduct/Quantity/edit/{id}','edit_quantity')->name('Mproduct.edit.quantity');
        Route::get('/Mproduct/changeStatus_quantity/{id}','changeStatus_quantity')->name('Mproduct.changeStatus_quantity');
        Route::get('/Mproduct/quantity/search-list2/{datakey}','searchquantity')->name('Mproduct.quantity.search');
        Route::get('/Mproduct/quantity/check-edit-name/{id}/{datakey}','dupicatequantity')->name('Mproduct.quantity.dupicate');
        Route::get('/Mproduct/quantity/update/{id}/{datakey}/{dataEN}','update_quantity')->name('Mproduct.update.quantity');
        Route::get('/Mproduct/Quantity/log/detail', 'quantity_log')->name('Quantity.Log');

        Route::post('quantity-search-table', 'quantity_search_table')->name('quantity-search-table');
        Route::post('quantity-paginate-table', 'quantity_paginate_table')->name('quantity-paginate-table');

        Route::post('quantity-Log-search-table', 'quantity_search_table_paginate_log');
        Route::post('quantity-Log-paginate-table', 'quantity_paginate_log_table');
        //----------------------------------Unit-----------------------------------------------------
        Route::get('/Mproduct/Unit/{menu}','index_unit')->name('Unit');
        Route::post('/Mproduct/Unit/Save','save_unit')->name('Mproduct.save.unit');
        Route::get('/Mproduct/Unit/edit/{id}','edit_unit')->name('Mproduct.edit.unit');
        Route::get('/Mproduct/changeStatus_unit/{id}','changeStatus_unit')->name('Mproduct.changeStatus_unit');
        Route::get('/Mproduct/Unit/search-list2/{datakey}','search')->name('Mproduct.unit.search');
        Route::get('/Mproduct/Unit/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.unit.dupicate');
        Route::get('/Mproduct/Unit/update/{id}/{datakey}/{dataEN}','update_unit')->name('Mproduct.update.unit');

        Route::get('/Mproduct/Unit/log/detail', 'unit_log')->name('Unit.Log');

        Route::post('unit-search-table', 'unit_search_table')->name('unit-search-table');
        Route::post('unit-paginate-table', 'unit_paginate_table')->name('unit-paginate-table');

        Route::post('unit-Log-search-table', 'unit_search_table_paginate_log');
        Route::post('unit-Log-paginate-table', 'unit_paginate_log_table');
    });

    #master prefix
    Route::controller(Master_prefix::class)->middleware('role:prefix')->group(function() {
        Route::get('/Mprefix/{menu}','index')->name('Mprefix');
        Route::get('/Mprefix/ac','ac')->name('Mprefix.ac');
        Route::get('/Mprefix/no','no')->name('Mprefix.no');
        Route::post('/Mprefix/Save','save')->name('Mprefix.save');
        Route::get('/Mprefix/change-Status/{id}','changeStatus')->name('Mcomt.changeStatus');
        Route::get('/Mprefix/Mprename/Mprefix_update/{id}/{datakey}/{dataEN}','update')->name('Mprefix.update');
        Route::get('/Mprefix/Mprename/edit/{id}','edit')->name('Mproduct.edit.Mprefix');
        Route::get('/Mprefix/Mprename/search-list2/{datakey}','searchMprename')->name('Mproduct.Mprefix.search');
        Route::get('/Mprefix/Mprename/check-edit-name/{id}/{datakey}','dupicateMprename')->name('Mproduct.Mprefix.dupicate');

        Route::get('/Mprefix/log/detail', 'log')->name('Mprefix.Log');

        Route::post('prename-search-table', 'prename_search_table')->name('prename-search-table');
        Route::post('prename-paginate-table', 'prename_paginate_table')->name('prename-paginate-table');

        Route::post('prename-Log-search-table', 'prename_search_table_paginate_log');
        Route::post('prename-Log-paginate-table', 'prename_paginate_log_table');


    });

    #master promotion
    Route::controller(Masterpromotion::class)->group(function() {
        Route::get('/Mpromotion/{menu}', 'index')->name('Mpromotion');
        Route::post('/Mpromotion/Save','save')->name('Mpromotion.save');
        Route::get('/Mpromotion/delete/{id}','delete')->name('Mpromotion.delete');
        Route::get('/Mpromotion/change-status/{id}', 'status')->name('Mpromotion.status');
        Route::post('Mpromotion-search-table', 'search_table')->name('Mpromotion-search-table');
        Route::post('Mpromotion-paginate-table', 'paginate_table')->name('Mpromotion-paginate-table');
        Route::get('/Mpromotion/log/detail', 'log')->name('Mpromotion.Log');

        Route::post('promotion-Log-search-table', 'search_table_paginate_log');
        Route::post('promotion-Log-paginate-table', 'paginate_log_table');
    });

    #master company type
    Route::controller(Master_Company_type::class)->middleware('role:company_type')->group(function() {
        Route::get('/Mcomt/{menu}','index')->name('Mcomt');
        Route::post('/Mcomt/Save','save')->name('Mcomt.save');
        Route::get('/Mcomt/change-Status/{id}','changeStatus')->name('Mcomt.changeStatus');
        Route::get('/Mcomt/update/{id}/{datakey}/{dataEN}','update')->name('Mcomt.update');
        Route::get('/Mcomt/edit/{id}','edit')->name('Mproduct.edit.Mcomt');
        Route::get('/Mcomt/search-list2/{datakey}','search')->name('Mproduct.Mcomt.search');
        Route::get('/Mcomt/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mcomt.dupicate');

        Route::get('/Mcomt/log/detail', 'log')->name('Mcomt.Log');

        Route::post('Mcomt-search-table', 'mcomt_search_table')->name('mcomt-search-table');
        Route::post('Mcomt-paginate-table', 'mcomt_paginate_table')->name('mcomt-paginate-table');

        Route::post('Mcomt-Log-search-table', 'mcomt_search_table_paginate_log');
        Route::post('Mcomt-Log-paginate-table', 'mcomt_paginate_log_table');
    });
    #master market
    Route::controller(Master_market::class)->middleware('role:company_market')->group(function() {
        Route::get('/Mmarket/{menu}','index')->name('Mmarket');
        Route::post('/Mmarket/Save','save')->name('Mmarket.save');
        Route::get('/Mmarket/change-Status/{id}','changeStatus')->name('Mmarket.changeStatus');
        Route::post('/Mmarket/update/{id}/{datakey}/{dataEN}','update')->name('Mmarket.update');
        Route::get('/Mmarket/edit/{id}','edit')->name('Mproduct.edit.Mmarket');
        Route::get('/Mmarket/search-list2/{datakey}','search')->name('Mproduct.Mmarket.search');
        Route::get('/Mmarket/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mmarket.dupicate');

        Route::get('/Mmarket/log/detail', 'log')->name('Mmarket.Log');

        Route::post('Mmarket-search-table', 'Mmarket_search_table')->name('Mmarket-search-table');
        Route::post('Mmarket-paginate-table', 'Mmarket_paginate_table')->name('Mmarket-paginate-table');

        Route::post('Mmarket-Log-search-table', 'Mmarket_search_table_paginate_log');
        Route::post('Mmarket-Log-paginate-table', 'Mmarket_paginate_log_table');
    });

    #Freelancer Check
    Route::controller(freelancer_register::class)->middleware('role:freelancer')->group(function() {
        Route::get('/Freelancer/checked/index','index')->name('freelancer.index');
        Route::get('/Freelancer/checked/create','create')->name('freelancer.create');
        Route::get('/Freelancer/checked/create/amphures/{id}','amphures')->name('freelancer.amphures');
        Route::get('/Freelancer/checked/create/districts/{id}','district')->name('freelancer.districts');
        Route::get('/Freelancer/checked/create/Tambon/{id}','Tambon')->name('freelancer.Tambon');
        Route::post('/Freelancer/check/save','save')->name('freelancer.save');
        Route::post('/Freelancer/check/update/{id}','update')->name('freelancer.update');
        Route::get('/Freelancer/checked/change-status/{id}','changeStatus')->name('freelancer.changeStatus');
        Route::get('/Freelancer/checked/ac','ac')->name('freelancer.ac');
        Route::get('/Freelancer/checked/no','no')->name('freelancer.no');
        Route::get('/Freelancer/checked/ap','ap')->name('freelancer.ap');
        Route::get('/Freelancer/checked/Approve/{id}','delete');
        Route::get('/Freelancer/check/edit/{id}','edit')->name('freelancer.edit');
        Route::get('/Freelancer/check/view/{id}','view')->name('freelancer.view');
    });

    #Freelancer Member
    Route::controller(FreelancerMemberController::class)->middleware('role:freelancer')->group(function() {
        Route::get('/Freelancer/member/index','index_member')->name('freelancer_member.index');
        Route::get('/Freelancer/member/view/{id}','viewmember')->name('freelancer_member.view');
        Route::get('/Freelancer/member/edit/{id}','editmember')->name('freelancer_member.edit');
        Route::post('/Freelancer/member/save/update/{id}','updatefreelancermember')->name('updatefreelancermember');
        Route::get('/Freelancer/member/order_list/{id}','order_list')->name('freelancer_member.Quotation');
        Route::post('/Freelancer/member/get-representative','getRepresentative')->name('get.representative');
        Route::post('/Freelancer/member/order_list/save/{id}','order_listsave')->name('quotationsave');
        Route::get('/Freelancer/member/view/data/{Freeid}/{Comid}','viewdatamember')->name('freelancer_member.viewdata');
        Route::get('/Freelancer/member/ac','ac')->name('freelancer_member.ac');
        Route::get('/Freelancer/member/no','no')->name('freelancer_member.no');
        Route::post('/Freelancer/member/change-status/','changeStatusmember')->name('freelancer.changeStatusmember');
        //boss
        Route::get('/Freelancer/boss/examine/viewcompany','examine')->name('freelancer.boss.examine');
        Route::get('/Freelancer/boss/view/data/{id}','viewdataexamine')->name('freelancer.boss.viewdata.examine');
        Route::get('/Freelancer/boss/examine/status{id}','examinestatus')->name('freelancer.boss.examine.status');
        //Employee
        Route::get('/Freelancer/employee/examine/viewcompany','examineemployee')->name('freelancer.employee.examine');
        Route::get('/Freelancer/employee/view/data/{id}','viewdataexamineemployee')->name('freelancer.employee.viewdata.examine');
        Route::get('/Freelancer/employee/examine/status{id}','examinestatusemployee')->name('freelancer.employee.examine.status');
    });

    # Master Event Formate
    Route::controller(MasterEventFormatController::class)->middleware('role:company_event')->group(function () {
        Route::get('/MEvent/{menu}', 'index')->name('MEvent');
        Route::post('/MEvent/Event_Formate/save', 'save')->name('MEvent.save');
        Route::get('/MEvent/update/{id}/{datakey}/{dataEN}', 'update')->name('MEvent.update');
        Route::get('/MEvent/change-Status/{id}','changeStatus')->name('MEvent.changeStatus');
        Route::get('/MEvent/edit/{id}','edit')->name('Mproduct.edit.MEvent');
        Route::get('/MEvent/search-list2/{datakey}','search')->name('Mproduct.MEvent.search');
        Route::get('/MEvent/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.MEvent.dupicate');

        Route::get('/MEvent/log/detail', 'log')->name('MEvent.Log');

        Route::post('MEvent-search-table', 'mevent_search_table')->name('mevent-search-table');
        Route::post('MEvent-paginate-table', 'mevent_paginate_table')->name('mevent-paginate-table');

        Route::post('MEvent-Log-search-table', 'mevent_search_table_paginate_log');
        Route::post('MEvent-Log-paginate-table', 'mevent_paginate_log_table');
    });

    Route::controller(Master_Vat::class)->group(function () {
        Route::get('/Mvat/{menu}', 'index')->name('Mvat');
        Route::post('/Mvat/Event_Formate/save', 'save')->name('Mvat.save');
        Route::get('/Mvat/update/{id}/{datakey}/{dataEN}', 'update')->name('Mvat.update');
        Route::get('/Mvat/change-Status/{id}','changeStatus')->name('Mvat.changeStatus');
        Route::get('/Mvat/edit/{id}','edit')->name('Mproduct.edit.Mvat');
        Route::get('/Mvat/search-list2/{datakey}','search')->name('Mproduct.Mvat.search');
        Route::get('/Mvat/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mvat.dupicate');

        Route::get('/Mvat/log/detail', 'log')->name('Mvat.Log');

        Route::post('Mvat-search-table', 'mvat_search_table')->name('mvat-search-table');
        Route::post('Mvat-paginate-table', 'mvat_paginate_table')->name('mvat-paginate-table');

        Route::post('Mvat-Log-search-table', 'mvat_search_table_paginate_log');
        Route::post('Mvat-Log-paginate-table', 'mvat_paginate_log_table');
    });

    #Quotation
    Route::controller(QuotationController::class)->middleware('role:document')->group(function () {
        Route::get('/Proposal/index', 'index')->name('Proposal.index');
        Route::get('/Proposal/create', 'create')->name('Proposal.create');
        Route::get('/Proposal/ac', 'ac')->name('Proposal.ac');
        Route::get('/Proposal/no', 'no')->name('Proposal.no');
        Route::get('/Proposal/create/company/{companyID}','Contactcreate')->name('Proposal.Contactcreate');
        Route::get(' /Proposal/create/Guest/{Guest}','Guestcreate')->name('Proposal.Guestcreate');
        Route::post('/Proposal/create/save', 'save')->name('Proposal.save');
        Route::get('/Proposal/selectproduct/company/create/{id}', 'selectProduct')->name('Proposal.SelectProduct');
        Route::post('/Proposal/company/create/quotation/{Quotation_ID}', 'savequotation')->name('Proposal.quotation');
        Route::get('/Proposal/edit/quotation/{id}','edit')->name('Proposal.edit');

        Route::get('/Proposal/change-Status/{id}/{status}','changestatus')->name('Proposal.changestatus');
        Route::post('/Proposal/edit/company/quotation/update/{id}', 'update')->name('Proposal.update');
        Route::get('/Proposal/company/product/{Quotation_ID}/addProduct', 'addProduct')->name('Proposal.addProduct');
        //----------------------------------Quotaion select product------------------------------------------------------
        Route::get('/Proposal/selectproduct/{Quotation_ID}/addProducttable', 'addProducttable')->name('Proposal.addProducttable');
        Route::get('/Proposal/selectproduct/{Quotation_ID}/addProducttableselect', 'addProducttableselect')->name('Proposal.addProducttableselect');
        Route::get('/Proposal/selectproduct/{Quotation_ID}/addProducttablemain', 'addProducttablemain')->name('Proposal.addProducttablemain');
        Route::get('/Proposal/selectproduct/{Quotation_ID}/addProductselect', 'addProductselect')->name('Proposal.addProductselect');
        Route::get('/Proposal/selectproduct/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('Proposal.addProducttablecreatemain');
        //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
        Route::get('/Proposal/Quotation/cover/document/PDF/{id}', 'sheetpdf')->name('Proposal.sheet');
        //--------------------------------------ลูกค้ายืนยัน------------------------------------------------------
        Route::get('/Proposal/Request/document/Approve/guest/{id}', 'Approve')->name('Proposal.Approve');
        //---------------------------------------LOG-----------------------------------------------------------
        Route::get('/Proposal/view/quotation/LOG/{id}','LOG')->name('Proposal.LOG');
        //-------------------------------------------------------------.
        Route::post('/Proposal/cancel/{id}','cancel')->name('Proposal.cancel');
        //--------------------------------------------------------------
        Route::get('/Proposal/Revice/{id}','Revice')->name('Proposal.Revice');
        //--------------------------------------------------------------
        Route::post('/Proposal/preview/document/PDF/', 'preview')->name('Proposal.preview');

        Route::get('/Proposal/send/email/{id}', 'email')->name('Proposal.email');

        Route::post('/Proposal/send/detail/email/{id}', 'sendemail')->name('Proposal.sendemail');

        Route::get('/Proposal/view/{id}','view')->name('Proposal.view');

        Route::get('/Proposal/Search/All', 'SearchAll')->name('Proposal.Search');

        Route::get('/Proposal/viewproposal/{id}','viewproposal')->name('Proposal.viewproposal');
        //----------------------------
        Route::post('Proposal-search-table', 'search_table_proposal');
        Route::post('Proposal-paginate-table', 'paginate_table_proposal');
        //--------------------------pending---------
        Route::post('Proposal-Pending-search-table', 'search_table_paginate_pending');
        Route::post('Proposal-Pending-paginate-table', 'paginate_pending_table_proposal');
        //--------------------------Awaiting---------
        Route::post('Proposal-Awaiting-search-table', 'search_table_paginate_awaiting');
        Route::post('Proposal-Awaiting-paginate-table', 'paginate_awaiting_table_proposal');
         //--------------------------Approved---------
        Route::post('Proposal-Approved-search-table', 'search_table_paginate_approved');
        Route::post('Proposal-Approved-paginate-table', 'paginate_approved_table_proposal');
        //--------------------------Reject-----------
        Route::post('Proposal-Reject-search-table', 'search_table_paginate_reject');
        Route::post('Proposal-Reject-paginate-table', 'paginate_reject_table_proposal');
        //--------------------------Cancel-----------
        Route::post('Proposal-Cancel-search-table', 'search_table_paginate_cancel');
        Route::post('Proposal-Cancel-paginate-table', 'paginate_cancel_table_proposal');
        //--------------------------LogPdf-----------
        Route::post('Proposal-Log-search-table', 'search_table_paginate_log_pdf');
        Route::post('Proposal-Log-paginate-table', 'paginate_log_pdf_table_proposal');
        //--------------------------LogDoc-----------
        Route::post('Proposal-LogDoc-search-table', 'search_table_paginate_log_doc');
        Route::post('Proposal-LogDoc-paginate-table', 'paginate_log_doc_table_proposal');

        Route::post('Proposal-Complete-search-table', 'search_table_paginate_complete');
        Route::post('Proposal-Complete-paginate-table', 'paginate_complete_table_proposal');
    });

    #DummyQuotaion
    Route::controller(DummyQuotationController::class)->middleware('role:document')->group(function () {
        Route::get('/Dummy/Proposal/index', 'index')->name('DummyQuotation.index');
        Route::get('/Dummy/Proposal/create', 'create')->name('DummyQuotation.create');
        Route::get('/Dummy/Proposal/create/company/{companyID}','Contactcreate')->name('DummyQuotation.Contactcreate');
        Route::get('/Dummy/Proposal/create/Guest/{Guest}','Guestcreate')->name('DummyQuotation.Guestcreate');
        Route::post('/Dummy/Proposal/create/save', 'save')->name('DummyQuotation.save');
        Route::get('/Dummy/Proposal/selectproduct/company/create/{id}', 'selectProduct')->name('DummyQuotation.SelectProduct');
        Route::post('/Dummy/Proposal/company/create/quotation/{Quotation_ID}', 'savequotation')->name('DummyQuotation.quotation');

        Route::get('/Dummy/Proposal/change-Status/{id}/{status}','changestatus')->name('DummyQuotation.changestatus');
        Route::post('/Dummy/Proposal/edit/company/quotation/update/{id}', 'update')->name('DummyQuotation.update');
        Route::get('/Dummy/Proposal/company/product/{Quotation_ID}/addProduct', 'addProduct')->name('DummyQuotation.addProduct');
        //----------------------------------Quotaion select product------------------------------------------------------
        Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttable', 'addProducttable')->name('DummyQuotation.addProducttable');
        Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttableselect', 'addProducttableselect')->name('DummyQuotation.addProducttableselect');
        Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttablemain', 'addProducttablemain')->name('DummyQuotation.addProducttablemain');
        Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProductselect', 'addProductselect')->name('DummyQuotation.addProductselect');
        Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('DummyQuotation.addProducttablecreatemain');
        Route::get('/Dummy/Proposal/edit/quotation/{id}','edit')->name('DummyQuotation.edit');
        Route::get('/Dummy/Proposal/cancel/{id}','Cancel')->name('DummyQuotation.cancel');
        Route::get('/Dummy/Proposal/Generate/{id}','Generate')->name('DummyQuotation.Generate');
        //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
        Route::get('/Dummy/Proposal/cover/document/PDF/{id}', 'sheetpdf')->name('DummyQuotation.sheet');
        //-----------------------------------ส่งเอกสาร-----------------------------------------------------------------------
        Route::post('/Dummy/Proposal/send/documents', 'senddocuments')->name('DummyQuotation.senddocuments');

        Route::get('/Dummy/Proposal/view/{id}','view')->name('DummyQuotation.view');

        Route::get('/Dummy/Proposal/view/quotation/LOG/{id}','LOG')->name('DummyQuotation.LOG');

        Route::get('/Dummy/Proposal/Revice/{id}','Revice')->name('Quotation.Revice');

        Route::get('/Dummy/Proposal/Search/All', 'SearchAll')->name('DummyProposal.Search');

        //----------------------------
        Route::post('DummyProposal-search-table', 'search_table_dummyproposal');
        Route::post('DummyProposal-paginate-table', 'paginate_table_dummyproposal');

        //--------------------------LogDoc-----------
        Route::post('DummyProposal-LogDoc-search-table', 'search_table_paginate_log_doc_dummyproposal');
        Route::post('DummyProposal-LogDoc-paginate-table', 'paginate_log_doc_table_dummyproposal');
         //--------------------------pending---------
        Route::post('DummyProposal-Pending-search-table', 'search_table_paginate_pending');
        Route::post('DummyProposal-Pending-paginate-table', 'paginate_pending_table_proposal');
        //--------------------------Awaiting---------
        Route::post('DummyProposal-Awaiting-search-table', 'search_table_paginate_awaiting');
        Route::post('DummyProposal-Awaiting-paginate-table', 'paginate_awaiting_table_proposal');
        //--------------------------Approved---------
        Route::post('DummyProposal-Approved-search-table', 'search_table_paginate_approved');
        Route::post('DummyProposal-Approved-paginate-table', 'paginate_approved_table_proposal');
        //--------------------------Generate---------
        Route::post('DummyProposal-Generate-search-table', 'search_table_paginate_generate');
        Route::post('DummyProposal-Generate-paginate-table', 'paginate_generate_table_proposal');
        //--------------------------Reject-----------
        Route::post('DummyProposal-Reject-search-table', 'search_table_paginate_reject');
        Route::post('DummyProposal-Reject-paginate-table', 'paginate_reject_table_proposal');
        //--------------------------Cancel-----------
        Route::post('DummyProposal-Cancel-search-table', 'search_table_paginate_cancel');
        Route::post('DummyProposal-Cancel-paginate-table', 'paginate_cancel_table_proposal');
    });

    #Proposal Request
    Route::controller(proposal_request::class)->middleware('role:document')->group(function () {
        Route::get('/Proposal/request/index', 'index')->name('ProposalReq.index');
        Route::get('/Dummy/Proposal/Request/document/view/{id}/{Type}', 'view')->name('ProposalReq.view');
        Route::post('/Dummy/Proposal/Request/document/view/Approve/', 'Approve')->name('DummyQuotation.Approve');
        Route::post('/Dummy/Proposal/Request/document/view/Reject/', 'Reject')->name('DummyQuotation.Reject');
        Route::get('/Dummy/Proposal/Request/document/view/Approve/viewApprove/{id}','viewApprove')->name('DummyQuotation.viewApprove');
        Route::get('/Proposal/request/log', 'LOG')->name('ProposalReq.log');

        Route::get('/Proposal/request/document/Additional/view/{id}', 'Additional')->name('ProposalReq.Additional');

        Route::post('/Proposal/request/Request/document/view/Approve/', 'Additional_Approve')->name('ProposalReq.Approve');
        Route::post('/Proposal/request/Request/document/view/Reject/', 'Additional_Reject')->name('ProposalReq.Reject');
        Route::get('/Proposal/request/Additional/log', 'Additional_LOG')->name('ProposalReq.LogAdditional');

        //----------------------------
        Route::post('Proposal-request-search-table', 'search_table_proposal');
        Route::post('Proposal-request-paginate-table', 'paginate_table_proposal');

        Route::post('Proposal-LogDoc-request-search-table', 'search_table_paginate_log_doc');
        Route::post('Proposal-LogDoc-request-paginate-table', 'paginate_log_doc_table_proposal');

        Route::post('request-Pending-search-table', 'search_table_paginate_pending');
        Route::post('request-Pending-paginate-table', 'paginate_pending_table_request');

        Route::post('Proposal-request-Additional-search-table', 'search_table_Additional');
        Route::post('Proposal-request-Additional-paginate-table', 'paginate_table_Additional');

        Route::post('Additional-LogDoc-request-search-table', 'search_table_paginate_log_doc_Additional');
        Route::post('Additional-LogDoc-request-paginate-table', 'paginate_log_doc_table_Additional');


    });

    ##-------------------------------TemplateController-----------------
    Route::controller(Master_TemplateController::class)->middleware('role:setting')->group(function () {
        Route::get('/Template/PDF/Template', 'TemplateA1')->name('Template.TemplateA1');
        Route::post('/Template/PDF/Template/save', 'save')->name('Template.save');
        Route::post('/Template/PDF/document/sheet/savetemplate','savesheet')->name('Template.savesheet');
    });

    ##-------------------------------document invoice-----------------
    Route::controller(Document_invoice::class)->middleware('role:document')->group(function () {
        Route::get('/Document/invoice/index', 'index')->name('invoice.index');
        Route::get('/Document/invoice/Generate/{id}','Generate')->name('invoice.Generate');
        Route::get('/Document/invoice/Generate/Additional/{id}','Generate_Additional')->name('invoice.Generate_Additional');
        Route::post('/Document/invoice/Generate/save', 'save')->name('invoice.save');
        Route::get('/Invoice/cover/document/PDF/{id}','export')->name('invoice.export');
        Route::get('/Document/Request/document/Approve/invoice/{id}', 'Approve')->name('invoice.Approve');
        Route::get('/Document/invoice/Delete/{id}','Delete')->name('invoice.Delete');
        Route::get('/Document/invoice/view/{id}','view')->name('invoice.view');
        Route::get('/Document/invoice/revised/{id}','edit')->name('invoice.edit');
        Route::post('/Document/invoice/update/revised/{id}', 'update')->name('invoice.revised');
        Route::get('/Document/invoice/receive/{id}','receive')->name('invoice.receive');
        Route::post('/Document/invoice/receive/check/payment/{id}', 'payment')->name('invoice.payment');
        Route::get('/Document/invoice/view/LOG/{id}','LOG')->name('invoice.LOG');
        //---------------------------------------LOG-----------------------------------------------------------
        Route::get('/Document/invoice/view/list/{id}','viewList')->name('invoice.viewList');
        //-------------------------------------delete------------------------------------
        Route::get('/Document/invoice/delete/{id}','Delete')->name('invoice.delete');
        //--------------------------------------Re---------------------------------------

        Route::get('/Document/invoice/viewinvoice/{id}','viewinvoice')->name('invoice.viewinvoice');

        Route::get('/Document/invoice/send/email/{id}', 'email')->name('invoice.email');

        Route::post('/Document/invoice/send/detail/email/{id}', 'sendemail')->name('invoice.sendemail');

        Route::get(' /Document/invoice/Generate/to/Re/{id}','GenerateRe')->name('invoice.GenerateRe');

        Route::post('invoice-search-table', 'search_table_invoice');
        Route::post('invoice-paginate-table', 'paginate_table_invoice');

        Route::post('invoice-pendind-search-table', 'search_table_invoice_pending');
        Route::post('invoice-pending-paginate-table', 'paginate_table_invoice_pending');

        Route::post('invoice-generate-search-table', 'search_table_invoice_generate');
        Route::post('invoice-generate-paginate-table', 'paginate_table_invoice_generate');

        Route::post('invoice-select-search-table', 'search_table_invoice_select');
        Route::post('invoice-select-paginate-table', 'paginate_table_invoice_select');

        //--------------------------LogPdf-----------
        Route::post('invoice-Log-search-table', 'search_table_paginate_log_pdf');
        Route::post('invoice-Log-paginate-table', 'paginate_log_pdf_table_proposal');
        //--------------------------LogDoc-----------
        Route::post('invoice-LogDoc-search-table', 'search_table_paginate_log_doc');
        Route::post('invoice-LogDoc-paginate-table', 'paginate_log_doc_table_proposal');


        Route::post('invoice-Cancel-search-table', 'search_table_paginate_cancel');
        Route::post('invoice-Cancel-paginate-table', 'paginate_cancel_table');
         //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
         Route::get('/Proposal/cover/document/PDF/{id}', 'sheetpdf')->name('invoice.sheet');
    });

    ##-------------------------------document receipt-----------------
    Route::controller(BillingFolioController::class)->middleware('role:document')->group(function () {
        Route::get('/Document/BillingFolio/index', 'index')->name('BillingFolio.index');
        Route::get('/Document/BillingFolio/issuebill', 'issuebill')->name('BillingFolio.issuebill');
        // Route::get('/Document/receipt/Proposal/invoice/view/LOG/{id}','LOG')->name('receipt.LOG');
        Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/{id}','PaidInvoice')->name('BillingFolio.PaidInvoice');
        Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/{id}','EditPaidInvoice')->name('BillingFolio.EditPaidInvoice');
        Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Data/{id}','PaidInvoiceData')->name('BillingFolio.PaidInvoiceData');
        Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/cheque/{id}','cheque');
        Route::get('/Document/BillingFolio/Proposal/invoice/prewive/{id}/{ids}','PaidInvoiceDataprewive')->name('BillingFolio.PaidInvoiceDataprewive');
        Route::get('/Document/BillingFolio/Proposal/invoice/CheckPI/{id}','CheckPI')->name('BillingFolio.CheckPI');
        // Route::get('/Document/receipt/Proposal/invoice/CheckPI/PD/{quotationid}/{id}','CheckPD')->name('receipt.CheckPD');
        // //-------------------------------------save------------------------------------
        Route::post('/Document/BillingFolio/Proposal/invoice/Generate/save', 'savere')->name('BillingFolio.savere');
        //-----------------------------------------update------------------------------------
        Route::post('/Document/BillingFolio/Proposal/invoice/Generate/update/{id}', 'update')->name('BillingFolio.update');
        // //-------------------------------------view------------------------------------
        Route::get('/Document/BillingFolio/Proposal/invoice/view/{id}','view')->name('receipt.view');
        Route::get('/Document/BillingFolio/Proposal/invoice/log/{id}','log')->name('receipt.log');
        Route::get('/Receipt/Quotation/view/quotation/view/{id}','QuotationView')->name('receipt.QuotationView');
        Route::get('/Document/BillingFolio/Proposal/invoice/CheckPI/ReceiptCreate/{id}','ReceiptCreate')->name('receipt.ReceiptCreate');

        Route::post('billing-search-table', 'search_table_billing');
        Route::post('billing-paginate-table', 'paginate_table_billing');

        Route::post('billingPD-search-table', 'search_table_billingpd');
        Route::post('billingPD-paginate-table', 'paginate_table_billingpd');


        //--------------------------LogPdf-----------
        Route::post('billing-Log-search-table', 'search_table_paginate_log_pdf');
        Route::post('billing-Log-paginate-table', 'paginate_log_pdf_table_billing');
        //--------------------------LogDoc-----------
        Route::post('billing-LogDoc-search-table', 'search_table_paginate_log_doc');
        Route::post('billing-LogDoc-paginate-table', 'paginate_log_doc_table_billing');


    });
    Route::controller(Additional::class)->middleware('role:document')->group(function () {
        Route::get('/Document/Additional/Charge/index', 'index')->name('Additional.index');
        Route::get('/Document/Additional/Charge/select', 'select')->name('Additional.select');
        Route::get('/Document/Additional/Charge/create/{id}','create')->name('Additional.create');
        Route::post('/Document/Additional/Charge/save/{id}', 'save')->name('Additional.save');
        Route::get('/Document/Additional/Charge/document/PDF/{id}', 'sheetpdf')->name('BillingFolioOver.sheet');
        Route::get('/Document/Additional/Charge/log/{id}', 'log')->name('Additional.log');
        Route::get('/Document/Additional/Charge/edit/{id}','edit')->name('Additional.edit');
        Route::post('/Document/Additional/Charge/update/{id}', 'update')->name('Additional.update');
        Route::get('/Document/Additional/Charge/view/{id}','view')->name('Additional.view');
        Route::post('/Document/Additional/Charge/Cancel/{id}','Cancel')->name('Additional.Cancel');
        Route::get('/Document/Additional/Charge/Delete/{id}','Delete')->name('Additional.Delete');
        Route::get('/Document/Additional/Charge/Revice/{id}','Revice')->name('Additional.Revice');

        Route::get('/Document/BillingFolio/{Quotation_ID}/addProduct', 'addProduct')->name('BillingFolioOver.addProduct');
        Route::get('/Document/BillingFolio/{Quotation_ID}/addProductselect', 'addProductselect')->name('BillingFolioOver.addProductselect');
        Route::get('/Document/BillingFolio/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('BillingFolioOver.addProducttablecreatemain');

        Route::post('billingover-search-table', 'search_table_billingover');
        Route::post('billingover-paginate-table', 'paginate_table_billingover');

        //--------------------------Awaiting---------
        Route::post('billingover-Awaiting-search-table', 'search_table_paginate_awaiting');
        Route::post('billingover-Awaiting-paginate-table', 'paginate_awaiting_table_proposal');

        //--------------------------Approved---------
        Route::post('billingover-Approved-search-table', 'search_table_paginate_approved');
        Route::post('billingover-Approved-paginate-table', 'paginate_approved_table_proposal');

        //--------------------------Reject-----------
        Route::post('billingover-Reject-search-table', 'search_table_paginate_reject');
        Route::post('billingover-Reject-paginate-table', 'paginate_reject_table_proposal');

        //--------------------------Cancel-----------
        Route::post('billingover-Cancel-search-table', 'search_table_paginate_cancel');
        Route::post('billingover-Cancel-paginate-table', 'paginate_cancel_table_proposal');
        //--------------------------LogPdf-----------
        Route::post('billing-Log-search-table', 'search_table_paginate_log_pdf');
        Route::post('billing-Log-paginate-table', 'paginate_log_pdf_table_billing');
        //--------------------------LogDoc-----------
        Route::post('billing-LogDoc-search-table', 'search_table_paginate_log_doc');
        Route::post('billing-LogDoc-paginate-table', 'paginate_log_doc_table_billing');




        Route::post('billingover-proposal-search-table', 'search_table_billingover_proposal');
        Route::post('billingover-proposal-paginate-table', 'paginate_table_billingover_proposal');

        Route::get('/Document/BillingFolio/Proposal/Over/Generate/{id}','Generate')->name('BillingFolioOver.Generate');

        Route::get('/Document/BillingFolio/Proposal/Additional/prewive/{id}','PaidDataprewive')->name('BillingFolioOver.PaidDataprewive');

        Route::post('/Document/BillingFolio/Proposal/Additional/Generate/save', 'savere')->name('BillingFolioOver.savere');
        Route::get('/Document/BillingFolio/Proposal/Additional/receipt/Edit/{id}','EditPaid')->name('BillingFolioOver.EditPaid');
        Route::get('/Document/BillingFolioOverbill/Proposal/invoice/export/{id}','export')->name('BillingFolioOver.export');

        Route::get('/Document/BillingFolio/Over/log/re/{id}', 'logre')->name('BillingFolioOver.logre');
        Route::post('/Document/BillingFolio/Over/Generate/update/{id}', 'update_re')->name('BillingFolioOver.update_re');


        Route::post('billingover-pending-search-table', 'search_table_billingover_pending');
        Route::post('billingover-pending-paginate-table', 'paginate_table_billingover_pending');



        //   //--------------------------LogPdf-----------
        // Route::post('billing-Logre-search-table', 'search_table_paginate_log_pdf');
        // Route::post('billing-Logre-paginate-table', 'paginate_log_pdf_table_billing');
        // //--------------------------LogDoc-----------
        // Route::post('billing-LogDocre-search-table', 'search_table_paginate_log_doc');
        // Route::post('billing-LogDocre-paginate-table', 'paginate_log_doc_table_billing');
    });


    Route::controller(ReceiveChequeController::class)->middleware('role:document')->group(function () {
        Route::get('/Document/ReceiveCheque/index', 'index')->name('ReceiveCheque.index');
        Route::get('/Document/ReceiveCheque/save', 'save')->name('ReceiveCheque.save');
        Route::get('/Document/ReceiveCheque/view/{id}', 'view')->name('ReceiveCheque.view');
        Route::get('/Document/ReceiveCheque/edit/{id}', 'edit')->name('ReceiveCheque.edit');
        Route::get('/Document/ReceiveCheque/update', 'update')->name('ReceiveCheque.update');
        Route::get('/Document/ReceiveCheque/Approved/{id}', 'Approved')->name('ReceiveCheque.Approved');

        Route::post('cheque-search-table', 'search_table_cheque');
        Route::post('cheque-paginate-table', 'paginate_table_cheque');
    });
    Route::controller(Master_Address_System::class)->middleware('role:document')->group(function () {
        Route::get('/Master/System/index', 'index')->name('System.index');
        Route::post('/Master/System/edit/{id}', 'edit')->name('System.edit');
        Route::get('/Master/System/log/detail', 'log')->name('System.Log');

        Route::post('Msys-Log-search-table', 'Msys_search_table_paginate_log');
        Route::post('Msys-Log-paginate-table', 'Msys_paginate_log_table');
    });
    Route::controller(confirmationrequest::class)->group(function () {
        Route::get('/Proposal-request/confirm-request/{id}', 'showConfirmPage')->name('showConfirmPage');
        Route::get('/Proposal-request/Cancel-request/{id}', 'showCancelPage')->name('showCancelPage');
        Route::get('/Cancel-request/{id}', 'cancelRequest')->name('cancelRequest');
        Route::post('/request-confirmation', 'sendRequest')->name('sendRequest');
        Route::get('/check-confirmation-status/{id}', 'checkConfirmationStatus')->name('checkConfirmationStatus');

        Route::post('/confirm-request/{id}', 'confirmRequest')->name('confirmRequest');
    });
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    return 'DONE'; //Return anything
});

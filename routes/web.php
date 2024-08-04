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
use App\Http\Controllers\Masterpromotion;


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

Route::get('sms-api-forward', [SMSController::class, 'forward'])->name('sms-api-forward');

Route::middleware(['auth'])->group(function () {

    # SMS Alert
    Route::controller(SMSController::class)->group(function () {
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
        Route::get('sms-detail/{topic}/{date_from}', 'detail')->name('sms-detail');
        Route::get('sms-agoda_detail/{date_from}', 'agoda_detail')->name('sms-agoda_detail');
        Route::get('sms-update-time/{id}/{time}', 'update_time')->name('sms-update-time');
        Route::post('sms-update-split', 'update_split')->name('sms-update-split');
        Route::get('sms-agoda-receive-payment/{id}', 'receive_payment')->name('sms-agoda-receive-payment');
        Route::get('sms-graph30days/{date}/{type}/{account}', 'graph30days')->name('sms-graph30days');
        Route::get('sms-graphToday/{to_date}', 'graphToday')->name('sms-graphToday');
        Route::get('sms-graphForcast/{to_date}', 'graphForcast')->name('sms-graphForcast');
    });

    # Revenue
    Route::controller(RevenuesController::class)->group(function () {
        Route::get('revenue', 'index')->name('revenue'); // By Type
        Route::get('revenue-department', 'index')->name('revenue-department'); // By Department
        Route::post('revenue-search-calendar', 'search_calendar')->name('revenue-search-calendar');
        Route::post('revenue-store', 'store')->name('revenue-store');
        Route::get('revenue-edit/{id}', 'edit')->name('revenue-edit');
        Route::get('revenue-export', 'export')->name('revenue-export');
        Route::get('revenue-detail/{topic}/{date}', 'detail')->name('revenue-detail');
        Route::get('revenue-input-month/{month}', 'input_month')->name('revenue-input-month');
        Route::post('revenue-daily-close', 'daily_close')->name('revenue-daily-close');
        Route::post('revenue-daily-open', 'daily_open')->name('revenue-daily-open');
    });

    # Debit Agoda Revenue
    Route::controller(AgodaRevenuesController::class)->group(function () {
        Route::get('debit-agoda', 'index')->name('debit-agoda');
        Route::get('debit-agoda-revenue/{month}/{year}', 'index_list_days')->name('debit-agoda-revenue');
        Route::get('debit-agoda-update/{month}/{year}', 'index_update_agoda')->name('debit-agoda-update');
        Route::get('debit-agoda-update-receive/{id}/{month}/{year}', 'index_receive')->name('debit-agoda-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-agoda-detail/{id}/{month}/{year}', 'index_detail_receive')->name('debit-agoda-detail'); // แสดงรายละเอียด
        Route::post('debit-agoda-store', 'receive_payment')->name('debit-agoda-store');
        Route::get('debit-select-agoda-outstanding/{id}', 'select_agoda_outstanding')->name('debit-select-agoda-outstanding');
        Route::get('debit-select-agoda-received/{id}', 'select_agoda_received')->name('debit-select-agoda-received');
        Route::get('debit-status-agoda-receive/{status}', 'status_agoda_receive')->name('debit-status-agoda-receive');
    });


    ## Master Data
    Route::controller(MasterController::class)->group(function () {
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
    });


    ## Users
    Route::controller(UsersController::class)->group(function () {
        Route::get('users/{menu}', 'index')->name('users');
        Route::get('user-create', 'create')->name('user-create');
        Route::get('user-edit/{id}', 'edit')->name('user-edit');
        Route::get('user-detail/{id}', 'detail')->name('user-detail');
        Route::post('user-update', 'update')->name('user-update');
        Route::post('user-delete', 'delete')->name('user-delete');
        Route::get('user/change-status/{id}', 'change_status');
    });
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

    ####################################################

    ## Master Booking Channal
    Route::controller(master_booking::class)->group(function () {
        Route::get('/Mbooking/index', 'index')->name('Mbooking.index');
        Route::get('/Mbooking/ac', 'ac')->name('Mbooking.ac');
        Route::get('/Mbooking/no', 'no')->name('Mbooking.no');
        Route::post('/Mbooking/master_booking/save', 'Mbookingsave')->name('Mbooking.save');
        Route::get('/Mbooking/update/{id}/{datakey}/{dataEN}/{code}', 'update')->name('Master.Mbooking_update');
        Route::get('/Mbooking/change-Status/{id}', 'changeStatus')->name('Master.changeStatus');
        Route::get('/Mbooking/edit/{id}','edit')->name('Mproduct.Mbooking.unit');
        Route::get('/Mbooking/search-list2/{datakey}','search')->name('Mproduct.Mbooking.search');
        Route::get('/Mbooking/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mbooking.dupicate');
    });

    ## Company
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/Company/index', 'index')->name('Company.index');
        Route::get('/Company/create', 'create')->name('Company.create');
        Route::get('/Company/amphures/{id}', 'amphures')->name('Company.amphures');
        Route::get('/Company/districts/{id}', 'district')->name('Company.districts');
        Route::get('/Company/Tambon/{id}', 'Tambon')->name('Company.Tambon');
        Route::get('/Company/ac', 'ac')->name('Company.ac');
        Route::get('/Company/no', 'no')->name('Company.no');
        Route::get('/Company/edit/{id}', 'Company_edit')->name('Company_edit');
        Route::post('/Company/save', 'save')->name('Company.save');
        Route::get('/Company/change-status/{id}', 'changeStatus')->name('Company.changeStatus');
        Route::post('/Company/Company_edit/Company_update/{id}', 'Company_update')->name('Company_update');
        Route::get('/Company/provinces/{id}', 'provinces')->name('Company.provinces');
        Route::get('/Company/amphuresA/{id}', 'amphuresAgent')->name('Company.amphuresAgent');
        Route::get('/Company/TambonA/{id}', 'TambonAgent')->name('Company.TambonAgent');
        Route::get('/Company/districtsA/{id}', 'districtAgent')->name('Company.districtAgent');
        Route::post('/Company/check/company', 'representative');
        Route::post('/Company/edit/contact/create/{id}', 'contactcreate')->name('contact.update');
        Route::get('/Company/edit/contact/editcontact/{companyId}/{itemId}', 'contactedit')->name('Company.contact.edit');
        Route::post('/Company/edit/contact/editcontact/update/{companyId}/{itemId}', 'contactupdate')->name('Company.contact.update');
        Route::get('/Company/edit/contact/detail/{id}', 'detail')->name('Company.detail');
        Route::get('/Company/contact/change-status/{id}', 'changeStatuscontact')->name('Company.contact.changeStatus');
    });

    Route::controller(GuestController::class)->group(function () {
        Route::get('/guest/index', 'index')->name('guest.index');
        Route::get('/guest/create', 'create')->name('guest.create');
        Route::get('/guest/amphures/{id}', 'amphures')->name('guest.amphures');
        Route::get('/guest/districts/{id}', 'district')->name('guest.districts');
        Route::get('/guest/Tambon/{id}', 'Tambon')->name('guest.Tambon');
        Route::get('/guest/ac', 'ac')->name('guest.ac');
        Route::get('/guest/users_no', 'no')->name('guest.no');
        Route::get('/guest/edit/{id}', 'guest_edit')->name('guest_edit');
        Route::post('/guest/save', 'guestsave')->name('saveguest');
        Route::get('/guest/change-status/{id}', 'guestStatus')->name('guestStatus');
        Route::post('/guest/edit/update/{id}', 'guest_update')->name('guest_edit_update');
    });
});
#master product
    Route::controller(master_product_i::class)->group(function() {
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
        // ----------------------------------Quantity-----------------------------------------------
        Route::get('/Mproduct/Quantity/index','index_quantity')->name('Mproduct.index.quantity');
        Route::post('/Mproduct/Quantity/Save','save_quantity')->name('Mproduct.save.quantity');
        Route::get('/Mproduct/Quantity/edit/{id}','edit_quantity')->name('Mproduct.edit.quantity');
        Route::get('/Mproduct/changeStatus_quantity/{id}','changeStatus_quantity')->name('Mproduct.changeStatus_quantity');
        Route::get('/Mproduct/quantity/ac','ac_quantity')->name('Mproduct.quantity.ac');
        Route::get('/Mproduct/quantity/no','no_quantity')->name('Mproduct.quantity.no');
        Route::get('/Mproduct/quantity/search-list2/{datakey}','searchquantity')->name('Mproduct.quantity.search');
        Route::get('/Mproduct/quantity/check-edit-name/{id}/{datakey}','dupicatequantity')->name('Mproduct.quantity.dupicate');
        Route::get('/Mproduct/quantity/update/{id}/{datakey}/{dataEN}','update_quantity')->name('Mproduct.update.quantity');
        //----------------------------------Unit-----------------------------------------------------
        Route::get('/Mproduct/Unit/index','index_unit')->name('Mproduct.index.unit');
        Route::post('/Mproduct/Unit/Save','save_unit')->name('Mproduct.save.unit');
        Route::get('/Mproduct/Unit/edit/{id}','edit_unit')->name('Mproduct.edit.unit');
        Route::get('/Mproduct/changeStatus_unit/{id}','changeStatus_unit')->name('Mproduct.changeStatus_unit');
        Route::get('/Mproduct/Unit/ac','ac_unit')->name('Mproduct.unit.ac');
        Route::get('/Mproduct/Unit/no','no_unit')->name('Mproduct.unit.no');
        Route::get('/Mproduct/Unit/search-list2/{datakey}','search')->name('Mproduct.unit.search');
        Route::get('/Mproduct/Unit/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.unit.dupicate');
        Route::get('/Mproduct/Unit/update/{id}/{datakey}/{dataEN}','update_unit')->name('Mproduct.update.unit');
    });
#master bank
    Route::controller(Master_bank::class)->group(function() {
        Route::get('/Mbank/index','index')->name('Mbank.index');
        Route::get('/Mbank/ac','ac')->name('Mbank.ac');
        Route::get('/Mbank/no','no')->name('Mbank.no');
        Route::post('/Mbank/Save','save')->name('Mbank.save');
        Route::get('/Mbank/update/{id}/{datakey}/{dataEN}/{code}/{swiftcode}','update')->name('Mbank.update');
        Route::get('/Mbank/change-Status/{id}','changeStatus')->name('Mbank.changeStatus');
        Route::get('/Mbank/edit/{id}','edit')->name('Mproduct.edit.unit');
        Route::get('/Mbank/search-list2/{id}/{datakey}','searchMbank')->name('Mproduct.Mbank.search');
        Route::get('/Mbank/check-edit-name/{id}/{datakey}','dupicateMbank')->name('Mproduct.Mbank.dupicate');
    });
#master prefix
    Route::controller(Master_prefix::class)->group(function() {
        Route::get('/Mprefix/index','index')->name('Mprefix.index');
        Route::get('/Mprefix/ac','ac')->name('Mprefix.ac');
        Route::get('/Mprefix/no','no')->name('Mprefix.no');
        Route::post('/Mprefix/Save','save')->name('Mprefix.save');
        Route::get('/Mprefix/change-Status/{id}','changeStatus')->name('Mcomt.changeStatus');
        Route::get('/Mprefix/Mprename/Mprefix_update/{id}/{datakey}/{dataEN}','update')->name('Mprefix.update');
        Route::get('/Mprefix/Mprename/edit/{id}','edit')->name('Mproduct.edit.Mprefix');
        Route::get('/Mprefix/Mprename/search-list2/{datakey}','searchMprename')->name('Mproduct.Mprefix.search');
        Route::get('/Mprefix/Mprename/check-edit-name/{id}/{datakey}','dupicateMprename')->name('Mproduct.Mprefix.dupicate');
    });
#master promotion
    Route::controller(Masterpromotion::class)->group(function() {
        Route::get('/Mpromotion/index','index')->name('Mpromotion.index');
        Route::post('/Mpromotion/Save','save')->name('Mpromotion.save');
        Route::get('/Mpromotion/ac','ac')->name('Mpromotion.ac');
        Route::get('/Mpromotion/no','no')->name('Mpromotion.no');
        Route::get('/Mpromotion/delete/{id}','delete')->name('Mpromotion.delete');
    });
#master company type
    Route::controller(Master_Company_type::class)->group(function() {
        Route::get('/Mcomt/index','index')->name('Mcomt.index');
        Route::get('/Mcomt/ac','ac')->name('Mcomt.ac');
        Route::get('/Mcomt/no','no')->name('Mcomt.no');
        Route::post('/Mcomt/Save','save')->name('Mcomt.save');
        Route::get('/Mcomt/change-Status/{id}','changeStatus')->name('Mcomt.changeStatus');
        Route::get('/Mcomt/update/{id}/{datakey}/{dataEN}','update')->name('Mcomt.update');
        Route::get('/Mcomt/edit/{id}','edit')->name('Mproduct.edit.Mcomt');
        Route::get('/Mcomt/search-list2/{datakey}','search')->name('Mproduct.Mcomt.search');
        Route::get('/Mcomt/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mcomt.dupicate');

    });
#master market
    Route::controller(Master_market::class)->group(function() {
        Route::get('/Mmarket/index','index')->name('Mmarket.index');
        Route::get('/Mmarket/ac','ac')->name('Mmarket.ac');
        Route::get('/Mmarket/no','no')->name('Mmarket.no');
        Route::post('/Mmarket/Save','save')->name('Mmarket.save');
        Route::get('/Mmarket/change-Status/{id}','changeStatus')->name('Mmarket.changeStatus');
        Route::get('/Mmarket/update/{id}/{datakey}/{dataEN}/{code}/','update')->name('Mmarket.update');
        Route::get('/Mmarket/edit/{id}','edit')->name('Mproduct.edit.Mmarket');
        Route::get('/Mmarket/search-list2/{datakey}','search')->name('Mproduct.Mmarket.search');
        Route::get('/Mmarket/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mmarket.dupicate');
    });

#Freelancer Check
    Route::controller(freelancer_register::class)->group(function() {
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
    Route::controller(FreelancerMemberController::class)->group(function() {
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
    Route::controller(MasterEventFormatController::class)->group(function () {
        Route::get('/MEvent/index', 'index')->name('MEvent.index');
        Route::get('/MEvent/ac', 'ac')->name('MEvent.ac');
        Route::get('/MEvent/no', 'no')->name('MEvent.no');
        Route::post('/MEvent/Event_Formate/save', 'save')->name('MEvent.save');
        Route::get('/MEvent/update/{id}/{datakey}/{dataEN}', 'update')->name('MEvent.update');
        Route::get('/MEvent/change-Status/{id}','changeStatus')->name('MEvent.changeStatus');
        Route::get('/MEvent/edit/{id}','edit')->name('Mproduct.edit.MEvent');
        Route::get('/MEvent/search-list2/{datakey}','search')->name('Mproduct.MEvent.search');
        Route::get('/MEvent/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.MEvent.dupicate');
    });
    Route::controller(Master_Vat::class)->group(function () {
        Route::get('/Mvat/index', 'index')->name('Mvat.index');
        Route::get('/Mvat/ac', 'ac')->name('Mvat.ac');
        Route::get('/Mvat/no', 'no')->name('Mvat.no');
        Route::post('/Mvat/Event_Formate/save', 'save')->name('Mvat.save');
        Route::get('/Mvat/update/{id}/{datakey}/{dataEN}', 'update')->name('Mvat.update');
        Route::get('/Mvat/change-Status/{id}','changeStatus')->name('Mvat.changeStatus');
        Route::get('/Mvat/edit/{id}','edit')->name('Mproduct.edit.Mvat');
        Route::get('/Mvat/search-list2/{datakey}','search')->name('Mproduct.Mvat.search');
        Route::get('/Mvat/check-edit-name/{id}/{datakey}','dupicate')->name('Mproduct.Mvat.dupicate');
    });

    #Quotation
    Route::controller(QuotationController::class)->group(function () {
        Route::get('/Quotation/index', 'index')->name('Quotation.index');
        Route::get('/Quotation/create', 'create')->name('Quotation.create');
        Route::get('/Quotation/ac', 'ac')->name('Quotation.ac');
        Route::get('/Quotation/no', 'no')->name('Quotation.no');
        Route::get('/Quotation/create/company/{companyID}','Contactcreate')->name('Quotation.Contactcreate');
        Route::post('/Quotation/create/save', 'save')->name('Quotation.save');
        Route::get('/Quotation/selectproduct/company/create/{id}', 'selectProduct')->name('Quotation.SelectProduct');
        Route::post('/Quotation/company/create/quotation/{Quotation_ID}', 'savequotation')->name('Quotation.quotation');
        Route::get('/Quotation/edit/quotation/{id}','edit')->name('Quotation.edit');

        Route::get('/Quotation/change-Status/{id}/{status}','changestatus')->name('Quotation.changestatus');
        Route::post('/Quotation/edit/company/quotation/update/', 'update')->name('Quotation.update');
        Route::get('/Quotation/company/product/{Quotation_ID}/addProduct', 'addProduct')->name('Quotation.addProduct');
        //----------------------------------Quotaion select product------------------------------------------------------
        Route::get('/Quotation/selectproduct/{Quotation_ID}/addProducttable', 'addProducttable')->name('Quotation.addProducttable');
        Route::get('/Quotation/selectproduct/{Quotation_ID}/addProducttableselect', 'addProducttableselect')->name('Quotation.addProducttableselect');
        Route::get('/Quotation/selectproduct/{Quotation_ID}/addProducttablemain', 'addProducttablemain')->name('Quotation.addProducttablemain');
        Route::get('/Quotation/selectproduct/{Quotation_ID}/addProductselect', 'addProductselect')->name('Quotation.addProductselect');
        Route::get('/Quotation/selectproduct/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('Quotation.addProducttablecreatemain');
        //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
        Route::get('/Quotation/Quotation/cover/document/PDF/{id}', 'sheetpdf')->name('Quotation.sheet');
        //---------------------------------------ส่งรายงาน------------------------------------------------------
        Route::get('/Quotation/Quotation/send/documents', 'senddocuments')->name('Quotation.senddocuments');
        //--------------------------------------ลูกค้ายืนยัน------------------------------------------------------
        Route::get('/Proposal/Request/document/Approve/guest/{id}', 'Approve')->name('Quotation.Approve');
        //---------------------------------------LOG-----------------------------------------------------------
        Route::get('/Quotation/view/quotation/LOG/{id}','LOG')->name('Quotation.LOG');
        //-------------------------------------------------------------.
        Route::get('/Quotation/cancel/{id}','cancel')->name('Quotation.cancel');
        //--------------------------------------------------------------
        Route::get('/Quotation/Revice/{id}','Revice')->name('Quotation.Revice');
        //--------------------------------------------------------------
        Route::post('/Quotation/preview/document/PDF/', 'preview')->name('Quotation.preview');

        Route::get('/Quotation/send/email/{id}', 'email')->name('Quotation.email');

        Route::post('/Quotation/send/detail/email/{id}', 'sendemail')->name('Quotation.sendemail');

        Route::get('/Quotation/view/{id}','view')->name('Quotation.view');
    });

    #DummyQuotaion
    Route::controller(DummyQuotationController::class)->group(function () {
        Route::get('/Dummy/Quotation/index', 'index')->name('DummyQuotation.index');
        Route::get('/Dummy/Quotation/create', 'create')->name('DummyQuotation.create');
        Route::get('/Dummy/Quotation/ac', 'ac')->name('DummyQuotation.ac');
        Route::get('/Dummy/Quotation/no', 'no')->name('DummyQuotation.no');
        Route::get('/Dummy/Quotation/create/company/{companyID}','Contactcreate')->name('DummyQuotation.Contactcreate');
        Route::post('/Dummy/Quotation/create/save', 'save')->name('DummyQuotation.save');
        Route::get('/Dummy/Quotation/selectproduct/company/create/{id}', 'selectProduct')->name('DummyQuotation.SelectProduct');
        Route::post('/Dummy/Quotation/company/create/quotation/{Quotation_ID}', 'savequotation')->name('DummyQuotation.quotation');

        Route::get('/Dummy/Quotation/change-Status/{id}/{status}','changestatus')->name('DummyQuotation.changestatus');
        Route::post('/Dummy/Quotation/edit/company/quotation/update/{id}', 'update')->name('DummyQuotation.update');
        Route::get('/Dummy/Quotation/company/product/{Quotation_ID}/addProduct', 'addProduct')->name('DummyQuotation.addProduct');
        //----------------------------------Quotaion select product------------------------------------------------------
        Route::get('/Dummy/Quotation/selectproduct/{Quotation_ID}/addProducttable', 'addProducttable')->name('DummyQuotation.addProducttable');
        Route::get('/Dummy/Quotation/selectproduct/{Quotation_ID}/addProducttableselect', 'addProducttableselect')->name('DummyQuotation.addProducttableselect');
        Route::get('/Dummy/Quotation/selectproduct/{Quotation_ID}/addProducttablemain', 'addProducttablemain')->name('DummyQuotation.addProducttablemain');
        Route::get('/Dummy/Quotation/selectproduct/{Quotation_ID}/addProductselect', 'addProductselect')->name('DummyQuotation.addProductselect');
        Route::get('/Dummy/Quotation/selectproduct/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('DummyQuotation.addProducttablecreatemain');
        Route::get('/Dummy/Quotation/edit/{id}','edit')->name('DummyQuotation.edit');
        Route::get('/Dummy/Quotation/cancel/{id}','Cancel')->name('DummyQuotation.cancel');
        Route::get('/Dummy/Quotation/Generate/{id}','Generate')->name('DummyQuotation.Generate');
        //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
        Route::get('/Dummy/Quotation/Quotation/cover/document/PDF/{id}', 'sheetpdf')->name('DummyQuotation.sheet');
        //-----------------------------------ส่งเอกสาร-----------------------------------------------------------------------
        Route::get('/Dummy/Quotation/send/documents', 'senddocuments')->name('DummyQuotation.senddocuments');
    });

    #Proposal Request
    Route::controller(proposal_request::class)->group(function () {
        Route::get('/Proposal/request/index', 'index')->name('ProposalReq.index');
        Route::get('/Dummy/Proposal/Request/document/view/{id}/{Type}', 'view')->name('ProposalReq.view');
        Route::post('/Dummy/Proposal/Request/document/view/Approve/', 'Approve')->name('DummyQuotation.Approve');
        Route::post('/Dummy/Proposal/Request/document/view/Reject/', 'Reject')->name('DummyQuotation.Reject');
        Route::get('/Proposal/request/search/cancel', 'searchcancel')->name('search.by.date');
        Route::get('/Proposal/request/search/Approved', 'searchApproved')->name('search.by.Approved');
        Route::get('/Dummy/Proposal/Request/document/view/Approve/viewApprove/{id}','viewApprove')->name('DummyQuotation.viewApprove');
    });
    ##-------------------------------TemplateController-----------------
    Route::controller(Master_TemplateController::class)->group(function () {
        Route::get('/Template/PDF/Template', 'TemplateA1')->name('Template.TemplateA1');
        Route::post('/Template/PDF/Template/save', 'save')->name('Template.save');
        Route::post('/Template/PDF/document/sheet/savetemplate','savesheet')->name('Template.savesheet');
    });
    ##-------------------------------document invoice-----------------
    Route::controller(Document_invoice::class)->group(function () {
        Route::get('/Document/invoice/index', 'index')->name('invoice.index');
        Route::get('/Document/invoice/Generate/{id}','Generate')->name('invoice.Generate');
        Route::post('/Document/invoice/Generate/save', 'save')->name('invoice.save');
        Route::get('/Invoice/cover/document/PDF/{id}','export')->name('invoice.export');
        Route::get('/Document/Request/document/Approve/invoice/{id}', 'Approve')->name('invoice.Approve');
        Route::get('/Document/invoice/ReviceCancel/{id}','Revice')->name('invoice.Revice');
        Route::get('/Document/invoice/Delete/{id}','Delete')->name('invoice.Delete');
        Route::get('/Document/invoice/view/{id}','view')->name('invoice.view');
    });


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

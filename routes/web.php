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
        Route::get('revenue', 'index')->name('revenue');
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
        Route::get('debit-agoda-revenue', 'index')->name('debit-agoda-revenue');
        Route::get('debit-agoda-update', 'index_update_agoda')->name('debit-agoda-update');
        Route::get('debit-agoda-update-receive/{id}', 'index_receive')->name('debit-agoda-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-agoda-detail/{id}', 'index_detail_receive')->name('debit-agoda-detail'); // แสดงรายละเอียด
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
        Route::get('/Mbooking/no', 'users_no')->name('Mbooking.no');
        Route::get('/Mbooking/master_booking', 'create')->name('Mbooking.create');
        Route::get('/Mbooking/edit/{id}', 'Mbooking_edit')->name('Master.Mbooking_edit');
        Route::post('/Mbooking/master_booking/save', 'Mbookingsave')->name('Mbookingsave');
        Route::post('/Mbooking/master_booking/Mbooking_update/{id}', 'Mbooking_update')->name('Master.Mbooking_update');
        Route::get('/Mbooking/change-Status/{id}/{status}', 'changeStatus')->name('Master.changeStatus');
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
        Route::post('/Company/change-status/', 'changeStatus')->name('Company.changeStatus');
        Route::post('/Company/Company_edit/Company_update/{id}', 'Company_update')->name('Company_update');
        Route::get('/Company/amphuresA/{id}', 'amphuresAgent')->name('Company.amphuresAgent');
        Route::get('/Company/TambonA/{id}', 'TambonAgent')->name('Company.TambonAgent');
        Route::get('/Company/districtsA/{id}', 'districtAgent')->name('Company.districtAgent');
        Route::post('/Company/check/company', 'representative');
        Route::get('/Company/edit/contact/{id}', 'contact')->name('Company.contact');
        Route::get('/Company/edit/contact/delete/{companyId}/{itemId}', 'deleteContact');
        Route::post('/Company/edit/contact/create/{id}', 'contactcreate')->name('contact.update');
        Route::get('/Company/edit/contact/editcontact/{companyId}/{itemId}', 'contactedit')->name('Company.contact.edit');
        Route::post('/Company/edit/contact/editcontact/update/{companyId}/{itemId}', 'contactupdate')->name('Company.contact.update');
        Route::get('/Company/edit/contact/detail/{id}', 'detail')->name('Company.detail');
        Route::get('/Company/contact/acCon/{id}', 'acCon')->name('contact.acCon');
        Route::get('/Company/contact/noCon/{id}', 'noCon')->name('contact.noCon');
        Route::post('/Company/contact/change-status/{id}', 'changeStatuscontact')->name('Company.contact.changeStatus');
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
        Route::post('/guest/change-status/', 'guestStatus')->name('guestStatus');
        Route::post('/guest/edit/update/{id}', 'guest_update')->name('guest_edit_update');
    });
});



Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

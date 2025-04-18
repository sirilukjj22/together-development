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
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Deposit_Revenue;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\LinkPDFProposal;
use App\Http\Controllers\ReportAgodaAccountReceivableController;
use App\Http\Controllers\ReportAgodaOutstandingController;
use App\Http\Controllers\ReportAgodaPaidController;
use App\Http\Controllers\ReportAgodaRevenueController;
use App\Http\Controllers\ReportAuditRevenueDateController;
use App\Http\Controllers\ReportElexaAccountReceivableController;
use App\Http\Controllers\ReportElexaOutstandingController;
use App\Http\Controllers\ReportElexaPaidController;
use App\Http\Controllers\ReportElexaRevenueController;
use App\Http\Controllers\ReportHotelManualChangeController;
use App\Http\Controllers\ReportHotelWaterparkRevenueController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\ReportDocumentController;
use App\Http\Controllers\Banquet_Event_OrderController;

## Harmony
use App\Http\Controllers\Harmony\HarmonyAgodaRevenuesController;
use App\Http\Controllers\Harmony\HarmonyElexaController;
use App\Http\Controllers\Harmony\RevenuesHarmonyController;
use App\Http\Controllers\Harmony\SMSHarmonyController;
use App\Http\Controllers\Harmony\ReportAuditRevenueDateHarmonyController;

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

// API Together
Route::get('sms-forward', [SMSController::class, 'forward'])->name('sms-forward');
Route::get('sms-api-forward', [SMSController::class, 'forward'])->name('sms-api-forward');

// API Harmony
Route::get('harmony-sms-forward', [SMSHarmonyController::class, 'forward'])->name('harmony-sms-forward');
Route::get('harmony-sms-api-forward', [SMSHarmonyController::class, 'forward'])->name('harmony-sms-api-forward');

// Link PDF
Route::get('/Quotation/Quotation/cover/document/PDF/{id}', [LinkPDFProposal::class, 'proposal'])->name('Proposal.link');
Route::get('/Invoice/Quotation/cover/document/PDF/{id}', [LinkPDFProposal::class, 'invoice'])->name('Invoice.link');
Route::get('/Deposit/Quotation/cover/document/PDF/{id}', [LinkPDFProposal::class, 'Deposit'])->name('Deposit.link');

Route::get('loopRevenueAmount', [RevenuesHarmonyController::class, 'loopRevenueAmount']);

// Test Gmail
// Route::get('/google/redirect', [GmailController::class, 'redirectToGoogle'])->name('google.auth');
// Route::get('/google/callback', [GmailController::class, 'handleGoogleCallback']);
// Route::get('/gmail/messages', [GmailController::class, 'listMessages'])->name('gmail.messages');

Route::middleware(['auth'])->group(function () {

    # Select Branch
    Route::controller(BranchController::class)->group(function () {
        Route::get('select-branch', 'index')->name('select-branch');
        Route::get('confirm-branch/{branch}', 'confirm_branch')->name('confirm-branch');
    });

    Route::controller(DashboardController::class)->middleware('checkTogetherOrHarmony')->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });

    # SMS Alert (Together)
    Route::controller(SMSController::class)->middleware('role:sms_alert', 'together:1')->group(function () {
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

    # SMS Alert (Harmony)
    Route::controller(SMSHarmonyController::class)->middleware('role:sms_alert', 'harmony:2')->group(function () {
        Route::get('harmony-sms-alert', 'index')->name('harmony-sms-alert');
        Route::get('harmony-sms-alert-refresh/{day}/{month}/{year}', 'index_refresh')->name('harmony-sms-alert-refresh');
        Route::get('harmony-sms-change-status/{id}/{status}', 'change_status')->name('harmony-sms-change-status');
        Route::post('harmony-sms-search-calendar', 'search_calendar')->name('harmony-sms-search-calendar');
        Route::get('harmony-sms-create', 'create')->name('harmony-sms-create');
        Route::post('harmony-sms-store', 'store')->name('harmony-sms-store');
        Route::get('harmony-sms-edit/{id}', 'edit')->name('harmony-sms-edit');
        Route::get('harmony-sms-delete/{id}', 'delete')->name('harmony-sms-delete');
        Route::get('harmony-sms-get-remark-other-revenue/{id}', 'get_other_revenue')->name('harmony-sms-get-remark-other-revenue');
        Route::post('harmony-sms-other-revenue', 'other_revenue')->name('harmony-sms-other-revenue');
        Route::post('harmony-sms-transfer', 'transfer')->name('harmony-sms-transfer');
        Route::get('harmony-sms-detail/{name}', 'detail')->name('harmony-sms-detail');
        Route::get('harmony-sms-update-time/{id}/{time}', 'update_time')->name('harmony-sms-update-time');
        Route::post('harmony-sms-update-split', 'update_split')->name('harmony-sms-update-split');
        Route::get('harmony-sms-agoda-receive-payment/{id}', 'receive_payment')->name('harmony-sms-agoda-receive-payment');

        // Graph
        Route::get('harmony-sms-graph-thisWeek/{date}/{type}/{account}', 'graphThisWeek')->name('harmony-sms-thisWeek');
        Route::get('harmony-sms-graph-thisMonth/{date}/{type}/{account}', 'graphThisMonth')->name('harmony-sms-thisMonth');
        Route::get('harmony-sms-graph-thisMonthByDay/{date}/{type}/{account}', 'graphThisMonthByDay')->name('harmony-sms-thisMonthByDay');
        Route::get('harmony-sms-graph-yearRange/{year}/{type}/{account}', 'graphYearRange')->name('harmony-sms-yearRange');
        Route::get('harmony-sms-graph-monthRange/{month}/{to_month}/{year}/{type}/{account}', 'graphMonthRange')->name('harmony-sms-monthRange');
        Route::get('harmony-sms-graph-daterang/{startdate}/{enddate}/{type}/{account}', 'graphDateRang')->name('harmony-sms-daterang');
        Route::get('harmony-sms-graph-daterang-detail/{startdate}/{enddate}/{type}/{account}', 'graphDateRangDetail')->name('harmony-sms-daterang-detail');
        Route::get('harmony-sms-graph30days/{date}/{type}/{account}', 'graph30days')->name('harmony-sms-graph30days');
        Route::get('harmony-sms-graphToday/{to_date}', 'graphToday')->name('harmony-sms-graphToday');
        Route::get('harmony-sms-graphForcast/{to_date}', 'graphForcast')->name('harmony-sms-graphForcast');

        // Table Search / Paginate
        Route::post('harmony-sms-search-table', 'search_table')->name('harmony-sms-search-table');
        Route::post('harmony-sms-paginate-table', 'paginate_table')->name('harmony-sms-paginate-table');
    });

    # Revenue (Together)
    Route::controller(RevenuesController::class)->middleware('role:revenue', 'together:1')->group(function () {
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

    # Revenue (Harmony)
    Route::controller(RevenuesHarmonyController::class)->middleware('role:revenue')->group(function () {
        Route::get('harmony-revenue', 'index')->name('harmony-revenue'); // By Type
        Route::get('harmony-revenue-department', 'index')->name('harmony-revenue-department'); // By Department
        Route::post('harmony-revenue-search-calendar', 'search_calendar')->name('harmony-revenue-search-calendar');
        Route::post('harmony-revenue-store', 'store')->name('harmony-revenue-store');
        Route::get('harmony-revenue-edit/{id}', 'edit')->name('harmony-revenue-edit');
        Route::get('harmony-revenue-export', 'export')->name('harmony-revenue-export');
        Route::post('harmony-revenue-detail', 'detail')->name('harmony-revenue-detail');
        Route::get('harmony-revenue-input-month/{month}', 'input_month')->name('harmony-revenue-input-month');
        Route::post('harmony-revenue-daily-close', 'daily_close')->name('harmony-revenue-daily-close');
        Route::post('harmony-revenue-daily-open', 'daily_open')->name('harmony-revenue-daily-open');

        // Table Search / Paginate
        Route::post('harmony-revenue-search-table', 'search_table')->name('harmony-revenue-search-table');
        Route::post('harmony-revenue-paginate-table', 'paginate_table')->name('harmony-revenue-paginate-table');
    });

    # Debit Agoda Revenue
    Route::controller(AgodaRevenuesController::class)->middleware('role:agoda', 'together:1')->group(function () {
        Route::get('debit-agoda', 'index')->name('debit-agoda');
        Route::get('debit-agoda-revenue', 'index_list_days')->name('debit-agoda-revenue'); // แสดงรายการรายได้จาก SMS
        Route::get('debit-agoda-update/{month}/{year}', 'index_update_agoda')->name('debit-agoda-update');
        Route::get('debit-agoda-update-receive/{id}', 'index_receive')->name('debit-agoda-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-agoda-detail/{id}', 'index_detail_receive')->name('debit-agoda-detail'); // แสดงรายละเอียด
        Route::post('debit-agoda-store', 'receive_payment')->name('debit-agoda-store'); // บันทึกข้อมูล
        Route::get('debit-select-agoda-outstanding/{id}', 'select_agoda_outstanding')->name('debit-select-agoda-outstanding');
        Route::post('debit-confirm-select-agoda-outstanding', 'confirm_select_agoda_outstanding')->name('debit-confirm-select-agoda-outstanding'); // Confirm รายการที่เลือก
        Route::get('debit-select-agoda-received/{id}', 'select_agoda_received')->name('debit-select-agoda-received');
        // Route::get('debit-status-agoda-receive/{status}/{startDate}/{endDate}', 'status_agoda_receive')->name('debit-status-agoda-receive');

        // Graph
        Route::get('debtor-agoda-graph-month-sales', 'graph_month_sales')->name('debtor-agoda-graph-month-sales');
        Route::get('debtor-agoda-graph-month-charge', 'graph_month_charge')->name('debtor-agoda-graph-month-charge');

        // Lock & Unlock
        Route::get('debtor-agoda-change-status-lock/{id}/{status}', 'change_lock_unlock')->name('debtor-agoda-change-status-lock');

        // Logs
        Route::get('debtor-agoda-logs/{id}', 'logs')->name('debtor-agoda-logs');

        // Search Child
        Route::get('debtor-agoda-search-detail-child/{id}', 'search_detail')->name('debtor-agoda-search-detail-child');

        // Search, Paginate
        Route::post('debtor-agoda-search-table', 'search_table')->name('debtor-agoda-search-table');
    });

    # Debit Elexa
    Route::controller(ElexaController::class)->middleware('role:elexa', 'together:1')->group(function () {
        Route::get('debit-elexa', 'index')->name('debit-elexa');
        Route::get('debit-elexa-revenue', 'index_list_days')->name('debit-elexa-revenue');
        Route::get('debit-elexa-update/{month}/{year}', 'index_update_elexa')->name('debit-elexa-update');
        Route::get('debit-elexa-update-receive/{id}', 'index_receive')->name('debit-elexa-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('debit-elexa-detail/{id}', 'index_detail_receive')->name('debit-elexa-detail'); // แสดงรายละเอียด
        Route::post('debit-elexa-store', 'receive_payment')->name('debit-elexa-store');
        Route::get('debit-select-elexa-outstanding/{id}', 'select_elexa_outstanding')->name('debit-select-elexa-outstanding');
        Route::post('debit-select-all-elexa-outstanding', 'select_all_elexa_outstanding')->name('debit-select-all-elexa-outstanding'); // เลือกมากกว่า 1 รายการ
        Route::post('debit-confirm-select-elexa-outstanding', 'confirm_select_elexa_outstanding')->name('debit-confirm-select-elexa-outstanding'); // Confirm รายการที่เลือก
        Route::get('debit-status-elexa-receive/{status}', 'status_elexa_receive')->name('debit-status-elexa-receive');

        // Get Data
        Route::post('debit-get-outstanding', 'get_outstanding')->name('debit-get-outstanding'); // Outstanding ทั้งหมด

        // Graph
        Route::get('debtor-elexa-graph-month-sales', 'graph_month_sales')->name('debtor-elexa-graph-month-sales');
        Route::get('debtor-elexa-graph-month-charge', 'graph_month_charge')->name('debtor-elexa-graph-month-charge');

        // Lock & Unlock
        Route::get('debtor-elexa-change-status-lock/{id}/{status}', 'change_lock_unlock')->name('debtor-elexa-change-status-lock');

        // Search Child
        Route::get('debtor-elexa-search-detail-child/{id}', 'search_detail')->name('debtor-elexa-search-detail-child');

        // Search, Paginate
        Route::post('debtor-elexa-search-table', 'search_table')->name('debtor-elexa-search-table');

        // Logs
        Route::get('debtor-elexa-logs/{id}', 'logs')->name('debtor-elexa-logs');
    });

    # Debit Agoda Revenue (Harmony)
    Route::controller(HarmonyAgodaRevenuesController::class)->middleware('role:agoda', 'harmony:2')->group(function () {
        Route::get('harmony-debit-agoda', 'index')->name('harmony-debit-agoda');
        Route::get('harmony-debit-agoda-revenue', 'index_list_days')->name('harmony-debit-agoda-revenue'); // แสดงรายการรายได้จาก SMS
        Route::get('harmony-debit-agoda-update/{month}/{year}', 'index_update_agoda')->name('harmony-debit-agoda-update');
        Route::get('harmony-debit-agoda-update-receive/{id}', 'index_receive')->name('harmony-debit-agoda-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('harmony-debit-agoda-detail/{id}', 'index_detail_receive')->name('harmony-debit-agoda-detail'); // แสดงรายละเอียด
        Route::post('harmony-debit-agoda-store', 'receive_payment')->name('harmony-debit-agoda-store'); // บันทึกข้อมูล
        Route::get('harmony-debit-select-agoda-outstanding/{id}', 'select_agoda_outstanding')->name('harmony-debit-select-agoda-outstanding');
        Route::post('harmony-debit-confirm-select-agoda-outstanding', 'confirm_select_agoda_outstanding')->name('harmony-debit-confirm-select-agoda-outstanding'); // Confirm รายการที่เลือก
        Route::get('harmony-debit-select-agoda-received/{id}', 'select_agoda_received')->name('harmony-debit-select-agoda-received');
        // Route::get('harmony-debit-status-agoda-receive/{status}/{startDate}/{endDate}', 'status_agoda_receive')->name('harmony-debit-status-agoda-receive');

        // Graph
        Route::get('harmony-debtor-agoda-graph-month-sales', 'graph_month_sales')->name('harmony-debtor-agoda-graph-month-sales');
        Route::get('harmony-debtor-agoda-graph-month-charge', 'graph_month_charge')->name('harmony-debtor-agoda-graph-month-charge');

        // Lock & Unlock
        Route::get('harmony-debtor-agoda-change-status-lock/{id}/{status}', 'change_lock_unlock')->name('harmony-debtor-agoda-change-status-lock');

        // Logs
        Route::get('harmony-debtor-agoda-logs/{id}', 'logs')->name('harmony-debtor-agoda-logs');

        // Search Child
        Route::get('harmony-debtor-agoda-search-detail-child/{id}', 'search_detail')->name('harmony-debtor-agoda-search-detail-child');

        // Search, Paginate
        Route::post('harmony-debtor-agoda-search-table', 'search_table')->name('harmony-debtor-agoda-search-table');
    });

    # Debit Elexa (Harmony)
    Route::controller(HarmonyElexaController::class)->middleware('role:elexa', 'harmony:2')->group(function () {
        Route::get('harmony-debit-elexa', 'index')->name('harmony-debit-elexa');
        Route::get('harmony-debit-elexa-revenue', 'index_list_days')->name('harmony-debit-elexa-revenue');
        Route::get('harmony-debit-elexa-update/{month}/{year}', 'index_update_elexa')->name('harmony-debit-elexa-update');
        Route::get('harmony-debit-elexa-update-receive/{id}', 'index_receive')->name('harmony-debit-elexa-update-receive'); // หน้าเพิ่ม / แก้ไขข้อมูล
        Route::get('harmony-debit-elexa-detail/{id}', 'index_detail_receive')->name('harmony-debit-elexa-detail'); // แสดงรายละเอียด
        Route::post('harmony-debit-elexa-store', 'receive_payment')->name('harmony-debit-elexa-store');
        Route::get('harmony-debit-select-elexa-outstanding/{id}', 'select_elexa_outstanding')->name('harmony-debit-select-elexa-outstanding');
        Route::post('harmony-debit-select-all-elexa-outstanding', 'select_all_elexa_outstanding')->name('harmony-debit-select-all-elexa-outstanding'); // เลือกมากกว่า 1 รายการ
        Route::post('harmony-debit-confirm-select-elexa-outstanding', 'confirm_select_elexa_outstanding')->name('harmony-debit-confirm-select-elexa-outstanding'); // Confirm รายการที่เลือก
        Route::get('harmony-debit-status-elexa-receive/{status}', 'status_elexa_receive')->name('harmony-debit-status-elexa-receive');

        // Get Data
        Route::post('harmony-debit-get-outstanding', 'get_outstanding')->name('harmony-debit-get-outstanding'); // Outstanding ทั้งหมด

        // Graph
        Route::get('harmony-debtor-elexa-graph-month-sales', 'graph_month_sales')->name('harmony-debtor-elexa-graph-month-sales');
        Route::get('harmony-debtor-elexa-graph-month-charge', 'graph_month_charge')->name('harmony-debtor-elexa-graph-month-charge');

        // Lock & Unlock
        Route::get('harmony-debtor-elexa-change-status-lock/{id}/{status}', 'change_lock_unlock')->name('harmony-debtor-elexa-change-status-lock');

        // Search Child
        Route::get('harmony-debtor-elexa-search-detail-child/{id}', 'search_detail')->name('harmony-debtor-elexa-search-detail-child');

        // Search, Paginate
        Route::post('harmony-debtor-elexa-search-table', 'search_table')->name('harmony-debtor-elexa-search-table');

        // Logs
        Route::get('harmony-debtor-elexa-logs/{id}', 'logs')->name('harmony-debtor-elexa-logs');
    });

    ## Master Data
    Route::controller(MasterController::class)->middleware(['role:setting', 'checkTogetherOrHarmony'])->group(function () {
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
    Route::controller(UsersController::class)->middleware(['role:user', 'checkTogetherOrHarmony'])->group(function () {
        Route::get('users/{menu}/{branch}', 'index')->name('users');
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
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post')->middleware(['role:user', 'checkTogetherOrHarmony']);


    ## User Department
    Route::controller(UserDepartmentsController::class)->middleware(['role:department', 'checkTogetherOrHarmony'])->group(function () {
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

    ## Report Audit Date (Together)
    Route::controller(ReportAuditRevenueDateController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-audit-revenue-date', 'index')->name('report-audit-revenue-date');
        Route::post('report-audit-revenue-date-search', 'search')->name('report-audit-revenue-date-search');
        Route::post('report-audit-paginate-table', 'paginate_table')->name('report-audit-paginate-table');
        Route::post('report-audit-search-table', 'search_table')->name('report-audit-search-table');
    });

    ## Report Audit Date (Harmony)
    Route::controller(ReportAuditRevenueDateHarmonyController::class)->middleware(['role:report', 'harmony:2'])->group(function () {
        Route::get('harmony-report-audit-revenue-date', 'index')->name('harmony-report-audit-revenue-date');
        Route::post('harmony-report-audit-revenue-date-search', 'search')->name('harmony-report-audit-revenue-date-search');
        Route::post('harmony-report-audit-paginate-table', 'paginate_table')->name('harmony-report-audit-paginate-table');
        Route::post('harmony-report-audit-search-table', 'search_table')->name('harmony-report-audit-search-table');
    });

    Route::controller(ReportHotelWaterparkRevenueController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-hotel-water-park-revenue', 'index')->name('report-hotel-water-park-revenue');
        Route::post('report-hotel-water-park-revenue-search', 'search')->name('report-hotel-water-park-revenue-search');
    });

    Route::controller(ReportHotelManualChangeController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-hotel-manual-charge', 'index')->name('report-hotel-manual-charge');
        Route::post('report-hotel-manual-charge-search', 'search')->name('report-hotel-manual-charge-search');
    });

    Route::controller(ReportAgodaRevenueController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-agoda-revenue', 'index')->name('report-agoda-revenue');
        Route::post('report-agoda-revenue-search', 'search')->name('report-agoda-revenue-search');
    });

    Route::controller(ReportAgodaOutstandingController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-agoda-outstanding', 'index')->name('report-agoda-outstanding');
        Route::post('report-agoda-outstanding-search', 'search')->name('report-agoda-outstanding-search');
    });

    Route::controller(ReportAgodaAccountReceivableController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-agoda-account-receivable', 'index')->name('report-agoda-account-receivable');
        Route::post('report-agoda-account-receivable-search', 'search')->name('report-agoda-account-receivable-search');
    });

    Route::controller(ReportAgodaPaidController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-agoda-paid', 'index')->name('report-agoda-paid');
        Route::post('report-agoda-paid-search', 'search')->name('report-agoda-paid-search');
    });

    Route::controller(ReportElexaRevenueController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-elexa-revenue', 'index')->name('report-elexa-revenue');
        Route::post('report-elexa-revenue-search', 'search')->name('report-elexa-revenue-search');
    });

    Route::controller(ReportElexaOutstandingController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-elexa-outstanding', 'index')->name('report-elexa-outstanding');
        Route::post('report-elexa-outstanding-search', 'search')->name('report-elexa-outstanding-search');
    });

    Route::controller(ReportElexaAccountReceivableController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-elexa-account-receivable', 'index')->name('report-elexa-account-receivable');
        Route::post('report-elexa-account-receivable-search', 'search')->name('report-elexa-account-receivable-search');
    });

    Route::controller(ReportElexaPaidController::class)->middleware(['role:report', 'together:1'])->group(function () {
        Route::get('report-elexa-paid', 'index')->name('report-elexa-paid');
        Route::post('report-elexa-paid-search', 'search')->name('report-elexa-paid-search');
    });


    Route::controller(ReportDocumentController::class)->middleware(['role:report', 'together:1'])->group(function () {
        //DummyProposal

        Route::get('report-dummy-proposal-day', 'dummy_today')->name('report-dummy-proposal-day');
        Route::get('report-dummy-proposal-cancellation', 'dummy_cancellation')->name('report-dummy-proposal-cancellation');
        Route::get('report-dummy-proposal-approved', 'dummy_approved')->name('report-dummy-proposal-approved');
        Route::get('report-dummy-proposal-reject', 'dummy_reject')->name('report-dummy-proposal-reject');
        Route::get('report-dummy-proposal-generate', 'dummy_generate')->name('report-dummy-proposal-generate');
        Route::post('report-proposal-search', 'search_proposal')->name('report-proposal-search');

        Route::get('report-proposal-index', 'proposal')->name('report-proposal-index');
        Route::post('report-proposal-search', 'search_proposal')->name('report-proposal-search');

        Route::get('report-invoice-index', 'invoice')->name('report-invoice-index');
        Route::post('report-invoice-search', 'search_invoice')->name('report-invoice-search');

        Route::get('report-additional-index', 'additional')->name('report-additional-index');
        Route::post('report-additional-search', 'search_additional')->name('report-additional-search');

        Route::get('report-billingfolio-index', 'billingfolio')->name('report-billingfolio-index');
        Route::post('report-billingfolio-search', 'search_billingfolio')->name('report-billingfolio-search');
    });

    ####################################################

    ## Master Booking Channal
    Route::controller(master_booking::class)->middleware(['role:setting', 'checkTogetherOrHarmony'])->group(function () {
        Route::get('/Mbooking/{menu}', 'index')->name('Mbooking');
        Route::post('/Mbooking/master_booking/save', 'Mbookingsave')->name('Mbooking.save');
        Route::get('/Mbooking/update/{id}/{datakey}/{dataEN}/{code}', 'update')->name('Master.Mbooking_update');
        Route::get('/Mbooking/change-Status/{id}', 'changeStatus')->name('Master.changeStatus');
        Route::get('/Mbooking/edit/{id}', 'edit')->name('Mproduct.Mbooking.unit');
        Route::get('/Mbooking/search-list2/{datakey}', 'search')->name('Mproduct.Mbooking.search');
        Route::get('/Mbooking/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.Mbooking.dupicate');

        Route::get('/Mbooking/log/detail', 'log')->name('Mbooking.Log');
    });

    ## Company
    Route::controller(CompanyController::class)->middleware(['role:company', 'together:1'])->group(function () {
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
    });

    Route::controller(GuestController::class)->middleware(['role:guest', 'together:1'])->group(function () {
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


        //--------------------------------เพิ่ม ซับ ------------------------
        Route::post('/guest/save/cover/{id}', 'guest_cover')->name('guest_cover');
        Route::get('/guest/change-status/tax/{id}', 'guestStatustax')->name('guestStatustax');



        Route::get('/guest/view/{id}', 'view')->name('guest_view');
        Route::get('/guest/Tax/edit/{id}', 'guest_edit_tax')->name('guest_edit_tax');
        Route::post('/guest/tax/edit/update/{id}', 'guest_update_tax')->name('guest_update_tax');
        Route::get('/guest/Tax/view/{id}', 'guest_view_tax')->name('guest_view_tax');
    });
});
#master product
Route::controller(master_product_i::class)->middleware(['role:product_item', 'together:1'])->group(function () {
    Route::get('/Mproduct/index', 'index')->name('Mproduct.index');
    Route::get('/Mproduct/create', 'create')->name('Mproduct.create');
    Route::get('/Mproduct/ac', 'ac')->name('Mproduct.ac');
    Route::get('/Mproduct/no', 'no')->name('Mproduct.no');
    Route::get('/Mproduct/Room_Type', 'Room_Type')->name('Mproduct.Room_Type');
    Route::get('/Mproduct/Banquet', 'Banquet')->name('Mproduct.Banquet');
    Route::get('/Mproduct/Meals', 'Meals')->name('Mproduct.Meals');
    Route::get('/Mproduct/Entertainment', 'Entertainment')->name('Mproduct.Entertainment');
    Route::get('/Mproduct/edit/{id}', 'edit')->name('Mproduct.edit');
    Route::get('/Mproduct/view/{id}', 'view')->name('Mproduct.view');
    Route::post('/Mproduct/Save', 'save')->name('Mproduct.save');
    Route::get('/Mproduct/change-Status/{id}', 'changeStatus')->name('Mproduct.changeStatus');
    Route::post('/Mproduct/master_Mproduct/Mproduct_update/{id}', 'update')->name('Mproduct.update');
    Route::post('/Mproduct/check/Category', 'Category')->name('Mproduct.Category');
    Route::get('/Mproduct/delete/{id}', 'delete')->name('Mproduct.delete');
    Route::get('/Mproduct/log/detail', 'product_log')->name('Mproduct.Log');
    //----------------------------

    // ----------------------------------Quantity-----------------------------------------------
    Route::get('/Mproduct/Quantity/{menu}', 'index_quantity')->name('Quantity');
    Route::post('/Mproduct/Quantity/Save', 'save_quantity')->name('Mproduct.save.quantity');
    Route::get('/Mproduct/Quantity/edit/{id}', 'edit_quantity')->name('Mproduct.edit.quantity');
    Route::get('/Mproduct/changeStatus_quantity/{id}', 'changeStatus_quantity')->name('Mproduct.changeStatus_quantity');
    Route::get('/Mproduct/quantity/search-list2/{datakey}', 'searchquantity')->name('Mproduct.quantity.search');
    Route::get('/Mproduct/quantity/check-edit-name/{id}/{datakey}', 'dupicatequantity')->name('Mproduct.quantity.dupicate');
    Route::get('/Mproduct/quantity/update/{id}/{datakey}/{dataEN}', 'update_quantity')->name('Mproduct.update.quantity');
    Route::get('/Mproduct/Quantity/log/detail', 'quantity_log')->name('Quantity.Log');


    //----------------------------------Unit-----------------------------------------------------
    Route::get('/Mproduct/Unit/{menu}', 'index_unit')->name('Unit');
    Route::post('/Mproduct/Unit/Save', 'save_unit')->name('Mproduct.save.unit');
    Route::get('/Mproduct/Unit/edit/{id}', 'edit_unit')->name('Mproduct.edit.unit');
    Route::get('/Mproduct/changeStatus_unit/{id}', 'changeStatus_unit')->name('Mproduct.changeStatus_unit');
    Route::get('/Mproduct/Unit/search-list2/{datakey}', 'search')->name('Mproduct.unit.search');
    Route::get('/Mproduct/Unit/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.unit.dupicate');
    Route::get('/Mproduct/Unit/update/{id}/{datakey}/{dataEN}', 'update_unit')->name('Mproduct.update.unit');

    Route::get('/Mproduct/Unit/log/detail', 'unit_log')->name('Unit.Log');
});

#master prefix
Route::controller(Master_prefix::class)->middleware(['role:prefix', 'together:1'])->group(function () {
    Route::get('/Mprefix/{menu}', 'index')->name('Mprefix');
    Route::get('/Mprefix/ac', 'ac')->name('Mprefix.ac');
    Route::get('/Mprefix/no', 'no')->name('Mprefix.no');
    Route::post('/Mprefix/Save', 'save')->name('Mprefix.save');
    Route::get('/Mprefix/change-Status/{id}', 'changeStatus')->name('Mcomt.changeStatus');
    Route::get('/Mprefix/Mprename/Mprefix_update/{id}/{datakey}/{dataEN}', 'update')->name('Mprefix.update');
    Route::get('/Mprefix/Mprename/edit/{id}', 'edit')->name('Mproduct.edit.Mprefix');
    Route::get('/Mprefix/Mprename/search-list2/{datakey}', 'searchMprename')->name('Mproduct.Mprefix.search');
    Route::get('/Mprefix/Mprename/check-edit-name/{id}/{datakey}', 'dupicateMprename')->name('Mproduct.Mprefix.dupicate');

    Route::get('/Mprefix/log/detail', 'log')->name('Mprefix.Log');
});

#master promotion
Route::controller(Masterpromotion::class)->group(function () {
    Route::get('/Mpromotion/{menu}', 'index')->name('Mpromotion');
    Route::post('/Mpromotion/Save', 'save')->name('Mpromotion.save');
    Route::get('/Mpromotion/delete/{id}', 'delete')->name('Mpromotion.delete');
    Route::get('/Mpromotion/change-status/{id}', 'status')->name('Mpromotion.status');
    Route::post('Mpromotion-search-table', 'search_table')->name('Mpromotion-search-table');
    Route::post('Mpromotion-paginate-table', 'paginate_table')->name('Mpromotion-paginate-table');
    Route::get('/Mpromotion/log/detail', 'log')->name('Mpromotion.Log');

});

#master company type
Route::controller(Master_Company_type::class)->middleware(['role:company_type', 'together:1'])->group(function () {
    Route::get('/Mcomt/{menu}', 'index')->name('Mcomt');
    Route::post('/Mcomt/Save', 'save')->name('Mcomt.save');
    Route::get('/Mcomt/change-Status/{id}', 'changeStatus')->name('Mcomt.changeStatus');
    Route::get('/Mcomt/update/{id}/{datakey}/{dataEN}', 'update')->name('Mcomt.update');
    Route::get('/Mcomt/edit/{id}', 'edit')->name('Mproduct.edit.Mcomt');
    Route::get('/Mcomt/search-list2/{datakey}', 'search')->name('Mproduct.Mcomt.search');
    Route::get('/Mcomt/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.Mcomt.dupicate');

    Route::get('/Mcomt/log/detail', 'log')->name('Mcomt.Log');

});
#master market
Route::controller(Master_market::class)->middleware(['role:company_market', 'together:1'])->group(function () {
    Route::get('/Mmarket/{menu}', 'index')->name('Mmarket');
    Route::post('/Mmarket/Save', 'save')->name('Mmarket.save');
    Route::get('/Mmarket/change-Status/{id}', 'changeStatus')->name('Mmarket.changeStatus');
    Route::post('/Mmarket/update/{id}/{datakey}/{dataEN}', 'update')->name('Mmarket.update');
    Route::get('/Mmarket/edit/{id}', 'edit')->name('Mproduct.edit.Mmarket');
    Route::get('/Mmarket/search-list2/{datakey}', 'search')->name('Mproduct.Mmarket.search');
    Route::get('/Mmarket/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.Mmarket.dupicate');

    Route::get('/Mmarket/log/detail', 'log')->name('Mmarket.Log');

});

#Freelancer Check
Route::controller(freelancer_register::class)->middleware(['role:freelancer', 'together:1'])->group(function () {
    Route::get('/Freelancer/checked/index', 'index')->name('freelancer.index');
    Route::get('/Freelancer/checked/create', 'create')->name('freelancer.create');
    Route::get('/Freelancer/checked/create/amphures/{id}', 'amphures')->name('freelancer.amphures');
    Route::get('/Freelancer/checked/create/districts/{id}', 'district')->name('freelancer.districts');
    Route::get('/Freelancer/checked/create/Tambon/{id}', 'Tambon')->name('freelancer.Tambon');
    Route::post('/Freelancer/check/save', 'save')->name('freelancer.save');
    Route::post('/Freelancer/check/update/{id}', 'update')->name('freelancer.update');
    Route::get('/Freelancer/checked/change-status/{id}', 'changeStatus')->name('freelancer.changeStatus');
    Route::get('/Freelancer/checked/ac', 'ac')->name('freelancer.ac');
    Route::get('/Freelancer/checked/no', 'no')->name('freelancer.no');
    Route::get('/Freelancer/checked/ap', 'ap')->name('freelancer.ap');
    Route::get('/Freelancer/checked/Approve/{id}', 'delete');
    Route::get('/Freelancer/check/edit/{id}', 'edit')->name('freelancer.edit');
    Route::get('/Freelancer/check/view/{id}', 'view')->name('freelancer.view');
});

#Freelancer Member
Route::controller(FreelancerMemberController::class)->middleware(['role:freelancer', 'together:1'])->group(function () {
    Route::get('/Freelancer/member/index', 'index_member')->name('freelancer_member.index');
    Route::get('/Freelancer/member/view/{id}', 'viewmember')->name('freelancer_member.view');
    Route::get('/Freelancer/member/edit/{id}', 'editmember')->name('freelancer_member.edit');
    Route::post('/Freelancer/member/save/update/{id}', 'updatefreelancermember')->name('updatefreelancermember');
    Route::get('/Freelancer/member/order_list/{id}', 'order_list')->name('freelancer_member.Quotation');
    Route::post('/Freelancer/member/get-representative', 'getRepresentative')->name('get.representative');
    Route::post('/Freelancer/member/order_list/save/{id}', 'order_listsave')->name('quotationsave');
    Route::get('/Freelancer/member/view/data/{Freeid}/{Comid}', 'viewdatamember')->name('freelancer_member.viewdata');
    Route::get('/Freelancer/member/ac', 'ac')->name('freelancer_member.ac');
    Route::get('/Freelancer/member/no', 'no')->name('freelancer_member.no');
    Route::post('/Freelancer/member/change-status/', 'changeStatusmember')->name('freelancer.changeStatusmember');
    //boss
    Route::get('/Freelancer/boss/examine/viewcompany', 'examine')->name('freelancer.boss.examine');
    Route::get('/Freelancer/boss/view/data/{id}', 'viewdataexamine')->name('freelancer.boss.viewdata.examine');
    Route::get('/Freelancer/boss/examine/status{id}', 'examinestatus')->name('freelancer.boss.examine.status');
    //Employee
    Route::get('/Freelancer/employee/examine/viewcompany', 'examineemployee')->name('freelancer.employee.examine');
    Route::get('/Freelancer/employee/view/data/{id}', 'viewdataexamineemployee')->name('freelancer.employee.viewdata.examine');
    Route::get('/Freelancer/employee/examine/status{id}', 'examinestatusemployee')->name('freelancer.employee.examine.status');
});

# Master Event Formate
Route::controller(MasterEventFormatController::class)->middleware(['role:company_event', 'together:1'])->group(function () {
    Route::get('/MEvent/{menu}', 'index')->name('MEvent');
    Route::post('/MEvent/Event_Formate/save', 'save')->name('MEvent.save');
    Route::get('/MEvent/update/{id}/{datakey}/{dataEN}', 'update')->name('MEvent.update');
    Route::get('/MEvent/change-Status/{id}', 'changeStatus')->name('MEvent.changeStatus');
    Route::get('/MEvent/edit/{id}', 'edit')->name('Mproduct.edit.MEvent');
    Route::get('/MEvent/search-list2/{datakey}', 'search')->name('Mproduct.MEvent.search');
    Route::get('/MEvent/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.MEvent.dupicate');

    Route::get('/MEvent/log/detail', 'log')->name('MEvent.Log');

});

Route::controller(Master_Vat::class)->group(function () {
    Route::get('/Mvat/{menu}', 'index')->name('Mvat');
    Route::post('/Mvat/Event_Formate/save', 'save')->name('Mvat.save');
    Route::get('/Mvat/update/{id}/{datakey}/{dataEN}', 'update')->name('Mvat.update');
    Route::get('/Mvat/change-Status/{id}', 'changeStatus')->name('Mvat.changeStatus');
    Route::get('/Mvat/edit/{id}', 'edit')->name('Mproduct.edit.Mvat');
    Route::get('/Mvat/search-list2/{datakey}', 'search')->name('Mproduct.Mvat.search');
    Route::get('/Mvat/check-edit-name/{id}/{datakey}', 'dupicate')->name('Mproduct.Mvat.dupicate');

    Route::get('/Mvat/log/detail', 'log')->name('Mvat.Log');

});

#Quotation
Route::controller(QuotationController::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Proposal/index', 'index')->name('Proposal.index');
    Route::get('/Proposal/create', 'create')->name('Proposal.create');
    Route::get('/Proposal/ac', 'ac')->name('Proposal.ac');
    Route::get('/Proposal/no', 'no')->name('Proposal.no');
    Route::get('/Proposal/create/company/{companyID}', 'Contactcreate')->name('Proposal.Contactcreate');
    Route::get(' /Proposal/create/Guest/{Guest}', 'Guestcreate')->name('Proposal.Guestcreate');
    Route::post('/Proposal/create/save', 'save')->name('Proposal.save');
    Route::get('/Proposal/selectproduct/company/create/{id}', 'selectProduct')->name('Proposal.SelectProduct');
    Route::post('/Proposal/company/create/quotation/{Quotation_ID}', 'savequotation')->name('Proposal.quotation');
    Route::get('/Proposal/edit/quotation/{id}', 'edit')->name('Proposal.edit');

    Route::get('/Proposal/change-Status/{id}/{status}', 'changestatus')->name('Proposal.changestatus');
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
    Route::get('/Proposal/view/quotation/LOG/{id}', 'LOG')->name('Proposal.LOG');
    //-------------------------------------------------------------.
    Route::post('/Proposal/cancel/{id}', 'cancel')->name('Proposal.cancel');
    //--------------------------------------------------------------
    Route::get('/Proposal/Revice/{id}', 'Revice')->name('Proposal.Revice');
    //--------------------------------------------------------------
    Route::post('/Proposal/preview/document/PDF/', 'preview')->name('Proposal.preview');

    Route::get('/Proposal/send/email/{id}', 'email')->name('Proposal.email');

    Route::post('/Proposal/send/detail/email/{id}', 'sendemail')->name('Proposal.sendemail');

    Route::get('/Proposal/view/{id}', 'view')->name('Proposal.view');

    Route::get('/Proposal/Search/All', 'SearchAll')->name('Proposal.Search');

    Route::get('/Proposal/viewproposal/{id}', 'viewproposal')->name('Proposal.viewproposal');

    Route::get('/Proposal/Request/document/noshow/{id}', 'noshow')->name('Proposal.noshow');

    Route::get('/Proposal/get/proposalTable', 'getproposalTable');
    //----------------------------
    Route::get('/Proposal/check/invoice/{id}', 'check_invoice');
    Route::get('/Proposal/check/additional/{id}', 'check_additional');
    Route::get('/Proposal/get/PendingTable', 'PendingTable');
    // Route::get('/invoice/get/PendingTable','PendingTable');
    // Route::get('/invoice/get/ApprovedTable','ApprovedTable');
    // Route::get('/invoice/get/CancelTable','CancelTable');
    // Route::get('/invoice/get/CompleteTable','CompleteTable');
});

Route::controller(Deposit_Revenue::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Deposit/index', 'index')->name('Deposit.index');
    Route::get('/Deposit/create/{id}', 'create')->name('Deposit.create');
    Route::get('/Deposit/new/create', 'createnew')->name('Deposit.create_new');
    Route::get('/Deposit/edit/{id}', 'edit')->name('Deposit.edit');
    Route::get('/Document/deposit_revenue/Data/{id}', 'deposit');
    Route::get('/Document/deposit_revenue/cheque/{id}', 'cheque');
    Route::post('/Deposit/save', 'save')->name('Deposit.save');
    Route::get('/Deposit/view/invoice/deposit/{id}', 'viewinvoicedeposit')->name('Deposit.viewinvoicedeposit');
    Route::get('/Deposit/LOG/{id}', 'log')->name('Deposit.log');
    Route::post('/Deposit/update/{id}', 'update')->name('Deposit.update');
    Route::get('/Deposit/Send/Email/{id}', 'email')->name('Deposit.email');
    Route::post('/Document/deposit/send/detail/email/{id}', 'sendemail')->name('Deposit.sendemail');
    Route::get('/Document/deposit/cover/document/PDF/{id}', 'sheetpdf')->name('Deposit.sheet');
    Route::post('/Document/Deposit/cancel/{id}', 'cancel')->name('Deposit.cancel');
    Route::get('/Document/Deposit/Revise/{id}', 'Revise')->name('Deposit.Revise');
    Route::post('/Document/Deposit/quotation', 'Quotation')->name('Deposit.Quotation');
    Route::get('/Document/deposit_revenue/Data/createnew/{id}', 'deposit_pd');
    // Route::get('/Deposit/edit/revenue/deposit/{id}', 'depositedit')->name('Deposit.depositedit');
    // Route::post('/Deposit/generate/Revenue/edit/save/{id}', 'edit_generate_dr')->name('Deposit.edit_generate_dr');
    // Route::post('/Deposit/generate/Revenue/save/{id}', 'generate_dr')->name('Deposit.generate_dr');
});
#DummyQuotaion
Route::controller(DummyQuotationController::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Dummy/Proposal/index', 'index')->name('DummyQuotation.index');
    Route::get('/Dummy/Proposal/create', 'create')->name('DummyQuotation.create');
    Route::get('/Dummy/Proposal/create/company/{companyID}', 'Contactcreate')->name('DummyQuotation.Contactcreate');
    Route::get('/Dummy/Proposal/create/Guest/{Guest}', 'Guestcreate')->name('DummyQuotation.Guestcreate');
    Route::post('/Dummy/Proposal/create/save', 'save')->name('DummyQuotation.save');
    Route::get('/Dummy/Proposal/selectproduct/company/create/{id}', 'selectProduct')->name('DummyQuotation.SelectProduct');
    Route::post('/Dummy/Proposal/company/create/quotation/{Quotation_ID}', 'savequotation')->name('DummyQuotation.quotation');

    Route::get('/Dummy/Proposal/change-Status/{id}/{status}', 'changestatus')->name('DummyQuotation.changestatus');
    Route::post('/Dummy/Proposal/edit/company/quotation/update/{id}', 'update')->name('DummyQuotation.update');
    Route::get('/Dummy/Proposal/company/product/{Quotation_ID}/addProduct', 'addProduct')->name('DummyQuotation.addProduct');
    //----------------------------------Quotaion select product------------------------------------------------------
    Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttable', 'addProducttable')->name('DummyQuotation.addProducttable');
    Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttableselect', 'addProducttableselect')->name('DummyQuotation.addProducttableselect');
    Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttablemain', 'addProducttablemain')->name('DummyQuotation.addProducttablemain');
    Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProductselect', 'addProductselect')->name('DummyQuotation.addProductselect');
    Route::get('/Dummy/Proposal/selectproduct/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('DummyQuotation.addProducttablecreatemain');
    Route::get('/Dummy/Proposal/edit/quotation/{id}', 'edit')->name('DummyQuotation.edit');
    Route::get('/Dummy/Proposal/cancel/{id}', 'Cancel')->name('DummyQuotation.cancel');
    Route::get('/Dummy/Proposal/Generate/{id}', 'Generate')->name('DummyQuotation.Generate');
    //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
    Route::get('/Dummy/Proposal/cover/document/PDF/{id}', 'sheetpdf')->name('DummyQuotation.sheet');
    //-----------------------------------ส่งเอกสาร-----------------------------------------------------------------------
    Route::post('/Dummy/Proposal/send/documents', 'senddocuments')->name('DummyQuotation.senddocuments');

    Route::get('/Dummy/Proposal/view/{id}', 'view')->name('DummyQuotation.view');

    Route::get('/Dummy/Proposal/view/quotation/LOG/{id}', 'LOG')->name('DummyQuotation.LOG');

    Route::get('/Dummy/Proposal/Revice/{id}', 'Revice')->name('Quotation.Revice');

    Route::get('/Dummy/Proposal/Search/All', 'SearchAll')->name('DummyProposal.Search');
});

#Proposal Request
Route::controller(proposal_request::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Proposal/request/index', 'index')->name('ProposalReq.index');
    Route::get('/Dummy/Proposal/Request/document/view/{id}/{Type}/{createby}', 'view')->name('ProposalReq.view');
    Route::post('/Dummy/Proposal/Request/document/view/Approve/', 'Approve')->name('DummyQuotation.Approve');
    Route::post('/Dummy/Proposal/Request/document/view/Reject/', 'Reject')->name('DummyQuotation.Reject');
    Route::get('/Dummy/Proposal/Request/document/view/Approve/viewApprove/{id}', 'viewApprove')->name('DummyQuotation.viewApprove');
    Route::get('/Proposal/request/log', 'LOG')->name('ProposalReq.log');

    Route::get('/Proposal/request/document/Additional/view/{id}', 'Additional')->name('ProposalReq.Additional');

    Route::post('/Proposal/request/Request/document/view/Approve/', 'Additional_Approve')->name('ProposalReq.Approve');
    Route::post('/Proposal/request/Request/document/view/Reject/', 'Additional_Reject')->name('ProposalReq.Reject');
    Route::get('/Proposal/request/Additional/log', 'Additional_LOG')->name('ProposalReq.LogAdditional');


});

##-------------------------------TemplateController-----------------
Route::controller(Master_TemplateController::class)->middleware(['role:setting', 'together:1'])->group(function () {
    Route::get('/Template/PDF/Template', 'TemplateA1')->name('Template.TemplateA1');
    Route::post('/Template/PDF/Template/save', 'save')->name('Template.save');
    Route::post('/Template/PDF/document/sheet/savetemplate', 'savesheet')->name('Template.savesheet');
});

##-------------------------------document invoice-----------------
Route::controller(Document_invoice::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Document/invoice/index', 'index')->name('invoice.index');
    Route::get('/Document/invoice/Generate/{id}', 'Generate')->name('invoice.Generate');
    Route::get('/Document/invoice/Generate/Additional/{id}', 'Generate_Additional')->name('invoice.Generate_Additional');
    Route::post('/Document/invoice/Generate/save', 'save')->name('invoice.save');
    Route::get('/Invoice/cover/document/PDF/{id}', 'export')->name('invoice.export');
    Route::get('/Document/Request/document/Approve/invoice/{id}', 'Approve')->name('invoice.Approve');
    Route::get('/Document/invoice/Delete/{id}', 'Delete')->name('invoice.Delete');
    Route::get('/Document/invoice/view/{id}', 'view')->name('invoice.view');
    // Route::get('/Document/invoice/revised/{id}', 'edit')->name('invoice.edit');s
    // Route::post('/Document/invoice/update/revised/{id}', 'update')->name('invoice.revised');
    Route::get('/Document/invoice/receive/{id}', 'receive')->name('invoice.receive');
    Route::post('/Document/invoice/receive/check/payment/{id}', 'payment')->name('invoice.payment');
    Route::get('/Document/invoice/view/LOG/{id}', 'LOG')->name('invoice.LOG');
    //---------------------------------------LOG-----------------------------------------------------------
    Route::get('/Document/invoice/view/list/{id}', 'viewList')->name('invoice.viewList');
    //-------------------------------------delete------------------------------------
    Route::get('/Document/invoice/delete/{id}', 'Delete')->name('invoice.delete');

    Route::get(' /Document/invoice/Revise/{id}', 'Revise')->name('invoice.Revise');
    //--------------------------------------Re---------------------------------------

    Route::get('/Document/invoice/viewinvoice/{id}', 'viewinvoice')->name('invoice.viewinvoice');

    Route::get('/Document/invoice/send/email/{id}', 'email')->name('invoice.email');

    Route::post('/Document/invoice/send/detail/email/{id}', 'sendemail')->name('invoice.sendemail');

    Route::get(' /Document/invoice/Generate/to/Re/{id}', 'GenerateRe')->name('invoice.GenerateRe');

    Route::post('/Document/invoice/cancel/{id}', 'cancel')->name('Proposal.cancel');
    //----------------------------------document cover ใบปะหน้า--------------------------------------------------------
    Route::get('/Proposal/cover/document/PDF/{id}', 'sheetpdf')->name('invoice.sheet');


    Route::get('/Document/invoice/data/{id}', 'deposit')->name('invoice.deposit');
    Route::get('/Document/invoice/data/edit/{id}', 'deposit_edit')->name('invoice.deposit_edit');

    Route::get('/invoice/get/proposal', 'getproposal');
    Route::get('/invoice/get/allTable', 'getallTable');
    Route::get('/invoice/get/PendingTable', 'PendingTable');
    Route::get('/invoice/get/ApprovedTable', 'ApprovedTable');
    Route::get('/invoice/get/CancelTable', 'CancelTable');
    Route::get('/invoice/get/CompleteTable', 'CompleteTable');
});

##-------------------------------document receipt-----------------
Route::controller(BillingFolioController::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Document/BillingFolio/index', 'index')->name('BillingFolio.index');
    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/multi/{id}', 'createmulti')->name('BillingFolio.createmulti');
    Route::post('/Document/BillingFolio/Proposal/invoice/Generate/createmulti/bill/{id}', 'spiltebill')->name('BillingFolio.spiltebill');
    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/{id}', 'create')->name('BillingFolio.PaidInvoice');
    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/{id}', 'EditPaidInvoice')->name('BillingFolio.EditPaidInvoice');
    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Data/{id}', 'PaidInvoiceData')->name('BillingFolio.PaidInvoiceData');
    Route::get('/Document/BillingFolio/Proposal/invoice/select/data/guest/{id}', 'SelectData')->name('BillingFolio.SelectData');
    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/cheque/{id}', 'cheque');
    Route::get('/Document/BillingFolio/Proposal/invoice/preview/{id}', 'previewPdf')->name('BillingFolio.previewPdf');
    Route::get('/Document/BillingFolio/Proposal/invoice/CheckPI/{id}', 'CheckPI')->name('BillingFolio.CheckPI');
    // //-------------------------------------save------------------------------------
    Route::post('/Document/BillingFolio/Proposal/invoice/Generate/multi/bill/save', 'savemulti')->name('BillingFolio.savemulti');
    // //-------------------------------------save------------------------------------
    Route::post('/Document/BillingFolio/Proposal/invoice/Generate/save', 'saveone')->name('BillingFolio.saveone');
    //-----------------------------------------update------------------------------------
    Route::post('/Document/BillingFolio/Proposal/invoice/Generate/update/{id}', 'update')->name('BillingFolio.update');
    // //-------------------------------------view------------------------------------
    Route::get('/Document/BillingFolio/Proposal/invoice/view/{id}', 'view')->name('receipt.view');
    Route::get('/Document/BillingFolio/Proposal/invoice/export/{id}', 'export')->name('receipt.export');
    Route::get('/Document/BillingFolio/Proposal/invoice/log/{id}', 'log')->name('receipt.log');
    Route::get('/Receipt/Quotation/view/quotation/view/{id}', 'QuotationView')->name('receipt.QuotationView');
    //---------------------deposit one paid---------------------------
    Route::get('/Document/BillingFolio/Deposit/generate/Revenue/{id}', 'deposit_re')->name('BillingFolio.generate');
    Route::post('/Document/BillingFolio/Proposal/Deposit/Revenue/generate/save', 'savedeposit')->name('BillingFolio.savedeposit');
    Route::get('/Document/BillingFolio/deposit/view/{id}', 'depositView')->name('BillingFolio.depositView');

    Route::get('/Document/BillingFolio/Deposit/edit/{id}', 'deposit_edit')->name('BillingFolio.deposit_edit');
    Route::post('/Document/BillingFolio/Deposit/update/{id}', 'deposit_update')->name('BillingFolio.deposit_update');
    Route::post('/Document/BillingFolio/Deposit/cancel/{id}', 'depositcancel')->name('Deposit.depositcancel');
 //---------------------Additional one paid---------------------------
    Route::get('/Document/BillingFolio/Additional/Charge/edit/{id}', 'additional_edit')->name('BillingFolio.additional_edit');
    Route::post('/Document/BillingFolio/Additional/Charge/update/{id}', 'additional_update')->name('BillingFolio.additional_update');
    Route::get('/Document/BillingFolio/Additional/generate/Receive/{id}', 'additional_re')->name('BillingFolio.additional_re');
    Route::post('/Document/BillingFolio/Additional/generate/save', 'additional_save')->name('BillingFolio.additional_save');

    Route::get('/Document/BillingFolio/Proposal/invoice/Generate/Paid/No/AD/{id}', 'create_one')->name('BillingFolio.create_one');

});
Route::controller(Additional::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Document/Additional/Charge/index', 'index')->name('Additional.index');
    Route::get('/Document/Additional/Charge/select', 'select')->name('Additional.select');
    Route::get('/Document/Additional/Charge/create/{id}', 'create')->name('Additional.create');
    Route::post('/Document/Additional/Charge/save/{id}', 'save')->name('Additional.save');
    Route::get('/Document/Additional/Charge/document/PDF/{id}', 'sheetpdf')->name('BillingFolioOver.sheet');
    Route::get('/Document/Additional/Charge/log/{id}', 'log')->name('Additional.log');
    Route::get('/Document/Additional/Charge/edit/{id}', 'edit')->name('Additional.edit');
    Route::post('/Document/Additional/Charge/update/{id}', 'update')->name('Additional.update');
    Route::get('/Document/Additional/Charge/view/{id}', 'view')->name('Additional.view');
    Route::post('/Document/Additional/Charge/Cancel/{id}', 'Cancel')->name('Additional.Cancel');
    Route::get('/Document/Additional/Charge/Delete/{id}', 'Delete')->name('Additional.Delete');
    Route::get('/Document/Additional/Charge/Revice/{id}', 'Revice')->name('Additional.Revice');

    Route::get('/Document/BillingFolio/{Quotation_ID}/addProduct', 'addProduct')->name('BillingFolioOver.addProduct');
    Route::get('/Document/BillingFolio/{Quotation_ID}/addProductselect', 'addProductselect')->name('BillingFolioOver.addProductselect');
    Route::get('/Document/BillingFolio/{Quotation_ID}/addProducttablecreatemain', 'addProducttablecreatemain')->name('BillingFolioOver.addProducttablecreatemain');

    Route::post(' /Document/Additional/Charge/select/save', 'select_save')->name('Additional.select_save');
    Route::get('/Document/Additional/Charge/select/create', 'select_create')->name('Additional.select_create');

    Route::post('billingover-proposal-search-table', 'search_table_billingover_proposal');
    Route::post('billingover-proposal-paginate-table', 'paginate_table_billingover_proposal');

    Route::get('/Document/BillingFolio/Proposal/Over/Generate/{id}', 'Generate')->name('BillingFolioOver.Generate');

    Route::get('/Document/BillingFolio/Proposal/Additional/prewive/{id}', 'PaidDataprewive')->name('BillingFolioOver.PaidDataprewive');
    Route::post('/Document/BillingFolio/Proposal/Additional/select', 'Quotation')->name('Additional.Quotation');
    Route::get('/Document/BillingFolio/Proposal/Additional/createnew/{id}', 'deposit_pd');
    // Route::post('/Document/BillingFolio/Proposal/Additional/Generate/save', 'savere')->name('BillingFolioOver.savere');
    // Route::get('/Document/BillingFolio/Proposal/Additional/receipt/Edit/{id}', 'EditPaid')->name('BillingFolioOver.EditPaid');
    // Route::get('/Document/BillingFolioOverbill/Proposal/invoice/export/{id}', 'export')->name('BillingFolioOver.export');

    // Route::get('/Document/BillingFolio/Over/log/re/{id}', 'logre')->name('BillingFolioOver.logre');
    // Route::post('/Document/BillingFolio/Over/Generate/update/{id}', 'update_re')->name('BillingFolioOver.update_re');
});

Route::controller(Banquet_Event_OrderController::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Banquet/Event/Order/index', 'index')->name('Banquet.index');
    Route::get('/Banquet/Event/Order/create/{id}', 'create')->name('Banquet.create');
    Route::post('/Banquet/Event/Order/save/detail/{id}', 'save')->name('Banquet.save');
    Route::get('/Banquet/Event/Order/create/room/{id}', 'create_room')->name('Banquet.create_room');
    Route::post('/Banquet/Event/Order/save/detail/room/{id}', 'save_room')->name('Banquet.save_room');

    Route::get('/Banquet/Event/Order/edit/view/{id}', 'edit')->name('Banquet.edit');
    Route::post('/Banquet/Event/Order/save/event/details', 'save_event')->name('Banquet.save_event');
    Route::post('/Banquet/Event/Order/save/schedule/details', 'save_schedule')->name('Banquet.save_schedule');
    Route::post('/Banquet/Event/Order/delete/schedule/details', 'delete_schedule')->name('Banquet.delete_schedule');
    Route::post('/Banquet/Event/Order/save/Asset/details', 'save_asset')->name('Banquet.save_asset');
    Route::post('/Banquet/Event/Order/delete/Asset/details', 'delete_asset')->name('Banquet.delete_asset');
});
Route::controller(ReceiveChequeController::class)->middleware(['role:document', 'together:1'])->group(function () {
    Route::get('/Document/ReceiveCheque/index', 'index')->name('ReceiveCheque.index');
    Route::get('/Document/ReceiveCheque/save', 'save')->name('ReceiveCheque.save');
    Route::get('/Document/ReceiveCheque/view/{id}', 'view')->name('ReceiveCheque.view');
    Route::get('/Document/ReceiveCheque/edit/{id}', 'edit')->name('ReceiveCheque.edit');
    Route::get('/Document/ReceiveCheque/update', 'update')->name('ReceiveCheque.update');
    Route::get('/Document/ReceiveCheque/Approved/{id}', 'Approved')->name('ReceiveCheque.Approved');
    Route::get('/Document/ReceiveCheque/Number', 'NumberID');
});
Route::controller(Master_Address_System::class)->middleware(['role:document', 'together:1'])->group(function () {
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

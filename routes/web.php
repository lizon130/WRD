<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\AlumniSectionController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DryerController;
use App\Http\Controllers\Backend\DryProcessIEController;
use App\Http\Controllers\Backend\DryProcessManualController;
use App\Http\Controllers\Backend\DryerProcessManualController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\FrontendSectionController;
use App\Http\Controllers\Backend\GamesController;
use App\Http\Controllers\Backend\MachineTransferController;
use App\Http\Controllers\Backend\PublicMachineDashboardController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SecondDryProcessEntryController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WashReportEntryController;
use App\Http\Controllers\Backend\WashReportManPowerController;
use App\Http\Controllers\Backend\DashboardSummeryController;
use App\Http\Controllers\WashReportDashboard;
use App\Http\Controllers\WashReportDashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


// Add to routes/web.php for testing
Route::get('/test-real-email', function () {
    try {
        // Get email from query parameter, or use default
        $recipient = request()->get('email', 'lizon_mis@tusuka.com');

        Mail::raw('This is a test email from the Wash Report system.', function ($message) use ($recipient) {
            $message->to($recipient)
                ->subject('Test Email from Wash Report System');
        });

        return "Test email sent to {$recipient}! Check your inbox.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});



Route::get('/Machine/Transfer/Dashboard', [PublicMachineDashboardController::class, 'dashboard'])->name('dashboard');
Route::match(['GET', 'POST'], '/Machine/Transfer/Dashboard/data', [PublicMachineDashboardController::class, 'dashboardData'])->name('dashboard.data');

// end public Dashboard Routes


Route::get('/test-laravel-connection', function () {
    try {
        DB::connection('sqlsrv')->getPdo();
        return "Laravel SQL Server connection SUCCESS!";
    } catch (\Exception $e) {
        return "Laravel SQL Server connection FAILED: " . $e->getMessage();
    }
});


// Auth route
Route::post('login-post', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('signup', [LoginController::class, 'signup'])->name('registration.post');

// admin route start
Route::get('/', function () {
    return view('backend.auth.login');
})->name('admin');



Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('profile', [LoginController::class, 'adminProfile'])->name('admin.profile');
    Route::post('profile/update', [LoginController::class, 'adminProfileUpdate'])->name('admin.profile.update');
    Route::get('profile/setting', [LoginController::class, 'adminProfileSetting'])->name('admin.profile.setting');
    Route::post('profile/change/password', [LoginController::class, 'adminChangePassword'])->name('admin.change.password');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.index');

    // Route::any('{any}',[FrontendController::class,'catchAll'])->where('any', '.*');

    Route::group(['prefix' => '/user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user');
        Route::get('/get/list', [UserController::class, 'getList']);
        Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::any('/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
        Route::post('/change', [UserController::class, 'changePassword'])->name('admin.user.changepassword');
        Route::post('/admin/user/update-status', [UserController::class, 'updateStatus'])->name('user.updateStatus');
    });

    Route::group(['prefix' => '/role'], function () {
        Route::get('/generate/right/{mdule_name}', [RoleController::class, 'generate'])->name('admin.role.right.generate');

        Route::get('/', [RoleController::class, 'index'])->name('admin.role');
        Route::get('/get/role/list', [RoleController::class, 'getRoleList']);
        Route::get('/create', [RoleController::class, 'create'])->name('admin.role.create');
        Route::post('/store', [RoleController::class, 'store'])->name('admin.role.store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::any('/update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete');

        Route::get('/right', [RoleController::class, 'right'])->name('admin.role.right');
        Route::get('/get/right/list', [RoleController::class, 'getRightList']);
        Route::post('/right/store', [RoleController::class, 'rightStore'])->name('admin.role.right.store');
        Route::get('/right/edit/{id}', [RoleController::class, 'editRight'])->name('admin.role.right.edit');
        Route::any('/right/update/{id}', [RoleController::class, 'roleRightUpdate'])->name('admin.role.right.update');
        Route::get('/right/delete/{id}', [RoleController::class, 'rightDelete'])->name('admin.role.right.delete');
    });

    // ..................................  WashTusuka Routes ....................................

    // unit routes
    Route::group(['prefix' => '/unit'], function () {
        Route::get('/', [UnitController::class, 'index'])->name('admin.unit.user');
        Route::get('/get/list', [UnitController::class, 'getList']);
        Route::post('/store', [UnitController::class, 'store'])->name('admin.unit.store');
        Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('admin.unit.edit');
        Route::any('/update/{id}', [UnitController::class, 'update'])->name('admin.unit.update');
        Route::get('/delete/{id}', [UnitController::class, 'delete'])->name('admin.unit.delete');
    });

    Route::get('/admin/unit/get/workorders/list', [UnitController::class, 'getWorkOrdersList'])->name('unit.workorders.list');

    //machine transfer
    Route::group(['prefix' => '/machine-tranfer'], function () {
        Route::get('/', [MachineTransferController::class, 'index'])->name('admin.machineTrans.user');
        Route::get('/get/list', [MachineTransferController::class, 'getList'])->name('admin.machineTrans.getList');
        Route::post('/store', [MachineTransferController::class, 'store'])->name('admin.machineTrans.store');
        Route::get('/edit/{id}', [MachineTransferController::class, 'edit'])->name('admin.machineTrans.edit');
        Route::any('/update/{id}', [MachineTransferController::class, 'update'])->name('admin.machineTrans.update');
        Route::get('/delete/{id}', [MachineTransferController::class, 'delete'])->name('admin.machineTrans.delete');
        Route::get('/get-unit-machines/{id}', [MachineTransferController::class, 'getUnitMachines'])->name('admin.machineTrans.getUnitMachines');

        //..............********** Dashboard ***********..............//
        Route::get('/tranfer/dashboard', [MachineTransferController::class, 'dashboard'])->name('admin.machineTrans.dashboard');
        Route::post('/tranfer/dashboard/data', [MachineTransferController::class, 'dashboardData'])->name('admin.machineTrans.dashboard.data');
    });

    Route::get('machine-tranfer/get-units-by-date/{date}', [MachineTransferController::class, 'getUnitsByDate'])->name('admin.machineTrans.getUnitsByDate');
    Route::get('machine-tranfer/refresh/{date}', [MachineTransferController::class, 'refreshTransfersForDate'])->name('admin.machineTrans.refresh');
    Route::get('machine-tranfer/fix-all', [MachineTransferController::class, 'fixAllTransfers'])->name('admin.machineTrans.fixAll');
    Route::post('machine-transfers/wash-dashboard-data', [MachineTransferController::class, 'washDashboardData'])->name('admin.machineTrans.wash.dashboard.data');

    // Machine Transfer Approval Routes
    Route::get('machine-transfer/approvals', [MachineTransferController::class, 'approvals'])->name('machine-transfer.approvals');
    Route::get('machine-transfer/get/pending-list', [MachineTransferController::class, 'getPendingList'])->name('machine-transfer.pending-list');
    Route::post('machine-transfer/approve/{id}', [MachineTransferController::class, 'approve'])->name('machine-transfer.approve');
    Route::post('machine-transfer/reject/{id}', [MachineTransferController::class, 'reject'])->name('machine-transfer.reject');
    Route::get('machine-transfer/{id}', [MachineTransferController::class, 'show'])->name('machine-transfer.show');

    // ..................................  WashTusuka Routes ....................................

    // Dashboard 2

    Route::group(['prefix' => '/wash-dashboard'], function () {
        Route::get('/', [WashReportDashboardController::class, 'index'])->name('admin.washReportDashboard');
        Route::get('/get-data', [WashReportDashboardController::class, 'getUnitData'])->name('admin.wash-report-dashboard.get-data');
        Route::get('/get-latest-date', [WashReportDashboardController::class, 'getLatestDate'])->name('admin.wash-report-dashboard.get-latest-date');

        // New Dry Process routes
        Route::get('/first-dry-process', [WashReportDashboardController::class, 'getFirstDryProcessData'])->name('admin.wash-report-dashboard.first-dry-process');
        Route::get('/second-dry-process', [WashReportDashboardController::class, 'getSecondDryProcessData'])->name('admin.wash-report-dashboard.second-dry-process');

        Route::get('/dryer-data', [WashReportDashboardController::class, 'getDryerData'])->name('admin.wash-report-dashboard.dryer-data');
        Route::get('/transfer-data', [WashReportDashboardController::class, 'getTransferData'])->name('admin.wash-report-dashboard.transfer-data');

        // Remarks management routes
        Route::post('/save-remark', [WashReportDashboardController::class, 'saveRemark'])->name('admin.wash-report-dashboard.save-remark');
        Route::get('/get-remarks/{unit}', [WashReportDashboardController::class, 'getRemarks'])->name('admin.wash-report-dashboard.get-remarks');
        Route::post('/update-remark', [WashReportDashboardController::class, 'updateRemark'])->name('admin.wash-report-dashboard.update-remark');

        // Dry Process Remarks management routes
        Route::post('/save-dry-process-remark', [WashReportDashboardController::class, 'saveDryProcessRemark'])->name('admin.wash-report-dashboard.save-dry-process-remark');
        Route::post('/update-dry-process-remark', [WashReportDashboardController::class, 'updateDryProcessRemark'])->name('admin.wash-report-dashboard.update-dry-process-remark');

        Route::get('/capp-machine-status', [WashReportDashboardController::class, 'getCappMachineStatus'])
            ->name('admin.wash-report-dashboard.capp-machine-status');

        Route::post('/download-pdf', [WashReportDashboardController::class, 'downloadPdf'])
            ->name('admin.wash-report-dashboard.download-pdf');
    });

    // Dashboard Summary Routes (Unit Details only)
    Route::group(['prefix' => 'dashboard-summary'], function () {
        Route::get('/', [DashboardSummeryController::class, 'index'])->name('dashboard.summary');
        Route::get('/get-data', [DashboardSummeryController::class, 'getUnitData'])->name('dashboard.summary.get-data');
        Route::get('/get-latest-date', [DashboardSummeryController::class, 'getLatestDate'])->name('dashboard.summary.get-latest-date');
        Route::get('/get-balance', [DashboardSummeryController::class, 'getBalanceData'])->name('dashboard.summary.get-balance');
        Route::get('/generate-pdf', [DashboardSummeryController::class, 'generatePdf'])->name('dashboard.summary.generate-pdf');
    });


    // Games

    Route::group(['prefix' => '/snake-games'], function () {
        Route::get('/', [GamesController::class, 'index'])->name('admin.games');
    });


    // Dryer Section Routes
    Route::group(['prefix' => '/dryer'], function () {
        Route::get('/', [DryerController::class, 'index'])->name('admin.dryer.user');
        Route::get('/get/list', [DryerController::class, 'getList'])->name('admin.dryer.get.list');
        Route::get('/create/form', [DryerController::class, 'createForm'])->name('admin.dryer.create.form');
        Route::post('/store', [DryerController::class, 'store'])->name('admin.dryer.store');
        Route::get('/edit/{id}', [DryerController::class, 'edit'])->name('admin.dryer.edit');
        Route::post('/update/{id}', [DryerController::class, 'update'])->name('admin.dryer.update');
        Route::get('/delete/{id}', [DryerController::class, 'delete'])->name('admin.dryer.delete');
        Route::get('/get/latest', [DryerController::class, 'getLatestValues'])->name('admin.dryer.get.latest');
    });


    Route::group(['prefix' => '/ManPower'], function () {
        Route::get('/', [WashReportManPowerController::class, 'index'])->name('admin.manpower.user');
        Route::get('/get/list', [WashReportManPowerController::class, 'getList'])->name('admin.manpower.get.list');
        Route::get('/create/form', [WashReportManPowerController::class, 'createForm'])->name('admin.manpower.create.form');
        Route::post('/store', [WashReportManPowerController::class, 'store'])->name('admin.manpower.store');
        Route::get('/edit/{id}', [WashReportManPowerController::class, 'edit'])->name('admin.manpower.edit');
        Route::post('/update/{id}', [WashReportManPowerController::class, 'update'])->name('admin.manpower.update');
        Route::get('/delete/{id}', [WashReportManPowerController::class, 'delete'])->name('admin.manpower.delete');
    });

    Route::group(['prefix' => '/DryProcessIE'], function () {
        Route::get('/', [DryProcessIEController::class, 'index'])->name('admin.dryprocessie.user');
        Route::get('/get/list', [DryProcessIEController::class, 'getList'])->name('admin.dryprocessie.get.list');
        Route::get('/create/form', [DryProcessIEController::class, 'createForm'])->name('admin.dryprocessie.create.form');
        Route::post('/store', [DryProcessIEController::class, 'store'])->name('admin.dryprocessie.store');
        Route::get('/edit/{id}', [DryProcessIEController::class, 'edit'])->name('admin.dryprocessie.edit');
        Route::post('/update/{id}', [DryProcessIEController::class, 'update'])->name('admin.dryprocessie.update');
        Route::get('/delete/{id}', [DryProcessIEController::class, 'delete'])->name('admin.dryprocessie.delete');
    });

    Route::group(['prefix' => '/wash-report-entry'], function () {
        Route::get('/', [WashReportEntryController::class, 'index'])->name('admin.wash-report-entry.index');
        Route::get('/get/list', [WashReportEntryController::class, 'getList'])->name('admin.wash-report-entry.get.list');
        Route::get('/create/form', [WashReportEntryController::class, 'createForm'])->name('admin.wash-report-entry.create.form');
        Route::post('/store', [WashReportEntryController::class, 'store'])->name('admin.wash-report-entry.store');
        Route::get('/edit/{id}', [WashReportEntryController::class, 'edit'])->name('admin.wash-report-entry.edit');
        Route::post('/update/{id}', [WashReportEntryController::class, 'update'])->name('admin.wash-report-entry.update');
        Route::get('/delete/{id}', [WashReportEntryController::class, 'delete'])->name('admin.wash-report-entry.delete');
    });

    Route::group(['prefix' => '/second-dry-process'], function () {
        Route::get('/', [SecondDryProcessEntryController::class, 'index'])->name('admin.second-dry-process.index');
        Route::get('/get/list', [SecondDryProcessEntryController::class, 'getList'])->name('admin.second-dry-process.get.list');
        Route::get('/create/form', [SecondDryProcessEntryController::class, 'createForm'])->name('admin.second-dry-process.create.form');
        Route::post('/store', [SecondDryProcessEntryController::class, 'store'])->name('admin.second-dry-process.store');
        Route::get('/edit/{id}', [SecondDryProcessEntryController::class, 'edit'])->name('admin.second-dry-process.edit');
        Route::post('/update/{id}', [SecondDryProcessEntryController::class, 'update'])->name('admin.second-dry-process.update');
        Route::get('/delete/{id}', [SecondDryProcessEntryController::class, 'delete'])->name('admin.second-dry-process.delete');
    });

    Route::group(['prefix' => '/dry-process-manual'], function () {
        Route::get('/', [DryProcessManualController::class, 'index'])->name('admin.dry-process-manual.index');
        Route::get('/get/list', [DryProcessManualController::class, 'getList'])->name('admin.dry-process-manual.get.list');
        Route::get('/create/form', [DryProcessManualController::class, 'createForm'])->name('admin.dry-process-manual.create.form');
        Route::post('/store', [DryProcessManualController::class, 'store'])->name('admin.dry-process-manual.store');
        Route::get('/edit/{id}', [DryProcessManualController::class, 'edit'])->name('admin.dry-process-manual.edit');
        Route::post('/update/{id}', [DryProcessManualController::class, 'update'])->name('admin.dry-process-manual.update');
        Route::get('/delete/{id}', [DryProcessManualController::class, 'delete'])->name('admin.dry-process-manual.delete');
    });

    // Dry Process Remarks management routes
    Route::post('/save-dry-process-remark', [WashReportDashboardController::class, 'saveDryProcessRemark'])->name('admin.wash-report-dashboard.save-dry-process-remark');
    Route::post('/update-dry-process-remark', [WashReportDashboardController::class, 'updateDryProcessRemark'])->name('admin.wash-report-dashboard.update-dry-process-remark');
    Route::get('/get-dry-process-remarks', [WashReportDashboardController::class, 'getDryProcessRemarks'])->name('admin.wash-report-dashboard.get-dry-process-remarks');


    Route::group(['prefix' => '/dryer-process-manual'], function () {
        Route::get('/', [DryerProcessManualController::class, 'index'])->name('admin.dryer-process-manual.index');
        Route::get('/get/list', [DryerProcessManualController::class, 'getList'])->name('admin.dryer-process-manual.get.list');
        Route::get('/create/form', [DryerProcessManualController::class, 'createForm'])->name('admin.dryer-process-manual.create.form');
        Route::post('/store', [DryerProcessManualController::class, 'store'])->name('admin.dryer-process-manual.store');
        Route::get('/edit/{id}', [DryerProcessManualController::class, 'edit'])->name('admin.dryer-process-manual.edit');
        Route::post('/update/{id}', [DryerProcessManualController::class, 'update'])->name('admin.dryer-process-manual.update');
        Route::get('/delete/{id}', [DryerProcessManualController::class, 'delete'])->name('admin.dryer-process-manual.delete');
    });

    Route::group(['prefix' => '/event'], function () {
        Route::get('/', [EventController::class, 'index'])->name('admin.event');
        Route::get('/get/list', [EventController::class, 'getList']);
        Route::post('/store', [EventController::class, 'store'])->name('admin.event.store');
        Route::get('/edit/{id}', [EventController::class, 'edit'])->name('admin.event.edit');
        Route::any('/update/{id}', [EventController::class, 'update'])->name('admin.event.update');
        Route::get('/delete/{id}', [EventController::class, 'delete'])->name('admin.event.delete');
    });


    Route::group(['prefix' => '/setting'], function () {
        Route::get('/general', [SettingController::class, 'general'])->name('admin.setting.general');
        Route::get('/static-content', [SettingController::class, 'staticContent'])->name('admin.setting.static.content');
        Route::get('/journey-unity-content', [SettingController::class, 'journeyUnityContent'])->name('admin.setting.journey.unity.content');
        Route::get('/setting-alumni-content', [SettingController::class, 'SettingAlumniContent'])->name('admin.setting.alumni-content');
        Route::get('/legal-content', [SettingController::class, 'legalContent'])->name('admin.setting.legal.content');
        Route::post('/update', [SettingController::class, 'update'])->name('admin.setting.update');
        Route::get('/change-language', [SettingController::class, 'changeLanguage'])->name('admin.setting.change.language');
    });

    // Frontend Section
    Route::group(['prefix' => '/frontend-section'], function () {
        Route::get('/journey', [FrontendSectionController::class, 'journey'])->name('admin.frontend.journey');
        Route::post('/update', [FrontendSectionController::class, 'update'])->name('admin.frontend.update');
        Route::post('/alumniBannerSection', [AlumniSectionController::class, 'update'])->name('admin.alumniBannerSection.update');
    });
});

Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
// admin route end
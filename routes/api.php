<?php

use App\Models\Registeredevent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDataController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\MachineControllerApi;
use App\Http\Controllers\ProfileApiController;
use App\Http\Controllers\Api\ClassesController;
use App\Http\Controllers\Api\NewsapiController;
use App\Http\Controllers\Api\SegmentController;
use App\Http\Controllers\Api\EventapiController;
use App\Http\Controllers\Api\TeachersController;
use App\Http\Controllers\Api\RoleRightController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Api\PreferanceController;
use App\Http\Controllers\Api\ContactUsAPIController;
use App\Http\Controllers\Api\DeliveryModeController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\DepartmentAPIController;
use App\Http\Controllers\Api\EventGalleryapiController;
use App\Http\Controllers\Api\RegisteredEventController;
use App\Http\Controllers\Api\CampusGallaryAPIController;
use App\Http\Controllers\Api\GeneralSettingAPIController;
use App\Http\Controllers\Api\QualificationTypeController;
use App\Http\Controllers\Api\MachineTransferApiController;
use App\Http\Controllers\Backend\PublicMachineDashboardController;


// Machine Transfer Dashboard API Routes
Route::prefix('Wash-Machine')->group(function () {
    // Dashboard page data (units for filter)
    Route::get('/dashboard-units', [MachineTransferApiController::class, 'getDashboardUnits']);

    // Dashboard data with filters
    Route::get('/dashboard-data', [MachineTransferApiController::class, 'getDashboardData']);

    // Get specific unit details
    Route::get('/unit/{id}', [MachineTransferApiController::class, 'getUnitDetails']);

    // Get date-wise unit details
    Route::get('/unit-date/{unitId}/{date}', [MachineTransferApiController::class, 'getUnitDateDetails']);
    Route::get('/date-counts', [MachineTransferApiController::class, 'getTodaySummation']);

    // Get transfer history
    Route::get('/transfer-history', [MachineTransferApiController::class, 'getTransferHistory']);

    // Get summary statistics
    Route::get('/summary', [MachineTransferApiController::class, 'getSummary']);

    // Get chart data
    Route::get('/chart-data', [MachineTransferApiController::class, 'getChartData']);

    // Get wash production data
    Route::get('/wash-production/{date}', [MachineTransferApiController::class, 'getWashProductionData']);
});


Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});


Route::get('/wash-production', [MachineControllerApi::class, 'getWashProductionData']);
Route::get('/wash-production/{date}', [MachineControllerApi::class, 'getWashProductionByDate']);
Route::get('/wash-production/unit/{unitId}', [MachineControllerApi::class, 'getWashProductionByUnit']);
Route::get('/wash-production/summary', [MachineControllerApi::class, 'getWashProductionSummary']);

// ........... Tusuka External API Routes ...........

Route::prefix('external')->group(function () {
    Route::get('/users', [ApiDataController::class, 'getUsers']);
    Route::get('/work-orders', [ApiDataController::class, 'getWorkOrders']);
    Route::get('/transactions', [ApiDataController::class, 'getDeliveryWashTransactionsAll']);
});

// ........... Tusuka External API Routes ...........

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/preferences', [PreferanceController::class, 'preferences']);
    Route::controller(RoleRightController::class)->group(function () {
        Route::get('/role-right-permissions', 'list');
    });

    // newly added routes
    Route::get('/user/details/{id}', [UserController::class, 'details']);
    Route::get('/user/search', [UserController::class, 'searchFilter']);

    // Protected routes
    Route::middleware(['auth:api', 'throttle:api'])->group(function () {
        // Auth routes
        Route::post('/verify-token', [AuthController::class, 'verifyToken']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Course routes
        Route::controller(CourseController::class)->group(function () {
            Route::get('/courses', 'list');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'list');
            Route::post('/user/store', 'store');
        });

        Route::controller(SegmentController::class)->group(function () {
            Route::get('/segments', 'list');
            Route::post('/segment/store', 'store');
            Route::any('/segment/update/{id}', 'update');
            Route::delete('/segment/delete/{id}', 'delete');
        });

        Route::controller(QualificationTypeController::class)->group(function () {
            Route::get('/qualificationtype', 'list');
            Route::post('/qualificationtype/store', 'store');
            Route::any('/qualificationtype/update/{id}', 'update');
            Route::delete('/qualificationtype/delete/{id}', 'delete');
        });

        Route::controller(DeliveryModeController::class)->group(function () {
            Route::get('/deliverymode', 'list');
            Route::post('/deliverymode/store', 'store');
            Route::any('/deliverymode/update/{id}', 'update');
            Route::delete('/deliverymode/delete/{id}', 'delete');
        });
    });

    // Registration
    Route::post('/register', [RegistrationController::class, 'register']);


    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});
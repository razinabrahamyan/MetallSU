<?php


use App\Http\Controllers\CostController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/get-tab-items/{id?}',[CostController::class, 'getTabItems']);
Route::get('/set-active-tab/{id}',[CostController::class, 'setActiveTab']);
Route::post('/update-items',[CostController::class, 'updateItems']);


Route::get('/get-base-tab-categories/{id?}',[WorkerController::class, 'getTabItems']);
Route::get('/set-base-active-tab/{id}',[WorkerController::class, 'setActiveTab']);
Route::post('/update-categories',[WorkerController::class, 'updateCategories']);


Route::group(['prefix'=>'workers'], function () {
    Route::get('/get-select-bases',[WorkerController::class, 'getSelectBases']);
    Route::post('/add-worker',[WorkerController::class, 'addWorker']);
    Route::get('/lazyLoad',[WorkerController::class, 'workersLazyLoad']);
    Route::get('/get-worker-data/{id}',[WorkerController::class, 'getWorkerData']);
    Route::post('/update',[WorkerController::class, 'update']);
    Route::post('/firing',[WorkerController::class, 'firing']);
    Route::post('/re-employ',[WorkerController::class, 'reEmploy']);
    Route::post('/category-change',[WorkerController::class, 'categoryChange']);
    Route::post('/update-firing',[WorkerController::class, 'updateFiring']);
    Route::post('/update-re-employ',[WorkerController::class, 'updateReEmploy']);
    Route::post('/update-category-change',[WorkerController::class, 'updateCategoryChange']);
});

Route::group(['prefix'=>'salary'], function () {
    Route::get('/get-select-bases/{year?}/{month?}',[SalaryController::class, 'getSelectBases']);
    Route::post('/pay',[SalaryController::class, 'pay']);
    Route::post('/holiday',[SalaryController::class, 'holiday']);
    Route::post('/day-off',[SalaryController::class, 'dayOff']);
    Route::post('/salary-increase',[SalaryController::class, 'salaryIncrease']);
    Route::post('/update-salary-increase',[SalaryController::class, 'updateSalaryIncrease']);

});

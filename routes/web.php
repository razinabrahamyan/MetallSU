<?php

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

use App\Http\Controllers\CostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogisticsController;
use App\Http\Controllers\Mytest\TestController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\QrCodeGeneratorController;
use App\Http\Controllers\User\ResponsibleController;
use App\Http\Controllers\User\StorageController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


//Логин
Route::get('/login', function () {
    return view('auth.login');
});

//Авторизация
Route::get('/test', [TestController::class, 'test']);
Route::get('/test1', [TestController::class, 'test1']);
Route::get('/view', [TestController::class, 'testView']);
Route::post('/dd', [TestController::class, 'ddRequest'])->name('ddd');
Auth::routes();

//Базовый роутинг для залогиненых пользователей
Route::group(['middleware' => ['auth']], function () {
    /* ТАБЛИЦЫ */
    //Модефикация таблиц
    Route::get('/', [HomeController::class, 'index'])
        ->name('index');
    //Создание новой таблицы


    Route::get('store',[StorageController::class, 'index'])->name('storage.index');
    Route::get('store/{index}',[StorageController::class, 'show'])->name('storage.show');
    Route::post('add-elem-to-store',[StorageController::class, 'addItem'])->name('storage.add');
    Route::post('create-store',[StorageController::class, 'createStorage'])->name('storageCreate');
    Route::post('deduct-item',[StorageController::class, 'deductItem'])->name('storage.deduct');
    Route::get('/deduction/{index}',[StorageController::class, 'editDeductItem'])->name('edit.deduct');
    Route::get('/item/{index}',[StorageController::class, 'editStorageItem'])->name('edit.storage.item');
    Route::post('/deduction/update',[StorageController::class, 'updateDeductItem'])->name('update.deduct');
    Route::post('/item/update',[StorageController::class, 'updateStorageItem'])->name('update.storage.item');
    Route::get('/deductions/search',[StorageController::class, 'searchDeduction'])->name('search.deduct');
    Route::get('/store-items/search',[StorageController::class, 'searchStoreItem'])->name('search.store.item');
    Route::get('/storage-calculate/{index}',[StorageController::class, 'toCalculationPage'])->name('storage.calculate');
    Route::get('/storage-history/{index}',[StorageController::class, 'toHistoryPage'])->name('storage.history');
    Route::get('/storage-get-driver-data',[StorageController::class, 'getStorageData'])->name('storage.get.data.info');
    Route::get('/set-history-filter',[StorageController::class, 'setHistoryFilter'])->name('history.set.filter');

    Route::get('/lists',[PositionController::class, 'lists'])->name('lists.index');
    Route::get('/lists/{id}',[PositionController::class, 'listEdit'])->name('lists.edit');
    Route::post('/list-update',[PositionController::class, 'listUpdate'])->name('lists.update');
    Route::post('/list-add',[PositionController::class, 'listAdd'])->name('lists.add');



    Route::get('/cost', [CostController::class, 'cost'])->name('cost');
    Route::get('/cost/lazyLoad', [CostController::class, 'costLazyLoad'])->name('costLazyLoad');

    Route::post('/cost-store', [CostController::class, 'store'])->name('cost.store');
    Route::post('/get-cost', [CostController::class, 'getCost'])->name('cost.get.cost');
    Route::post('/cost/delete/file', [CostController::class, 'deleteCostFile'])->name('cost.delete.cost.file');

    Route::get('/get-statistic-rows', [CostController::class, 'getStatisticRows'])->name('statistic.get.rows');
    Route::get('/get-items-filtered', [ResponsibleController::class, 'getItemRows'])->name('statistic.filter.items');
    Route::get('/get-category-filtered', [ResponsibleController::class, 'getCategoryRows'])->name('statistic.filter.category');
    Route::get('/get-responsibles-filtered', [ResponsibleController::class, 'getResponsiblesRows'])->name('statistic.filter.responsibles');
    Route::get('/get-tree-info-filtered', [ResponsibleController::class, 'getTreeInfo'])->name('tree.filter.all');


    Route::get('/cost-items', [CostController::class, 'editCostItems'])->name('costs.edit.items');


    Route::get('/cost-edit/{id}',[CostController::class, 'toCostEditPage'])->name('edit.cost.page');
    Route::post('/cost-update',[CostController::class, 'updateCost'])->name('cost.update.save');
    Route::get('/get-responsibles',[CostController::class, 'getResponsibles'])->name('chart.get.responsibles');
    Route::get('/get-chart-info',[CostController::class, 'getChartInfo'])->name('chart.get.info');
    Route::post('/delete-cost',[CostController::class, 'costDelete'])->name('cost.delete');
    Route::get('/edit-groups',[CostController::class, 'toGroupEditPage'])->name('edit.group.page');
    Route::get('/edit-export-groups',[OutcomeController::class, 'toExportGroupEditPage'])->name('edit.export.group.page');
    Route::post('/update-groups',[CostController::class, 'updateGroups'])->name('groups.update');
    Route::post('/update-export-groups',[OutcomeController::class, 'updateExportGroups'])->name('export.groups.update');
    Route::get('/responsible/{id}',[ResponsibleController::class, 'responsibleIndex'])->name('responsible');
    Route::get('/product-item/{id}',[ResponsibleController::class, 'itemsIndex'])->name('product.item.statistic');
    Route::get('/category/{id}',[ResponsibleController::class, 'categoryIndex'])->name('category.statistic');
    Route::get('/search',[ResponsibleController::class, 'mainSearch'])->name('main.search');
    Route::get('/outcome',[OutcomeController::class, 'index'])->name('outcome.index');
    Route::get('/export-category-search',[OutcomeController::class, 'search'])->name('outcome.search');
    Route::group(['middleware' => ['checkID:1'],'prefix'=>'logistics'], function () {
        Route::get('/',[LogisticsController::class, 'index'])->name('logistics.index');
        Route::get('/lazy-load',[LogisticsController::class, 'lazyLoad'])->name('logistics.lazy.load');
    });
    Route::post('/outcome-export',[OutcomeController::class, 'export'])->name('outcome.export');


    Route::get('/new-design',function (){
        return view('pages.salary.edit');
    });
    Route::group(['prefix'=>'salary'], function () {
        Route::get('/',[SalaryController::class, 'index'])->name('salary.index');
    });

    Route::group(['prefix'=>'posts'], function () {
        Route::get('/index',[WorkerPostController::class, 'index'])->name('posts.index');
        Route::post('/update',[WorkerPostController::class, 'update'])->name('posts.update');
    });
    Route::group(['prefix'=>'workers'], function () {
        Route::get('/categories',[WorkerController::class, 'index'])->name('categories.index');
        Route::get('/create',[WorkerController::class, 'toWorkerAddPage'])->name('workers.create');
        Route::get('/homepage',[WorkerController::class, 'homepage'])->name('workers.homepage');
    });

    Route::group(['prefix'=>'bases'], function () {
        Route::get('/edit',[WorkerController::class, 'toBasesEditPage'])->name('edit.bases.page');
        Route::post('/update',[WorkerController::class, 'updateBases'])->name('bases.update');
    });

    /* ПОЛЬЗОВАТЕЛИ */
    // Интерфейс для создания нового юзера
    Route::get('/user/new', [UserController::class, 'new'])
         ->name('user.new')
         ->middleware('admin');
    // Создание нового юзера
    Route::post('/user/create', [UserController::class, 'create'])
         ->name('user.create')
         ->middleware('admin');
    // Обновление персональных данных
    Route::post('/user/settings/update/{id}', [UserController::class, 'update'])
         ->name('user.update');
    // Интерфейс персональных настроек
    Route::get('/user/settings/{id}', [UserController::class, 'settings'])
         ->name('user.settings');
    // Список всех пользователей
    Route::get('/users/list', [UserController::class, 'list'])
         ->name('user.list')
         ->middleware('admin');

    /*QR коды*/
    Route::get('/qr-code/{tableId}/{rowId}', [QrCodeGeneratorController::class, 'index'])
         ->name('qr.code.url');

    Route::post('/post-request', [TestController::class, 'postRequest'])
        ->name('post.request');

    /*БАЗОВЫЕ РОУТЫ*/
    //Домашняя страница

    Route::get('/vayvay', function (){
        return response()->json([
            'success' => 'success'
        ]);
    });


    /*ИСТОРИЯ ПОЛЬЗОВАТЕЛЯ*/
    //Вся история
    Route::get('/history', [HistoryController::class, 'index'])
         ->name('history');


});

//Роут для тестов
Route::get('/mytest/{action}', function ($action, Request $request) {
    $class = "App\\Http\\Controllers\\Mytest\\TestController";
    if(class_exists($class) && method_exists($class, $action)){
        return (new $class())->callAction($action, [$request]);
    }else
        return response('Экшена '.$action.' не существует');

    return redirect()->route('lk');
});

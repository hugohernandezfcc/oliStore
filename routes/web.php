<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


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

Route::middleware([ 'auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::resource('boxcut',           App\Http\Controllers\BoxCutController::class        )->middleware('auth:sanctum');
Route::resource('financials',       App\Http\Controllers\FinancialController::class     )->middleware('auth:sanctum');
Route::resource('products',         App\Http\Controllers\ProductController::class       )->middleware('auth:sanctum');
Route::resource('liabilities',      App\Http\Controllers\LiabilitiesController::class   )->middleware('auth:sanctum');
Route::resource('reports',          App\Http\Controllers\ReportsController::class       )->middleware('auth:sanctum');
Route::resource('tickets',          App\Http\Controllers\TicketsController::class       )->middleware('auth:sanctum');
Route::resource('purchaseorders',   App\Http\Controllers\PurchaseOrderController::class )->middleware('auth:sanctum');
Route::resource('sales',            App\Http\Controllers\SalesController::class         )->middleware('auth:sanctum');
Route::resource('salesproducts',    App\Http\Controllers\SalesProductsController::class )->middleware('auth:sanctum');
Route::resource('stores',           App\Http\Controllers\StoreController::class         )->middleware('auth:sanctum');
Route::resource('providers',        App\Http\Controllers\ProvidersController::class     )->middleware('auth:sanctum');
Route::resource('stocks',           App\Http\Controllers\StockController::class         )->middleware('auth:sanctum');
Route::resource('barcodes',         App\Http\Controllers\BarCodeController::class       )->middleware('auth:sanctum');
Route::resource('prices',           App\Http\Controllers\BarCodeController::class       )->middleware('auth:sanctum');

Route::post('/lookup/field/', [App\Http\Controllers\CoreController::class,          'lookupField' ])->name('lookup.field');


Route::get('/sales/retrieveproduct/{folio}',            [App\Http\Controllers\SalesController::class,          'retrieveProduct']);
Route::get('/sales/retrieveproduct/{folio}/stock',      [App\Http\Controllers\SalesController::class,          'retrieveProductForStock']);
Route::get('/sales/delete/{salesId}',                   [App\Http\Controllers\SalesController::class,          'deleteSales']);
Route::get('/sales/{start}/{end}/results/{storeId}',    [App\Http\Controllers\SalesController::class,  'salesToday'])->middleware('auth:sanctum')->name('sales.today');
Route::get('/sales/yesterday/results',                  [App\Http\Controllers\SalesController::class,  'salesYesterday'])->middleware('auth:sanctum')->name('sales.yesterday');
Route::post('/storeProduct',                            [App\Http\Controllers\StockController::class,          'storeProduct' ]);
Route::post('/storeticket',                             [App\Http\Controllers\TicketsController::class,          'store' ]);
Route::post('/storeProductFromPos',          [App\Http\Controllers\SalesController::class,          'storeProductFromPos' ]);
Route::get('sales/show/{id}',                [App\Http\Controllers\SalesController::class, 'showById'])->middleware('auth:sanctum')->name('sales.show');
Route::get('tickets/check/{noTicket}',       [App\Http\Controllers\TicketsController::class, 'validateTicket'])->middleware('auth:sanctum');

Route::get('tickets/destroy/{id}',           [App\Http\Controllers\TicketsController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('tickets/destroyItem/{id}',       [App\Http\Controllers\TicketsController::class, 'destroyItem'])->middleware('auth:sanctum');
Route::get('ticketItem/show/{id}',           [App\Http\Controllers\TicketsController::class, 'ticketItemShow'])->middleware('auth:sanctum');
Route::post('ticketItem/update/{id}',         [App\Http\Controllers\TicketsController::class, 'ticketItemUpdate'])->middleware('auth:sanctum');

/**
 * Core routes
 */
Route::group(
    [
        'prefix' => 'core',
        'middleware' => ['auth:sanctum']
    ], function () {

        /**
         * Routes for users
         */
        Route::get('/active/users', [App\Http\Controllers\CoreController::class, 'getActiveUsers']  )->name('core.active.users');

        /**
         * Routes for tasks
         */
        Route::get('/get/task/{recordType}',        [App\Http\Controllers\CoreController::class, 'getTasks']        )->name('core.tasks');
        Route::get('/get/single/task/{name}',       [App\Http\Controllers\CoreController::class, 'getSingleTask']   )->name('core.single.task');
        Route::get('/get/another/task/{recordType}',[App\Http\Controllers\CoreController::class, 'getAnotherTasks'] )->name('core.another.tasks');
        Route::post('/add/task',                    [App\Http\Controllers\CoreController::class, 'createTask']      )->name('core.add.task');
        Route::post('/close/task',                  [App\Http\Controllers\CoreController::class, 'editTask']        )->name('core.close.task');
        Route::post('/open/task',                   [App\Http\Controllers\CoreController::class, 'editTask']        )->name('core.open.task');
        Route::post('/delete/task',                 [App\Http\Controllers\CoreController::class, 'deleteTask']      )->name('core.delete.task');

        Route::post('/store/price',                 [App\Http\Controllers\PricesController::class,  'storePrice']   )->name('core.store.price');
        Route::post('/active/price',                [App\Http\Controllers\PricesController::class,  'activePrice']  )->name('core.active.price');
        Route::post('/delete/price',                [App\Http\Controllers\PricesController::class,  'deletePrice']  )->name('core.delete.price');

        Route::get('/manager/task',                 [App\Http\Controllers\CoreController::class,    'taskManager']  )->name('core.task.manager');
});

/**
 * related list routes
 */
Route::group(
    [
        'prefix' => 'relatedlist',
        'middleware' => ['auth:sanctum']
    ], function () {

        Route::post('/store', [App\Http\Controllers\LineAnyItemController::class, 'storeFromRelatedList'])->name('relatedlist.store');
        Route::post('/delete', [App\Http\Controllers\LineAnyItemController::class, 'delete'])->name('relatedlist.delete');
        Route::post('/update', [App\Http\Controllers\LineAnyItemController::class, 'update'])->name('relatedlist.update');

});


Route::group(
    [
        'prefix' => 'social',
        'middleware' => ['auth:sanctum']
    ], function (){
        Route::post('/create/post', [App\Http\Controllers\PostsController::class, 'createPost'])->name('social.create.post');
        Route::post('/get/posts', [App\Http\Controllers\PostsController::class, 'getPosts'])->name('social.posts');
        Route::post('/like/post', [App\Http\Controllers\PostsController::class, 'createLike'])->name('social.like.post');
        Route::get('/get/all', [App\Http\Controllers\CoreController::class, 'getAll'])->name('social.get.all');
});

Route::group(
    [
        'prefix' => 'box',
        'middleware' => ['auth:sanctum']
    ], function (){
        Route::get('/is/open/box', [App\Http\Controllers\BoxController::class, 'getBox'])->name('box.is.open');
        Route::post('/open/box', [App\Http\Controllers\BoxController::class, 'store'])->name('box.open');
        Route::post('/close/box/{id}', [App\Http\Controllers\BoxController::class, 'update'])->name('box.close');
        Route::post('/save/box/cut', [App\Http\Controllers\BoxCutController::class, 'store'])->name('box.cut.save');
});


Route::get('/products2/readcsv',             [App\Http\Controllers\ProductController::class,        'storeMasive' ]);
Route::get('/passtoyesterday',               [App\Http\Controllers\SalesProductsController::class,  'editSales' ]);

Route::post('/dashboard',                    [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.results');;
Route::post('/cardsales',                     [App\Http\Controllers\DashboardController::class, 'cardSales'])->name('card.sales');;


            
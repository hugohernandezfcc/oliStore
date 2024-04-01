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
Route::get('/faker', function () {

    $previously = null;
    $toReturn = array();
    
    $tickets = App\Models\tickets::with('createdBy', 'ticketItems')->orderBy('created_at', 'desc')->get();

    for ($i=0; $i < count($tickets) ; $i++) { 
        $previously[$tickets[$i]->provider] = array();
        
        if(count($tickets[$i]->ticketItems) != 0){
            for ($o=0; $o < count($tickets[$i]->ticketItems); $o++) 
                if($tickets[$i]->ticketItems[$o]->product_id == null)
                    array_push($previously[$tickets[$i]->provider], $tickets[$i]->ticketItems[$o]->bar_code);
        }else
            array_push($previously[$tickets[$i]->provider], 'SIN PRODUCTOS RELACIONADOS');
        
        if(count($previously[$tickets[$i]->provider]) == 0)
            unset($previously[$tickets[$i]->provider]);


        $dataObj = new \stdClass();
        $dataObj->ticketId = $tickets[$i]->id;
        $dataObj->date_time_issued = $tickets[$i]->date_time_issued;
        $dataObj->provider = $tickets[$i]->provider;

        if(isset($previously[$tickets[$i]->provider])){
            $dataObj->issues = $previously[$tickets[$i]->provider];
            array_push($toReturn, $dataObj);
        }
            

    }

    echo "<pre>";
        print_r($toReturn);
    echo "</pre>";
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware([ 'auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::resource('products',         App\Http\Controllers\ProductController::class       )->middleware('auth:sanctum');
Route::resource('tickets',          App\Http\Controllers\TicketsController::class       )->middleware('auth:sanctum');
Route::resource('sales',            App\Http\Controllers\SalesController::class         )->middleware('auth:sanctum');
Route::resource('salesproducts',    App\Http\Controllers\SalesProductsController::class )->middleware('auth:sanctum');
Route::resource('stores',           App\Http\Controllers\StoreController::class         )->middleware('auth:sanctum');
Route::resource('providers',        App\Http\Controllers\ProvidersController::class     )->middleware('auth:sanctum');
Route::resource('stocks',           App\Http\Controllers\StockController::class         )->middleware('auth:sanctum');
Route::resource('barcodes',         App\Http\Controllers\BarCodeController::class       )->middleware('auth:sanctum');
Route::resource('prices',           App\Http\Controllers\BarCodeController::class       )->middleware('auth:sanctum');

Route::get('/sales/retrieveproduct/{folio}', [App\Http\Controllers\SalesController::class,          'retrieveProduct']);
Route::get('/sales/delete/{salesId}',        [App\Http\Controllers\SalesController::class,          'deleteSales']);
Route::get('/sales/{start}/{end}/results',   [App\Http\Controllers\SalesController::class,  'salesToday'])->middleware('auth:sanctum')->name('sales.today');
Route::get('/sales/yesterday/results',       [App\Http\Controllers\SalesController::class,  'salesYesterday'])->middleware('auth:sanctum')->name('sales.yesterday');
Route::post('/storeProduct',                 [App\Http\Controllers\StockController::class,          'storeProduct' ]);
Route::post('/storeticket',                 [App\Http\Controllers\TicketsController::class,          'store' ]);
Route::post('/storeProductFromPos',          [App\Http\Controllers\SalesController::class,          'storeProductFromPos' ]);
Route::get('sales/show/{id}',                [App\Http\Controllers\SalesController::class, 'showById'])->middleware('auth:sanctum');
Route::get('tickets/check/{noTicket}',       [App\Http\Controllers\TicketsController::class, 'validateTicket'])->middleware('auth:sanctum');

Route::get('tickets/destroy/{id}',           [App\Http\Controllers\TicketsController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('tickets/destroyItem/{id}',       [App\Http\Controllers\TicketsController::class, 'destroyItem'])->middleware('auth:sanctum');
Route::get('ticketItem/show/{id}',           [App\Http\Controllers\TicketsController::class, 'ticketItemShow'])->middleware('auth:sanctum');
Route::post('ticketItem/update/{id}',         [App\Http\Controllers\TicketsController::class, 'ticketItemUpdate'])->middleware('auth:sanctum');





Route::get('/products2/readcsv',             [App\Http\Controllers\ProductController::class,        'storeMasive' ]);
Route::get('/passtoyesterday',               [App\Http\Controllers\SalesProductsController::class,  'editSales' ]);

Route::post('/dashboard', 
            [App\Http\Controllers\DashboardController::class,          
            'index'])->name('dashboard.results');;


            
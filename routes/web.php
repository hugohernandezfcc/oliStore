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
    $faker = Faker\Factory::create();
    echo $faker->ean13();
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
Route::resource('sales',            App\Http\Controllers\SalesController::class         )->middleware('auth:sanctum');
Route::resource('salesproducts',    App\Http\Controllers\SalesProductsController::class )->middleware('auth:sanctum');
Route::resource('stores',           App\Http\Controllers\StoreController::class         )->middleware('auth:sanctum');
Route::resource('providers',        App\Http\Controllers\ProvidersController::class     )->middleware('auth:sanctum');
Route::resource('stocks',           App\Http\Controllers\StockController::class         )->middleware('auth:sanctum');

Route::get('/sales/retrieveproduct/{folio}', [App\Http\Controllers\SalesController::class, 'retrieveProduct']);
Route::get('/products2/readcsv',             [App\Http\Controllers\ProductController::class,  'storeMasive' ]);
Route::get('/passtoyesterday',               [App\Http\Controllers\SalesProductsController::class,  'editSales' ]);
Route::post('/storeProduct',                 [App\Http\Controllers\StockController::class,  'storeProduct' ]);
Route::post('/storeProductFromPos',          [App\Http\Controllers\SalesController::class,  'storeProductFromPos' ]);

    




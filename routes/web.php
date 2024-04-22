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

use App\Models\Product;
Route::get('/matchProducts', function () {


    $products = Product::select('folio', \DB::raw('count(*) as total') )
                        ->groupBy('folio')
                        ->get();

    $array = array();

    foreach ($products as $product) {
        if($product->total > 1){
            echo "Folio: " . $product->folio . ", Total: " . $product->total . "<br>";
            array_push($array, $product->folio);
        }
    }
    echo "<br><br>";
    $details = Product::select( 'id', 'name', 'Description', 'folio' )->whereIn('folio', $array)->orderBy('folio', 'asc')->get();
    foreach ($details as $detail) {
        echo "Id: " . $detail->id . " Folio: " . $detail->folio . ", Name: " . $detail->name . ", Description: " . $detail->Description . "<br>";
    }

});
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
    });


Route::group([
    'prefix' => 'social',
    'middleware' => ['auth:sanctum']
], function (){
    Route::post('/create/post', [App\Http\Controllers\PostsController::class, 'createPost'])->name('social.create.post');
    Route::post('/get/posts', [App\Http\Controllers\PostsController::class, 'getPosts'])->name('social.posts');
    Route::post('/like/post', [App\Http\Controllers\PostsController::class, 'createLike'])->name('social.like.post');
    Route::get('/get/all', [App\Http\Controllers\CoreController::class, 'getAll'])->name('social.get.all');


    // Route::post('/unlike/post', [App\Http\Controllers\LikesController::class, 'unlikePost'])->name('social.unlike.post');
    // Route::post('/comment/post', [App\Http\Controllers\CommentsController::class, 'commentPost'])->name('social.comment.post');
    // Route::post('/reply/comment', [App\Http\Controllers\RepliesController::class, 'replyComment'])->name('social.reply.comment');
    // Route::post('/delete/post', [App\Http\Controllers\PostsController::class, 'deletePost'])->name('social.delete.post');
    // Route::post('/delete/comment', [App\Http\Controllers\CommentsController::class, 'deleteComment'])->name('social.delete.comment');
    // Route::post('/delete/reply', [App\Http\Controllers\RepliesController::class, 'deleteReply'])->name('social.delete.reply');
    // Route::post('/edit/post', [App\Http\Controllers\PostsController::class, 'editPost'])->name('social.edit.post');
    // Route::post('/edit/comment', [App\Http\Controllers\CommentsController::class, 'editComment'])->name('social.edit.comment');
    // Route::post('/edit/reply', [App\Http\Controllers\RepliesController::class, 'editReply'])->name('social.edit.reply');
    // Route::get('/get/likes', [App\Http\Controllers\LikesController::class, 'getLikes'])->name('social.likes');
    // Route::get('/get/comments', [App\Http\Controllers\CommentsController::class, 'getComments'])->name('social.comments');
    // Route::get('/get/replies', [App\Http\Controllers\RepliesController::class, 'getReplies'])->name('social.replies');
    // Route::get('/get/single/post/{id}', [App\Http\Controllers\PostsController::class, 'getSinglePost'])->name('social.single.post');
    // Route::get('/get/single/comment/{id}', [App\Http\Controllers\CommentsController::class, 'getSingleComment'])->name('social.single.comment');

});


Route::get('/products2/readcsv',             [App\Http\Controllers\ProductController::class,        'storeMasive' ]);
Route::get('/passtoyesterday',               [App\Http\Controllers\SalesProductsController::class,  'editSales' ]);

Route::post('/dashboard', 
            [App\Http\Controllers\DashboardController::class,          
            'index'])->name('dashboard.results');;


            
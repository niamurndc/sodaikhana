<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// public route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot', [AuthController::class, 'forgot']);
Route::post('/getpassword', [AuthController::class, 'getpassword']);




// protected route

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/varify', [AuthController::class, 'varify']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/sendcode', [AuthController::class, 'sendcode']);

    // order
    Route::get('/orders/{phone}', [FrontendOrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::post('/order/add', [OrderController::class, 'store']);
    
    // request
    Route::get('/request/add/{id}', [RequestController::class, 'store']);

    // customer
    Route::post('/profile/update', [AuthController::class, 'update']);

});


// admin routes
Route::group(['middleware' => ['auth:sanctum', 'admin']], function(){

    // brand route
    Route::get('/brands', [BrandController::class, 'index']);
    Route::post('/brand/add', [BrandController::class, 'store']);
    Route::get('/brand/{id}', [BrandController::class, 'show']);
    Route::post('/brand/edit/{id}', [BrandController::class, 'update']);
    Route::delete('/brand/delete/{id}', [BrandController::class, 'delete']);

    // category route
    Route::post('/category/add', [CategoryController::class, 'store']);
    Route::post('/category/edit/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/delete/{id}', [CategoryController::class, 'delete']);

    // product route
    Route::post('/product/add', [ProductController::class, 'store']);
    Route::post('/product/edit/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete']);

    // order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/order/sms', [OrderController::class, 'ordersms']);
    Route::post('/order/edit/{id}', [OrderController::class, 'update']);
    Route::delete('/order/delete/{id}', [OrderController::class, 'delete']);

    // request routes
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/request/{id}', [RequestController::class, 'show']);
    Route::delete('/request/delete/{id}', [RequestController::class, 'delete']);

    // customer
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::post('/customer/edit/{id}', [CustomerController::class, 'update']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::delete('/customer/delete/{id}', [CustomerController::class, 'delete']);
});













// *********
// front end
// -----------
Route::get('/productoffer', [FrontendProductController::class, 'offer']);
Route::get('/product/search/{id}', [FrontendProductController::class, 'search']);
Route::get('/productpopular', [FrontendProductController::class, 'popular']);
Route::get('/showcategory/{id}', [FrontendCategoryController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);


Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);





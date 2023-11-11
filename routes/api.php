<?php

use App\Http\Controllers\AnalyticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpanceController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\MyDayController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmsItemController;
use App\Http\Controllers\SmsListController;

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

// public category route
Route::get('/categorys', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);

// public product route
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);


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

    // story route
    Route::post('/story/add', [MyDayController::class, 'store']);
    Route::get('/story/{id}', [MyDayController::class, 'show']);
    Route::delete('/story/delete/{id}', [MyDayController::class, 'delete']);

    // product route
    Route::post('/product/add', [ProductController::class, 'store']);
    Route::post('/product/edit/{id}', [ProductController::class, 'update']);
    Route::post('/product/edit-qty/{id}', [ProductController::class, 'editQty']);
    Route::post('/product/edit-price/{id}', [ProductController::class, 'editPrice']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete']);

    // order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/order/edit/{id}', [OrderController::class, 'update']);
    Route::post('/order/pay/{id}', [OrderController::class, 'payDue']);
    Route::delete('/order/delete/{id}', [OrderController::class, 'delete']);

    // request routes
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/request/{id}', [RequestController::class, 'show']);
    Route::delete('/request/delete/{id}', [RequestController::class, 'delete']);

    // customer
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::post('/customer/add', [CustomerController::class, 'store']);
    Route::get('/customer/search/{query}', [CustomerController::class, 'search']);
    Route::post('/customer/edit/{id}', [CustomerController::class, 'update']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::delete('/customer/delete/{id}', [CustomerController::class, 'delete']);

    // area
    Route::post('/area/add', [AreaController::class, 'store']);
    Route::delete('/area/delete/{id}', [AreaController::class, 'delete']);

    // sms list
    Route::get('/sms-lists', [SmsListController::class, 'index']);
    Route::post('/sms-list/add', [SmsListController::class, 'store']);
    Route::get('/sms-list/{id}', [SmsListController::class, 'show']);
    Route::delete('/sms-list/delete/{id}', [SmsListController::class, 'delete']);

    // sms item
    Route::post('/sms-item/add', [SmsItemController::class, 'store']);
    Route::delete('/sms-item/delete/{id}', [SmsItemController::class, 'delete']);

    // send SMS
    Route::post('/sms-send/{id}', [SmsListController::class, 'send']);
    Route::post('/sms-send/order/{id}', [SmsListController::class, 'sendOrderSms']);

    // setting
    Route::post('/settings', [SettingController::class, 'update']);

    // analytics routes
    Route::get('/daily-sale', [AnalyticController::class, 'sales']);
    Route::get('/product-sale', [AnalyticController::class, 'products']);
    Route::get('/profit-loss', [AnalyticController::class, 'profit']);
    Route::get('/daily-report', [AnalyticController::class, 'dailyReports']);
    Route::post('/save-report', [AnalyticController::class, 'addReport']);

    // exapnce route
    Route::get('/expances', [ExpanceController::class, 'index']);
    Route::post('/expance/add', [ExpanceController::class, 'store']);
});


Route::get('/settings', [SettingController::class, 'index']);
Route::get('/storys', [MyDayController::class, 'index']);





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
Route::get('/product/find/{id}', [ProductController::class, 'search']);
Route::get('/product/findid/{id}', [ProductController::class, 'searchId']);
Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/areas', [AreaController::class, 'index']);





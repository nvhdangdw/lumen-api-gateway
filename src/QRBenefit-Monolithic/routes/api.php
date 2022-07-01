<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

Route::middleware(['cors'])->post('/store/login', 'Api\Store\LoginController@index');
Route::middleware(['cors'])->post('/store/password/reset', 'Api\Store\ResetPasswordController@reset');
Route::middleware(['cors'])->post('/store/password/forgot', 'Api\Store\ForgotPasswordController@sendResetLinkEmail');

Route::middleware(['api_log', 'api_token'])->group(function () {
	Route::post('/store/customer/qrcode', 'Api\Store\CustomerController@generateQRCode');
});

Route::prefix('/store')->middleware(['cors', 'auth:api'])->group(function () {
	Route::group(['middleware' => ['store_tfa_logged']], function () {
		Route::get('/logout', 'Api\Store\LogoutController@index');

		Route::get('/order', 'Api\Store\OrderController@getTransactions')->middleware(['permission:ORDER_VIEW']);
		Route::get('/order/{id}', 'Api\Store\OrderController@info')->middleware(['permission:ORDER_VIEW']);
		Route::post('/order', 'Api\Store\OrderController@create')->middleware(['permission:ORDER_CREATE']);
		Route::get('/export/order', 'Api\Store\ExportController@order')->middleware(['permission:ORDER_VIEW']);

		Route::get('/transaction', 'Api\Store\TransactionController@index')->middleware(['permission:TRANSACTION_VIEW']);
		Route::post('/transaction', 'Api\Store\TransactionController@create')->middleware(['permission:TRANSACTION_CREATE']);
		Route::get('/export/transaction', 'Api\Store\ExportController@transaction')->middleware(['permission:TRANSACTION_VIEW']);

		Route::get('/customer/{id}', 'Api\Store\CustomerController@info');
		Route::post('/customer/scan', 'Api\Store\CustomerController@getCustomerFromQRCode');
		Route::post('/customer', 'Api\Store\CustomerController@create');
		Route::get('/customer', 'Api\Store\CustomerController@getCustomers');
		Route::put('/customer/update', 'Api\Store\CustomerController@update');
		Route::get('/total-customers', 'Api\Store\CustomerController@getTotalCustomers');

		Route::get('/discount', 'Api\Store\DiscountController@getPromotions');
		Route::get('/discount/{id}', 'Api\Store\DiscountController@info');
		Route::get('/discounts', 'Api\Store\DiscountController@getAll');
		Route::post('/discount', 'Api\Store\DiscountController@create');
		Route::put('/discount/{id}', 'Api\Store\DiscountController@update');
		Route::delete('/discount/{id}', 'Api\Store\DiscountController@delete');
		Route::delete('/discount', 'Api\Store\DiscountController@deleteList');

		Route::get('/promotion', 'Api\Store\PromotionController@index')->middleware(['permission:PROMOTION_VIEW']);
		Route::get('/promotion/{id}', 'Api\Store\PromotionController@info')->middleware(['permission:PROMOTION_VIEW']);
		Route::put('/promotion/{id}', 'Api\Store\PromotionController@update')->middleware(['permission:PROMOTION_UPDATE']);
		Route::post('/promotions', 'Api\Store\PromotionController@getAll');
		Route::get('/available-promotions', 'Api\Store\PromotionController@getAvailablePromotions');

		Route::post('/user', 'Api\Store\UserController@update');
		Route::post('/user/password', 'Api\Store\UserController@updatePassword');

		Route::get('/tfa/info', 'Api\Store\TwoFactorAuthController@getSrcQrcode')->middleware(['store_tfa_setting']);
		Route::post('/tfa/enabled', 'Api\Store\TwoFactorAuthController@enabled')->middleware(['store_tfa_setting']);
		Route::post('/tfa/disabled', 'Api\Store\TwoFactorAuthController@disabled')->middleware(['store_tfa_setting']);

		Route::get('/dashboard/{storeId}', 'Api\Store\DashboardController@index');

		Route::get('/promotiontype', 'Api\Store\PromotionTypeController@getAll');

	});

	Route::get('/info', 'Api\Store\UserController@info');
	Route::post('/tfa/verify_code', 'Api\Store\TwoFactorAuthController@verifyCode')->middleware(['store_tfa_setting', 'store_tfa_user']);
});

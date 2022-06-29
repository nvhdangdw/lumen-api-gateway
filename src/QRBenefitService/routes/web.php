<?php
use Illuminate\Support\Str;
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


Route::get('/test', function () {
	return Str::random(60);
});

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/admin/login', 'Admin\LoginController@showLoginForm')->name('admin_show_login_form');
Route::post('/admin/login', 'Admin\LoginController@login')->name('admin_login');
Route::get('/admin/password/reset', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
Route::get('/admin/password/reset/{token}', 'Admin\ResetPasswordController@showResetForm')->name('admin.show_reset_form');
Route::post('/admin/password/reset', 'Admin\ResetPasswordController@reset')->name('admin.password.reset');
Route::post('/admin/password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');



Route::prefix('admin')->middleware(['admin'])->group(function () {
	Route::get('', 'Admin\HomeController@index')->name('admin_dashbroard');
	Route::get('/logout', 'Admin\LogoutController@index')->name('admin_logout');

	Route::get('/customer', 'Admin\CustomerController@index')->name('admin_customer_index');
	Route::get('/customer/add', 'Admin\CustomerController@showViewAdd')->name('admin_customer_show_view_add');
	Route::post('/customer/add', 'Admin\CustomerController@create')->name('admin_customer_create');
	Route::get('/customer/{id}/edit', 'Admin\CustomerController@showViewEdit')->name('admin_customer_show_view_edit');
	Route::post('/customer/{id}/edit', 'Admin\CustomerController@update')->name('admin_customer_update');
	Route::any('/customer/delete', 'Admin\CustomerController@delete')->name('admin_customer_delete');
	Route::get('/customer/{id}/info', 'Admin\CustomerController@showViewInfo')->name('admin_customer_show_view_info');

	Route::get('/store', 'Admin\StoreController@index')->name('admin_store_index');
	Route::get('/store/add', 'Admin\StoreController@showViewAdd')->name('admin_store_show_view_add');
	Route::post('/store/add', 'Admin\StoreController@create')->name('admin_store_create');
	Route::get('/store/{id}/edit', 'Admin\StoreController@showViewEdit')->name('admin_store_show_view_edit');
	Route::post('/store/{id}/edit', 'Admin\StoreController@update')->name('admin_store_update');
	Route::any('/store/delete', 'Admin\StoreController@delete')->name('admin_store_delete');
	Route::get('/store/{id}/info', 'Admin\StoreController@showViewInfo')->name('admin_store_show_view_info');

	Route::get('/order', 'Admin\OrderController@index')->name('admin_order_index');
	Route::get('/order/{id}/info', 'Admin\OrderController@showViewInfo')->name('admin_order_show_view_info');

	Route::get('/discount', 'Admin\DiscountController@index')->name('admin_discount_index');
	Route::get('/discount/add', 'Admin\DiscountController@showViewAdd')->name('admin_discount_show_view_add');
	Route::post('/discount/add', 'Admin\DiscountController@create')->name('admin_discount_create');
	Route::get('/discount/{id}/edit', 'Admin\DiscountController@showViewEdit')->name('admin_discount_show_view_edit');
	Route::post('/discount/{id}/edit', 'Admin\DiscountController@update')->name('admin_discount_update');
	Route::any('/discount/delete', 'Admin\DiscountController@delete')->name('admin_discount_delete');
	Route::get('/discount/{id}/info', 'Admin\DiscountController@showViewInfo')->name('admin_discount_show_view_info');

	Route::get('/user', 'Admin\UserController@index')->name('admin_user_index');
	Route::get('/user/add', 'Admin\UserController@showViewAdd')->name('admin_user_show_view_add');
	Route::post('/user/add', 'Admin\UserController@create')->name('admin_user_create');
	Route::get('/user/{id}/edit', 'Admin\UserController@showViewEdit')->name('admin_user_show_view_edit');
	Route::post('/user/{id}/edit', 'Admin\UserController@update')->name('admin_user_update');
	Route::any('/user/delete', 'Admin\UserController@delete')->name('admin_user_delete');
	Route::get('/user/{id}/info', 'Admin\UserController@showViewInfo')->name('admin_user_show_view_info');

});

Route::get('/store/login', 'Store\LoginController@showLoginForm')->name('store_show_login_form');
Route::post('/store/login', 'Store\LoginController@login')->name('store_login');

Route::prefix('store')->middleware(['store'])->group(function () {
    Route::get('', 'Store\HomeController@index')->name('store_dashboard');
    Route::get('/logout', 'Store\LogoutController@index')->name('store_logout');
    
    Route::get('promotion', 'Store\PromotionController@index')->name('store_promotion');
    Route::post('promotion/getPromotionList', 'Store\PromotionController@getPromotionList');
    Route::get('promotion/showViewInfo/{id}', 'Store\PromotionController@showViewInfo');
    Route::post('promotion/deletePromotion', 'Store\PromotionController@deletePromotion');
    Route::post('/promotion/submitPromotion','Store\PromotionController@submitPromotion')->name('promotion_submit');
    
    Route::get('/checkout/{id}', 'Store\HomeController@checkout')->name('store_checkout');
    Route::post('/order/create','Admin\OrderController@create')->name('order_add');
    Route::post('/order/getOrderList','Admin\OrderController@getOrderList');
    Route::post('/checkout/checkCustomer','Admin\CustomerController@checkCustomer');
    
    Route::get('/customer/{id}', 'Store\HomeController@customerInfo')->name('store_checkout');
});

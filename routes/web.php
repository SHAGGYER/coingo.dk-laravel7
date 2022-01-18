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

/**
 * Public routes
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post(
  'stripe/webhook',
  '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);

Route::get('/', function () {
  return view('welcome');
});

Route::get('/login', function () {
  if (Auth::check()) {
    return redirect()->to('/app');
  }
  return view('auth.login');
});

Route::get('/register', function () {
  return view('auth.register');
});

Route::get('/forgotPassword', function () {
  return view('auth.forgotPassword');
});

Route::get('/app', function () {
    if (!Auth::check()) {
      return redirect()->to('/login');
    }
    return view('app');
});

Route::prefix('auth')->group(function () {
  Route::get('init', 'AuthController@init');
  Route::post('install', 'AuthController@install');
  Route::post('logout', 'AuthController@logout');
  Route::post('login', 'AuthController@login');
  Route::post('register', 'AuthController@register');
  Route::post('forgotPassword', 'AuthController@forgotPassword');
  Route::get('newPassword/{token}', 'AuthController@newPassword');
  Route::post('resetPassword', 'AuthController@resetPassword');
  Route::post('checkVerifyEmailToken', 'AuthController@checkVerifyEmailToken');
});

Route::prefix('users')->group(function () {
  Route::post('updateHasSeenTour', 'UsersController@updateHasSeenTour');
  Route::post('changePlan', 'UsersController@changePlan');
  Route::post('addPlan', 'UsersController@addPlan');
  Route::post('addCard', 'UsersController@addCard');
  Route::post('deleteCard', 'UsersController@deleteCard');
  Route::post('cancelPlan', 'UsersController@cancelPlan');
  Route::post('resumePlan', 'UsersController@resumePlan');
  Route::get('getInvoices', 'UsersController@getInvoices');
  Route::get('getDashboardData', 'UsersController@getDashboardData');
  Route::get('getCardInfo', 'UsersController@getCardInfo');
  Route::get('invoice/{invoiceId}', 'UsersController@downloadInvoice');
  Route::post('changeName', 'UsersController@changeName');
  Route::post('changeEmail', 'UsersController@changeEmail');
  Route::post('changePassword', 'UsersController@changePassword');
  Route::post('resendEmailVerification', 'UsersController@resendEmailVerification');
});

Route::prefix('feedback')->group(function() {
  Route::post('create', 'FeedbackController@create');
  Route::post('delete', 'FeedbackController@delete');
  Route::get('browse', 'FeedbackController@browse');
});

Route::prefix('companies')->group(function () {
  Route::post('create', 'CompaniesController@create');
  Route::post('delete/{id}', 'CompaniesController@delete');
  Route::get('show/{id}', 'CompaniesController@show');
  Route::post('update', 'CompaniesController@update');
});

Route::prefix('contactMessages')->group(function () {
  Route::post('send', 'AuthController@sendContactMessage');
});

Route::prefix('places')->group(function () {
  Route::get('browse', 'PlacesController@browse');
  Route::post('create', 'PlacesController@create');
  Route::post('delete/{id}', 'PlacesController@delete');
  Route::get('show/{id}', 'PlacesController@show');
  Route::post('update', 'PlacesController@update');
});

Route::prefix('items')->group(function () {
  Route::get('browse', 'ItemsController@browse');
  Route::get('printWarehouse', 'ItemsController@printWarehouse');
  Route::post('create', 'ItemsController@create');
  Route::post('delete/{id}', 'ItemsController@delete');
  Route::get('show/{id}', 'ItemsController@show');
  Route::post('update', 'ItemsController@update');
});

Route::prefix('contacts')->group(function () {
  Route::get('browse', 'ContactsController@browse');
  Route::post('create', 'ContactsController@create');
  Route::post('delete/{id}', 'ContactsController@delete');
  Route::get('show/{id}', 'ContactsController@show');
  Route::post('update', 'ContactsController@update');
});

Route::prefix('invoices')->group(function () {
  Route::get('browse', 'InvoicesController@browse');
  Route::post('create', 'InvoicesController@create');
  Route::post('delete/{id}', 'InvoicesController@delete');
  Route::get('show/{id}', 'InvoicesController@show');
  Route::get('print/{id}', 'InvoicesController@printInvoice');
  Route::post('update', 'InvoicesController@update');
  Route::post('registerPayment', 'InvoicesController@registerPayment');
  Route::post('sendInvoiceToEmail', 'InvoicesController@sendInvoiceToEmail');
});

Route::prefix('accounting')->group(function () {
  Route::get('getChartData', 'AccountingController@getChartData');
  Route::get('getAccounting', 'AccountingController@getAccounting');
  Route::get('getVat', 'AccountingController@getVat');
});

/**
 * Admin routes
 */



Route::prefix('admin')->group(function () {

  Route::prefix('auth')->group(function () {
    Route::get('init', 'AdminAuthController@init');
    Route::post('login', 'AdminAuthController@login');
    Route::post('logout', 'AdminAuthController@logout');
  });

  Route::prefix('users')->group(function () {
    Route::get('browse', 'AdminController@browseUsers');
    Route::post('resumeSubscription', 'AdminController@resumeSubscription');
    Route::post('cancelSubscription', 'AdminController@cancelSubscription');
  });

  Route::prefix('admins')->group(function () {
    Route::get('browse', 'AdminController@browseAdmins');
    Route::get('getDashboardData', 'AdminController@getDashboardData');
    Route::post('create', 'AdminController@createAdmin');
    Route::post('delete', 'AdminController@deleteAdmin');
  });

  Route::prefix('feedback')->group(function () {
    Route::get('browse', 'AdminController@browseFeedback');
  });

  Route::prefix('contact')->group(function () {
    Route::get('browse', 'AdminController@browseContactMessages');
  });

  Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
      return view('admin');
    } else {
      return view('adminLogin');
    }
  });
});

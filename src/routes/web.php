<?php

use App\Http\Controllers\Checkout;
use App\Http\Controllers\CustomerController;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/shop', function () {
    return view('pages.all-products');
})->name('shop');

Route::get('/cart', function () {
    return view('pages.checkout');
})->name('checkout');

Route::post('/order/confirm', [Checkout::class,'submit'])->name('order.confirm.submit');

Route::get('/order/confirm/{id}', function ($id) {
    return view('pages.order-confirm', ['orderId' => $id]);
})->name('order.confirm');

Route::get('/order/pay/{id}', [Checkout::class, 'showPayment'])->name('order.pay');
Route::post('/order/pay/{id}', [Checkout::class, 'pay'])->name('order.pay.submit');


Route::get('/product/{slug}', function ($slug) {
    return view('pages.single-product')->with('slug', $slug . '');
})->name('product.show');




Route::get('/story', function () {
    return view('pages.story');
})->name('story');



// LOGIN: only for guests (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login-pass', function () {
        return view('pages.auth.login');
    })->name('login.password');

    Route::get('/login', function () {
        return view('pages.auth.alt-login');
    })->name('login');
    Route::post('/login-otp/submit', [CustomerController::class, 'loginWithOtp'])
        ->name('login.otp.submit');


    Route::get('/register', function () {
        return view('pages.auth.register');
    })->name('register');


});


// LOGIN: only for guests (not logged in)
Route::middleware('auth')->group(function () {
    Route::get('/account', [CustomerController::class, 'showAccount'])->name('account');
    Route::get('/order/view/{order_number}', [CustomerController::class, 'viewOrder'])->name('order.view');
    Route::post('/order/reorder/{order_number}', [CustomerController::class, 'reorder'])->name('order.reorder');
});

// Admin (storekeeper) — printable order invoice
Route::middleware('auth:admin')->group(function () {
    Route::get('/orekeeper/order-center/invoice/{order}', function (Order $order) {
        $order->load(['items', 'shippingAddress', 'billingAddress', 'paymentMethod']);

        return view('invoices.order', ['order' => $order]);
    })->name('admin.orders.invoice');
});

Route::get('/account/verify', [CustomerController::class, 'showVerificationForm'])->name('account.otp.show');
Route::post('/account/verify', [CustomerController::class, 'verifyOTP'])->name('account.otp.verify');
Route::post('/account/resend-otp', [CustomerController::class, 'resendOTP'])->name('account.otp.resend');

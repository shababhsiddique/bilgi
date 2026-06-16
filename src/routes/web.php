<?php

use App\Http\Controllers\Checkout;
use App\Http\Controllers\CustomerController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
    $order = Order::with(['items', 'shippingAddress', 'billingAddress', 'paymentMethod'])
        ->findOrFail($id);

    // If a customer is logged in, the order must belong to them.
    $customer = Auth::guard('customers')->user();
    if ($customer && $order->customer_id !== $customer->id) {
        abort(403, 'You do not have permission to view this order.');
    }

    return view('pages.order-confirm', ['order' => $order]);
})->name('order.confirm');

// Customer-facing printable invoice (auto-opens the print dialog on load).
Route::get('/order/invoice/{id}', function ($id) {
    $order = Order::with(['items', 'shippingAddress', 'billingAddress', 'paymentMethod'])
        ->findOrFail($id);

    $customer = Auth::guard('customers')->user();
    if ($customer && $order->customer_id !== $customer->id) {
        abort(403, 'You do not have permission to view this order.');
    }

    return view('invoices.order', ['order' => $order]);
})->name('order.invoice');

Route::get('/order/pay/{id}', [Checkout::class, 'showPayment'])->name('order.pay');
Route::post('/order/pay/{id}', [Checkout::class, 'pay'])->name('order.pay.submit');


Route::get('/product/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();

    $categoryIds = $product->categories()->pluck('categories.id');

    // Up to 4 other visible products that share a category with this one.
    $related = Product::query()
        ->where('visible', true)
        ->where('id', '!=', $product->id)
        ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
        ->with(['defaultVariant', 'variants'])
        ->inRandomOrder()
        ->limit(4)
        ->get();

    // Fallback: if the product has no category (or no category siblings),
    // show the latest products instead so the section is never empty.
    if ($related->isEmpty()) {
        $related = Product::query()
            ->where('visible', true)
            ->where('id', '!=', $product->id)
            ->with(['defaultVariant', 'variants'])
            ->latest()
            ->limit(4)
            ->get();
    }

    return view('pages.single-product', [
        'slug'    => $slug . '',
        'related' => $related,
    ]);
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

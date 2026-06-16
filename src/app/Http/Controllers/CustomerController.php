<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\CartService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function showAccount(Request $request)
    {
        $customer = Auth::guard('customers')->user();

        return view('pages.account', [
            'customer' => $customer,
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        // Basic validation - ensure a valid Bangladeshi phone number is entered
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
        ], [
            'username.required' => 'Phone number is required.',
            'username.regex' => 'Please enter a valid phone number (e.g. 017XXXXXXXX).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $phone = trim($request->input('username'));

        // Check if phone number exists as a customer
        $customer = Customer::where('phone', $phone)->first();

        // If new number, create a customer with temp password and default name
        if (!$customer) {
            $customer = Customer::create([
                'phone' => $phone,
                'full_name' => 'Customer', // Default name
                'password' => Hash::make(uniqid('temp_', true)), // Temporary password
            ]);
        }else{
            Log::debug("OTP login detected for existing customer ID {$customer->id}");
        }

        // Generate OTP and send SMS
        $otp = $customer->generateOTP();
        $this->sendOTPSMS($customer->phone, $otp);

        // Redirect to OTP form with customer_id and redirect_route to /account
        return redirect()->route('account.otp.show', [
            'customer_id' => $customer->id,
            'redirect_route' => 'account',
        ]);
    }

    public function viewOrder($order_number)
    {
        $customer = Auth::guard('customers')->user();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $order = Order::where('order_number', $order_number)
            ->with(['items', 'shippingAddress', 'billingAddress', 'paymentMethod'])
            ->firstOrFail();

        // Check if the order belongs to the authenticated customer
        if ($order->customer_id !== $customer->id) {
            abort(403, 'You do not have permission to view this order.');
        }

        return view('pages.order-view', [
            'order' => $order,
            'customer' => $customer,
        ]);
    }

    public function reorder($order_number)
    {
        $customer = Auth::guard('customers')->user();

        if (!$customer) {
            abort(403, 'Unauthorized');
        }

        $order = Order::where('order_number', $order_number)
            ->where('customer_id', $customer->id)
            ->with('items')
            ->firstOrFail();

        $cartService = new CartService($customer);

        // Add each order item to the cart
        foreach ($order->items as $item) {
            $cartService->addItem(
                productId: $item->product_id,
                variantId: $item->product_variant_id,
                quantity: $item->quantity
            );
        }

        // Redirect to checkout
        return redirect()->route('checkout')
            ->with('success', 'Items from order #' . $order->order_number . ' have been added to your cart.');
    }

    public function showVerificationForm(Request $request)
    {
        $customer = $this->resolveCustomer($request);

        /*if ($customer->phone_verified_at) {
            return redirect()->route('account')
                ->with('success', 'Your phone number is already verified.');
        }*/

        if (!$customer->otp_code || ($customer->otp_expires_at && $customer->otp_expires_at->isPast())) {
            $otp = $customer->generateOTP();
            $this->sendOTPSMS($customer->phone, $otp);
        }

        $order = null;
        $orderId = $request->input('order_id');
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('customer_id', $customer->id)
                ->first();
        }

        $otpRedirect = [
            'route' => $request->input('redirect_route', 'account'),
            'params' => $this->decodeRedirectParams($request->input('redirect_params')),
        ];

        return view('pages.auth.otp-form', [
            'customer' => $customer,
            'order' => $order,
            'otpRedirect' => $otpRedirect,
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $customer = $this->resolveCustomer($request);
        $otpLength = (int) config('auth.one_time_password_length', 6);
        $redirectRoute = $request->input('redirect_route', 'account');
        $redirectParams = $this->decodeRedirectParams($request->input('redirect_params'));

        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'string', 'size:' . $otpLength],
        ]);

        if ($validator->fails()) {
            Log::debug('OTP verification failed due to invalid OTP code');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($customer->verifyOTP($request->otp)) {
            if ($request->filled('order_id')) {
                $this->markOrderAsVerified((int) $request->input('order_id'), $customer->id);
            }

            if (Auth::guard('customers')->guest()) {
                Log::debug("OTP verified, logging in customer");
                Auth::guard('customers')->login($customer);
            }else{
                Log::debug("OTP verified but was not logged in, redirecting to {$redirectRoute} with params:");
            }

            if (Route::has($redirectRoute)) {
                return redirect()->route($redirectRoute, $redirectParams)
                    ->with('success', 'Phone number verified! Your account is now confirmed.');
            }

            return redirect()->route('account')
                ->with('success', 'Phone number verified! Your account is now confirmed.');
        }

        return redirect()->back()
            ->withErrors(['otp' => 'Invalid or expired OTP code.'])
            ->withInput();
    }

    public function resendOTP(Request $request)
    {
        $customer = $this->resolveCustomer($request);

        /*if ($customer->phone_verified_at) {
            return redirect()->route('account')
                ->with('success', 'Your phone number is already verified.');
        }*/

        // Check if OTP is still valid (not expired)
        if ($customer->otp_expires_at && $customer->otp_expires_at->isFuture()) {

            Log::debug("Resend otp attempt denied");
            // Calculate remaining minutes (ensure at least 1 minute is shown)
            $remainingMinutes = max(1, round(now()->diffInMinutes($customer->otp_expires_at, false)));

            return redirect()->back()
                ->with('error', "A valid OTP has already been sent. Please wait {$remainingMinutes} minute(s) to generate a new one.");
        }

        // OTP is expired or doesn't exist, generate a new one
        $otp = $customer->generateOTP();
        $this->sendOTPSMS($customer->phone, $otp);

        return redirect()->back()
            ->with('success', 'OTP has been resent to your phone number.');
    }

    protected function resolveCustomer(Request $request): Customer
    {
        $customer = Auth::guard('customers')->user();

        if (!$customer && $request->filled('customer_id')) {
            $customer = Customer::findOrFail((int) $request->input('customer_id'));
        }

        if (!$customer) {
            Log::debug('Customer not found in request');
            abort(404, 'Customer not found');
        }

        return $customer;
    }

    protected function markOrderAsVerified(int $orderId, int $customerId): void
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();

        if ($order) {
            $order->status = 'pending';
            $order->save();
        }
    }

    protected function decodeRedirectParams(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function sendOTPSMS(string $phone, string $otp): void
    {
        $appName = config('app.name', 'StemToysBD');
        $expiryMinutes = (int) config('auth.one_time_password_expiry', 5);

        $message = "Your {$appName} verification code is {$otp}. It expires in {$expiryMinutes} minute(s).";

        $sent = app(SmsService::class)->send($phone, $message);

        if (! $sent) {
            Log::error("Failed to send OTP SMS to {$phone}");
        }
    }
}

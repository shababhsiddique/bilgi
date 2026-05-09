<?php

namespace App\Http\Controllers;

use App\Helpers\Sms;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class Checkout extends Controller
{

    public function submit(Request $request)
    {
        /*echo "<pre>";
        print_r($_POST);
        exit();*/

        // Check if user is guest (not authenticated)
        $isGuestUser = !Auth::guard('customers')->check();

        // Base validation rules
        $rules = [
            // Delivery Information - required only if selected_address_id is null/empty
            'name' => 'required_without:selected_address_id|string|max:255',
            'phone' => 'required_without:selected_address_id|string|max:20',
            'address' => 'required_without:selected_address_id|string|max:255',
            'city' => 'required_without:selected_address_id|string|max:100',
            'postcode' => 'nullable|string|max:4',
            'state' => 'required_without:selected_address_id|string|max:100',
            'country' => 'required_without:selected_address_id|string|max:10',

            // Payment Information
            'payment_method_id' => 'required',

            // Additional fields
            'discount_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];

        // Base error messages
        $messages = [
            // Customer Information
            'name.required' => 'Full name is required.',
            'name.string' => 'Name must be a valid text.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a valid text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',

            // Delivery Information
            'address.string' => 'Address must be a valid text.',
            'address.max' => 'Address cannot exceed 255 characters.',

            'city.string' => 'City must be a valid text.',
            'city.max' => 'City name cannot exceed 100 characters.',

            'postcode.string' => 'Postal code must be a valid text.',
            'postcode.max' => 'Postal code cannot exceed 4 characters.',

            'state.string' => 'State/Division must be a valid text.',
            'state.max' => 'State/Division name cannot exceed 100 characters.',

            'country.string' => 'Country must be a valid text.',
            'country.max' => 'Country name cannot exceed 10 characters.',

            // Payment Information
            'payment_method_id.required' => 'Please select a payment method.',

            // Additional fields
            'notes.string' => 'Notes code must be a valid text.',
            'discount_code.string' => 'Discount code must be a valid text.',
            'discount_code.max' => 'Discount code cannot exceed 20 characters.',
        ];

        // Add password validation for guest users
        if ($isGuestUser) {
            /*$rules['password'] = ['required', 'string', Password::default(), 'confirmed'];
            $rules['password_confirmation'] = 'required|string';*/

            $messages['password.required'] = 'Password is required for guest checkout.';
            $messages['password.confirmed'] = 'Password confirmation does not match.';
            $messages['password_confirmation.required'] = 'Password confirmation is required.';
        }

        // Validate the form input
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get cart items and validate cart is not empty
        $cartService = CartService::forCurrent();
        $cartItems = $cartService->getItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout')
                ->withErrors(['cart' => 'Your cart is empty'])
                ->withInput();
        }

        $totals = $cartService->getTotals();

        $customer = Auth::guard('customers')->user();
        $tempPassword = Str::random(12);
        $isGuestUser = false;

        if(!$customer){
            $customer = Customer::where('phone', $request->phone)->first();

            if($customer){
                Log::debug("Guest checkout detected for existing customer ID {$customer->id}");
                $isGuestUser = true;
            } else {
                Log::debug("Guest checkout detected. creating temp password: $tempPassword");
                // User is guest, lets create a temp customer
                $customer = Customer::create([
                    'full_name' => $request->name,
                    'phone' => $request->phone,
                    'password' => Hash::make($tempPassword),
                    'is_guest_registered' => true,
                ]);
                $isGuestUser = true;
            }
        }

        try {
            DB::beginTransaction();

            // Calculate order totals
            $subtotal = $totals['subtotal'];
            $shippingCosts = config('address.shipping_costs');

            $discountAmount = $this->calculateDiscount($request->discount_code, $subtotal);

            if($request->selected_address_id){
                $orderAddressId = $request->selected_address_id;
                $orderAddress = CustomerAddress::find($orderAddressId);
            }else{
                $orderAddress = CustomerAddress::Create([
                    'customer_id' => $customer->id,
                    'is_default' => $request->is_default??false,
                    'address_name' => $request->address_name ?? 'Home'.uniqid(),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postcode' => $request->postcode,
                    'country' => $request->country,
                ]);
                $orderAddressId = $orderAddress->id;
            }

            // Get payment method to check if it's Cash on Delivery
            $paymentMethod = PaymentMethod::find($request->payment_method_id);

            $deliveryFee =  $shippingCosts[$orderAddress->state];
            $totalAmount = $subtotal + $deliveryFee - $discountAmount;

            // Determine if OTP is required for COD orders
            // OTP is required if:
            // 1. Guest user placing COD order, OR
            // 2. Registered user with unverified phone placing COD order
            $isCashOnDelivery = $paymentMethod->code == 'cod';
            $requireOTP = false;

            if ($isCashOnDelivery) {
                if ($isGuestUser) {
                    // Guest user placing COD order
                    $requireOTP = true;
                } elseif ($customer && $customer->phone_verified_at === null) {
                    // Registered user with unverified phone placing COD order
                    $requireOTP = true;
                }
            }

            // Create the order
            $order = Order::create([
                'customer_id' => $customer->id, // null if guest
                'order_number' => $this->generateOrderNumber(),
                'status' => $requireOTP ? 'unverified' : 'pending',
                'subtotal' => $subtotal, // Convert from cents to decimal
                'discount_amount' => $discountAmount,
                'shipping_amount' => $deliveryFee,
                'total_amount' => $totalAmount,
                'shipping_address_id' => $orderAddressId,
                'billing_address_id' => $orderAddressId,
                'payment_method_id' => $request->payment_method_id,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'placed_at' => now(),
            ]);

            // Create order items from cart
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_name' => $cartItem->productVariant->name ?? null,
                    'sku' => $cartItem->productVariant->sku ?? $cartItem->product->sku,
                    'barcode' => $cartItem->productVariant->barcode ?? $cartItem->product->barcode,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price, // Convert from cents
                    'line_total' => $cartItem->total_price,
                ]);
            }

            // Clear the cart
            $cartService->clear();

            DB::commit();

            // Determine redirect route based on payment method
            $redirectRoute = $this->getPostOrderRedirectRoute($paymentMethod, $order->id);

            if($requireOTP){
                // Generate and send OTP
                $otp = $customer->generateOTP();

                // TODO: Send OTP via SMS
                $this->sendOTPSMS($customer->phone, $otp);

                // Redirect to general OTP verification page
                return redirect()->route('account.otp.show', [
                        'customer_id' => $customer->id,
                        'order_id' => $order->id,
                        'redirect_route' => $redirectRoute['route'],
                        'redirect_params' => json_encode($redirectRoute['params']),
                    ])
                    ->with('success', 'Order placed! Please verify your phone number with the OTP sent to you.')
                    ->with('otp_sent', true);
            }

            // If COD, redirect to confirm page
            if ($isCashOnDelivery) {
                return redirect()->route('order.confirm', $order->id)
                    ->with('success', 'Your order has been placed successfully!');
            }

            // For non-COD payment methods, redirect to payment page
            return redirect()->route('order.pay', $order->id)
                ->with('success', 'Please complete your payment to confirm your order.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Checkout submission error l148 ".$e->getMessage());

            return redirect()->back()
                ->withErrors(['order' => 'Failed to place order. Please try again.'])
                ->withInput();
        }
    }

    private function generateOrderNumber(): string
    {
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        $timestamp = now()->format('His'); // Hours, Minutes, Seconds (e.g., 143052)

        return 'ORD' . str_pad($nextId, 6, '0', STR_PAD_LEFT) . $timestamp;
    }


    private function calculateDiscount(?string $discountCode, int $subtotal): int
    {
        if (!$discountCode) {
            return 0;
        }

        // Simple discount logic - you can make this more sophisticated
        return match (strtoupper($discountCode)) {
            'WELCOME10' => (int) ($subtotal * 0.10), // 10% discount
            'SAVE50' => min(50, $subtotal), // 50 BDT off, max discount
            default => 0,
        };
    }

    private function sendOTPSMS(string $phone, string $otp): void
    {
        // Implement your SMS service here (e.g., Twilio, SMS API, etc.)
        Sms::send("OTP {$otp} should be sent to {$phone}");
    }

    /**
     * Determine the redirect route after order creation based on payment method
     */
    private function getPostOrderRedirectRoute(PaymentMethod $paymentMethod, int $orderId): array
    {
        // If Cash on Delivery, redirect to confirm page
        if ($paymentMethod->code === 'cod') {
            return [
                'route' => 'order.confirm',
                'params' => ['id' => $orderId]
            ];
        }

        // For other payment methods, redirect to payment page
        return [
            'route' => 'order.pay',
            'params' => ['id' => $orderId]
        ];
    }

    /**
     * Show payment page for order
     */
    public function showPayment(Request $request, $id)
    {
        $order = Order::with(['paymentMethod', 'customer', 'items'])
            ->findOrFail($id);

        // Verify order belongs to customer (if authenticated)
        $customer = Auth::guard('customers')->user();
        if ($customer && $order->customer_id !== $customer->id) {
            abort(403, 'You do not have permission to access this order.');
        }

        // Check if payment method is COD (shouldn't reach here, but safety check)
        if ($order->paymentMethod->code === 'cod') {
            return redirect()->route('order.confirm', $order->id)
                ->with('success', 'Your order has been placed successfully!');
        }

        return view('pages.order-payment', [
            'order' => $order,
        ]);
    }

    /**
     * Handle payment initiation
     */
    public function pay(Request $request, $id)
    {
        $order = Order::with(['paymentMethod', 'customer'])
            ->findOrFail($id);

        // Verify order belongs to customer (if authenticated)
        $customer = Auth::guard('customers')->user();
        if ($customer && $order->customer_id !== $customer->id) {
            abort(403, 'You do not have permission to access this order.');
        }

        // Validate payment method
        if ($order->paymentMethod->code === 'cod') {
            return redirect()->route('order.confirm', $order->id)
                ->with('success', 'Your order has been placed successfully!');
        }

        // TODO: Implement payment gateway integration based on payment method
        // For now, we'll just log and redirect to confirm
        Log::info("Payment initiated for order {$order->order_number} with payment method {$order->paymentMethod->code}");

        // Update order payment status
        $order->payment_status = 'processing';
        $order->save();

        // Here you would integrate with payment gateway (bKash, Stripe, etc.)
        // For now, redirecting to confirm page
        return redirect()->route('order.confirm', $order->id)
            ->with('success', 'Payment is being processed. Your order will be confirmed shortly.');
    }


    /*private function storeCustomerInfo(Order $order, Request $request): void
    {
        // Create a simple JSON field to store customer info
        // Or create separate CustomerAddress model
        $customerInfo = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'delivery_method' => $request->delivery_method,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'district' => $request->district,
            'payment_method' => $request->payment_method,
            'payment_number' => $request->payment_number,
        ];

        $order->update(['customer_info' => json_encode($customerInfo)]);
    }*/
}

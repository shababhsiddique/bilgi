<?php

namespace App\Livewire\Account;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function reorder($orderId)
    {
        $customer = Auth::guard('customers')->user();
        
        $order = Order::where('customer_id', $customer->id)
            ->where('id', $orderId)
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

        // Dispatch cart updated event
        $this->dispatch('cart-updated');

        // Redirect to checkout
        return redirect()->route('checkout');
    }

    public function render()
    {
        $customer = Auth::guard('customers')->user();

        $orders = Order::where('customer_id', $customer->id)
            ->orderByRaw('COALESCE(placed_at, created_at) DESC')
            ->paginate(5);

        return view('livewire.account.my-orders', [
            'orders' => $orders,
        ]);
    }
}

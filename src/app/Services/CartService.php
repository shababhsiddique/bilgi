<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartService
{
    protected ?Authenticatable $customer;
    protected string $sessionId;

    public function __construct(?Authenticatable $customer = null, ?string $sessionId = null)
    {
        $this->customer = $customer;
        $this->sessionId = $sessionId ?? $this->resolveSessionId();
    }

    /**
     * Factory for the current request (for controllers / Livewire).
     */
    public static function forCurrent(): self
    {
        /** @var \App\Models\Customer|null $customer */
        $customer = auth('web')->user() ?? auth()->user(); // adapt to your guards

        return new self($customer);
    }

    /**
     * Ensure we have a stable session id for guests.
     */
    protected function resolveSessionId(): string
    {
        if (session()->has('cart_session_id')) {
            return session('cart_session_id');
        }

        $id = (string) Str::uuid();
        session(['cart_session_id' => $id]);

        return $id;
    }

    /**
     * Base query for this user's cart (either by customer or session).
     */
    protected function baseQuery()
    {
        if ($this->customer) {
            return CartItem::where('customer_id', $this->customer->id);
        }

        return CartItem::where('session_id', $this->sessionId);
    }

    /**
     * Add a product (or variant) to the cart.
     *
     * @param int $productId
     * @param int|null $variantId
     * @param int $quantity
     */
    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1): CartItem
    {
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Determine price: use variant price if available, otherwise product base price
        $unitPrice = $this->resolveUnitPrice($productId, $variantId);

        return DB::transaction(function () use ($productId, $variantId, $quantity, $unitPrice) {
            /** @var CartItem|null $item */
            $item = $this->baseQuery()
                ->where('product_id', $productId)
                ->when($variantId, fn ($q) => $q->where('product_variant_id', $variantId))
                ->first();

            if ($item) {
                $item->quantity += $quantity;
                $item->unit_price = $unitPrice; // in case price changed
                $item->save();
            } else {
                $item = CartItem::create([
                    'customer_id'       => $this->customer?->id,
                    'session_id'        => $this->customer ? null : $this->sessionId,
                    'product_id'        => $productId,
                    'product_variant_id'=> $variantId,
                    'quantity'          => $quantity,
                    'unit_price'        => $unitPrice,
                ]);
            }

            return $item;
        });
    }

    /**
     * Update quantity of an existing cart item.
     */
    public function updateQuantity(int $cartItemId, int $quantity): ?CartItem
    {
        if ($quantity < 1) {
            $this->removeItem($cartItemId);
            return null;
        }

        /** @var CartItem|null $item */
        $item = $this->baseQuery()->where('id', $cartItemId)->first();

        if (! $item) {
            return null;
        }

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    /**
     * Remove a single item from the cart.
     */
    public function removeItem(int $cartItemId): void
    {
        $this->baseQuery()->where('id', $cartItemId)->delete();
    }

    /**
     * Clear entire cart.
     */
    public function clear(): void
    {
        $this->baseQuery()->delete();
    }

    /**
     * Get all cart items with related product & variant.
     */
    public function getItems(): Collection
    {
        return $this->baseQuery()
            ->with(['product', 'productVariant'])
            ->get();
    }

    /**
     * Calculate totals: subtotal, item count, quantity sum.
     * Values are in the same unit as CartItem::unit_price (e.g. cents).
     */
    public function getTotals(): array
    {
        $items = $this->getItems();

        $subtotal = $items->sum(fn (CartItem $item) => $item->total_price);
        $itemCount = $items->count();
        $totalQuantity = $items->sum('quantity');

        return [
            'subtotal'       => $subtotal,
            'item_count'     => $itemCount,
            'total_quantity' => $totalQuantity,
        ];
    }

    /**
     * Merge a guest session cart into the logged-in customer's cart.
     * Call this right after customer login.
     */
    public function mergeSessionCartInto(Authenticatable $customer, ?string $fromSessionId = null): void
    {
        $fromSessionId = $fromSessionId ?? session('cart_session_id');

        if (! $fromSessionId) {
            return;
        }

        DB::transaction(function () use ($customer, $fromSessionId) {
            $sessionItems = CartItem::where('session_id', $fromSessionId)->get();

            foreach ($sessionItems as $sessionItem) {
                /** @var CartItem|null $existing */
                $existing = CartItem::where('customer_id', $customer->id)
                    ->where('product_id', $sessionItem->product_id)
                    ->where('product_variant_id', $sessionItem->product_variant_id)
                    ->first();

                if ($existing) {
                    $existing->quantity += $sessionItem->quantity;
                    $existing->save();
                    $sessionItem->delete();
                } else {
                    $sessionItem->update([
                        'customer_id' => $customer->id,
                        'session_id'  => null,
                    ]);
                }
            }
        });

        // Optionally regenerate session cart id
        session()->forget('cart_session_id');
    }

    /**
     * Internal: get unit price from variant or product.
     */
    protected function resolveUnitPrice(int $productId, ?int $variantId = null): int
    {
        if ($variantId) {
            /** @var ProductVariant $variant */
            $variant = ProductVariant::findOrFail($variantId);
            return $variant->sales_price;
        }

        /** @var Product $product */
        $product = Product::findOrFail($productId);

        // if you store a price on product:
        $price = $product->default?->sales_price ?? $product->sales_price ?? 0;

        return (int) round($price * 100);
    }

    public function authenticated(Request $request, $customer)
    {
        CartService::forCurrent()->mergeSessionCartInto($customer);
        // con tinue redirect…
    }

}

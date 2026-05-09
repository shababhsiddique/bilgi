<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Show extends Component
{
    public Product $product;
    public $quantity = 1;
    public $activeTab = 'description';
    public $selectedImageIndex = 0;
    public $galleryImages = [];
    public ProductVariant $selectedVariant;
    public $availableVariants = [];


    public function mount($slug)
    {
        // Find product by slug instead of expecting a Product model
        $this->product = Product::where('slug', $slug)->with(['medias','variants'])->firstOrFail();
        $this->selectedVariant = $this->product->defaultVariant;
        $this->loadAvailableVariants();
        $this->buildGalleryImages();
    }

    protected function loadAvailableVariants()
    {
        // Load all variants with default variant first
        $this->availableVariants = $this->product->variants()
            ->where('visible', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }

    protected function buildGalleryImages()
    {
        $this->galleryImages = [];

        // Always add thumbnail first if it exists
        if ($this->product->thumbnail) {
            $this->galleryImages[] = [
                'url' => asset("storage/{$this->product->thumbnail}"),
                'alt' => $this->product->name,
                'variant_id' => null
            ];
        }

        // Add all media images (assuming all are images for now)
        foreach ($this->product->medias as $media) {
            $this->galleryImages[] = [
                'url' => asset("storage/{$media->media_url}"),
                'alt' => $this->product->name,
                'variant_id' => null
            ];
        }

        // Add images from each available variant
        foreach ($this->availableVariants as $variant) {
            if ($variant->image) {
                $this->galleryImages[] = [
                    'url' => asset("storage/{$variant->image}"),
                    'alt' => $this->product->name . ' - ' . $variant->name,
                    'variant_id' => $variant->id // Mark which variant this image belongs to
                ];
            }
        }

        // If no images at all, add a placeholder
        if (empty($this->galleryImages)) {
            $this->galleryImages[] = [
                'url' => null,
                'alt' => $this->product->name." no images found.",
                'placeholder' => true,
                'variant_id' => null
            ];
        }
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function selectVariant($variantId)
    {
        $variant = $this->availableVariants->firstWhere('id', $variantId);
        if ($variant) {
            $this->selectedVariant = $variant;

            // Find the gallery image index for this variant
            $variantImageIndex = collect($this->galleryImages)->search(function ($image) use ($variantId) {
                return isset($image['variant_id']) && $image['variant_id'] == $variantId;
            });

            // If variant image found in gallery, switch to it
            if ($variantImageIndex !== false) {
                $this->selectedImageIndex = $variantImageIndex;
            }
        }
    }

    public function increaseQuantity()
    {
        $this->quantity++;
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addToCart()
    {
        // Use CartService to add item to cart, matching product card functionality
        CartService::forCurrent()->addItem(
            productId: $this->selectedVariant->product_id,
            variantId: $this->selectedVariant->id,
            quantity: $this->quantity,
        );

        $this->dispatch('cart-updated');
    }

    public function buyNow()
    {
        // Add to cart first with the selected quantity
        CartService::forCurrent()->addItem(
            productId: $this->selectedVariant->product_id,
            variantId: $this->selectedVariant->id,
            quantity: $this->quantity,
        );

        $this->dispatch('cart-updated');

        // Redirect to checkout page
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.product.show');
    }
}

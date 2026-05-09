<?php

namespace App\Livewire\Checkout;

use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Delivery extends Component
{
    public $addresses = [];
    public $selectedAddressId = null;

    // form mode: 'none' | 'create' | 'edit'
    public $formMode = 'none';

    // form fields
    public $addressId = null; // used in edit mode
    public $address_name;
    public $name;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $postcode;
    public $country;
    public $is_default = false;

    protected $rules = [
        'name'         => 'nullable|string|max:255',
        'address_name' => 'required|string|max:255',
        'phone'        => 'required|string|max:50',
        'address'      => 'required|string|max:255',
        'city'         => 'required|string|max:150',
        'state'        => 'required|string|max:150',
        'postcode'     => 'nullable|string|max:50',
        'country'      => 'required|in:Bangladesh',
        'is_default'   => 'boolean',
    ];

    public function mount()
    {
        $customer = Auth::guard('customers')->user();

        if ($customer) {
            // Populate with old input values if available
            $this->name = old('name', $this->name)??$customer->name;
            $this->phone = old('phone', $this->phone)??$customer->phone;
            $this->address = old('address', $this->address);
            $this->city = old('city', $this->city);
            $this->state = old('state', $this->state);
            $this->postcode = old('postcode', $this->postcode);
            $this->country = old('country', $this->country)??"Bangladesh";
            $this->address_name = old('address_name', $this->address_name);

        } else {
            // Guest: default country
            $this->country = 'Bangladesh';
        }

        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $customer = Auth::guard('customers')->user();

        if (! $customer) {
            $this->addresses = collect();
            $this->selectedAddressId = null;

            // No logged in customer: show create form
            $this->formMode = 'create';

            return;
        }

        $this->addresses = $customer->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        if ($this->addresses->isNotEmpty()) {
            // auto-select default or first address
            $default = $this->addresses->firstWhere('is_default', true);
            $this->selectedAddressId = $default
                ? $default->id
                : $this->addresses->first()->id;

            // only auto-show form if not already in edit/create mode
            if ($this->formMode === 'none') {
                $this->formMode = 'none';
            }
        } else {
            // Logged in but no addresses: open create form by default
            $this->selectedAddressId = null;
            $this->formMode = 'create';
        }
    }

    public function selectAddress(int $addressId): void
    {
        if (! $this->addresses->contains('id', $addressId)) {
            return;
        }

        $this->selectedAddressId = $addressId;

        session(['checkout.shipping_address_id' => $addressId]);
        session(['checkout.billing_address_id' => $addressId]);
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->formMode = 'create';

        // Keep any prefilled name/phone from customer if available
        $customer = Auth::guard('customers')->user();
        if ($customer) {
            $this->name    = $customer->full_name;
            $this->phone   = $customer->phone;
            $this->country = 'Bangladesh';
        } else {
            $this->country = $this->country ?: 'Bangladesh';
        }
    }

    public function startEdit(int $addressId): void
    {
        $address = $this->addresses->firstWhere('id', $addressId);

        if (! $address) {
            return;
        }

        $this->resetForm();

        $this->formMode      = 'edit';
        $this->addressId     = $address->id;
        $this->name          = $address->name;
        $this->address_name  = $address->address_name;
        $this->phone         = $address->phone;
        $this->address       = $address->address;
        $this->city          = $address->city;
        $this->state         = $address->state;
        $this->postcode      = $address->postcode; // fixed
        $this->country       = $address->country;
        $this->is_default    = (bool) $address->is_default;
    }

    public function cancelForm(): void
    {
        $this->formMode = 'none';
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'addressId',
            'address_name',
            'name',
            'phone',
            'address',
            'city',
            'state',
            'postcode',
            'country',
            'is_default',
        ]);

        $this->is_default = false;
    }

    public function saveAddress(): void
    {
        $customer = Auth::guard('customers')->user();

        $this->validate();

        if (! $customer) {
           /* // Guest: store address in session instead of DB temporary
            $guestAddress = [
                'address_name' => $this->address_name,
                'name'         => $this->name,
                'phone'        => $this->phone,
                'address'      => $this->address,
                'city'         => $this->city,
                'state'         => $this->state,
                'postcode'     => $this->postcode,
                'country'      => $this->country,
            ];

            session(['checkout.guest_address' => $guestAddress]);

            $this->formMode = 'none';
            $this->resetForm();
            $this->selectedAddressId = 'guest';

            Log::debug("Guest address saved: ".json_encode($guestAddress, JSON_PRETTY_PRINT));*/
            return;
        }

        if ($this->is_default) {
            CustomerAddress::where('customer_id', $customer->id)->update([
                'is_default' => false,
            ]);
        }

        $address = CustomerAddress::create([
            'customer_id'  => $customer->id,
            'address_name' => $this->address_name,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'address'      => $this->address,
            'city'         => $this->city,
            'state'        => $this->state,
            'postcode'     => $this->postcode,
            'country'      => $this->country,
            'is_default'   => $this->is_default,
        ]);

        $this->formMode = 'none';
        $this->resetForm();
        $this->loadAddresses();
        $this->selectedAddressId = $address->id;
    }

    public function updateAddress(): void
    {
        $customer = Auth::guard('customers')->user();
        if (! $customer || ! $this->addressId) {
            return;
        }

        $this->validate();

        $address = CustomerAddress::where('customer_id', $customer->id)
            ->where('id', $this->addressId)
            ->first();

        if (! $address) {
            return;
        }

        if ($this->is_default) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $this->addressId)
                ->update(['is_default' => false]);
        }

        $address->update([
            'name'         => $this->name,
            'address_name' => $this->address_name,
            'phone'        => $this->phone,
            'address'      => $this->address,
            'city'         => $this->city,
            'state'        => $this->state,
            'postcode'     => $this->postcode,
            'country'      => $this->country,
            'is_default'   => $this->is_default,
        ]);

        $this->formMode = 'none';
        $this->resetForm();
        $this->loadAddresses();

        if (! $this->selectedAddressId) {
            $this->selectedAddressId = $address->id;
        }
    }

    public function render()
    {
        return view('livewire.checkout.delivery');
    }
}

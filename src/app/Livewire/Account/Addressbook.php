<?php

namespace App\Livewire\Account;

use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Addressbook extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public bool $showModal = false;
    public ?int $editingAddressId = null;

    // Form fields
    public string $address_name = '';
    public string $name = '';
    public ?string $phone = null;
    public string $address = '';
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postcode = null;
    public string $country = 'Bangladesh';
    public bool $is_default = false;

    protected $rules = [
        'address_name' => 'required|string|max:30',
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:30',
        'address' => 'required|string',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'postcode' => 'nullable|string|max:20',
        'country' => 'required|string|max:255',
    ];

    public function openModal(?int $addressId = null): void
    {
        $this->resetForm();
        $this->editingAddressId = $addressId;

        if ($addressId) {
            $address = CustomerAddress::where('customer_id', Auth::guard('customers')->id())
                ->findOrFail($addressId);

            $this->address_name = $address->address_name;
            $this->name = $address->name;
            $this->phone = $address->phone;
            $this->address = $address->address;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->postcode = $address->postcode;
            $this->country = $address->country;
            $this->is_default = $address->is_default;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $customer = Auth::guard('customers')->user();

        // If setting as default, unset other defaults
        if ($this->is_default) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $this->editingAddressId)
                ->update(['is_default' => false]);
        }

        if ($this->editingAddressId) {
            // Update existing address
            $address = CustomerAddress::where('customer_id', $customer->id)
                ->findOrFail($this->editingAddressId);

            $address->update([
                'address_name' => $this->address_name,
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postcode' => $this->postcode,
                'country' => $this->country,
                'is_default' => $this->is_default,
            ]);

            session()->flash('message', 'Address updated successfully.');
        } else {
            // Create new address
            // If this is the first address, set it as default
            $isFirstAddress = CustomerAddress::where('customer_id', $customer->id)->count() === 0;
            $isDefault = $isFirstAddress || $this->is_default;

            if ($isDefault) {
                CustomerAddress::where('customer_id', $customer->id)
                    ->update(['is_default' => false]);
            }

            CustomerAddress::create([
                'customer_id' => $customer->id,
                'address_name' => $this->address_name,
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postcode' => $this->postcode,
                'country' => $this->country,
                'is_default' => $isDefault,
            ]);

            session()->flash('message', 'Address added successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function setDefault(int $addressId): void
    {
        $customer = Auth::guard('customers')->user();

        // Unset all other defaults
        CustomerAddress::where('customer_id', $customer->id)
            ->update(['is_default' => false]);

        // Set this one as default
        $address = CustomerAddress::where('customer_id', $customer->id)
            ->findOrFail($addressId);
        $address->update(['is_default' => true]);

        session()->flash('message', 'Default address updated successfully.');
    }

    public function delete(int $addressId): void
    {
        $customer = Auth::guard('customers')->user();

        // Check if this is the last address
        $addressCount = CustomerAddress::where('customer_id', $customer->id)->count();

        if ($addressCount <= 1) {
            session()->flash('error', 'Cannot delete the last address. Please add another address first.');
            return;
        }

        $address = CustomerAddress::where('customer_id', $customer->id)
            ->findOrFail($addressId);

        // If deleting default address, set another one as default
        if ($address->is_default) {
            $otherAddress = CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $addressId)
                ->first();

            if ($otherAddress) {
                $otherAddress->update(['is_default' => true]);
            }
        }

        $address->delete();

        session()->flash('message', 'Address deleted successfully.');
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingAddressId = null;
        $this->address_name = '';
        $this->name = '';
        $this->phone = null;
        $this->address = '';
        $this->city = null;
        $this->state = null;
        $this->postcode = null;
        $this->country = 'Bangladesh';
        $this->is_default = false;
        $this->resetValidation();
    }

    public function render()
    {
        $customer = Auth::guard('customers')->user();

        $addresses = CustomerAddress::where('customer_id', $customer->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalAddressCount = CustomerAddress::where('customer_id', $customer->id)->count();

        return view('livewire.account.addressbook', [
            'addresses' => $addresses,
            'totalAddressCount' => $totalAddressCount,
        ]);
    }
}

<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AccountInfo extends Component
{
    public string $full_name = '';
    public ?string $email = null;
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'full_name' => 'required|string|max:100',
        'email' => 'nullable|email|max:191|unique:customers,email',
        'password' => 'nullable|string|min:8|confirmed',
    ];

    public function mount(): void
    {
        $customer = Auth::guard('customers')->user();
        
        if ($customer) {
            $this->full_name = $customer->full_name ?? '';
            $this->email = $customer->email;
        }
    }

    public function updated($propertyName): void
    {
        // Reset email validation when email changes
        if ($propertyName === 'email') {
            $customer = Auth::guard('customers')->user();
            $this->validateOnly('email', [
                'email' => 'nullable|email|max:191|unique:customers,email,' . $customer->id,
            ]);
        }
    }

    public function save(): void
    {
        $customer = Auth::guard('customers')->user();

        if (!$customer) {
            return;
        }

        // Build validation rules
        $rules = [
            'full_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:191|unique:customers,email,' . $customer->id,
        ];

        // Only validate password if it's provided
        if (!empty($this->password)) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $this->validate($rules);

        $updateData = [
            'full_name' => $this->full_name,
            'email' => $this->email ?: null,
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $customer->update($updateData);

        session()->flash('message', 'Account information updated successfully.');
        
        // Clear password fields
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function render()
    {
        return view('livewire.account.account-info');
    }
}



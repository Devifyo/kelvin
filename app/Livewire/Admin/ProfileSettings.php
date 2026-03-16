<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSettings extends Component
{
    // The active tab state
    public $tab = 'profile';

    public $name;
    public $email;
    
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    // Method to switch tabs instantly
    public function setTab($selectedTab)
    {
        $this->tab = $selectedTab;
        $this->resetValidation(); // Clear any errors when switching tabs
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('notify', message: 'Profile information updated successfully.', type: 'success');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('notify', message: 'Password changed securely.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.profile-settings')
            ->layout('layouts.admin', ['title' => 'Account Settings']);
    }
}